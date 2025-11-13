<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_to_task()
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $project = Project::factory()->create(['created_by' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $user->id]);

        $response = $this->postJson("/api/v1/tasks/comments", [
            'body' => 'This is a comment.',
            'task_id' => $task->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.body', 'This is a comment.');
    }
}