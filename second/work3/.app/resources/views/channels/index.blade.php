<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャンネル一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h1 class="mb-4">チャンネル一覧</h1>

                <form action="{{ route('channels.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="新しいチャンネル名">
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" placeholder="チャンネルの説明"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">チャンネルを作成</button>
                </form>

                <div class="list-group">
                    @foreach($channels as $channel)
                        <a href="{{ route('channels.show', $channel) }}" class="list-group-item list-group-item-action">
                            <h5 class="mb-1"># {{ $channel->name }}</h5>
                            <p class="mb-1">{{ $channel->description }}</p>
                            <small>作成者: {{ $channel->creator->name }}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html> 