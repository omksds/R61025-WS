<!DOCTYPE html>
<html>
<head>
    <title>PHP Tetris</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="game-container">
        <canvas id="tetris" width="240" height="400"></canvas>
        <div class="info-container">
            <p>スコア: <span id="score">0</span></p>
            <button id="startButton">ゲームスタート</button>
        </div>
        <div class="controls-info">
            <p>操作方法:</p>
            <p>←→: 移動　↑: 回転　↓: 落下速度アップ</p>
        </div>
    </div>
    <script src="tetris.js"></script>
</body>
</html>
