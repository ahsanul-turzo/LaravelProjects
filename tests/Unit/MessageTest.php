<?php

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * MESSAGE MODEL UNIT TESTS
 *
 * Unit tests focus on individual pieces of code (like model methods).
 * They're fast and test business logic in isolation.
 */

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Relationships are the backbone of your app.
// If the sender relationship breaks, you can't
// display who sent a message! This test catches
// typos in relationship methods.
// ============================================
test('message belongs to a sender', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $message = Message::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);

    // Assert the relationship works
    expect($message->sender)->toBeInstanceOf(User::class);
    expect($message->sender->id)->toBe($sender->id);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// If the receiver relationship breaks, your UI
// can't show who received the message!
// ============================================
test('message belongs to a receiver', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $message = Message::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);

    expect($message->receiver)->toBeInstanceOf(User::class);
    expect($message->receiver->id)->toBe($receiver->id);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Messages should be unread by default.
// If this breaks, users won't know when they
// have new messages!
// ============================================
test('new messages are unread by default', function () {
    $message = Message::factory()->create();

    expect($message->is_read)->toBeFalse();
    expect($message->read_at)->toBeNull();
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// The markAsRead() method is used when users
// view messages. If this breaks, messages stay
// "unread" forever!
// ============================================
test('messages can be marked as read', function () {
    $message = Message::factory()->create(['is_read' => false]);

    // Mark as read
    $message->markAsRead();

    expect($message->is_read)->toBeTrue();
    expect($message->read_at)->not->toBeNull();
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Tests that calling markAsRead() on an already
// read message doesn't cause errors. This prevents
// edge case bugs.
// ============================================
test('marking an already read message as read does not cause errors', function () {
    $message = Message::factory()->create([
        'is_read' => true,
        'read_at' => now()->subHour(),
    ]);

    $originalReadAt = $message->read_at->copy();

    // Mark as read again
    $message->markAsRead();

    // Refresh to get updated data
    $message->refresh();

    // Should not throw errors and status remains read
    expect($message->is_read)->toBeTrue();
    expect($message->read_at)->not->toBeNull();
    // The read_at may be updated, but that's okay - the important thing is it doesn't error
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Ensures messages are ordered correctly in conversations.
// Without proper ordering, chats become confusing!
// ============================================
test('messages are ordered by creation time', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Create messages in specific order
    $message1 = Message::factory()->create([
        'sender_id' => $user1->id,
        'receiver_id' => $user2->id,
        'content' => 'First message',
        'created_at' => now()->subMinutes(5),
    ]);

    $message2 = Message::factory()->create([
        'sender_id' => $user2->id,
        'receiver_id' => $user1->id,
        'content' => 'Second message',
        'created_at' => now()->subMinutes(3),
    ]);

    $message3 = Message::factory()->create([
        'sender_id' => $user1->id,
        'receiver_id' => $user2->id,
        'content' => 'Third message',
        'created_at' => now()->subMinutes(1),
    ]);

    // Get messages ordered by created_at
    $orderedMessages = Message::orderBy('created_at')->get();

    expect($orderedMessages->first()->content)->toBe('First message');
    expect($orderedMessages->last()->content)->toBe('Third message');
});
