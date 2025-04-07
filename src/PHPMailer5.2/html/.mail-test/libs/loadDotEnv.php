<?php

namespace dotenv;

function loadDotEnv($path) {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;

        // "=" が含まれていない行はスキップ
        if (strpos($line, '=') === false) continue;

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'"); // 空白＆引用符を除去

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

// デフォルト値付きの環境変数取得関数
function env($key, $default = null) {
    $value = \getenv($key);

    if ($value === false || $value === '') {
        return $default;
    }

    // Laravel 風の型変換
    $lower = strtolower($value);
    switch ($lower) {
        case 'true':
            return true;
        case 'false':
            return false;
        case 'null':
            return null;
        case 'empty':
            return '';
        default:
            return $value;
    }

}
