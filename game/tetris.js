const canvas = document.getElementById("tetris");
const context = canvas.getContext("2d");
const BLOCK_SIZE = 20;
const COLS = 12;
const ROWS = 20;
const COLORS = [
    null,
    "#FF0D72", // I
    "#0DC2FF", // J
    "#0DFF72", // L
    "#F538FF", // O
    "#FF8E0D", // S
    "#FFE138", // T
    "#3877FF", // Z
];

let dropCounter = 0;
let dropInterval = 1000;
let lastTime = 0;
let score = 0;
let gameOver = false;
let isPlaying = false;

// テトリミノの形状定義を修正
const PIECES = {
    I: [
        [0, 0, 0, 0],
        [1, 1, 1, 1],
        [0, 0, 0, 0],
        [0, 0, 0, 0],
    ],
    J: [
        [2, 0, 0],
        [2, 2, 2],
        [0, 0, 0],
    ],
    L: [
        [0, 0, 3],
        [3, 3, 3],
        [0, 0, 0],
    ],
    O: [
        [4, 4],
        [4, 4],
    ],
    S: [
        [0, 5, 5],
        [5, 5, 0],
        [0, 0, 0],
    ],
    T: [
        [0, 6, 0],
        [6, 6, 6],
        [0, 0, 0],
    ],
    Z: [
        [7, 7, 0],
        [0, 7, 7],
        [0, 0, 0],
    ],
};

// プレイヤーの状態
const player = {
    pos: { x: 0, y: 0 },
    matrix: null,
    score: 0,
};

// アリーナ（ゲーム場）の作成
const arena = createMatrix(COLS, ROWS);

function createMatrix(w, h) {
    const matrix = [];
    while (h--) {
        matrix.push(new Array(w).fill(0));
    }
    return matrix;
}

// 衝突判定
function collide(arena, player) {
    const [m, o] = [player.matrix, player.pos];
    for (let y = 0; y < m.length; y++) {
        for (let x = 0; x < m[y].length; x++) {
            if (
                m[y][x] !== 0 &&
                (arena[y + o.y] && arena[y + o.y][x + o.x]) !== 0
            ) {
                return true;
            }
        }
    }
    return false;
}

// マトリックスの描画
function drawMatrix(matrix, offset) {
    matrix.forEach((row, y) => {
        row.forEach((value, x) => {
            if (value !== 0) {
                context.fillStyle = COLORS[value];
                context.fillRect(
                    (x + offset.x) * BLOCK_SIZE,
                    (y + offset.y) * BLOCK_SIZE,
                    BLOCK_SIZE,
                    BLOCK_SIZE
                );
                context.strokeStyle = "#000";
                context.strokeRect(
                    (x + offset.x) * BLOCK_SIZE,
                    (y + offset.y) * BLOCK_SIZE,
                    BLOCK_SIZE,
                    BLOCK_SIZE
                );
            }
        });
    });
}

// 画面の描画
function draw() {
    context.fillStyle = "#000";
    context.fillRect(0, 0, canvas.width, canvas.height);
    drawMatrix(arena, { x: 0, y: 0 });
    drawMatrix(player.matrix, player.pos);
}

// マトリックスの結合
function merge(arena, player) {
    player.matrix.forEach((row, y) => {
        row.forEach((value, x) => {
            if (value !== 0) {
                arena[y + player.pos.y][x + player.pos.x] = value;
            }
        });
    });
}

// 回転ロジックを修正
function rotate(matrix, dir) {
    const N = matrix.length;
    const result = matrix.map((row, i) =>
        row.map((val, j) => matrix[N - 1 - j][i])
    );

    if (dir > 0) {
        return result;
    } else {
        return result.map((row) => row.reverse()).reverse();
    }
}

// プレイヤーの移動
function playerMove(dir) {
    player.pos.x += dir;
    if (collide(arena, player)) {
        player.pos.x -= dir;
    }
}

// playerReset関数を修正
function playerReset() {
    const pieces = "IJLOSTZ";
    const type = pieces[(pieces.length * Math.random()) | 0];
    player.matrix = createPiece(type);
    player.pos.y = 0;
    player.pos.x =
        ((arena[0].length / 2) | 0) - ((player.matrix[0].length / 2) | 0);

    if (collide(arena, player)) {
        gameOver = true;
        isPlaying = false;
        saveScore(score);
        alert("ゲームオーバー！ スコア: " + score);
    }
}

// プレイヤーの回転処理を修正
function playerRotate(dir) {
    const pos = player.pos.x;
    const originalMatrix = player.matrix;
    player.matrix = rotate(player.matrix, dir);

    // 壁蹴り処理の追加
    let offset = 1;
    while (collide(arena, player)) {
        player.pos.x += offset;
        offset = -(offset + (offset > 0 ? 1 : -1));
        if (offset > player.matrix[0].length) {
            player.matrix = originalMatrix;
            player.pos.x = pos;
            return;
        }
    }
}

// ライン消去とスコア計算
function arenaSweep() {
    let rowCount = 1;
    outer: for (let y = arena.length - 1; y > 0; --y) {
        for (let x = 0; x < arena[y].length; ++x) {
            if (arena[y][x] === 0) {
                continue outer;
            }
        }
        const row = arena.splice(y, 1)[0].fill(0);
        arena.unshift(row);
        ++y;

        score += rowCount * 100;
        rowCount *= 2;
        document.getElementById("score").textContent = score;
    }
}

// ピースの作成関数を修正
function createPiece(type) {
    return PIECES[type].map((row) => [...row]);
}

// プレイヤーのドロップ
function playerDrop() {
    player.pos.y++;
    if (collide(arena, player)) {
        player.pos.y--;
        merge(arena, player);
        playerReset();
        arenaSweep();
    }
    dropCounter = 0;
}

// ゲームループ
function update(time = 0) {
    if (!isPlaying) return;

    const deltaTime = time - lastTime;
    lastTime = time;
    dropCounter += deltaTime;

    if (dropCounter > dropInterval) {
        playerDrop();
    }

    draw();
    requestAnimationFrame(update);
}

// キーボード操作
document.addEventListener("keydown", (event) => {
    if (!isPlaying) return;

    if (event.keyCode === 37) {
        playerMove(-1);
    } else if (event.keyCode === 39) {
        playerMove(1);
    } else if (event.keyCode === 40) {
        playerDrop();
    } else if (event.keyCode === 38) {
        playerRotate(1);
    }
});

// スタートボタンの処理
document.getElementById("startButton").addEventListener("click", () => {
    if (!isPlaying) {
        isPlaying = true;
        gameOver = false;
        score = 0;
        document.getElementById("score").textContent = "0";
        arena.forEach((row) => row.fill(0));
        playerReset();
        update();
    }
});

function updateScore(points) {
    score += points;
    document.getElementById("score").textContent = score;

    // スコアが更新されたらサーバーに送信
    if (gameOver) {
        saveScore(score);
    }
}

function saveScore(finalScore) {
    fetch("save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `score=${finalScore}`,
    });
}
