<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendMessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_message_when_unauthenticated_should_return_error()
    {
        $data = ['message' => 'Hello how are you?', 'receiver_id' => 2];
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(['message' => 'Unauthenticated.']);
        $response->assertStatus(401);
    }

    public function test_send_message_when_authenticated()
    {
        $data = ['message' => 'Hello how are you?', 'receiver_id' => 2];
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(['result' => 'Message sent successfully', 'message' => 'Hello how are you?']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('sender_receiver', ['receiver_id' => 2, 'sender_id' => $user->id, 'message' => 'Hello how are you?']);
    }

    public function test_send_empty_message_when_authenticated()
    {
        $data = ['message' => '', 'receiver_id' => 2];
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(["message" => [
            'The message field is required.',
        ]]);
        $response->assertStatus(400);
    }

    public function test_send_message_to_self_when_authenticated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $data = ['message' => 'Hello how are you', 'receiver_id' => $user->id];
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(['result' => 'You cannot send a message to yourself']);
        $response->assertStatus(400);
    }

    public function test_send_message_without_receiver_id_when_authenticated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $data = ['message' => 'Hello how are you', 'receiver_id' => ''];
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(["receiver_id" => [
            'The receiver id field is required.',
        ]]);
        $response->assertStatus(400);
    }

    public function test_send_message_with_non_numeric_receiver_id_when_authenticated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $data = ['message' => 'Hello how are you', 'receiver_id' => 'a'];
        $response = $this->post('/api/chat', $data, ['Accept' => 'application/json']);
        $response->assertJson(["receiver_id" => [
            'The receiver id must be an integer.',
        ]]);
        $response->assertStatus(400);
    }
}
