<?php

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Event;

/**
 * MESSAGING FEATURE TESTS
 *
 * These tests ensure our real-time messaging works correctly.
 * They catch bugs BEFORE users experience them!
 */

beforeEach(function () {
    // Create two users for testing conversations
    $this->sender = User::factory()->create(['name' => 'Alice']);
    $this->receiver = User::factory()->create(['name' => 'Bob']);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// If the messages page breaks, users can't communicate!
// This test catches that immediately.
// ============================================
test('users can view their messages inbox', function () {
    // Act as the sender
    $this->actingAs($this->sender);

    // Visit the messages page
    $response = $this->get(route('messages.index'));

    // Assert the page loads successfully
    $response->assertStatus(200);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// If users can't view conversations, the whole
// messaging feature is broken. This catches routing
// issues, permission problems, etc.
// ============================================
test('users can view a conversation with another user', function () {
    // Act as the sender
    $this->actingAs($this->sender);

    // Visit the conversation page
    $response = $this->get(route('messages.show', $this->receiver));

    // Assert the page loads successfully
    $response->assertStatus(200);
    // Assert the receiver's name is shown
    $response->assertSee($this->receiver->name);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// This is the CORE functionality - sending messages!
// If this breaks, your entire messaging system fails.
// This test catches database issues, validation problems,
// and ensures messages are saved correctly.
// ============================================
test('users can send messages to other users', function () {
    // Act as the sender
    $this->actingAs($this->sender);

    // Send a message
    $response = $this->post(route('messages.store', $this->receiver), [
        'content' => 'Hey Bob, how are you?'
    ]);

    // Assert the message was saved to the database
    $this->assertDatabaseHas('messages', [
        'sender_id' => $this->sender->id,
        'receiver_id' => $this->receiver->id,
        'content' => 'Hey Bob, how are you?',
    ]);

    // Assert response redirects back (normal form submission behavior)
    $response->assertRedirect();
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Empty messages waste database space and confuse users.
// This test ensures your validation works correctly.
// Without this test, a code change could accidentally
// allow empty messages!
// ============================================
test('messages cannot be empty', function () {
    $this->actingAs($this->sender);

    // Try to send an empty message
    $response = $this->post(route('messages.store', $this->receiver), [
        'content' => ''
    ]);

    // Assert validation error
    $response->assertSessionHasErrors('content');

    // Assert NO message was saved to database
    $this->assertDatabaseCount('messages', 0);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Long messages can break your UI or database.
// This test ensures messages don't exceed limits.
// ============================================
test('messages cannot exceed maximum length', function () {
    $this->actingAs($this->sender);

    // Try to send a message that's too long (over 1000 characters)
    $veryLongMessage = str_repeat('A', 1001);

    $response = $this->post(route('messages.store', $this->receiver), [
        'content' => $veryLongMessage
    ]);

    // Assert validation error
    $response->assertSessionHasErrors('content');
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Unauthenticated users shouldn't be able to send messages!
// This test ensures your authentication is working.
// Without this, anyone could spam your users!
// ============================================
test('guests cannot send messages', function () {
    // Don't authenticate - act as guest

    $response = $this->post(route('messages.store', $this->receiver), [
        'content' => 'Hello'
    ]);

    // Assert redirect to login
    $response->assertRedirect(route('login'));

    // Assert no message was created
    $this->assertDatabaseCount('messages', 0);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Real-time messaging requires broadcasting events.
// This test ensures the MessageSent event is fired
// when a message is sent. If this breaks, real-time
// updates stop working!
// ============================================
test('sending a message broadcasts an event for real-time updates', function () {
    Event::fake([MessageSent::class]);

    $this->actingAs($this->sender);

    // Send a message
    $this->post(route('messages.store', $this->receiver), [
        'content' => 'Testing real-time!'
    ]);

    // Assert the MessageSent event was broadcast
    Event::assertDispatched(MessageSent::class, function ($event) {
        return $event->message->content === 'Testing real-time!'
            && $event->message->receiver_id === $this->receiver->id;
    });
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// When API clients send messages (AJAX), they expect
// JSON responses. This test ensures your API returns
// the correct format with all necessary data.
// ============================================
test('messages endpoint returns JSON for AJAX requests', function () {
    $this->actingAs($this->sender);

    // Send message as JSON (like our AJAX code does)
    $response = $this->postJson(route('messages.store', $this->receiver), [
        'content' => 'API test message'
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message' => [
            'id',
            'content',
            'sender_id',
            'receiver_id',
            'created_at',
            'sender' => ['id', 'name'],
            'receiver' => ['id', 'name']
        ]
    ]);
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Messages should be marked as read when viewed.
// This test ensures the read status works correctly.
// ============================================
test('viewing a conversation marks messages as read', function () {
    // Bob sends a message to Alice
    $message = Message::create([
        'sender_id' => $this->receiver->id,
        'receiver_id' => $this->sender->id,
        'content' => 'Hey Alice!',
        'is_read' => false,
    ]);

    // Assert message is unread
    expect($message->is_read)->toBeFalse();

    // Alice views the conversation
    $this->actingAs($this->sender);
    $this->get(route('messages.show', $this->receiver));

    // Refresh the message from database
    $message->refresh();

    // Assert message is now marked as read
    expect($message->is_read)->toBeTrue();
    expect($message->read_at)->not->toBeNull();
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Users should only see messages in their own conversations.
// This test ensures privacy and security - User A shouldn't
// see User B and User C's private messages!
// ============================================
test('users only see messages in their own conversations', function () {
    // Create a third user
    $otherUser = User::factory()->create(['name' => 'Charlie']);

    // Bob sends a message to Charlie (NOT to Alice)
    Message::create([
        'sender_id' => $this->receiver->id,
        'receiver_id' => $otherUser->id,
        'content' => 'Private message to Charlie',
    ]);

    // Alice sends a message to Bob
    Message::create([
        'sender_id' => $this->sender->id,
        'receiver_id' => $this->receiver->id,
        'content' => 'Hi Bob!',
    ]);

    // Alice views her conversation with Bob
    $this->actingAs($this->sender);
    $response = $this->get(route('messages.show', $this->receiver));

    // Alice should see her message to Bob
    $response->assertSee('Hi Bob!');

    // Alice should NOT see Bob's private message to Charlie
    $response->assertDontSee('Private message to Charlie');
});

// ============================================
// WHY THIS TEST IS IMPORTANT:
// Users need to know how many unread messages they have.
// This test ensures the unread count is accurate.
// ============================================
test('unread message count is accurate', function () {
    // Create some messages
    // 2 unread messages from Bob to Alice
    Message::factory()->count(2)->create([
        'sender_id' => $this->receiver->id,
        'receiver_id' => $this->sender->id,
        'is_read' => false,
    ]);

    // 1 read message from Bob to Alice
    Message::factory()->create([
        'sender_id' => $this->receiver->id,
        'receiver_id' => $this->sender->id,
        'is_read' => true,
    ]);

    // Alice checks her unread count
    $this->actingAs($this->sender);
    $response = $this->getJson(route('messages.unread'));

    $response->assertJson(['count' => 2]);
});
