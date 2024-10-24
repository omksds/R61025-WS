<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);
    $date = date('Y-m-d H:i:s');
    
    // スコアをファイルに保存
    $data = sprintf("%s - Score: %d\n", $date, $score);
    file_put_contents('scores.txt', $data, FILE_APPEND);
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}