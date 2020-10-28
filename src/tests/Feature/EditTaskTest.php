<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class EditTaskTest extends TestCase
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
    public function Todo更新ページに正常アクセスできること()
    {
        $response = $this->get(route('tasks.edit', [ 'task' => $this->task->id ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function Todo更新ページにタイトルが表示されていること()
    {
        $response = $this->get(route('tasks.edit', [ 'task' => $this->task->id ]));

        $response->assertSee('Todo 更新');
    }

    /**
     * @test
     */
    public function Todo更新ページに一覧ページリンク名が表示されていること()
    {
        $response = $this->get(route('tasks.edit', [ 'task' => $this->task->id ]));

        $response->assertSee('一覧へ戻る');
    }

    /**
     * @test
     */
    public function Todo更新ページに選択したタスク名が表示されていること()
    {
        $response = $this->get(route('tasks.edit', [ 'task' => $this->task->id ]));

        $response->assertSee($this->task->task);
    }
}
