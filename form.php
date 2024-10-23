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
    <title>アンケートフォーム</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .required {
            color: red;
            margin-left: 5px;
        }
        .submit-btn {
            background-color: #4285f4;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #357abd;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                <?php echo $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <h1>顧客満足度調査</h1>
        <form action="process.php" method="POST">
            <div class="form-group">
                <label>お名前<span class="required">*</span></label>
                <input type="text" class="form-control" name="name" value="<?php echo isset($old['name']) ? htmlspecialchars($old['name']) : ''; ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>メールアドレス<span class="required">*</span></label>
                <input type="email" class="form-control" name="email" value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>年齢層<span class="required">*</span></label>
                <select class="form-control" name="age" required>
                    <option value="">選択してください</option>
                    <?php
                    $ages = ['10-19' => '10代', '20-29' => '20代', '30-39' => '30代', 
                            '40-49' => '40代', '50-59' => '50代', '60+' => '60代以上'];
                    foreach ($ages as $value => $label):
                        $selected = isset($old['age']) && $old['age'] === $value ? 'selected' : '';
                    ?>
                        <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['age'])): ?>
                    <div class="error"><?php echo $errors['age']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>当社の製品をどのように知りましたか？（複数選択可）</label>
                <?php
                $sources = [
                    'web' => 'ウェブサイト',
                    'sns' => 'SNS',
                    'friend' => '友人・知人',
                    'ad' => '広告'
                ];
                foreach ($sources as $value => $label):
                    $checked = isset($old['source']) && in_array($value, $old['source']) ? 'checked' : '';
                ?>
                    <div>
                        <input type="checkbox" name="source[]" value="<?php echo $value; ?>" <?php echo $checked; ?>>
                        <?php echo $label; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label>製品の満足度<span class="required">*</span></label>
                <?php
                $satisfactions = [
                    '5' => '大変満足',
                    '4' => '満足',
                    '3' => '普通',
                    '2' => 'やや不満',
                    '1' => '不満'
                ];
                foreach ($satisfactions as $value => $label):
                    $checked = isset($old['satisfaction']) && $old['satisfaction'] === $value ? 'checked' : '';
                ?>
                    <div>
                        <input type="radio" name="satisfaction" value="<?php echo $value; ?>" <?php echo $checked; ?> required>
                        <?php echo $label; ?>
                    </div>
                <?php endforeach; ?>
                <?php if (isset($errors['satisfaction'])): ?>
                    <div class="error"><?php echo $errors['satisfaction']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>ご意見・ご要望</label>
                <textarea class="form-control" name="feedback" rows="5"><?php echo isset($old['feedback']) ? htmlspecialchars($old['feedback']) : ''; ?></textarea>
            </div>

            <button type="submit" class="submit-btn">送信する</button>
        </form>
    </div>
</body>
</html>
