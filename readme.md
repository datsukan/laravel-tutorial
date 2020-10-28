# Laravel Tutorial

Laravel の学習をするための開発環境・ソースコードのセットです。

# Requirement \ 前提要件

- [Docker](https://www.docker.com/get-started)
- [Visual Studio Code](https://azure.microsoft.com/ja-jp/products/visual-studio-code/) （任意）
- [REST Client](https://marketplace.visualstudio.com/items?itemName=humao.rest-client) （任意）

# Installation \ 導入

下記のコマンドを順番に実行してください。

```bash
docker-compose up -d
docker exec -it tutorial-php bash
cp .env.example .env
php artisan key:generate
composer install
```

# Usage \ 使用方法

導入手順の実施後は Docker コンテナを起動すれば使える状態になります。  
使い終わったら Docker コンテナを停止してください。  
composer や artisan のコマンドを使用する場合はアプリケーションのコンテナに接続して実行してください。

## 起動

```bash
docker-compose up -d
```

## 停止

```bash
docker-compose stop
```

または

```bash
docker-compose down
```

## アプリケーションコンテナに接続

```bash
docker exec -it tutorial-php bash
```

## テスト実行

### 統合テスト・ユニットテスト

```bash
./vendor/bin/phpunit
```

### ブラウザテスト

```bash
php artisan dusk --env=testing
```

# Note \ 注意事項

まだ作成中です。

# Author \ 著者

- 神達小楠

# License \ ライセンス

「Laravel Tutorial」は[MIT license](https://en.wikipedia.org/wiki/MIT_License)です。
