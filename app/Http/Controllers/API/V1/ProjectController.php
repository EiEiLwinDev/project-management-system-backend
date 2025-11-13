<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\APIController; 
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends APIController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Cache::remember('projects_list', now()->addMinutes(10), function () {
            return Project::with('tasks')->get();
        });

        return $this->successResponse($projects, 'Projects retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date',
        ]);

        $data['created_by'] = $request->user()->id;
        
        $project = Project::create($data);

        Cache::forget('projects_list');
        
        return $this->successResponse($project, 'Project created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $project = Project::findOrFail($id);
            return $this->successResponse($project, 'Project retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Project not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $data = $request->validate([
            'title'=>'sometimes|required|string',
            'description'=>'nullable|string',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date',
        ]);

        $project->update($data);

        Cache::forget('projects_list');

        return $this->successResponse($project, 'Project updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            Cache::forget('projects_list');
            return $this->successResponse(null, 'Project deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Project not found', 404);
        }
    }
}