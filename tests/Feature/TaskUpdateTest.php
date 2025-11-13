<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_update_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        Sanctum::actingAs($manager);

        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'status' => 'in-progress',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'in-progress');
    }
}