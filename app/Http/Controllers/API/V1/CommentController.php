<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends APIController
{
    /**
     * Display a listing of the resource.
     */
    public function index($taskId)
    {
        $comments = Comment::where('task_id', $taskId)->get();
        return $this->successResponse($comments, 'Comments retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'body'=>'required|string',
            'task_id'=>'required|exists:tasks,id',
        ]);

        $data['user_id'] = $request->user()->id;
        $comment = Comment::create($data);

        return $this->successResponse($comment, 'Comment created successfully', 201);
    }
}