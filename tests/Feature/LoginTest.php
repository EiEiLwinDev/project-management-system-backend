<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['access_token']]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }
}