<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Ensure authentication for all task routes
    }

    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->paginate(10);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date|after:today',
        ]);

        $user = Auth::user();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            "status" => true,
            'message' => 'Task Created Successfully.',
            'data' => $task
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $oldtask = Task::find($id);
        if(empty($oldtask)){
            return response()->json(["status" => false,"message" => "task not found!" ]);
        }
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $task->update($validated);

        return response()->json([
            "status" => true,
            'message' => 'Task Updated Successfully.',
            'data' => $task
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $oldtask = Task::find($id);
        if(empty($oldtask)){
            return response()->json(["status" => false,"message" => "task not found!" ]);
        }
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(["status" => true,'message' => 'Task Deleted Successfully'], 200);
    }
}

