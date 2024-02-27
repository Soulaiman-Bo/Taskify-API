<?php

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;


it('returns a JSON response with formatted tasks for the authenticated user', function () {

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



// it('can fetch user tasks', function () {

//     $user = User::factory()->create();

//     $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

//     $response = $this->actingAs($user)->getJson(route('tasks.index'));

//     $response->assertJson([
//         'tasks' => TaskResource::collection($tasks)
//     ]);

//     // And the response should have a 200 status
//     $response->assertStatus(200);
// })->group('TaskController');
