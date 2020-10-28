<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskApiPost;
use App\Http\Requests\UpdateTaskApiPut;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskApiPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskApiPost $request)
    {
        return Task::create([ 'task' => $request->input('task') ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskApiPut  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskApiPut $request, Task $task)
    {
        $task->task = $request->input('task');
        $task->save();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return Task::all();
    }
}
