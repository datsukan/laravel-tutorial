<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectTest extends TestCase
{
    /**
     * @test
     */
    public function ルートにアクセスした場合にTodo一覧ページにリダイレクトすること()
    {
        $response = $this->get('/');

        $response->assertStatus(301)->assertRedirect(route('tasks.index'));
    }
}
