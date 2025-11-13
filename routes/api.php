<?php

use App\Http\Controllers\API\V1\AuthController;
use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\API\V1\ProjectController;
use App\Http\Controllers\API\V1\TaskController;
use App\Http\Controllers\API\V1\CommentController;

// Prefix all v1 routes with /api/v1
Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum', 'log')->group(function () {

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Admin → Projects CRUD
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('projects', ProjectController::class);
        });

       // Manager only → create & delete tasks
        Route::middleware('role:manager')->group(function () {
            // List tasks for a specific project
            Route::get('project/tasks/{projectId}', [TaskController::class, 'index']);
            
            // Create task for a specific project
            Route::post('tasks', [TaskController::class, 'store']);

            // Delete task
            Route::delete('tasks/{id}', [TaskController::class, 'destroy']);

            // Assign user to task
            Route::patch('tasks/{id}', [TaskController::class, 'assignedUser']);
        });

        // Manager & Assigned User → View & Update tasks
        Route::middleware('manager_or_assigned')->group(function() {
          Route::get('tasks/{id}', [TaskController::class, 'show']);
          Route::put('tasks/{id}', [TaskController::class, 'update']);
        });

        // get comments for a specific task
        Route::get('tasks/comments/{taskId}', [CommentController::class, 'index']);

        //create comment for a specific task
        Route::post('tasks/comments', [CommentController::class, 'store']);
    });
});