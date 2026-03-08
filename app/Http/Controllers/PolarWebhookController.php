<?php

namespace App\Http\Controllers;

use App\Models\FeaturedSlot;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PolarWebhookController extends Controller
{
    /**
     * Handle Polar webhook (Standard Webhooks).
     * Respond 202 quickly; process order.paid to create featured slot and approve product.
     */
    public function __invoke(Request $request): Response
    {
        $secret = config('polar.webhook_secret');
        if (empty($secret)) {
            Log::warning('Polar webhook: POLAR_WEBHOOK_SECRET not set');
            return response('', 403);
        }

        $payload = $request->getContent();
        if (! $this->verifySignature($request, $payload, $secret)) {
            Log::warning('Polar webhook: invalid signature');
            return response('', 403);
        }

        $data = json_decode($payload, true);
        if (! is_array($data)) {
            return response('', 400);
        }

        $type = $data['type'] ?? null;
        if ($type === 'order.paid') {
            $this->handleOrderPaid($data);
        }
        // subscription.active / subscription.canceled could be handled here for recurring plans

        return response('', 202);
    }

    protected function verifySignature(Request $request, string $payload, string $secret): bool
    {
        $id = $request->header('webhook-id');
        $timestamp = $request->header('webhook-timestamp');
        $sigHeader = $request->header('webhook-signature');
        if (empty($id) || empty($timestamp) || empty($sigHeader)) {
            return false;
        }
        // Replay: reject if timestamp too old (e.g. 5 min)
        if (abs((int) $timestamp - time()) > 300) {
            return false;
        }
        $signed = $id . '.' . $timestamp . '.' . $payload;
        $key = base64_decode($secret, true) ?: $secret;
        $expected = base64_encode(hash_hmac('sha256', $signed, $key, true));
        // Header can be "v1,<sig>" or "v1=<sig>"
        if (! preg_match('/v1[,=](.+)/', $sigHeader, $m)) {
            return false;
        }
        $received = trim($m[1]);

        return hash_equals($expected, $received);
    }

    protected function handleOrderPaid(array $data): void
    {
        $payload = $data['data'] ?? [];
        $orderId = $payload['id'] ?? null;
        $metadata = $payload['metadata'] ?? [];
        $productId = isset($metadata['product_id']) ? (int) $metadata['product_id'] : null;
        $plan = $metadata['plan'] ?? null;

        if (! $orderId || ! $productId) {
            Log::warning('Polar order.paid: missing order id or product_id in metadata', ['data' => $data]);
            return;
        }

        $product = Product::find($productId);
        if (! $product) {
            Log::warning('Polar order.paid: product not found', ['product_id' => $productId]);
            return;
        }

        $amount = (float) ($payload['amount'] ?? 0) / 100; // cents to dollars

        $startsAt = now();
        $endsAt = match ($plan) {
            'launch_featured' => $product->launch_date
                ? $product->launch_date->copy()->endOfDay()
                : now()->addDay(),
            'directory_standard', 'directory_featured' => now()->addMonth(),
            'bundle_standard', 'bundle_featured' => now()->addMonth(),
            default => now()->addMonth(),
        };
        $endsAtString = $endsAt instanceof \Carbon\Carbon ? $endsAt->toDateTimeString() : $endsAt;

        $slotType = match ($plan) {
            'launch_featured' => 'launch',
            'directory_standard', 'directory_featured' => 'directory',
            'bundle_standard', 'bundle_featured' => 'directory',
            default => 'directory',
        };

        FeaturedSlot::create([
            'product_id' => $product->id,
            'slot_type' => $slotType,
            'starts_at' => $startsAt,
            'ends_at' => $endsAtString,
            'amount_paid' => $amount,
            'polar_order_id' => $orderId,
        ]);

        $product->update([
            'status' => 'approved',
            'featured_until' => \Carbon\Carbon::parse($endsAtString)->toDateString(),
        ]);

        Log::info('Polar order.paid: created featured slot and approved product', [
            'product_id' => $productId,
            'order_id' => $orderId,
            'plan' => $plan,
        ]);
    }
}
