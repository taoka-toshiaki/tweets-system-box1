<?php
ini_set("display_errors",0);
require_once "tw-post.php";
session_start();
$id = xss_def($_POST["text"]);
$password = xss_def($_POST["password"]);
$tw_text = xss_def($_POST["tw-text"]);
$timeonoff = (int)xss_def($_POST["timeonoff"]);
$times = xss_def($_POST["time"]);
$mode = xss_def($_POST["mode"]);
$key =  (int)xss_def($_POST["tw_id"]);

$ret["msg"] = "NG";
if (isset($_POST["csrf"])  && xss_def($_POST["csrf"]) === $_SESSION['csrf_token']) {

    if($id === ID && $password === xss_def(PASSWORD)){
       $tw = new tw();
       if(!$mode){
        if($tw->pickup_tweets($tw_text,$timeonoff,$times,$id)){
            $ret["msg"] = "ok";
           }else{
            $ret["msg"] = "NG";
           }
       }else{

        switch ($mode) {
            case 'edit':
                # code...
                $ret["msg"] = $tw->tweets_update($key,1,$times,$id,$tw_text)?"ok":"NG";
                break;
            case 'delete':
                # code...
                $ret["msg"] = $tw->tweets_delete($key,1,$times,$id)?"ok":"NG";
                break;
            default:
                # code...
                $ret["value"] = $tw->tweets_load($id);
                $ret["msg"] = $ret["value"]?"ok":"NG";
                break;
        }
       }

    }
}

print json_encode($ret);


function xss_def($val=false){
    if(is_array($val)){
        foreach ($val as $key => $value) {
          $val[$key]  = strip_tags($value);
          $val[$key]  = htmlspecialchars($val[$key]);
        }
    }else{
        $val  = strip_tags($val);
        $val  = htmlspecialchars($val);
    }
    return $val;
}