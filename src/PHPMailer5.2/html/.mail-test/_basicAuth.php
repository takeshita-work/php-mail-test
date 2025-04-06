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
