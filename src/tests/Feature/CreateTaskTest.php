<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class CreateTaskTest extends TestCase
{
    /**
     * @test
     */
    public function Todo登録ページに正常アクセスできること()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function Todo登録ページにタイトルが表示されていること()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertSee('Todo 登録');
    }

    /**
     * @test
     */
    public function Todo一覧ページに一覧ページリンク名が表示されていること()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertSee('一覧へ戻る');
    }
}
