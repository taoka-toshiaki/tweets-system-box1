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
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e9ecef;
        }
    </style>
    <title>?</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <span id="loading" class="h2"><img src="1474.gif">数珠繋ぎツイート😋</span><a href="./edit.php">[編集]</a>
                <div class="radio h5">
                    <label>
                        <input id="select_radio1" type="radio" name="time" value="0" checked="checked"><label for="select_radio1">Now()</label>
                        <input id="select_radio2" type="radio" name="time" value="1"><label for="select_radio2">予約</label>
                        <input type="datetime-local" id="tweets-time" name="tweets-time" value="<?= date("Y-m-d\TH:i") ?>" min="<?= date("Y-m-d\TH:i") ?>" max="<?= (function () {
                                                                                                                                                                    $d = new DateTime();
                                                                                                                                                                    $d->modify("+7days");
                                                                                                                                                                    return $d->format("Y-m-d\TH:i");
                                                                                                                                                                })() ?>T00:00">
                    </label>
                </div>                
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                <input class="form-control" type="text" name="text">
                <input class="form-control" type="password" name="password">
                <button id="btn1" class="btn btn-dark" type="button">追加</button>
                <button id="btn2" class="btn btn-success" type="button">ツイートする</button>
            </div>
            <div id="frm" class="col-12">
            </div>            
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js' integrity='sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==' crossorigin='anonymous'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
    <script src="./src/js/m1.js?<?=time()?>"></script>
</body>
</html>
<?php
