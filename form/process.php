<?php
session_start();

// バリデーション関数
function validate($data) {
    $errors = [];

    // 名前のバリデーション
    if (empty($data['name'])) {
        $errors['name'] = 'お名前は必須です。';
    } elseif (strlen($data['name']) > 50) {
        $errors['name'] = 'お名前は50文字以内で入力してください。';
    }

    // メールアドレスのバリデーション
    if (empty($data['email'])) {
        $errors['email'] = 'メールアドレスは必須です。';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = '正しいメールアドレスを入力してください。';
    }

    // 年齢層のバリデーション
    if (empty($data['age'])) {
        $errors['age'] = '年齢層を選択してください。';
    }

    // 満足度のバリデーション
    if (empty($data['satisfaction'])) {
        $errors['satisfaction'] = '満足度を選択してください。';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate($_POST);

    if (empty($errors)) {
        // データベースへの保存をシミュレートし、結果をターミナルに表示
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'age' => $_POST['age'],
            'source' => isset($_POST['source']) ? implode(',', $_POST['source']) : '',
            'satisfaction' => $_POST['satisfaction'],
            'feedback' => $_POST['feedback']
        ];

        // ターミナルに結果を表示
        error_log("アンケート回答が送信されました：\n" . print_r($data, true));

        $_SESSION['success'] = 'アンケートの送信が完了しました。ご協力ありがとうございました。';
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
