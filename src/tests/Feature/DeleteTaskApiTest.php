<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class DeleteTaskApiTest extends TestCase
{
    use RefreshDatabase;

    private $task;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->task = factory(Task::class)->create();
    }

    /**
     * @test
     */
    public function Todoが正常削除できること()
    {
        $response = $this->json('DELETE', route('api.tasks.destroy', [ 'task' => $this->task->id ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 削除したTodoがDBに存在していないこと()
    {
        $this->json('DELETE', route('api.tasks.destroy', [ 'task' => $this->task->id ]));

        $this->assertDatabaseMissing('tasks', [ 'task' => $this->task->task ]);
    }
}
