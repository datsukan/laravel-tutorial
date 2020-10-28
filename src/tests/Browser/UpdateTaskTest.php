<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Task;

class UpdateTaskTest extends DuskTestCase
{
    /**
     * @test
     */
    public function Todoが正常に更新できること()
    {
        $this->artisan('migrate:refresh');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.create'))
                    ->type('task', 'てすと1')
                    ->press('登録')
                    ->visit(route('tasks.index'))
                    ->clickLink('更新')
                    ->assertSee('Todo 更新')
                    ->type('task', 'てすと2')
                    ->press('更新')
                    ->assertSee('更新しました。');
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
                    ->type('task', 'てすと')
                    ->press('登録')
                    ->visit(route('tasks.index'))
                    ->clickLink('更新')
                    ->assertSee('Todo 更新')
                    ->type('task', '')
                    ->press('更新')
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
                    ->type('task', 'てすと')
                    ->press('登録')
                    ->visit(route('tasks.index'))
                    ->clickLink('更新')
                    ->assertSee('Todo 更新')
                    ->type('task', $text)
                    ->press('更新')
                    ->assertSee('タスクは100文字以内で入力してください。');
        });
    }

    /**
     * @test
     */
    public function Todoが更新済みでバリデーションエラーになること()
    {
        $this->artisan('migrate:refresh');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.create'))
                    ->type('task', 'てすと')
                    ->press('登録')
                    ->visit(route('tasks.index'))
                    ->clickLink('更新')
                    ->type('task', 'てすと')
                    ->press('更新')
                    ->assertSee('タスクは既に登録されています。');
        });
    }
}
