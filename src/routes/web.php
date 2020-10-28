<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ルートへのアクセスをリダイレクト
Route::redirect('/', '/tasks', 301);

// Todo リソースルーティング
Route::resource('tasks', 'TaskController');