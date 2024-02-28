<?php

namespace App\repositories;

use App\Contract\TaskRepositoryInterface;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
        public function deleteTask(Task $task)
        {
                $task->delete();
        }


        public function showAllTasks($user)
        {

                $tasks = $user->tasks;
                $formattedTasks = TaskResource::collection($tasks);

                return $formattedTasks;
        }


        public function updateTask(Request $request, Task $task)
        {
                $task->update($request->all());
                $formattedTask = new TaskResource($task);

                return $formattedTask ;
        }
        public function showTask($id)
        {
                $task = Task::findOrFail($id);
                
                return $task;
        }
        public function storeTask(Request $request, $userId)
        {
                $task = User::findOrFail($userId)->tasks()->create([
                        'title' => $request->input('title'),
                        'body' => $request->input('body'),
                        'status' => 'to-do',
                        'user_id' => $userId,
                ]);

                return $task;
        }
}
