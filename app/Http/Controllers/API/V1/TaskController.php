<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\APIController; 
use App\Models\Task;
use App\Services\TaskAssignmentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TaskController extends APIController
{
    /**
     * Display a listing of the resource.
     */
    public function index($projectId)
    {
        $tasks = Task::where('project_id', $projectId)->get();
        return $this->successResponse($tasks, 'Tasks retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'status'=>'nullable|in:pending,in-progress,done',
            'due_date'=>'nullable|date',
            'project_id'=>'required|exists:projects,id',
            'assigned_to'=>'nullable|exists:users,id',
        ]);

        $task = Task::create($data);
        return $this->successResponse($task, 'Task created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            //prevent get someone else's task
            if($request->user()->isUser()) {
                $task = $request->user()->tasks()->where('id', $id)->first();
                if(!$task) {
                    return $this->errorResponse('Task not found', 404);
                }
                return $this->successResponse($task, 'Task retrieved successfully'); 
            }
            $task = Task::findOrFail($id);
            return $this->successResponse($task, 'Task retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //prevent updating someone else's task
            if($request->user()->isUser()) {
                $task = $request->user()->tasks()->where('id', $id)->first();
                if(!$task) {
                    return $this->errorResponse('Task not found', 404);
                }
            }else{
                $task = Task::findOrFail($id);
            }
            $data = $request->validate([
                'title'=>'sometimes|required|string',
                'description'=>'nullable|string',
                'status'=>'nullable|in:pending,in-progress,done',
                'due_date'=>'nullable|date',
                'project_id'=>'sometimes|required|exists:projects,id',
                'assigned_to'=>'nullable|exists:users,id',
            ]);

            $task->update($data);
            return $this->successResponse($task, 'Task updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return $this->successResponse(null, 'Task deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found', 404);
        }
    }

    public function assignedUser(Request $request, string $id, TaskAssignmentService $taskAssignmentService)
    {
        try {
            $task = Task::findOrFail($id);

            $data = $request->validate(['assigned_to'=>'required|exists:users,id']);

            $task = $taskAssignmentService->assignTask($task, $data['assigned_to']);  

            return $this->successResponse($task, 'Task assigned user updated successfully');
            
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found', 404);
        }
    }

}