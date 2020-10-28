<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class IndexTaskApiTest extends TestCase
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
    public function Todo一覧が正常取得できること()
    {
        $response = $this->json('GET', route('api.tasks.index'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function Todo一覧に登録されたToDoが含まれていること()
    {
        $response = $this->json('GET', route('api.tasks.index'));

        $response->assertJson([[
            'id'            => $this->task->id,
            'task'          => $this->task->task,
            'created_at'    => $this->task->created_at,
            'updated_at'    => $this->task->updated_at,
        ]]);
    }
}
