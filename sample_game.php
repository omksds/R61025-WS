<?php

function drawBoard($board) {
    echo " {$board[0]} | {$board[1]} | {$board[2]} \n";
    echo "---+---+---\n";
    echo " {$board[3]} | {$board[4]} | {$board[5]} \n";
    echo "---+---+---\n";
    echo " {$board[6]} | {$board[7]} | {$board[8]} \n";
}

function checkWin($board) {
    $winPatterns = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],  // 横
        [0, 3, 6], [1, 4, 7], [2, 5, 8],  // 縦
        [0, 4, 8], [2, 4, 6]              // 斜め
    ];

    foreach ($winPatterns as $pattern) {
        if ($board[$pattern[0]] === $board[$pattern[1]] && $board[$pattern[1]] === $board[$pattern[2]] && $board[$pattern[0]] !== ' ') {
            return true;
        }
    }
    return false;
}

function isBoardFull($board) {
    return !in_array(' ', $board);
}

$board = array_fill(0, 9, ' ');
$currentPlayer = 'X';

while (true) {
    system('clear');
    drawBoard($board);
    
    echo "プレイヤー {$currentPlayer} の番です。位置を選んでください (0-8): ";
    $position = trim(fgets(STDIN));

    if (!is_numeric($position) || $position < 0 || $position > 8 || $board[$position] !== ' ') {
        echo "無効な選択です。もう一度お試しください。\n";
        sleep(2);
        continue;
    }

    $board[$position] = $currentPlayer;

    if (checkWin($board)) {
        system('clear');
        drawBoard($board);
        echo "プレイヤー {$currentPlayer} の勝利です！\n";
        break;
    }

    if (isBoardFull($board)) {
        system('clear');
        drawBoard($board);
        echo "引き分けです！\n";
        break;
    }

    $currentPlayer = ($currentPlayer === 'X') ? 'O' : 'X';
}

