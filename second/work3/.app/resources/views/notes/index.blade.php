<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notionライクアプリ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="flex h-screen bg-white">
        <aside class="fixed left-0 top-0 h-screen bg-gray-50 border-r border-gray-200 w-64">
            <div class="p-4">
                <div class="flex items-center justify-between mb-6">
                    <button class="p-1 hover:bg-gray-200 rounded">
                        <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
                    </button>
                    <i data-lucide="settings" class="w-5 h-5 text-gray-600 cursor-pointer"></i>
                </div>
                
                <div class="relative mb-4">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" placeholder="検索" class="w-full pl-10 pr-4 py-2 bg-gray-100 border border-transparent rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none text-sm">
                </div>

                <button class="flex items-center space-x-2 text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 w-full mb-6">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="text-sm">新規ページ</span>
                </button>

                <div class="space-y-1 notes-list">
                    @foreach($notes as $note)
                        <button class="flex items-center space-x-2 w-full px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-200">
                            <i data-lucide="file" class="w-4 h-4"></i>
                            <span>{{ $note->title }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="flex-1 ml-64">
            <div class="flex-1 flex flex-col h-full">
                <div class="border-b border-gray-200 p-4">
                    <h1 class="text-3xl font-bold text-gray-800" id="currentTitle">新規ページ</h1>
                </div>
                
                <div class="flex-1 p-6 overflow-auto">
                    <div class="max-w-3xl mx-auto">
                        <div class="min-h-[500px] prose prose-lg focus:outline-none" 
                             contenteditable="true"
                             id="editor"
                             placeholder="ここに入力を開始..."></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Lucideアイコンの初期化
        lucide.createIcons();
        
        // エディタの機能実装
        const editor = document.getElementById('editor');
        let currentNote = null;

        editor.addEventListener('input', async () => {
            if (currentNote) {
                await updateNote(currentNote.id, {
                    title: document.getElementById('currentTitle').textContent,
                    content: editor.innerHTML
                });
            }
        });

        async function updateNote(id, data) {
            try {
                const response = await fetch(`/notes/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                console.error('Error updating note:', error);
            }
        }
    </script>
</body>
</html> 