<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\SenderReceiver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetConversationListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_conversation_list()
    {
        [$user1, $user2, $user3] = User::factory(3)->create();
        $message_from_user2 = SenderReceiver::factory()->create(['receiver_id' => $user1->id, 'sender_id' => $user2->id]);
        $message_from_user3 = SenderReceiver::factory()->create(['receiver_id' => $user1->id, 'sender_id' => $user3->id]);
        Sanctum::actingAs($user1);
        $response = $this->get("/api/chat/", ['Accept' => 'application/json']);
        $response->assertJson(["data" =>
        [
            [
                "unread_count" => 1,
                "name" => $user2->name,
                "message" => $message_from_user2->message,
            ],
            [
                "unread_count" => 1,
                "name" => $user3->name,
                "message" => $message_from_user3->message,
            ]
        ]]);
        $response->assertStatus(200);
    }

    public function test_get_all_conversation_list_when_unauthenticated_should_return_error()
    {
        $response = $this->get('/api/chat', ['Accept' => 'application/json']);
        $response->assertJson(['message' => 'Unauthenticated.']);
        $response->assertStatus(401);
    }

    public function test_get_all_conversation_list_when_there_is_no_conversation()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->get("/api/chat/", ['Accept' => 'application/json']);
        $response->assertJson(['result' => 'You have not started any conversation yet']);
        $response->assertStatus(200);
    }
}
