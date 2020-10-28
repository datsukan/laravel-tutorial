<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskPost;
use App\Http\Requests\UpdateTaskPut;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return view('index', [ 'tasks' => $tasks ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskPost $request)
    {
        Task::create([ 'task' => $request->input('task') ]);

        return view('create', [ 'message' => '登録しました。' ]);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('edit', [ 'task' => $task ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskPut  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskPut $request, Task $task)
    {
        $task->task = $request->input('task');
        $task->save();

        return view('edit', [ 'task' => $task, 'message' => '更新しました。' ]);
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

        $tasks = Task::all();

        return view('index', [ 'tasks' => $tasks, 'message' => '削除しました。' ]);
    }
}
