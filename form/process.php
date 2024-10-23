<?php
session_start();

// バリデーション関数
function validate($data) {
    $errors = [];

    // 名前のバリデーション
    if (empty($data['name'])) {
        $errors['name'] = 'お名前は必須です。';
    } elseif (mb_strlen($data['name']) > 50) {
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
        // ここでデータベースへの保存や、メール送信などの処理を行う
        // 例: データベースに保存する場合
        /*
        $pdo = new PDO("mysql:host=localhost;dbname=survey;charset=utf8", "username", "password");
        $stmt = $pdo->prepare("INSERT INTO responses (name, email, age, source, satisfaction, feedback) 
                              VALUES (:name, :email, :age, :source, :satisfaction, :feedback)");
        $stmt->execute([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'age' => $_POST['age'],
            'source' => isset($_POST['source']) ? implode(',', $_POST['source']) : '',
            'satisfaction' => $_POST['satisfaction'],
            'feedback' => $_POST['feedback']
        ]);
        */

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