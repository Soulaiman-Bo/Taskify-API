<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CompleteTaskController extends Controller
{
    public function __invoke($id)
    {
        $task = Task::findOrFail($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        try {
            $this->authorize('done', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        $task->status = 'done';
        $task->save();

        return response()->json(['message' => 'Task marked as done successfully']);
    }
}
