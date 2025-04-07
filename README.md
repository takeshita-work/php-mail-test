# mail test

メール送信のテストコード

## 環境構築

### PHPMailer

- php5.x 系では PHPMailer 5.2.x を利用
- php7.x ～ 8.x では PHPMailer 6.x を利用

### ディレクトリ構成

```
|-- .docker
|   |-- scripts
|   `-- docker-compose.yml
|-- src
|   |-- PHPMailer5.2
|   |   `-- html
|   |       |-- .mail-test
|   |       |   |-- libs
|   |       |   |   |-- vendor/PHPMailer-6.9.3
|   |       |   |   `-- loadDotEnv.php
|   |       |   |-- _basicAuth.php
|   |       |   |-- .env                       ... .env.sample をコピーして作成
|   |       |   |-- .env.sample                ... メール送信関連の設定
|   |       |   |-- .htaccess
|   |       |   |-- index.php
|   |       |   |-- send_mail_sendmail.php     ... Sendmail でメールを送信
|   |       |   `-- send_mail_smtp.php         ... SMTP認証 でメールを送信
|   |       `-- index.php
|   `-- PHPMailer6.9
|       `-- html
|           |-- .mail-test
|           |   |-- libs
|           |   |   |-- vendor/PHPMailer-6.9.3
|           |   |   `-- loadDotEnv.php
|           |   |-- _basicAuth.php
|           |   |-- .env                       ... .env.sample をコピーして作成
|           |   |-- .env.sample                ... メール送信関連の設定
|           |   |-- .htaccess
|           |   |-- index.php
|           |   |-- send_mail_sendmail.php     ... Sendmail でメールを送信
|           |   `-- send_mail_smtp.php         ... SMTP認証 でメールを送信
|           `-- index.php
|-- .env                                       ... .env.sample をコピーして作成
|-- .env.sample                                ... docker-compose 関連の設定
`-- README.md
```
- PHPMailer-x.x.x は [PHPMailer](https://github.com/PHPMailer/PHPMailer) から zip をダウンロードして展開

### 開発環境

- docker コンテナを起動
    ```
    .docker/scripts/start.ps1
    ```
    - [PHPMailer5.2](https://127.0.0.1:10443/)
        - PHPMailer5.2 での メール送信
    - [PHPMailer6.9](https://127.0.0.1:20443/)
        - PHPMailer6.9 での メール送信
    - [MailCatcher](http://127.0.0.1:1080/)
        - MailCatcher での メール受信（smptで送信したものはキャッチされない）

- docker コンテナを停止
    ```
    .docker/scripts/stop.ps1
    ```