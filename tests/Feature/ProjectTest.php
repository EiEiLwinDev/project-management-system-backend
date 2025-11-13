<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/projects', [
            'title' => 'New Project',
            'description' => 'Some description'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'New Project');
    }

    public function test_non_admin_cannot_create_project()
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'title' => 'New Project',
        ]);

        $response->assertStatus(403);
    }
}