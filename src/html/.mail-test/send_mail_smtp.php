<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Basic認証（任意）
$USERNAME = 'test';
$PASSWORD = 'test';
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $USERNAME || $_SERVER['PHP_AUTH_PW'] !== $PASSWORD) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo '認証が必要です';
    exit;
}

// 初期値
$defaultTo         = 'test.terimukuri@gmail.com';
$defaultSMTPHost   = 'smtp.example.com';
$defaultSMTPPort   = 587;
$defaultSMTPSecure = 'tls';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to         = !empty($_POST['to']) ? $_POST['to'] : $defaultTo;
    $returnPath = !empty($_POST['return_path']) ? $_POST['return_path'] : '';

    $smtpHost   = !empty($_POST['smtp_host']) ? $_POST['smtp_host'] : $defaultSMTPHost;
    $smtpPort   = !empty($_POST['smtp_port']) ? (int)$_POST['smtp_port'] : $defaultSMTPPort;
    $smtpUser   = !empty($_POST['smtp_user']) ? $_POST['smtp_user'] : '';
    $smtpPass   = !empty($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '';
    $smtpSecure = isset($_POST['smtp_secure']) ? $_POST['smtp_secure'] : $defaultSMTPSecure;


    // FromはSMTPユーザー名に強制設定
    $from = $smtpUser;
    $effectiveReturnPath = ($returnPath === '') ? $from : $returnPath;

    $subject = "テストメール（SMTP, " . $_SERVER['HTTP_HOST'] . "）";
    $message = "これはテストメールです。\r\n送信元のドメイン: " . $_SERVER['HTTP_HOST'];

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

        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "メール送信成功（" . date('Y/m/d H:i:s') . "）<br>";
    } catch (Exception $e) {
        echo "メール送信失敗（" . date('Y/m/d H:i:s') . "）: {$mail->ErrorInfo}<br>";
    }

    echo "To: $to<br>";
    echo "From (＝SMTPユーザー): $from<br>";
    echo "Return-Path: $effectiveReturnPath<br>";
    echo "SMTP: $smtpHost:$smtpPort（$smtpSecure）<br><br>";
} else {
    $to         = $defaultTo;
    $returnPath = '';
    $smtpHost   = $defaultSMTPHost;
    $smtpPort   = $defaultSMTPPort;
    $smtpUser   = '';
    $smtpPass   = '';
    $smtpSecure = 'tls';
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
        <label>SMTPユーザー名（＝Fromになります）:</label><br>
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
</body>
</html>
