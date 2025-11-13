<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TaskAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskAssignmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskAssignmentService();
    }

    /** @test */
    public function it_can_assign_task_to_user()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $task = Task::factory()->create();

        $result = $this->service->assignTask($task, $user->id);

        $this->assertEquals($user->id, $result->assigned_to);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'assigned_to' => $user->id,
        ]);
    }

    /** @test */
    public function it_throws_validation_exception_if_user_does_not_exist()
    {
        $this->expectException(ValidationException::class);

        User::factory()->create();

        Project::factory()->create();
        
        $task = Task::factory()->create();

        // Non-existent user ID
        $this->service->assignTask($task, 9999);
    }
}