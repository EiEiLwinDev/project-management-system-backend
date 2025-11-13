<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerOrAssigned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $taskId = $request->route('id'); // gets {id} from route

        // If user is manager, allow
        if ($user->role === 'manager') {
            return $next($request);
        }

        // Check if task exists and is assigned to this user
        $task = Task::find($taskId);
        if ($task && $task->assigned_to === $user->id) {
            return $next($request);
        }

        // Otherwise, deny access
        return response()->json([
            'success' => false,
            'message' => 'Forbidden. You are not authorized to update this task.'
        ], 403);
    }
}