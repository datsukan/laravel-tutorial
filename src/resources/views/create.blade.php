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