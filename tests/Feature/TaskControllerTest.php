<?php

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;

use Mockery;


it("should returns all user's tasks", function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->getJson(route('tasks.index'));

    $response->assertSuccessful();

    $response->assertJson([
        'tasks' => TaskResource::collection($tasks)->jsonSerialize(),
    ]);
});



it('can create a new task', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson(route('tasks.store'), [
        'title' => 'New Task',
        'body' => 'Task description',
    ]);

    $response->assertJsonStructure([
        'task' => [
            'id',
            'title',
            'body',
            'status',
        ],
    ]);

    $response->assertStatus(201);
})->group('TaskController');
