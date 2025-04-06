<?php
require 'PHPMailer-5.2.28/class.phpmailer.php';
require 'PHPMailer-5.2.28/class.smtp.php';

require '_basicAuth.php'; //基本認証

$defaultTo         = 'test.terimukuri@gmail.com';
$defaultFrom       = "example@" . str_replace('www.', '', explode(':', $_SERVER['HTTP_HOST']))[0];
$defaultReturnPath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to         = !empty($_POST['to']) ? $_POST['to'] : $defaultTo;
    $from       = !empty($_POST['from']) ? $_POST['from'] : $defaultFrom;
    $returnPath = !empty($_POST['return_path']) ? $_POST['return_path'] : '';

    // Return-Pathが空の場合はFromの値を使用
    $effectiveReturnPath = ($returnPath === '') ? $from : $returnPath;

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->XMailer  = null;

        // Sendmailを使用
        $mail->isMail();

        $mail->setFrom($from);
        $mail->addAddress($to);
        $mail->addReplyTo($from);
        $mail->Sender = $effectiveReturnPath; // Return-Path

        $mail->Subject = "テストメール（Sendmail, " . $_SERVER['HTTP_HOST']."）";
        $mail->Body    = "これはテストメールです。\r\n送信元のドメイン: " . $_SERVER['HTTP_HOST'];

        $mail->send();

        echo "メール送信成功（" . date('Y/m/d H:i:s') . "）<br>";
    } catch (Exception $e) {
        echo "メール送信失敗（" . date('Y/m/d H:i:s') . "）: {$mail->ErrorInfo}<br>";
    }

    echo "To: $to<br>";
    echo "From: $from<br>";
    echo "Return-Path: $effectiveReturnPath<br><br>";
} else {
    // フォームの初期値を設定
    $to         = $defaultTo;
    $from       = $defaultFrom;
    $returnPath = '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール送信</title>
</head>
<body>
    <form method="POST">
        <label>To:</label><br>
        <input type="email" name="to" size="60" value="<?php echo htmlspecialchars($to, ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label>From:</label><br>
        <input type="email" name="from" size="60" value="<?php echo htmlspecialchars($from, ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label>Return-Path:</label><br>
        <input type="email" name="return_path" size="60" value="<?php echo htmlspecialchars($returnPath, ENT_QUOTES, 'UTF-8'); ?>"> ※空の場合はFromを使用<br>
        <button type="submit">送信</button>
    </form>
    <p><a href="https://www.mail-tester.com/" target="_blank">mail tester</a></p>
</body>
</html>
