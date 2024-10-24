<?php
session_start();

// エラーメッセージを保持する配列
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
// 送信後の入力値を保持
$old = isset($_SESSION['old']) ? $_SESSION['old'] : [];

// セッションをクリア
unset($_SESSION['errors']);
unset($_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTACT | お問い合わせ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>CONTACT</h1>
        <div class="subtitle">お問い合わせ</div>
        
        <div class="description">
            制作・開発料金のお見積り、ご相談など、お気軽にお問い合わせください。<br>
            お問い合わせいただいた内容の確認後、担当者よりご連絡いたします。<br><br>
            お問い合わせいただいた内容によって、お時間を頂戴する場合がございます。<br>
            ご提案・ご紹介につきましては、お受けを差し上げられない場合がございます。<br>
            あらかじめご了承ください。
        </div>

        <form action="process.php" method="POST">
            <div class="form-group">
                <div class="form-label">会社名</div>
                <div class="form-input">
                    <input type="text" name="company" placeholder="法人のみは空欄で問題ございません。">
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">お名前<span class="required">必須</span></div>
                <div class="form-input">
                    <input type="text" name="name" placeholder="山田 太郎" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">フリガナ<span class="required">必須</span></div>
                <div class="form-input">
                    <input type="text" name="furigana" placeholder="ヤマダ タロウ" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">メールアドレス<span class="required">必須</span></div>
                <div class="form-input">
                    <input type="email" name="email" placeholder="例) xxxxx@example.com" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">電話番号</div>
                <div class="form-input">
                    <input type="tel" name="phone" placeholder="000-0000-0000">
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">お問い合わせ項目<span class="required">必須</span></div>
                <div class="form-input">
                    <select name="inquiry_type" required>
                        <option value="">--以下から選択してください--</option>
                        <option value="estimate">お見積り依頼</option>
                        <option value="consultation">ご相談</option>
                        <option value="other">その他</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label">お問い合わせ内容<span class="required">必須</span></div>
                <div class="form-input">
                    <textarea name="message" placeholder="お問い合わせ内容をご記入ください。" required></textarea>
                </div>
            </div>

            <div class="submit-btn">
                <button type="submit">入力内容を確認する</button>
            </div>
        </form>
    </div>

    <div class="breadcrumb">
        <a href="/">ホーム</a> > CONTACT
    </div>

    <div class="footer">
        © Exmple Inc.
    </div>
</body>
</html>
