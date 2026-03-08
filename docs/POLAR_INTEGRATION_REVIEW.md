# Polar.sh Integration Review

## 1. Current Form Submit & Payment Flow (Review)

### Submit flow (no payment gateway today)

- **Component:** `resources/views/livewire/submit-product.blade.php` (Volt class-based Livewire).
- **Steps:** 5-step wizard: Product Info → Details → Listing Type + Plan → Plan selection → Review → Submit.
- **Plans:**
  - **Launch:** `free` | `launch_featured` ($19 one-time)
  - **Directory:** `directory_standard` ($9/mo) | `directory_featured` ($19/mo)
  - **Both:** `bundle_standard` ($9/mo) | `bundle_featured` ($39/mo)
- **On submit (`submit()`):**
  - Logo stored to `storage/logos`.
  - `Product` created with `status = 'pending'`, `is_featured` from plan.
  - **No payment:** UI says *"Payment will be processed after your product is approved. You'll receive an email with a secure payment link."* — i.e. manual/email flow, no gateway.
- **Pricing page:** `resources/views/pages/pricing.blade.php` — CTAs link to submit or contact; no checkout.
- **Featured slots:** `featured_slots` table has `stripe_payment_id` (and `amount_paid`); no Stripe/Polar logic exists yet. Ready to store Polar order/session IDs instead.

**Summary:** Payment is deferred and manual. To integrate Polar.sh, we need to either (A) create a Polar checkout when the user chooses a paid plan and redirect before/after submit, or (B) create checkout after admin approval and send link by email. Option (A) gives immediate payment; (B) matches current “pay after approval” copy.

---

## 2. Polar.sh API Overview (from [API Introduction](https://polar.sh/docs/api-reference/introduction))

- **Base URLs:** Production `https://api.polar.sh/v1`, Sandbox `https://sandbox-api.polar.sh/v1`.
- **Auth (backend):** Organization Access Token (OAT) in `Authorization: Bearer <token>`.
- **Auth (customer-facing):** Customer Access Token from `/v1/customer-sessions/` for Customer Portal API (view orders/subscriptions). For **creating checkouts** you use the **Core API with OAT** on your server.
- **Pagination:** `page` (1-based), `limit` (default 10, max 100); responses include `pagination.total_count`, `pagination.max_page`.
- **Rate limits:** 300 req/min per org; 429 with `Retry-After` when exceeded.

Relevant for Launchory:

- **Checkouts:** Create session → get `url` (and `client_secret` for embedded). Redirect user to `url` or embed.
- **Products/Prices:** Define in Polar dashboard (or via API); checkout takes **product IDs** (UUIDs).
- **Orders:** After payment, Polar sends **webhooks** (e.g. `order.paid`); you persist `order_id` and link to your `Product` / `FeaturedSlot`.
- **Metadata:** Checkout supports `metadata` (and `external_customer_id`). Use metadata to pass e.g. `product_id` (Launchory DB id) so webhooks can attach payment to the right product.

---

## 3. Polar.sh Concepts Relevant to Launchory

| Concept | Use in Launchory |
|--------|-------------------|
| **Products (Polar)** | One Polar product per “sellable” (e.g. “Featured Launch”, “Featured Directory”, “Featured Bundle” or per plan). Create in Polar dashboard (or API) and copy Product IDs into config. |
| **Checkout Session** | Create with `products: [polar_product_id]`, optional `metadata: { product_id: "123" }`, `success_url` / `return_url`. Redirect user to `checkout.url`. |
| **Orders** | Created when checkout succeeds. Webhook `order.paid` gives you `order.id` and metadata; store in `featured_slots` (e.g. `polar_order_id` / `stripe_payment_id` repurposed). |
| **Subscriptions** | For $9/mo and $39/mo plans; Polar supports subscriptions; webhooks: `subscription.active`, `subscription.canceled`, etc. |
| **Customer** | Use `external_customer_id: auth()->id()` (or user UUID) so Polar ties orders to your user. Optional: `customer_email`, `customer_name` for prefill. |

---

## 4. Recommended Flow for Launchory

### Option A — Pay at submit (recommended for clarity)

1. User completes steps 1–4 and lands on Review (step 5).
2. If plan is paid:
   - On “Submit to Launchory”:
     - Create `Product` with `status = 'pending'` (and `is_featured` from plan).
     - Create Polar checkout with the right Polar product ID, `metadata: { product_id: <id> }`, `external_customer_id: user_id`, `success_url` / `return_url`.
     - Redirect to `checkout.url`.
3. On success, Polar redirects to `success_url` (e.g. `/submit/success?product_id=...`).
4. Webhook `order.paid`: find product by metadata, create/update `FeaturedSlot`, set `polar_order_id`, mark product approved if desired (or keep approval step).

### Option B — Pay after approval (matches current copy)

1. Submit as now: create `Product`, show “we’ll email you”.
2. Admin approves in Filament → trigger “send payment link”: create Polar checkout with `metadata: { product_id }`, email the checkout URL to the user.
3. User pays → same webhook handling as above.

Option A is simpler for the user (one flow); Option B keeps “approval first” and avoids charging before review.

---

## 5. Implementation Checklist

- [ ] **Config:** Add `POLAR_ACCESS_TOKEN`, `POLAR_WEBHOOK_SECRET`, `POLAR_SANDBOX=true` (or env-specific). Optional: map plan slugs to Polar product IDs (e.g. `launch_featured` → UUID).
- [ ] **SDK:** `composer require polar-sh/sdk`. Use sandbox in non-production if needed (see SDK `setServer('sandbox')`).
- [ ] **Products in Polar:** Create products (and prices) in Polar dashboard for: Featured Launch ($19 one-time), Directory Standard ($9/mo), Directory Featured ($19/mo), Bundle Standard ($9/mo), Bundle Featured ($39/mo). Copy Product IDs into config.
- [ ] **Checkout creation:** Service or action that: receives plan + Launchory `product_id` + user, calls Polar `POST /v1/checkouts/` with `products`, `metadata`, `external_customer_id`, `success_url`, `return_url`; returns redirect URL.
- [ ] **Submit flow:** In `submit-product.blade.php` (or a controller if you prefer): if paid plan, after creating `Product`, call checkout service then `return redirect($checkoutUrl)`. If free, keep current step 6 success view.
- [ ] **Success/return URLs:** Add routes e.g. `submit/success` and `submit/return`; success can show “Payment received; we’ll approve shortly” or auto-approve when using Option A.
- [ ] **Webhook:** New route (e.g. `POST /webhooks/polar`) that verifies signature, handles `order.paid` (and subscription events if needed): parse metadata → update `Product` / create `FeaturedSlot`, set `polar_order_id`. Exclude from CSRF.
- [ ] **DB:** Migration to add `polar_order_id` (or repurpose `stripe_payment_id`) on `featured_slots`; optionally `products.polar_checkout_id` or similar if you want to track last checkout per product.
- [ ] **Pricing page:** Optionally link “Get Featured Launch” (etc.) to submit flow with plan pre-selected (query param), so user goes straight to plan step or review.

---

## 6. Polar.sh Docs Quick Links

- [API Overview](https://polar.sh/docs/api-reference/introduction) — Base URLs, auth, pagination, rate limits.
- [Create Checkout Session](https://polar.sh/docs/api-reference/checkouts/create-session) — Request body: `products`, `metadata`, `success_url`, `return_url`, `external_customer_id`, etc.
- [How to Create Checkout Session](https://polar.sh/docs/guides/create-checkout-session) — Minimal guide.
- [Laravel adapter (community)](https://polar.sh/docs/integrate/sdk/adapters/laravel.md) — Optional `danestves/laravel-polar` (Billable trait, webhooks); or use official `polar-sh/sdk` only.
- [PHP SDK](https://polar.sh/docs/integrate/sdk/php) — Official SDK; use for checkouts and server-side calls.
- [Webhooks](https://polar.sh/docs/integrate/webhooks/endpoints) — Register endpoint; subscribe to `order.paid`, `subscription.active`, etc.
- [Sandbox](https://polar.sh/docs/integrate/sandbox) — Use sandbox URL and sandbox tokens for testing.

---

## 7. Security & Best Practices

- Never expose OAT in frontend or logs; use env only.
- Validate webhook payload with `POLAR_WEBHOOK_SECRET` (HMAC or Polar’s documented method).
- Use `external_customer_id` and checkout `metadata` to link payments to your `Product`/user; don’t trust client-supplied IDs in webhooks without server-side lookup.
- Prefer creating checkouts server-side (as in this design); redirect to Polar-hosted checkout to avoid handling card data.

---

---

## 8. Option A implemented (Pay at submit)

The following is in place:

- **Config:** `config/polar.php` — `POLAR_ACCESS_TOKEN`, `POLAR_WEBHOOK_SECRET`, `POLAR_SANDBOX`, `plan_definitions` (name, price_cents, recurring_interval), and optional `polar.products.*` / `POLAR_ORGANIZATION_ID`.
- **DB:** `polar_products` table stores `plan_slug` → `polar_product_id`; products are created in Polar via API on first use and reused.
- **Service:** `App\Services\PolarCheckoutService` — `ensureProductForPlan(plan)` gets or creates the Polar product (API + DB); `createCheckoutUrl(...)` builds checkout and returns the redirect URL.
- **Submit flow:** In `submit-product.blade.php`, after creating the product, paid plans call the service and redirect to Polar checkout; free plans still go to step 6.
- **Routes:** `GET /submit/success/{product}` (auth) and `POST /webhooks/polar` (no auth, CSRF excluded).
- **Webhook:** `PolarWebhookController` — verifies Standard Webhooks signature, handles `order.paid`: creates `FeaturedSlot` (with `polar_order_id`), sets product `status` to `approved` and `featured_until`.
- **DB:** `featured_slots.polar_order_id` added; `FeaturedSlot` model updated.

**Setup for you:** **Auto-created products:** You do *not* need to create products in the Polar dashboard; they are created via API on first use and stored in `polar_products`. **Setup:** Set `POLAR_ACCESS_TOKEN` and `POLAR_WEBHOOK_SECRET`. Register webhook [Polar dashboard](https://polar.sh/dashboard) (or sandbox) for each paid plan, copy each Product ID into the corresponding `.env` (e.g. `POLAR_PRODUCT_LAUNCH_FEATURED=...`). Register a webhook endpoint with URL `https://yourdomain.com/webhooks/polar` and subscribe to `order.paid` (and optionally `subscription.active` / `subscription.canceled` for recurring); set `POLAR_WEBHOOK_SECRET` from the endpoint’s secret.
