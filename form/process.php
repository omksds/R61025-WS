<?php
session_start();

// デバッグ用のログ出力関数
function debugLog($message) {
    $logFile = 'php://stderr';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// バリデーション関数
function validate($data) {
    $errors = [];

    // お名前のバリデーション
    if (empty($data['name'])) {
        $errors['name'] = 'お名前は必須です。';
    } elseif (strlen($data['name']) > 50) {
        $errors['name'] = 'お名前は50文字以内で入力してください。';
    }

    // フリガナのバリデーション
    if (empty($data['furigana'])) {
        $errors['furigana'] = 'フリガナは必須です。';
    } elseif (strlen($data['furigana']) > 50) {
        $errors['furigana'] = 'フリガナは50文字以内で入力してください。';
    }

    // メールアドレスのバリデーション
    if (empty($data['email'])) {
        $errors['email'] = 'メールアドレスは必須です。';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = '正しいメールアドレスを入力してください。';
    }

    // 電話番号のバリデーション（任意項目）
    if (!empty($data['phone']) && !preg_match('/^[0-9\-]+$/', $data['phone'])) {
        $errors['phone'] = '電話番号は正しい形式で入力してください。';
    }

    // お問い合わせ項目のバリデーション
    if (empty($data['inquiry_type'])) {
        $errors['inquiry_type'] = 'お問い合わせ項目を選択してください。';
    }

    // お問い合わせ内容のバリデーション
    if (empty($data['message'])) {
        $errors['message'] = 'お問い合わせ内容は必須です。';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate($_POST);

    if (empty($errors)) {
        // フォームデータをログ出力
        debugLog("=== フォーム送信内容 ===");
        debugLog("会社名: " . ($_POST['company'] ?? '未入力'));
        debugLog("お名前: " . ($_POST['name'] ?? '未入力'));
        debugLog("フリガナ: " . ($_POST['furigana'] ?? '未入力'));
        debugLog("メールアドレス: " . ($_POST['email'] ?? '未入力'));
        debugLog("電話番号: " . ($_POST['phone'] ?? '未入力'));
        debugLog("お問い合わせ項目: " . ($_POST['inquiry_type'] ?? '未入力'));
        debugLog("お問い合わせ内容:\n" . ($_POST['message'] ?? '未入力'));
        debugLog("送信日時: " . date('Y-m-d H:i:s'));
        debugLog("=====================");

        // 成功メッセージをセット
        $_SESSION['success'] = 'お問い合わせを受け付けました。担当者より連絡させていただきます。';
        
        // フォームページにリダイレクト
        header('Location: form.php');
        exit;
    } else {
        // エラーがある場合は入力値を保持して戻る
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header('Location: form.php');
        exit;
    }
}
