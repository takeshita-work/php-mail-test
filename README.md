# Docker Web Server Sample

ローカルの Docker で PHP の開発環境をセットアップ

## 開発の流れ

1. `.env_sample` をコピーして、`.env` を作成
    ```
    COMPOSE_PROJECT_NAME=terimukuri_sample-project

    PHP_VERSION=8.1
    MYSQL_VERSION=8.0
    APP_DIR=./src
    ```

1. 環境を構築
    ```
    .docker/scripts/start.ps1
    ```

1. 開発作業

- WEBサーバ
    - https://127.0.0.1/ でアクセス
    - `src` 以下にコードを設置
    - `src/html` 以下が公開される

- phpMyAdmin
    - http://127.0.0.1:8080/ でアクセス

- メールキャッチャー
    - http://127.0.0.1:1080/ でアクセス
    - PHP からメールを送信するとメールキャッチャーで確認ができる

1. 環境を停止
    ```
    .docker/scripts/stop.ps1
    ```