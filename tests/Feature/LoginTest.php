<?php

namespace Tests\Feature;

use App\Models\SenderReceiver;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_with_correct_credentials()
    {
        $user = User::factory()->create();
        $data = ['email' => $user->email, 'password' => 'password'];
        $response = $this->post('/api/login', $data, ['Accept' => 'application/json']);
        $response->assertJson([
            "user" =>
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
        $response->assertStatus(201);
    }

    public function test_login_with_incorrect_credentials()
    {
        $user = User::factory()->create();
        $data = ['email' => $user->email, 'password' => 'notmypassword'];
        $response = $this->post('/api/login', $data, ['Accept' => 'application/json']);
        $response->assertJson(["message" => "These credentials do not match our records."]);
        $response->assertStatus(404);
    }
}
