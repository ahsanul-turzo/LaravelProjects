<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription;

        return view('subscriptions.index', compact('activeSubscription'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:verified,purple',
        ]);

        $user = auth()->user();

        // Check if user already has active subscription
        if ($user->activeSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription!');
        }

        $priceId = $validated['type'] === 'verified'
            ? config('services.stripe.verified_badge_price_id')
            : config('services.stripe.purple_badge_price_id');

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('subscriptions.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscriptions.index'),
                'client_reference_id' => $user->id,
                'metadata' => [
                    'user_id' => $user->id,
                    'subscription_type' => $validated['type'],
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to create checkout session: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('subscriptions.index');
        }

        return view('subscriptions.success');
    }

    public function cancel(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        try {
            // Cancel Stripe subscription
            if ($subscription->stripe_subscription_id) {
                $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
                $stripeSubscription->cancel();
            }

            $subscription->cancel();

            // Update user badge
            $user = $subscription->user;
            $user->update([
                'badge_type' => 'none',
                'badge_expires_at' => null,
            ]);

            return redirect()->route('subscriptions.index')->with('success', 'Subscription cancelled successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to cancel subscription: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            default:
                // Unexpected event type
                return response()->json(['error' => 'Unexpected event type'], 400);
        }

        return response()->json(['success' => true]);
    }

    protected function handleCheckoutCompleted($session)
    {
        $userId = $session->client_reference_id ?? $session->metadata->user_id;
        $subscriptionType = $session->metadata->subscription_type;

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return;
        }

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'type' => $subscriptionType,
            'stripe_subscription_id' => $session->subscription,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Update user badge
        $user->update([
            'badge_type' => $subscriptionType,
            'badge_expires_at' => now()->addMonth(),
            'stripe_customer_id' => $session->customer,
        ]);
    }

    protected function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => $stripeSubscription->status === 'active' ? 'active' : 'canceled',
                'ends_at' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
            ]);

            // Update user badge
            $subscription->user->update([
                'badge_expires_at' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
            ]);
        }
    }

    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->cancel();

            // Remove user badge
            $subscription->user->update([
                'badge_type' => 'none',
                'badge_expires_at' => null,
            ]);
        }
    }
}
