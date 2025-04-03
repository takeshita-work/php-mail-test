<?php
// Basic Authentication
$USERNAME = 'test'; // 認証用のユーザー名
$PASSWORD = 'test'; // 認証用のパスワード
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $USERNAME || $_SERVER['PHP_AUTH_PW'] !== $PASSWORD) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo '認証が必要です';
    exit;
}

$defaultTo         = 'test.terimukuri@gmail.com';
$defaultFrom       = "example@" . str_replace('www.', '', $_SERVER['HTTP_HOST']);
$defaultReturnPath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to         = !empty($_POST['to']) ? $_POST['to'] : $defaultTo;
    $from       = !empty($_POST['from']) ? $_POST['from'] : $defaultFrom;
    $returnPath = !empty($_POST['return_path']) ? $_POST['return_path'] : '';

    // Return-Pathが空の場合はFromの値を使用
    $effectiveReturnPath = ($returnPath === '') ? $from : $returnPath;

    $subject = "テストメール（Sendmail, " . $_SERVER['HTTP_HOST']."）";
    $message = "これはテストメールです。\r\n送信元のドメイン: " . $_SERVER['HTTP_HOST'];

    $headers  = "From: $from" . "\r\n";
    $headers .= "Reply-To: $from" . "\r\n";
    $headers .= "Return-Path: $effectiveReturnPath" . "\r\n";

    if (mail($to, $subject, $message, $headers, "-f $effectiveReturnPath")) {
        echo "メール送信成功（".date('Y/m/d H:i:s')."）<br>";
    } else {
        echo "メール送信失敗（".date('Y/m/d H:i:s')."）<br>";
    }
    echo "To: $to<br>";
    echo "From: $from<br>";
    echo "Return-Path: $effectiveReturnPath<br><br>";
} else {
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
