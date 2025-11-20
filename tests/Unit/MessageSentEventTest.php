<?php

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * MESSAGE SENT EVENT TESTS
 *
 * These tests ensure your real-time broadcasting works correctly.
 * Without these, you wouldn't know if WebSocket updates are broken!
 */

// ============================================
// WHY THIS TEST IS IMPORTANT:
// If the event broadcasts to the wrong channel,
// users won't receive real-time updates!
// This test ensures messages go to the RIGHT person.
// ============================================
test('message sent event broadcasts on correct channel', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $message = Message::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);

    $event = new MessageSent($message);

    // Get the channels this event broadcasts on
    $channels = $event->broadcastOn();

    // Assert it's a PrivateChannel (for security)
    expect($channels[0])->toBeInstanceOf(PrivateChannel::class);

    // Assert it broadcasts to the RECEIVER's channel
    // Note: Laravel automatically prefixes private channels with 'private-'
    expect($channels[0]->name)->toBe('private-chat.' . $receiver->id);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// The event name must match what Echo listens for.
// If this changes accidentally, real-time updates
// stop working and users see "Failed to receive message"!
// ============================================
test('message sent event has correct broadcast name', function () {
    $message = Message::factory()->create();
    $event = new MessageSent($message);

    expect($event->broadcastAs())->toBe('message.sent');
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// The broadcast data is what JavaScript receives.
// If this doesn't include the sender info, your
// UI can't display who sent the message!
// This test ensures all necessary data is sent.
// ============================================
test('message sent event broadcasts correct data', function () {
    $sender = User::factory()->create(['name' => 'Alice']);
    $receiver = User::factory()->create(['name' => 'Bob']);

    $message = Message::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'content' => 'Test message',
    ]);

    $event = new MessageSent($message);

    $broadcastData = $event->broadcastWith();

    // Assert all necessary data is included
    expect($broadcastData)->toHaveKeys([
        'id',
        'sender_id',
        'receiver_id',
        'content',
        'created_at',
        'sender',
    ]);

    // Assert sender information is complete
    expect($broadcastData['sender'])->toHaveKeys(['id', 'name', 'avatar']);
    expect($broadcastData['sender']['name'])->toBe('Alice');
    expect($broadcastData['content'])->toBe('Test message');
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Events should broadcast immediately (not queued)
// for real-time messaging. This test ensures we're
// using ShouldBroadcastNow, not ShouldBroadcast.
// ============================================
test('message sent event broadcasts immediately', function () {
    // Get the interfaces the MessageSent class implements
    $interfaces = class_implements(MessageSent::class);

    // Assert it implements ShouldBroadcastNow (not ShouldBroadcast)
    expect($interfaces)->toHaveKey('Illuminate\Contracts\Broadcasting\ShouldBroadcastNow');
});
