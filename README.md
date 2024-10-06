# ファイルサーバ兼ドキュメントサーバ

## 概要

ファイルサーバー兼wiki（markdown）サーバー。  
docsディレクトリの中に拡張子（.md）を置いた状態でブラウザでアクセスするとマークアップされたドキュメントとして表示される。  
ファイルサーバ機能はsambaで対応している。  

## 利用方法

起動  

```
docker-compose up -d
```

※1回目の起動直後の場合は以下のコマンドを投入する。  

* phpのライブラリをインストールする

docker exec -it documents_web composer install
```

sambaの管理者ユーザーのパスワードを設定する。  

```
docker exec -it documents_samba smbpasswd -a root
New SMB password:
Retype new SMB password:
Added user root.
```

ブラウザでアクセスする。  
`http://localhost:8080/`  

※sambaについてはdocker-engineをインストールした環境で実行した場合は `\\サーバーのIPアドレス\docs\` でアクセスできるはずです。  
こちらについては、実環境に置いての確認ができていないので"はず"と表現させていただきます。  

## .mdのファイルがマークアップされる技術的な設定内容

apache2の設定（httpd.conf）

```
    # .mdをmarkdown paser処理を通して表示する
    AddType text/markdown .md
    Action text/markdown /php/markdown.php
    DirectoryIndex index.php index.md index.html index.htm
```

上記の中でまず `AddType` によって `.md` のファイルを `test/markdown` というcontent-typeに定義します。  
次にこの `text/markdown` のファイルを `/php/markdown.php` に処理して出力（フィルター処理）することを定義します。  
なお、この `/php/markdown.php` の `/php/` は以下の設定で ドキュメントディレクトリ（docs）ではなく `docker/apache2/www/php/markdown.php` でアクセスしています。  

Alias /php/ /var/www/php/
<Directory "/var/www/php">
    Require all granted
</Directory>
```

なおここには書きませんがphpと同じ階層に `css` と `js` をaliasとして定義しております。
また、 `/var/www` や `/var/www/docs` （document root）のマウントは `docker-compose.yml` の中で定義しています。  

なお、defaultのapacheでは下記の `Action` は処理できないので

```
    Action text/markdown /php/markdown.php
```

`docker/apach2/Dockerfile` にてapacheの `actions` モジュールを有効化しています。  

```
#Apache のモジュールを有効化
RUN a2enmod deflate expires rewrite actions
```

### ToDo

* sambaでのファイル共有の確認（virtualboxなどで確認予定）  
* .md ファイルをブラウザ上で編集、プレニューできるようにする
* markdown parserがあまりにも古いので最新のイケてるparserに更新したい
* ブラウザだけでファイルマネージャの機能ライクな操作ができるようにしたい
* ドキュメントの自動的なバージョン管理をできりようにしたい

### 参考にしたサイト

apache2 + php
https://lazesoftware.com/ja/blog/230220/

samba
https://at-sushi.com/pukiwiki/index.php?Docker%20Compose%20%A4%AA%BC%EA%B7%DA%20Samba%20%A5%B5%A1%BC%A5%D0

* markdownのパーサー及びapacheの設定など
    * 参照元 : http://blog.fenrir-inc.com/jp/2012/05/github_markdown.html
        * 
    * markdown paser(parsedown) : http://parsedown.org/
        * いくつか比較した結果一番しっくり来たmarkdown parserと判定した。

