# Admin System & Payment Integration Guide

This guide explains how to create admin/superadmin accounts and integrate Stripe/bKash payment processing.

---

## 1. Admin/Superadmin System

### Creating Admin Accounts

#### Method 1: Using Artisan Command (Recommended)

Promote an existing user to admin:
```bash
php artisan user:make-admin user@example.com
```

Promote to superadmin:
```bash
php artisan user:make-admin user@example.com --role=superadmin
```

#### Method 2: Using Tinker

```bash
php artisan tinker
```

Then run:
```php
// Make admin
$user = User::where('email', 'user@example.com')->first();
$user->update(['role' => 'admin']);

// Make superadmin
$user = User::where('email', 'user@example.com')->first();
$user->update(['role' => 'superadmin']);
```

#### Method 3: Direct Database Update

```sql
UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
-- or
UPDATE users SET role = 'superadmin' WHERE email = 'user@example.com';
```

### Role Differences

- **user**: Regular user (default)
- **admin**: Can access Filament admin panel, manage content
- **superadmin**: Full access to all admin features including user management

### Using Role-Based Middleware

Protect routes with middleware:

```php
// Admin and superadmin can access
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Only superadmin can access
Route::middleware(['superadmin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'users']);
});
```

### Accessing Filament Admin Panel

Once promoted to admin/superadmin, users can access the admin panel at:
```
https://yourdomain.com/admin
```

---

## 2. Stripe Payment Integration

### Step 1: Install Laravel Cashier

```bash
composer require laravel/cashier
```

### Step 2: Run Cashier Migrations

```bash
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

### Step 3: Configure Stripe Credentials

Add to `.env`:
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

Get your keys from: https://dashboard.stripe.com/test/apikeys

### Step 4: Update User Model

The User model already has `stripe_customer_id` field. Update it to use Cashier's Billable trait:

```php
use Laravel\Cashier\Billable;

class User extends Authenticatable implements HasMedia, FilamentUser
{
    use Billable; // Add this trait
    // ... rest of the code
}
```

### Step 5: Create Stripe Products

In Stripe Dashboard (https://dashboard.stripe.com/test/products):

1. Create "Verified Badge" product
   - Price: $9.99/month (or your choice)
   - Copy the Price ID (starts with `price_`)

2. Create "Purple Badge" product
   - Price: $29.99/month (or your choice)
   - Copy the Price ID

### Step 6: Update Subscription Controller

Update `app/Http/Controllers/SubscriptionController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('subscriptions.index', [
            'user' => $user,
            'plans' => [
                'verified' => [
                    'name' => 'Verified Badge',
                    'price' => '$9.99',
                    'price_id' => 'price_verified_badge_monthly', // Replace with your Stripe Price ID
                    'features' => [
                        'Blue verified badge',
                        'Stand out in the community',
                        'Show authenticity',
                    ],
                ],
                'purple' => [
                    'name' => 'Purple Badge',
                    'price' => '$29.99',
                    'price_id' => 'price_purple_badge_monthly', // Replace with your Stripe Price ID
                    'features' => [
                        'Purple premium badge',
                        'All verified features',
                        'Priority support',
                        'Exclusive features',
                    ],
                ],
            ],
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:verified,purple',
        ]);

        $user = auth()->user();
        $plan = $request->input('plan');

        // Map plan to Stripe Price ID
        $priceIds = [
            'verified' => 'price_verified_badge_monthly', // Replace with your actual Price ID
            'purple' => 'price_purple_badge_monthly',     // Replace with your actual Price ID
        ];

        try {
            return $user->newSubscription($plan, $priceIds[$plan])
                ->checkout([
                    'success_url' => route('subscriptions.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('subscriptions.index'),
                ]);
        } catch (\Exception $e) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Failed to create checkout session: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('subscriptions.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            $user = auth()->user();

            // Determine badge type from subscription
            $subscription = $user->subscriptions()->latest()->first();
            $badgeType = $subscription ? $subscription->name : 'verified';

            // Update user badge
            $user->update([
                'badge_type' => $badgeType,
                'badge_expires_at' => now()->addMonth(),
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Subscription activated! Your badge is now active.');
        }

        return redirect()->route('subscriptions.index');
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();

        if ($user->subscribed('verified')) {
            $user->subscription('verified')->cancel();
        }

        if ($user->subscribed('purple')) {
            $user->subscription('purple')->cancel();
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled. You will retain access until the end of the billing period.');
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $endpoint_secret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook error'], 400);
        }

        // Handle different event types
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $user = User::where('stripe_id', $invoice->customer)->first();

                if ($user) {
                    $subscription = $user->subscriptions()->where('stripe_id', $invoice->subscription)->first();
                    if ($subscription) {
                        $user->update([
                            'badge_type' => $subscription->name,
                            'badge_expires_at' => now()->addMonth(),
                        ]);
                    }
                }
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $user = User::where('stripe_id', $subscription->customer)->first();

                if ($user) {
                    $user->update([
                        'badge_type' => 'none',
                        'badge_expires_at' => null,
                    ]);
                }
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
```

### Step 7: Set Up Stripe Webhook

1. Go to https://dashboard.stripe.com/test/webhooks
2. Click "Add endpoint"
3. Enter your webhook URL: `https://yourdomain.com/webhook/stripe`
4. Select events to listen to:
   - `invoice.payment_succeeded`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
5. Copy the webhook secret and add to `.env` as `STRIPE_WEBHOOK_SECRET`

### Step 8: Test Stripe Integration

Use Stripe test cards:
- Success: `4242 4242 4242 4242`
- Requires Authentication: `4000 0025 0000 3155`
- Declined: `4000 0000 0000 9995`

Use any future expiry date, any 3-digit CVC, and any postal code.

---

## 3. bKash Payment Integration

bKash is a popular mobile financial service in Bangladesh.

### Step 1: Get bKash Merchant Account

1. Contact bKash: https://www.bkash.com/
2. Apply for merchant account
3. Get credentials:
   - App Key
   - App Secret
   - Username
   - Password
   - Base URL (sandbox/production)

### Step 2: Install bKash SDK

```bash
composer require shipu/bkash
```

Or create a custom integration.

### Step 3: Configure bKash

Add to `.env`:
```env
BKASH_APP_KEY=your_app_key
BKASH_APP_SECRET=your_app_secret
BKASH_USERNAME=your_username
BKASH_PASSWORD=your_password
BKASH_BASE_URL=https://tokenized.sandbox.bka.sh/v1.2.0-beta
# For production: https://tokenized.pay.bka.sh/v1.2.0-beta
```

Add to `config/services.php`:
```php
'bkash' => [
    'app_key' => env('BKASH_APP_KEY'),
    'app_secret' => env('BKASH_APP_SECRET'),
    'username' => env('BKASH_USERNAME'),
    'password' => env('BKASH_PASSWORD'),
    'base_url' => env('BKASH_BASE_URL'),
    'callback_url' => env('APP_URL') . '/payment/bkash/callback',
],
```

### Step 4: Create bKash Payment Controller

```bash
php artisan make:controller BkashPaymentController
```

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class BkashPaymentController extends Controller
{
    private $baseUrl;
    private $appKey;
    private $appSecret;
    private $username;
    private $password;

    public function __construct()
    {
        $this->baseUrl = config('services.bkash.base_url');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
    }

    private function getToken()
    {
        $response = Http::withHeaders([
            'username' => $this->username,
            'password' => $this->password,
        ])->post($this->baseUrl . '/tokenized/checkout/token/grant', [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        return $response->json()['id_token'] ?? null;
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:verified,purple',
        ]);

        $amounts = [
            'verified' => '999', // 999 BDT
            'purple' => '2999',  // 2999 BDT
        ];

        $token = $this->getToken();

        if (!$token) {
            return redirect()->back()->with('error', 'Failed to initialize payment');
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
            'X-APP-Key' => $this->appKey,
        ])->post($this->baseUrl . '/tokenized/checkout/create', [
            'mode' => '0011',
            'payerReference' => auth()->id(),
            'callbackURL' => config('services.bkash.callback_url'),
            'amount' => $amounts[$request->plan],
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => 'SUB-' . time(),
        ]);

        $data = $response->json();

        if (isset($data['bkashURL'])) {
            return redirect($data['bkashURL']);
        }

        return redirect()->back()->with('error', 'Payment initialization failed');
    }

    public function callback(Request $request)
    {
        $paymentID = $request->paymentID;
        $status = $request->status;

        if ($status === 'success') {
            $token = $this->getToken();

            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/execute', [
                'paymentID' => $paymentID,
            ]);

            $data = $response->json();

            if (isset($data['transactionStatus']) && $data['transactionStatus'] === 'Completed') {
                // Update user subscription
                $user = auth()->user();
                $user->update([
                    'badge_type' => 'verified', // Determine based on amount
                    'badge_expires_at' => now()->addMonth(),
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Payment successful! Your badge is now active.');
            }
        }

        return redirect()->route('subscriptions.index')
            ->with('error', 'Payment failed or cancelled');
    }
}
```

### Step 5: Add bKash Routes

In `routes/web.php`:
```php
use App\Http\Controllers\BkashPaymentController;

Route::middleware(['auth'])->group(function () {
    Route::post('payment/bkash/create', [BkashPaymentController::class, 'createPayment'])->name('bkash.create');
    Route::get('payment/bkash/callback', [BkashPaymentController::class, 'callback'])->name('bkash.callback');
});
```

### Step 6: Update Subscription View

Add bKash payment option to your subscription view alongside Stripe.

---

## Testing

### Testing Admin System

1. Create a test user
2. Promote to admin: `php artisan user:make-admin test@example.com`
3. Login and visit `/admin`

### Testing Stripe

1. Use sandbox mode (test keys)
2. Use test card: `4242 4242 4242 4242`
3. Monitor in Stripe Dashboard

### Testing bKash

1. Use sandbox credentials
2. Test with bKash sandbox wallet
3. Check payment status in bKash merchant portal

---

## Security Considerations

1. **Never commit API keys** - Keep them in `.env`
2. **Verify webhook signatures** - Already implemented in webhook methods
3. **Use HTTPS in production** - Required for payment processing
4. **Validate all inputs** - Already implemented with request validation
5. **Log all transactions** - Consider adding transaction logging
6. **Implement rate limiting** - Prevent payment spam

---

## Going Live

### Stripe Production

1. Complete Stripe account verification
2. Switch to live API keys
3. Update webhook URL to production
4. Test with real cards (small amounts)

### bKash Production

1. Complete merchant verification
2. Switch to production credentials
3. Update base URL to production
4. Test thoroughly before launch

---

## Support

- Stripe Documentation: https://stripe.com/docs
- Laravel Cashier: https://laravel.com/docs/billing
- bKash API Documentation: https://developer.bka.sh/
- Filament Admin: https://filamentphp.com/docs

---

## Quick Reference

### Create Admin
```bash
php artisan user:make-admin admin@example.com --role=superadmin
```

### Access Admin Panel
```
https://yourdomain.com/admin
```

### Test Stripe Card
```
Card: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
ZIP: Any postal code
```

### Check Subscriptions
```php
$user->subscribed('verified')
$user->subscription('verified')->cancel()
```
