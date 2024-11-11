class Game {
    constructor() {
        this.canvas = document.getElementById("gameCanvas");
        this.ctx = this.canvas.getContext("2d");
        this.score = 0;
        this.gameOver = false;
        this.stage = 1;
        this.powerUpActive = false;
        this.powerUpTimer = 0;
        this.invincible = false;

        // サウンドの初期化
        this.sounds = {
            shoot: new Audio("/sounds/shoot.wav"),
            explosion: new Audio("/sounds/explosion.wav"),
            gameOver: new Audio("/sounds/gameover.wav"),
        };

        // 敵の基本設定を追加
        this.enemyWidth = 40;
        this.enemyHeight = 30;
        this.enemyPadding = 10;
        this.enemyDirection = 1;
        this.enemyStepDown = 30;
        this.enemySpeed = 2; // 敵の移動速度

        // プレイヤー設定
        this.player = {
            x: this.canvas.width / 2,
            y: this.canvas.height - 30,
            width: 50,
            height: 30,
            speed: 5,
            shootCooldown: 0,
            normalShootDelay: 20,
            powerUpShootDelay: 13,
            lives: 1,
        };

        // アイテム設定
        this.items = [];
        this.itemTypes = {
            POWER_UP: "powerUp",
            INVINCIBLE: "invincible",
        };

        // 敵の設定とステージパターン
        this.enemyPatterns = {
            1: { rows: 5, cols: 10, arrangement: "normal" },
            2: { rows: 6, cols: 8, arrangement: "diamond" },
            3: { rows: 4, cols: 12, arrangement: "wave" },
        };

        this.setupStage(this.stage);
        this.bullets = [];
        this.enemyBullets = [];
        this.keys = {};

        this.init();
    }

    setupStage(stageNum) {
        this.enemies = [];
        const pattern = this.enemyPatterns[stageNum] || this.enemyPatterns[1];
        const startX =
            (this.canvas.width -
                pattern.cols * (this.enemyWidth + this.enemyPadding)) /
            2;

        for (let row = 0; row < pattern.rows; row++) {
            for (let col = 0; col < pattern.cols; col++) {
                const isStrong = Math.random() < 0.2; // 20%の確率で強敵
                this.enemies.push({
                    x: startX + col * (this.enemyWidth + this.enemyPadding),
                    y: row * (this.enemyHeight + this.enemyPadding) + 50,
                    width: this.enemyWidth,
                    height: this.enemyHeight,
                    alive: true,
                    health: isStrong ? 2 : 1,
                    isStrong: isStrong,
                });
            }
        }
    }

    spawnItem() {
        if (Math.random() < 0.005) {
            // 0.5%の確率でアイテム出現
            const itemType =
                Math.random() < 0.5
                    ? this.itemTypes.POWER_UP
                    : this.itemTypes.INVINCIBLE;
            this.items.push({
                x: Math.random() * (this.canvas.width - 20),
                y: 0,
                width: 20,
                height: 20,
                type: itemType,
                speed: 2,
            });
        }
    }

    updateItems() {
        this.items = this.items.filter((item) => {
            item.y += item.speed;

            // プレイヤーとの衝突判定
            if (this.isColliding(item, this.player)) {
                if (item.type === this.itemTypes.POWER_UP) {
                    this.powerUpActive = true;
                    this.powerUpTimer = 1800; // 30秒 (60FPS × 30)
                } else if (item.type === this.itemTypes.INVINCIBLE) {
                    this.invincible = true;
                    this.player.lives = 2;
                }
                return false;
            }

            return item.y < this.canvas.height;
        });
    }

    shoot() {
        const currentDelay = this.powerUpActive
            ? this.player.powerUpShootDelay
            : this.player.normalShootDelay;

        if (this.player.shootCooldown <= 0) {
            this.bullets.push({
                x: this.player.x + this.player.width / 2,
                y: this.player.y,
                width: 3,
                height: 15,
                speed: 7,
            });
            this.sounds.shoot.play();
            this.player.shootCooldown = currentDelay;
        }
    }

    checkBulletCollisions() {
        // プレイヤーの弾と敵の弾の相殺判定
        this.bullets.forEach((playerBullet, pIndex) => {
            this.enemyBullets.forEach((enemyBullet, eIndex) => {
                if (this.isColliding(playerBullet, enemyBullet)) {
                    this.bullets.splice(pIndex, 1);
                    this.enemyBullets.splice(eIndex, 1);
                    return;
                }
            });
        });
    }

    update() {
        if (this.gameOver) return;

        // パワーアップタイマーの更新
        if (this.powerUpActive) {
            this.powerUpTimer--;
            if (this.powerUpTimer <= 0) {
                this.powerUpActive = false;
            }
        }

        // シューティングクールダウンの更新
        if (this.player.shootCooldown > 0) {
            this.player.shootCooldown--;
        }

        // 通常の更新処理
        this.updatePlayerMovement();
        this.updateBullets();
        this.updateEnemies();
        this.spawnItem();
        this.updateItems();
        this.checkBulletCollisions();
        this.checkCollisions();

        // ステージクリア判定
        if (this.enemies.every((enemy) => !enemy.alive)) {
            this.stage++;
            this.setupStage(this.stage);
        }
    }

    draw() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // 背景の描画
        this.drawBackground();

        // プレイヤーの描画
        this.ctx.fillStyle = this.invincible ? "#00ffff" : "#00ff00";
        this.ctx.fillRect(
            this.player.x,
            this.player.y,
            this.player.width,
            this.player.height
        );

        // 敵の描画
        this.enemies.forEach((enemy) => {
            if (enemy.alive) {
                this.ctx.fillStyle = enemy.isStrong ? "#ff00ff" : "#ff0000";
                this.ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
            }
        });

        // アイテムの描画
        this.items.forEach((item) => {
            this.ctx.fillStyle =
                item.type === this.itemTypes.POWER_UP ? "#ffff00" : "#00ffff";
            this.ctx.fillRect(item.x, item.y, item.width, item.height);
        });

        // 弾の描画
        this.drawBullets();

        // UI要素の描画
        this.drawUI();
    }

    drawBackground() {
        // 星空の背景を描画
        this.ctx.fillStyle = "#000033";
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        // 星を描画
        this.ctx.fillStyle = "#ffffff";
        for (let i = 0; i < 100; i++) {
            const x = Math.random() * this.canvas.width;
            const y = Math.random() * this.canvas.height;
            this.ctx.fillRect(x, y, 1, 1);
        }
    }

    drawUI() {
        this.ctx.fillStyle = "#ffffff";
        this.ctx.font = "20px Arial";
        this.ctx.fillText(`スコア: ${this.score}`, 10, 30);
        this.ctx.fillText(`ステージ: ${this.stage}`, 10, 60);

        if (this.powerUpActive) {
            this.ctx.fillText(
                `パワーアップ: ${Math.ceil(this.powerUpTimer / 60)}秒`,
                10,
                90
            );
        }

        if (this.gameOver) {
            this.ctx.font = "48px Arial";
            this.ctx.textAlign = "center";
            this.ctx.fillText(
                "GAME OVER",
                this.canvas.width / 2,
                this.canvas.height / 2
            );
        }
    }

    init() {
        // 敵の初期配置
        this.setupStage(this.stage);

        // イベントリスナーの設定
        document.addEventListener("keydown", (e) => (this.keys[e.key] = true));
        document.addEventListener("keyup", (e) => (this.keys[e.key] = false));
        document.addEventListener("keypress", (e) => {
            if (e.key === " " && !this.gameOver) {
                this.shoot();
            }
        });
    }

    updatePlayerMovement() {
        if (this.keys["ArrowLeft"]) {
            this.player.x = Math.max(0, this.player.x - this.player.speed);
        }
        if (this.keys["ArrowRight"]) {
            this.player.x = Math.min(
                this.canvas.width - this.player.width,
                this.player.x + this.player.speed
            );
        }
    }

    updateBullets() {
        // プレイヤーの弾の更新
        this.bullets = this.bullets.filter((bullet) => {
            bullet.y -= bullet.speed;
            return bullet.y > 0;
        });

        // 敵の弾の更新
        this.enemyBullets = this.enemyBullets.filter((bullet) => {
            bullet.y += bullet.speed;
            return bullet.y < this.canvas.height;
        });
    }

    updateEnemies() {
        let touchedEdge = false;
        this.enemies.forEach((enemy) => {
            if (!enemy.alive) return;
            enemy.x += this.enemySpeed * this.enemyDirection;
            if (enemy.x <= 0 || enemy.x + enemy.width >= this.canvas.width) {
                touchedEdge = true;
            }
        });

        if (touchedEdge) {
            this.enemyDirection *= -1;
            this.enemies.forEach((enemy) => {
                enemy.y += this.enemyStepDown;
            });
        }

        // ランダムな敵の攻撃
        if (Math.random() < 0.02) {
            this.enemyShoot();
        }
    }

    enemyShoot() {
        const livingEnemies = this.enemies.filter((enemy) => enemy.alive);
        if (livingEnemies.length > 0) {
            const shooter =
                livingEnemies[Math.floor(Math.random() * livingEnemies.length)];
            this.enemyBullets.push({
                x: shooter.x + shooter.width / 2,
                y: shooter.y + shooter.height,
                width: 3,
                height: 15,
                speed: 5,
            });
        }
    }

    drawBullets() {
        // プレイヤーの弾
        this.ctx.fillStyle = "#ffffff";
        this.bullets.forEach((bullet) => {
            this.ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
        });

        // 敵の弾
        this.ctx.fillStyle = "#ff9900";
        this.enemyBullets.forEach((bullet) => {
            this.ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
        });
    }

    checkCollisions() {
        // プレイヤーの弾と敵の衝突判定
        this.bullets.forEach((bullet, bulletIndex) => {
            this.enemies.forEach((enemy) => {
                if (enemy.alive && this.isColliding(bullet, enemy)) {
                    enemy.health--;
                    if (enemy.health <= 0) {
                        enemy.alive = false;
                        this.score += enemy.isStrong ? 200 : 100;
                        this.sounds.explosion.play();
                    }
                    this.bullets.splice(bulletIndex, 1);
                    document.getElementById("score").textContent = this.score;
                }
            });
        });

        // 敵の弾とプレイヤーの衝突判定
        this.enemyBullets.forEach((bullet, bulletIndex) => {
            if (this.isColliding(bullet, this.player)) {
                if (this.invincible) {
                    this.player.lives--;
                    this.invincible = this.player.lives > 1;
                } else {
                    this.gameOver = true;
                    this.sounds.gameOver.play();
                }
                this.enemyBullets.splice(bulletIndex, 1);
            }
        });
    }

    isColliding(rect1, rect2) {
        return (
            rect1.x < rect2.x + rect2.width &&
            rect1.x + rect1.width > rect2.x &&
            rect1.y < rect2.y + rect2.height &&
            rect1.y + rect1.height > rect2.y
        );
    }
}

// ゲーム開始処理
document.getElementById("startButton").addEventListener("click", () => {
    const game = new Game();
    let lastTime = 0;

    function gameLoop(timestamp) {
        const deltaTime = timestamp - lastTime;
        lastTime = timestamp;

        game.update();
        game.draw();
        requestAnimationFrame(gameLoop);
    }

    requestAnimationFrame(gameLoop);
});
