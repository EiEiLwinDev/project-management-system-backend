<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Validation\ValidationException;

class TaskAssignmentService
{
    /**
     * Assign a task to a user
     */
    public function assignTask(Task $task, int $userId): Task
    {
        $user = User::find($userId);
        if (!$user) {
            throw ValidationException::withMessages(['assigned_to' => 'User not found']);
        }

        $task->assigned_to = $user->id;

        $task->save();

        $user->notify(new TaskAssignedNotification($task));

        return $task;
    }
}