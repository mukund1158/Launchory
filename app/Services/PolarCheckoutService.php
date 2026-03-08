<?php

namespace App\Services;

use App\Models\PolarProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Polar\Models\Components\PresentmentCurrency;
use Polar\Models\Components\ProductCreateOneTime;
use Polar\Models\Components\ProductCreateRecurring;
use Polar\Models\Components\ProductPriceFixedCreate;
use Polar\Models\Components\ProductVisibility;
use Polar\Models\Components\SubscriptionRecurringInterval;
use Polar\Models\Components\CheckoutCreate;
use Polar\Polar;

class PolarCheckoutService
{
    public function __construct(
        protected Polar $polar
    ) {}

    /**
     * Resolve PresentmentCurrency from config (must match Polar org default).
     */
    protected function getPresentmentCurrency(): PresentmentCurrency
    {
        $code = config('polar.currency', 'usd');
        return match (strtolower((string) $code)) {
            'eur' => PresentmentCurrency::Eur,
            'gbp' => PresentmentCurrency::Gbp,
            'aud' => PresentmentCurrency::Aud,
            'cad' => PresentmentCurrency::Cad,
            'chf' => PresentmentCurrency::Chf,
            'jpy' => PresentmentCurrency::Jpy,
            'sek' => PresentmentCurrency::Sek,
            'brl' => PresentmentCurrency::Brl,
            'inr' => PresentmentCurrency::Inr,
            default => PresentmentCurrency::Usd,
        };
    }

    public static function make(): self
    {
        $token = config('polar.access_token');
        if (empty($token)) {
            throw new \RuntimeException('POLAR_ACCESS_TOKEN is not set.');
        }

        $builder = Polar::builder()->setSecurity($token);
        if (config('polar.sandbox')) {
            $builder->setServer(Polar::SERVER_SANDBOX);
        }

        return new self($builder->build());
    }

    /**
     * Get or create Polar product ID for a plan. Checks DB, then config, then creates via API.
     */
    public function ensureProductForPlan(string $plan): string
    {
        $cacheKey = "polar_product_id:{$plan}";
        $id = Cache::get($cacheKey);
        if ($id) {
            return $id;
        }

        $id = PolarProduct::getPolarIdForPlan($plan);
        if ($id) {
            Cache::put($cacheKey, $id, now()->addDay());
            return $id;
        }

        $id = config("polar.products.{$plan}");
        if ($id) {
            PolarProduct::updateOrCreate(
                ['plan_slug' => $plan],
                ['polar_product_id' => $id]
            );
            Cache::put($cacheKey, $id, now()->addDay());
            return (string) $id;
        }

        $definition = config("polar.plan_definitions.{$plan}");
        if (! $definition || empty($definition['name']) || ! isset($definition['price_cents'])) {
            throw new \RuntimeException("Plan '{$plan}' is not configured. Add it to config/polar.php plan_definitions.");
        }

        $polarProductId = $this->createPolarProduct($plan, $definition);
        PolarProduct::updateOrCreate(
            ['plan_slug' => $plan],
            ['polar_product_id' => $polarProductId]
        );
        Cache::put($cacheKey, $polarProductId, now()->addDay());

        return $polarProductId;
    }

    protected function createPolarProduct(string $plan, array $definition): string
    {
        $name = $definition['name'];
        $priceCents = (int) $definition['price_cents'];
        $recurring = $definition['recurring_interval'] ?? null;
        $orgId = config('polar.organization_id');
        $currency = $this->getPresentmentCurrency();

        $price = new ProductPriceFixedCreate(
            priceAmount: $priceCents,
            priceCurrency: $currency
        );

        try {
            if ($recurring === 'month' || $recurring === 'year') {
                $interval = $recurring === 'year'
                    ? SubscriptionRecurringInterval::Year
                    : SubscriptionRecurringInterval::Month;
                $request = new ProductCreateRecurring(
                    name: $name,
                    prices: [$price],
                    recurringInterval: $interval,
                    description: "Launchory plan: {$name}",
                    visibility: ProductVisibility::Public,
                    organizationId: $orgId
                );
            } else {
                $request = new ProductCreateOneTime(
                    name: $name,
                    prices: [$price],
                    description: "Launchory plan: {$name}",
                    visibility: ProductVisibility::Public,
                    organizationId: $orgId
                );
            }

            $response = $this->polar->products->create($request);
        } catch (\Throwable $e) {
            Log::error('Polar product create failed', [
                'plan' => $plan,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Unable to create payment product. Please try again.', 0, $e);
        }

        $product = $response->product;
        if (empty($product->id)) {
            throw new \RuntimeException('Invalid product response from payment provider.');
        }

        Log::info('Polar product created', ['plan' => $plan, 'polar_product_id' => $product->id]);

        return $product->id;
    }

    /**
     * Get Polar product ID for a Launchory plan (from DB or config; creates in Polar if missing).
     */
    public function getProductIdForPlan(string $plan): ?string
    {
        try {
            return $this->ensureProductForPlan($plan);
        } catch (\Throwable $e) {
            Log::warning('Polar: Could not resolve product for plan.', ['plan' => $plan, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create a checkout session for a paid plan and return the redirect URL.
     *
     * @param  string  $plan  Plan slug: launch_featured, directory_standard, directory_featured, bundle_standard, bundle_featured
     * @return string Checkout URL to redirect the user to
     *
     * @throws \RuntimeException If plan has no Polar product or API fails
     */
    public function createCheckoutUrl(
        Product $product,
        User $customer,
        string $plan,
        string $successUrl,
        string $returnUrl
    ): string {
        $polarProductId = $this->ensureProductForPlan($plan);
        if (empty($polarProductId)) {
            throw new \RuntimeException("Payment is not configured for this plan. Please contact support.");
        }

        $request = new CheckoutCreate(
            products: [$polarProductId],
            metadata: [
                'product_id' => (string) $product->id,
                'plan' => $plan,
            ],
            externalCustomerId: (string) $customer->id,
            customerEmail: $customer->email,
            customerName: $customer->name,
            successUrl: $successUrl,
            returnUrl: $returnUrl
        );

        try {
            $response = $this->polar->checkouts->create($request);
        } catch (\Throwable $e) {
            Log::error('Polar checkout create failed', [
                'product_id' => $product->id,
                'plan' => $plan,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Unable to start checkout. Please try again or contact support.', 0, $e);
        }

        $checkout = $response->checkout;
        if (empty($checkout->url)) {
            throw new \RuntimeException('Invalid checkout response from payment provider.');
        }

        return $checkout->url;
    }
}
