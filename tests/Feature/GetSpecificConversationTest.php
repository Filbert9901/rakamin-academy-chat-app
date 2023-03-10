<?php

namespace Tests\Feature;

use App\Models\SenderReceiver;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSpecificConversationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_conversation_from_a_specific_user()
    {
        [$user1, $user2] = User::factory(2)->create();
        $message_from_user1 = SenderReceiver::factory()->create(['receiver_id' => $user2->id, 'sender_id' => $user1->id]);
        $message_from_user2 = SenderReceiver::factory()->create(['receiver_id' => $user1->id, 'sender_id' => $user2->id]);
        Sanctum::actingAs($user1);
        $response = $this->get("/api/chat/{$user2->id}", ['Accept' => 'application/json']);
        $response->assertJson(["data" =>
        [
            [
                "receiver_id" => $user2->id,
                "sender_id" =>  $user1->id,
                "status" => "Unread",
                "message" => $message_from_user1->message,
            ],
            [
                "receiver_id" => $user1->id,
                "sender_id" =>  $user2->id,
                "status" => "Unread",
                "message" => $message_from_user2->message,
            ]
        ]]);
        $response->assertStatus(200);
    }

    public function test_get_conversation_from_a_specific_user_when_unauthenticated_should_return_error()
    {
        $response = $this->get('/api/chat/1', ['Accept' => 'application/json']);
        $response->assertJson(['message' => 'Unauthenticated.']);
        $response->assertStatus(401);
    }

    public function test_get_conversation_from_self()
    {
        $user1 = User::factory()->create();
        Sanctum::actingAs($user1);
        $response = $this->get("/api/chat/{$user1->id}", ['Accept' => 'application/json']);
        $response->assertJson(["result" => "You cannot view a conversation with yourself"]);
        $response->assertStatus(400);
    }

    public function test_get_conversation_from_a_specific_user_when_conversation_is_not_started()
    {
        [$user1, $user2] = User::factory(2)->create();
        Sanctum::actingAs($user1);
        $response = $this->get("/api/chat/{$user2->id}", ['Accept' => 'application/json']);
        $response->assertJson(["result" => "You have not started a conversation with {$user2->name}"]);
        $response->assertStatus(200);
    }
}
