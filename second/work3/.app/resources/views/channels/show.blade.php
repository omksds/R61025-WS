<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title># {{ $channel->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light p-3 vh-100">
                <h4>チャンネル</h4>
                <div class="list-group">
                    @foreach(\App\Models\Channel::all() as $ch)
                        <a href="{{ route('channels.show', $ch) }}" 
                           class="list-group-item list-group-item-action {{ $channel->id === $ch->id ? 'active' : '' }}">
                            # {{ $ch->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-10 vh-100 d-flex flex-column">
                <div class="p-3 border-bottom">
                    <h2># {{ $channel->name }}</h2>
                    <p class="text-muted">{{ $channel->description }}</p>
                </div>
                <div class="flex-grow-1 overflow-auto p-3" id="messages-container">
                    @foreach($messages as $message)
                        <div class="mb-3">
                            <strong>{{ $message->user->name }}</strong>
                            <small class="text-muted">{{ $message->created_at->format('Y/m/d H:i') }}</small>
                            <p class="mb-1">{{ $message->content }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="p-3 border-top">
                    <form id="message-form" class="d-flex">
                        <input type="text" id="message-input" class="form-control me-2" placeholder="メッセージを入力">
                        <button type="submit" class="btn btn-primary">送信</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');

        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = messageInput.value;
            if (!content.trim()) return;

            try {
                const response = await fetch('{{ route("messages.store", $channel) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ content })
                });

                const message = await response.json();
                const messageElement = document.createElement('div');
                messageElement.className = 'mb-3';
                messageElement.innerHTML = `
                    <strong>${message.user.name}</strong>
                    <small class="text-muted">${new Date().toLocaleString()}</small>
                    <p class="mb-1">${message.content}</p>
                `;
                messagesContainer.appendChild(messageElement);
                messageInput.value = '';
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html> 