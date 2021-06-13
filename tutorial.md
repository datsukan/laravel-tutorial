# チュートリアル概要

Todo リストの Web アプリと API、およびそれら実装のテストコード作成までを一連の流れで学習できます。  
開発環境の構築作業をせずに、アプリケーション部分に集中して作業することができます。

master ブランチがサンプル実装済み、defalut ブランチが未実装・実装前準備済みになっています。  
defalut ブランチでチュートリアルを進めつつ、master ブランチを見てカンニングしましょう。

# 環境準備

- [仮想環境 + ソース](https://github.com/datsukan/laravel-tutorial)を Github から取得
- Docker をインストール
- readme.md に合わせてセットアップ

## 環境の確認

`http://localhost`で接続して画面が表示されたら構築完了です。  
ブランチは master がサンプル実装済み、default が実装前の準備済みになっています。

# チュートリアル対象

- Laravel 5.6
- PHP 7.3

Laravel の使い方は基本的に[公式ドキュメント](https://readouble.com/laravel/5.6/ja/)を読んでください。

# チュートリアル内容

## Web アプリケーションの実装

- 一覧ページ表示
- 登録ページ表示
- 登録
- 更新ページ表示
- 更新
- 削除

## API の実装

- 一覧取得
- 登録
- 更新
- 削除

## Web アプリケーションの統合テスト

- 疎通
- HTTP ステータス
- 画面表示

## API の統合テスト

- 疎通
- HTTP ステータス
- レスポンス内容
- バリデーション

## Web アプリケーションのブラウザテスト

- 表示
- 画面遷移
- フォームの制御
- CRUD の整合性

# 実装手順

## Web アプリケーション

### コントローラーとモデルの作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

`src`配下でリソースコントローラー・ルーティング・モデルの作成コマンドを実行する。

```bash
php artisan make:controller TaskController --resource --model=Task
```

### ルーティングの追加

1. `routes/web.php`を開く。

2. 下記を削除する。

```php
Route::get('/', function () {
    return view('welcome');
});
```

3. 下記を追記する。

```php
// ルートへのアクセスをリダイレクト
Route::redirect('/', '/tasks', 301);

// Todo リソースルーティング
Route::resource('tasks', 'TaskController');
```

### index でハローワールド確認

`app\Http\Controllers\TaskController.php`の`index`メソッドに追記する。

```php
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Hello world";
    }
```

`http://localhost/tasks`にブラウザで接続して「Hello world」が表示されることを確認する。

### マイグレーションの作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

マイグレーションファイルの作成コマンドを実行する。

```bash
$ php artisan make:migration create_tasks_table --create=tasks
```

### マイグレーションの定義作成

作成コマンドで`database/migrations`配下に`YYYY_MM_DD_hhmmss_create_tasks_table.php`でマイグレーションファイルが生成されている。  
下記の内容に編集して定義を作成する。

```php
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('タスクID');
            $table->string('task', 100)->comment('タスク');
            $table->timestamps();
        });
    }
```

### マイグレーションの実行

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

マイグレーションコマンドを実行して DB にテーブルを生成する。

```bash
$ php artisan migrate
```

### DB の確認

SQL（DB）クライアントソフトでデータベースに接続してテーブルが生成されていることを確認する。  
詳細は[SQLクライアントソフトの接続手順](sqlclient.md)を参照してください。

| 設定項目 | 値        |
| -------- | --------- |
| db       | mysql     |
| Host     | localhost |
| Port     | 3306      |
| Database | tutorial  |
| UserName | root      |
| Password | password  |

### シーダーを作成する

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

動作確認用に DB にデータを登録するためのシーダーを作成する。

```bash
$ php artisan make:seeder TasksTableSeeder
```

`database/seeds/TasksTableSeeder.php`を開いて下記の通り編集する。

```php
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
```

`database/seeds/DatabaseSeeder.php`を開いて下記の通り編集する。

```php
/**
 * Seed the application's database.
 *
 * @return void
 */
public function run()
{
    $this->call(TasksTableSeeder::class);
}
```

シーダーを実行する。

```bash
$ php artisan db:seed
```

SQL（DB）クライアントソフトでデータベースに接続してテーブルにデータが登録されていることを確認する。

### Index のレスポンス

DB の値を取得して返すように`app\Http\Controllers\TaskController.php`の`index`メソッドを変更する。

```php
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
    }
```

`http://localhost/tasks`にブラウザで接続して DB の値が配列として表示されることを確認する。  
※日本語は URL エンコードされている状態

### 一覧のビューの作成

`resources\views\`配下に Todo 一覧のビューのブレードとして`index.blade.php`を作成する。

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoリスト | 一覧</title>

    <style>
        ul li {
            margin: 5px 0;
        }
        ul li * {
            display:inline-block;
        }
    </style>
</head>
<body>
    <h1>Todoリスト</h1>
    @if (!empty($message))
        <p><b>{{ $message }}</b></p>
    @endif
    <a href="{{ route('tasks.index') }}">再読み込み</a>
    <a href="{{ route('tasks.create') }}">登録</a>
    @if (count($tasks) === 0)
        <p>Todoが登録されていません。</p>
    @endif
    <ul>
        @foreach($tasks as $task)
            <li>
                {{ $task->task }}
                <a href="{{ route('tasks.edit', ['task' => $task->id]) }}">更新</a>
                <form action="{{ route('tasks.destroy', [ 'task' => $task->id ]) }}" method="post">
                    @csrf
                    @method('delete')
                    <input type="submit" value="削除" />
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
```

ビューにデータを渡すように`app\Http\Controllers\TaskController.php`の`index`メソッドを変更する。

```php
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return view('index', [ 'tasks' => $tasks ]);
    }
```

### 登録のビューを作成

`resources\views\`配下に Todo 登録のビューのブレードとして`create.blade.php`を作成する。

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoリスト | 登録</title>
</head>
<body>
    <h1>Todo 登録</h1>
    @if (!empty($message))
        <p><b>{{ $message }}</b></p>
    @endif
    @if (!empty($errors) && count($errors) > 0)
        @foreach($errors->get('task') as $error)
            <p><b>{{ $error }}</b></p>
        @endforeach
    @endif
    <form action="{{ route('tasks.store') }}" method="post">
        @csrf
        <input type="text" name="task">
        <input type="submit" value="登録" />
    </form>
    <a href="{{ route('tasks.index') }}">一覧へ戻る</a>
</body>
</html>
```

登録ページへ遷移するように`app\Http\Controllers\TaskController.php`の`create`メソッドを変更する。

```php
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

```

### 登録処理を作成

Todo 登録ページから入力された内容で登録を行って、再度 Todo 登録ページを返却する。  
※`app\Http\Controllers\TaskController.php`の`store`メソッド

```php
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Task::create([ 'task' => $request->input('task') ]);

        return view('create', [ 'message' => '登録しました。' ]);
    }
```

### モデルに複数代入可能にする設定を追加

Laravel の ORM である Eloquent の create メソッドが使用できるように、モデルに複数代入が可能な設定を追加する。  
`app/Task.php`を開いて下記の通り編集する。

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = ['task'];
}
```

### 更新のビューを作成

`resources\views\`配下に Todo 登録のビューのブレードとして`edit.blade.php`を作成する。

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoリスト | 更新</title>
</head>
<body>
    <h1>Todo 更新</h1>
    @if (!empty($message))
        <p><b>{{ $message }}</b></p>
    @endif
    @if (!empty($errors) && count($errors) > 0)
        @foreach($errors->get('task') as $error)
            <p><b>{{ $error }}</b></p>
        @endforeach
    @endif
    <form action="{{ route('tasks.update', [ 'task' => $task->id ]) }}" method="post">
        @csrf
        @method('put')
        <input type="text" name="task" value="{{ $task->task }}">
        <input type="submit" value="更新" />
    </form>
    <a href="{{ route('tasks.index') }}">一覧へ戻る</a>
</body>
</html>
```

更新ページへ遷移するように`app\Http\Controllers\TaskController.php`の`edit`メソッドを変更する。

```php
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('edit', [ 'task' => $task ]);
    }
```

### 更新処理を作成

Todo 更新ページから入力された内容で更新を行って、再度 Todo 更新ページを返却する。  
`app\Http\Controllers\TaskController.php`の`update`メソッドを下記の通り編集する。

```php
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->task = $request->input('task');
        $task->save();

        return view('edit', [ 'task' => $task, 'message' => '更新しました。' ]);
    }
```

### 削除処理を作成

Todo 一覧ページから入力された内容で削除を行って、再度 Todo 一覧ページを返却する。  
`app\Http\Controllers\TaskController.php`の`destroy`メソッドを下記の通り編集する。

```php
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        $tasks = Task::all();

        return view('index', [ 'tasks' => $tasks, 'message' => '削除しました。' ]);
    }
```

### バリデーションを作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

入力内容の検証を行うため、FormRequest クラスを作成する。

```bash
$ php artisan make:request StoreTaskPost
$ php artisan make:request UpdateTaskPut
```

### バリデーション処理を作成

`app\Http\Requests\StoreTaskPost.php`を開いて下記の通り編集する。

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task' => 'required|string|max:100|unique:tasks',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'task' => 'タスク',
        ];
    }

    /**
     * Set the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'task.required' => ':attributeは必須項目です。',
            'task.string' => ':attributeは文字を入力してください。',
            'task.max' => ':attributeは:max文字以内で入力してください。',
            'task.unique' => ':attributeは既に登録されています。',
        ];
    }
}
```

`app\Http\Requests\UpdateTaskPut.php`を開いて下記の通り編集する。

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskPut extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task' => 'required|string|max:100|unique:tasks',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'task' => 'タスク',
        ];
    }

    /**
     * Set the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'task.required' => ':attributeは必須項目です。',
            'task.string' => ':attributeは文字を入力してください。',
            'task.max' => ':attributeは:max文字以内で入力してください。',
            'task.unique' => ':attributeは既に登録されています。',
        ];
    }
}
```

### 動作確認

ここまでの手順で一通りの画面実装が完成しました！  
ブラウザから`http://localhost/task`を開いて下記の操作を試してみましょう。  
すべて正しく実装されていれば正常に動作します。

#### タスク一覧の表示

1. ブラウザから`http://localhost/task`を開く
2. `再読み込み`リンクをクリックする
3. タスクの一覧が表示されることを確認する

#### タスクの削除

1. ブラウザから`http://localhost/task`を開く
2. 表示されているタスクの`削除`ボタンをクリックする
3. 削除した内容が一覧に表示されなくなっていることを確認する

#### タスクの登録

1. ブラウザから`http://localhost/task`を開く
2. `登録`リンクをクリックする
3. タスク内容を入力して`登録`ボタンをクリックする
4. `一覧に戻る`リンクをクリックする
5. 登録した内容が一覧に追加されていることを確認する

#### タスクの更新

1. ブラウザから`http://localhost/task`を開く
2. 各タスクの`更新`リンクをクリックする
3. 入力欄のタスクを書き換えて`更新`ボタンをクリックする
4. `一覧へ戻る`リンクをクリックする
5. 更新した内容が一覧の表示へ反映されていることを確認する

## API の実装

### API のルーティング

下記の処理を削除する。

```php
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
```

API でタスクリソースを操作できるように`routes/api.php`にルーティングを追加する。  
※Web のルーティングと name が重複しないようにエイリアス（api.）を付与する。

```php
Route::group([ 'as' => 'api.' ], function(){
    Route::apiResource('tasks', 'TaskApiController');
});
```

### API のコントローラーの作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

API のコントローラーを生成するコマンドを実行する。

```bash
$ php artisan make:controller TaskApiController --resource --model=Task --api
```

### REST Client の確認用ファイルの作成

API をコールするために`tests\Http`配下に REST Client（VSCode 拡張）の定義ファイルとして`Todo.php`を作成する。  
DBのデータ状態は操作した状況によって変わっているはずなので、「Todo 更新」「Todo 削除」のURL末尾で指定しているIDは、「ToDo 登録」のレスポンス内容に合わせて変えてください。

```
### ToDo 一覧取得
GET http://localhost/api/tasks

### ToDo 登録
POST http://localhost/api/tasks
content-type: application/json

{
    "task": "部屋の換気"
}

### Todo 更新
PUT http://localhost/api/tasks/7
content-type: application/json

{
    "task": "部屋の換気!?"
}

### ToDo 削除
DELETE http://localhost/api/tasks/7
```

### コントローラーの実装

タスクリソースの CRUD を行うための処理を`app\Http\Controllers\TaskApiController.php`に記述する。  
レスポンスは配列で戻せば Laravel が自動的に Json に変換する。

```php
<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Task::create([ 'task' => $request->input('task') ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->task = $request->input('task');
        $task->save();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return Task::all();
    }
}

```

### バリデーションの実装

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

入力内容の検証を行うため、FormRequest クラスを作成する。

```bash
$ php artisan make:request StoreTaskApiPost
$ php artisan make:request UpdateTaskApiPut
```

## バリデーション処理を作成

`app\Http\Requests\StoreTaskApiPost.php`を下記の通り編集する。

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskApiPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task' => 'required|string|max:100|unique:tasks',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'task' => 'タスク',
        ];
    }

    /**
     * Set the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'task.required' => ':attributeは必須項目です。',
            'task.string' => ':attributeは文字を入力してください。',
            'task.max' => ':attributeは:max文字以内で入力してください。',
            'task.unique' => ':attributeは既に登録されています。',
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        $res = response()->json([
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($res);
    }
}
```

`app\Http\Requests\UpdateTaskApiPut.php`を下記の通り編集する。

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskApiPut extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task' => 'required|string|max:100|unique:tasks',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'task' => 'タスク',
        ];
    }

    /**
     * Set the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'task.required' => ':attributeは必須項目です。',
            'task.string' => ':attributeは文字を入力してください。',
            'task.max' => ':attributeは:max文字以内で入力してください。',
            'task.unique' => ':attributeは既に登録されています。',
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        $res = response()->json([
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($res);
    }
}
```

### REST Client で API をコール

`tests\Http`配下に定義した内容で API の機能を一通り実行してレスポンスが正常か確認する。

## テストの実装

### ファクトリの生成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

テストデータを生成するためのファクトリを作成する。

```bash
$ php artisan make:factory TaskFactory --model=Task
```

`database\factories\`配下の`TaskFactory.php`を下記の通り編集する。

```php
<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'task'          => $faker->word,
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ];
});
```

### 統合テストの作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

コマンドを実行して統合テストを作成する。

```bash
$ php artisan make:test IndexTaskTest
```

### 統合テストを記述

1. `tests/Feature/ExampleTest.php`を削除する。
2. `tests/Feature/IndexTaskTest.php`を開く。
3. `testExample`メソッドを削除する。
4. コマンドで作成したファイルに疎通、HTTP ステータス、バリデーションなどのテストを記述する。  
下記はあくまで一例なので、masterブランチの`tests/Feature/IndexTaskTest.php`を参照して他のテストケースも作成する。

```php

    /**
     * @test
     */
    public function Todo一覧ページに正常アクセスできること()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
    }
```

### 統合テストを実行

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

作成した統合テストを実施するためのコマンドを実行する。

```bash
./vendor/bin/phpunit
```

### ブラウザテストを作成

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

コマンドを実行してブラウザテストを作成する。

```bash
$ php artisan dusk:make IndexTaskTest
```

### ブラウザテストを記述

1. `tests/Browser/ExampleTest.php`を削除する。
2. `tests/Browser/IndexTaskTest.php`を開く。
3. `testExample`メソッドを削除する。
4. コマンドで作成したファイルに画面表示、画面遷移、フォームの制御などのテストを記述する。
下記はあくまで一例なので、masterブランチの`tests/Browser/IndexTaskTest.php`を参照して他のテストケースも作成する。

```php
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
```

### ブラウザテスト設定を修正する

1. `tests/DuskTestCase.php`を開く。
2. 下記の通り編集する。

```php
<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function baseUrl()
    {
         return 'http://tutorial-nginx';
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        //
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {

        return RemoteWebDriver::create(
            'http://tutorial-selenium:4444/wd/hub', DesiredCapabilities::chrome()
        );
    }
}
```

### ブラウザテストを実行

アプリケーションコンテナに接続していない場合は接続する。  
※コマンドラインが`bash-4.2#`から始まってる場合は接続しています

```bash
docker exec -it tutorial-php bash
```

作成したブラウザテストを実施するためのコマンドを実行する。

```bash
$ php artisan dusk --env=testing
```

# おわり

これでチュートリアルはすべて完了です！  
master ブランチのソースと見比べて更にブラッシュアップしてみてください。
