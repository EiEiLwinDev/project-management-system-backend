<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::factory()->count(3)->state(['role' => 'admin'])->create();
        User::factory()->count(3)->state(['role' => 'manager'])->create();
        User::factory()->count(5)->state(['role' => 'user'])->create();

        // Projects
        Project::factory()->count(5)->create();

        // Tasks
        Task::factory()->count(10)->create();

        // Comments
        Comment::factory()->count(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
    }
}