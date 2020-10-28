<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = ['買い出し', '電球の交換', 'トイレ掃除', 'ゴミ出し', '申請書類の作成'];
        $insertArr = [];

        foreach ($tasks as $task) {
            $insertArr[] = [
                'task' => $task,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('tasks')->insert($insertArr);
    }
}
