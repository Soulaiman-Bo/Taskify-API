<?php
namespace App\Contract;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function deleteTask(Task $task);
    public function updateTask(Request $request, Task $task);
    public function storeTask(Request $request, $userId);
    public function showTask($id);
    public function showAllTasks($user);
}
