<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * @test
     */
    public function Todo一覧が正常に表示されること()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('tasks.index'))
                    ->assertSee('Todoリスト');
        });
    }
}
