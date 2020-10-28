<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class StoreTaskApiTest extends TestCase
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
    public function Todoが正常登録できること()
    {
        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => 'テスト1' ]);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function 登録したTodoがDBに存在していること()
    {
        $task = 'テスト2';

        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => $task ]);

        $this->assertDatabaseHas('tasks', [ 'task' => $task ]);
    }

    /**
     * @test
     */
    public function Todoが未指定でバリデーションエラーになること()
    {
        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => '' ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function Todoが文字列以外でバリデーションエラーになること()
    {
        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => 1234 ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function Todoが100文字超えでバリデーションエラーになること()
    {
        $text = '';
        for ($i = 0; $i <= 11; $i++) {
            $text = $text . '0123456789';
        }

        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => $text ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function Todoが登録済みでバリデーションエラーになること()
    {
        $response = $this->json('POST', route('api.tasks.store'), [ 'task' => $this->task->task ]);

        $response->assertStatus(422);
    }
}
