<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Task;

class CreateTaskTest extends DuskTestCase
{
    /**
     * @test
     */
    public function Todoが正常に登録できること()
    {
        $this->artisan('migrate:refresh');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.create'))
                    ->assertSee('Todo 登録')
                    ->type('task', 'てすと')
                    ->press('登録')
                    ->assertSee('登録しました。');
        });
    }

    /**
     * @test
     */
    public function Todo未入力でバリデーションエラーになること()
    {
        $this->artisan('migrate:refresh');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.create'))
                    ->assertSee('Todo 登録')
                    ->type('task', '')
                    ->press('登録')
                    ->assertSee('タスクは必須項目です。');
        });
    }

    /**
     * @test
     */
    public function Todo100桁以上入力でバリデーションエラーになること()
    {
        $this->artisan('migrate:refresh');

        $text = '';
        for ($i = 0; $i <= 11; $i++) {
            $text = $text . '0123456789';
        }

        $this->browse(function (Browser $browser) use ($text) {
            $browser->visit(route('tasks.create'))
                    ->assertSee('Todo 登録')
                    ->type('task', $text)
                    ->press('登録')
                    ->assertSee('タスクは100文字以内で入力してください。');
        });
    }

    /**
     * @test
     */
    public function Todoが登録済みでバリデーションエラーになること()
    {
        $this->artisan('migrate:refresh');

        $task = 'てすとてすと';

        $this->browse(function (Browser $browser) use ($task) {
            $browser->visit(route('tasks.create'))
                    ->assertSee('Todo 登録')
                    ->type('task', $task)
                    ->press('登録')
                    ->assertSee('登録しました。')
                    ->visit(route('tasks.create'))
                    ->type('task', $task)
                    ->press('登録')
                    ->assertSee('タスクは既に登録されています。');
        });
    }
}
