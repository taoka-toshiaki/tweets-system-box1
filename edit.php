<?php
date_default_timezone_set('Asia/Tokyo');
session_start();
// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<title>?Edit</title>
<style>
    body {
            background-color: #e9ecef;
        }
</style>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-12">
                <span id="loading" class="h2"><img src="./1474.gif">数珠繋ぎツイート😋Edit</span><a href="./index.php">Top</a>
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                <input class="form-control" type="text" name="text">
                <input class="form-control" type="password" name="password">
                <button id="btn1" class="btn btn-success" type="button">ロードする</button>
            </div>           
        </div>
</div>
<span id="frm">
</span> 
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js' integrity='sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==' crossorigin='anonymous'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
<script src="./src/js/m2.js?<?=time()?>"></script>
</body>
</html>