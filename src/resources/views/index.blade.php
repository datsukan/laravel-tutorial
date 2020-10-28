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