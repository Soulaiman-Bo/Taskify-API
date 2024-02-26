<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tasks = $user->tasks;
        $formattedTasks = TaskResource::collection($tasks);
        return response()->json(['tasks' => $formattedTasks]);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        $formattedTask = new TaskResource($task);



        try {
            $this->authorize('view', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        return response()->json(['task' => $formattedTask]);
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        $task = User::findOrFail($userId)->tasks()->create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'status' => 'to-do',
            'user_id' => $userId,
        ]);

        $formattedTask = new TaskResource($task);


        return response()->json(['task' => $formattedTask], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        try {
            $this->authorize('update', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        $task->update($request->all());

        $formattedTask = new TaskResource($task);


        return response()->json(['task' => $formattedTask]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        try {
            $this->authorize('delete', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
