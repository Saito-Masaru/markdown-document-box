<?php
/*
=======================================================================
    Markdown記法(.md)ファイルをHTML記法に変換するスクリプト
    
    拡張子.mdの場合、このファイルを開くようにhtaccessで定義。環境変数（$_SERVER）情報より
    該当mdファイルを参照し、Parsedownクラスを使ってHTML形式に変換を行う。
    
    ・ Parsedown : http://parsedown.org/
    ・ 詳細　 : https://blog.keinos.com/20161213_1906
    ・ 参照元 : http://blog.fenrir-inc.com/jp/2012/05/github_markdown.html
   =======================================================================
*/

require_once 'vendor/autoload.php';

if (isset($_SERVER['PATH_TRANSLATED'])) {
    $file = realpath($_SERVER['PATH_TRANSLATED']);
    $ext  = substr($file, strrpos( $file, '.' ) + 1);
}

if ($file and is_readable($file) and $ext === 'md') {
    $oParsedown = new Parsedown();
    $sBody      = $oParsedown->text(file_get_contents( $file));
} else {
    $sBody = '<p>cannot read file</p>';
}

header('Content-Type: text/html;');
?>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title><?= $file ?></title>
    <link rel="stylesheet" href="/css/base.css"/>
    <script src="/js/autoLinktargetChange.js"></script>
</head>
<body>
<p>
[<a href="/">top</a>]&nbsp;&nbsp;[<a href="./">index</a>]
</p>
<hr/>
<?= $sBody ?>
</body>
</html>
