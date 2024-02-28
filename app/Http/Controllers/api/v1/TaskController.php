<?php

namespace App\Http\Controllers\api\v1;

use App\Contract\TaskRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    protected $Taskrepository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->Taskrepository = $repository;
    }


    public function index()
    {
        $user = auth()->user();

        $task = $this->Taskrepository->showAllTasks($user);

        return response()->json(['tasks' => $task]);
    }

    public function show($id)
    {
        $task = $this->Taskrepository->showTask($id);

        try {
            $this->authorize('view', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        $formattedTask = new TaskResource($task);


        return response()->json(['task' => $formattedTask]);
    }

    public function store(StoreTaskRequest $request)
    {
        $userId = auth()->id();

        $task = $this->Taskrepository->storeTask($request, $userId);

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

        $tasks = $this->Taskrepository->updateTask($request, $task);

        return response()->json(['task' => $tasks]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        try {
            $this->authorize('delete', $task);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
        }

        $this->Taskrepository->deleteTask($task);

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
