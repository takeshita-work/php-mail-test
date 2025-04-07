<?php
require 'libs/vendor/PHPMailer-5.2.28/class.phpmailer.php';
require 'libs/vendor/PHPMailer-5.2.28/class.smtp.php';

require 'libs/loadDotEnv.php';
dotenv\loadDotEnv(__DIR__ . '/.env');

require '_basicAuth.php'; //基本認証

// 初期値
$defaultTo         = dotenv\env('DEFAULT_TO', 'example@example.com');
$defaultSMTPHost   = dotenv\env('DEFAULT_SMTP_HOST', 'smtp.example.com');
$defaultSMTPPort   = dotenv\env('DEFAULT_SMTP_PORT', 587);
$defaultSMTPSecure = dotenv\env('DEFAULT_SMTP_SECURE', '');
$defaultSMTPUser   = dotenv\env('DEFAULT_FROM', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to         = !empty($_POST['to']) ? $_POST['to'] : $defaultTo;
    $returnPath = !empty($_POST['return_path']) ? $_POST['return_path'] : '';

    $smtpHost   = !empty($_POST['smtp_host']) ? $_POST['smtp_host'] : $defaultSMTPHost;
    $smtpPort   = !empty($_POST['smtp_port']) ? (int)$_POST['smtp_port'] : $defaultSMTPPort;
    $smtpUser   = !empty($_POST['smtp_user']) ? $_POST['smtp_user'] : $defaultSMTPUser;
    $smtpPass   = !empty($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '';
    $smtpSecure = isset($_POST['smtp_secure']) ? $_POST['smtp_secure'] : $defaultSMTPSecure;

    $from = $smtpUser; // FromはSMTPユーザー名
    $effectiveReturnPath = ($returnPath === '') ? $from : $returnPath;

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->SMTPDebug = 0;
        $mail->XMailer = null;

        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = $smtpSecure ?: false;
        $mail->Port       = (int)$smtpPort;

        $mail->setFrom($from, '');
        $mail->addAddress($to);
        $mail->addReplyTo($from);
        $mail->Sender = $effectiveReturnPath;

        $mail->Subject = "テストメール（SMTP, " . $_SERVER['HTTP_HOST'] . "）";
        $mail->Body    = "これはテストメールです。\r\n送信元のドメイン: " . $_SERVER['HTTP_HOST'];

        $mail->send();
        echo "メール送信成功（" . date('Y/m/d H:i:s') . "）<br>";
    } catch (Exception $e) {
        echo "メール送信失敗（" . date('Y/m/d H:i:s') . "）: {$mail->ErrorInfo}<br>";
    }

    echo "To: $to<br>";
    echo "From (=SMTPユーザー): $from<br>";
    echo "Return-Path: $effectiveReturnPath<br>";
    echo "SMTP: $smtpHost:$smtpPort（$smtpSecure）<br><br>";
} else {
    // フォームの初期値を設定
    $to         = $defaultTo;
    $returnPath = '';
    $smtpHost   = $defaultSMTPHost;
    $smtpPort   = $defaultSMTPPort;
    $smtpUser   = $defaultSMTPUser;
    $smtpPass   = '';
    $smtpSecure = $defaultSMTPSecure;
    $from       = '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SMTP メール送信フォーム</title>
</head>
<body>
    <h2>SMTP メール送信フォーム</h2>
    <form method="POST">
        <h3>メール情報</h3>
        <label>To:</label><br>
        <input type="email" name="to" size="60" value="<?php echo htmlspecialchars($to); ?>"><br>

        <label>Return-Path:</label><br>
        <input type="email" name="return_path" size="60" value="<?php echo htmlspecialchars($returnPath); ?>"><br>

        <h3>SMTP設定</h3>
        <label>SMTPホスト:</label><br>
        <input type="text" name="smtp_host" size="40" value="<?php echo htmlspecialchars($smtpHost); ?>"><br>
        <label>SMTPポート:</label><br>
        <input type="number" name="smtp_port" value="<?php echo htmlspecialchars($smtpPort); ?>"><br>
        <label>SMTPユーザー名（=Fromになります）:</label><br>
        <input type="email" name="smtp_user" size="40" value="<?php echo htmlspecialchars($smtpUser); ?>"><br>
        <label>SMTPパスワード:</label><br>
        <input type="password" name="smtp_pass" size="40" value="<?php echo htmlspecialchars($smtpPass); ?>"><br>
        <label>暗号化方式:</label><br>
        <select name="smtp_secure">
            <option value="" <?php echo ($smtpSecure === '') ? 'selected' : ''; ?>>なし</option>
            <option value="tls" <?php echo ($smtpSecure === 'tls') ? 'selected' : ''; ?>>TLS</option>
            <option value="ssl" <?php echo ($smtpSecure === 'ssl') ? 'selected' : ''; ?>>SSL</option>
        </select><br><br>

        <button type="submit">メール送信</button>
    </form>
    <p><a href="https://www.mail-tester.com/" target="_blank">mail tester</a></p>
</body>
</html>
