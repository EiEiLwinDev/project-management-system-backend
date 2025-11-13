<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['user', 'access_token']]);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_registration_fails_without_required_fields()
    {
        $response = $this->postJson('/api/v1/auth/register', []);
        $response->assertStatus(422);
    }

    public function test_registration_fails_with_duplicate_email()
    {
        // Create an existing user with the same email
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Attempt to register with the duplicate email
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User 2',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        // Assert that validation fails (HTTP 422)
        $response->assertStatus(422);

        // Optionally, assert that the error contains 'email' key
        $response->assertJsonValidationErrors('email');
    }

}