<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Task;

class DeleteTaskTest extends DuskTestCase
{
    /**
     * @test
     */
    public function Todoが正常に削除できること()
    {
        $this->artisan('migrate:refresh');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.create'))
                    ->assertSee('Todo 登録')
                    ->type('task', 'てすと!!')
                    ->press('登録')
                    ->assertSee('登録しました。')
                    ->visit(route('tasks.index'))
                    ->assertSee('Todoリスト')
                    ->press('削除')
                    ->assertDontSee('てすと!!');
        });
    }
}
