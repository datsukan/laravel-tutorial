<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class IndexTaskTest extends TestCase
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
    public function Todo一覧ページに正常アクセスできること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function Todo一覧ページにタイトルが表示されていること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertSee('Todoリスト');
    }

    /**
     * @test
     */
    public function Todo一覧ページに再読み込みリンク名が表示されていること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertSee('再読み込み');
    }

    /**
     * @test
     */
    public function Todo一覧ページに登録ページリンク名が表示されていること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertSee('登録');
    }

    /**
     * @test
     */
    public function Todo一覧ページに登録されたToDoが表示されていること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertSee($this->task->task);
    }
}
