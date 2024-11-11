<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スペースインベーダー</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        canvas {
            border: 2px solid #fff;
            background-color: #000033;
        }
        body {
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Press Start 2P', cursive;
        }
        .game-container {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
        }
        .score {
            color: #fff;
            font-size: 24px;
            margin: 20px 0;
            text-shadow: 0 0 10px #00ff00;
        }
        .controls {
            color: #fff;
            margin-top: 20px;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #00ff00;
            border: none;
            padding: 10px 30px;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 5px #000;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #00cc00;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="score">スコア: <span id="score">0</span></div>
        <canvas id="gameCanvas" width="800" height="600"></canvas>
        <div class="mt-3">
            <button class="btn btn-primary" id="startButton">ゲームスタート</button>
        </div>
        <div class="controls">
            <p>操作方法:</p>
            <p>← →: 移動 | スペース: 発射</p>
        </div>
    </div>
    <script src="{{ asset('js/game.js') }}"></script>
</body>
</html> 