<?php
date_default_timezone_set('Asia/Tokyo');
ini_set("display_errors",0);
require_once "./data/tw-config-v2.php";
require_once "../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

class tw
{
    var $connection = null;
    var $pdo = null;
    function __construct()
    {
        $this->connection = new TwitterOAuth(APIKEY, APISECRET, ACCESSTOKEN, ACCESSTOKENSECRET);
        $this->connection->setApiVersion("2");
    }
    function db_connection()
    {
        try {
            //code...
            $res = $this->pdo = new PDO("sqlite:./data/tw-tweets-db.sqlite3");
        } catch (\Throwable $th) {
            //throw $th;
            //print $th->getMessage();
            $res = false;
        }
        return $res;
    }

    function timecheck($timeonoff, $times)
    {
        if (!$timeonoff) return true;
        $n = new DateTime();
        $t = new DateTime($times);
        return $t <= $n ? true : false;
    }

    function pickup_tweets(mixed $tw_text = null, int $timeonoff = 0, mixed $times = null, string $id = "")
    {
        if (!$times) return false;
        $obj = (object)[];
        $times = preg_replace("/\-/", "/", $times);
        $times = preg_replace("/T/", " ", $times);

        if ($this->timecheck($timeonoff, $times)) {
            if (isset($tw_text) && is_array($tw_text)) {
                foreach ($tw_text as $key => $value) {
                    if (preg_replace("/[ |　]/", "", $value)) {
                        $obj = !$key ? ($this->connection->post("tweets", ["text" => $value], true)
                        ) : ($this->connection->post("tweets", ["reply" => ["in_reply_to_tweet_id" => $obj->data->id], "text" => $value], true)
                        );
                    }
                }
                return true;
            }
        } else {
            return $timeonoff ? $this->save_sqlite($tw_text, $timeonoff, $times, $id): true;
        }
    }

    function save_sqlite($tw_text = null, int $timeonoff = 0, mixed $times = null, string $id = "")
    {
        if ($this->db_connection()) {
            try {
                //code...
                if (isset($tw_text) && is_array($tw_text)) {
                    foreach ($tw_text as $key => &$value) {
                        if (preg_replace("/[ |　]/", "", $value)) {
                            $stmt = $this->pdo->prepare("insert into tweets (tw_id,user,times,tw_text)values(:tw_id,:user,:times,:tw_text)");
                            $stmt->bindValue(":tw_id", $key, PDO::PARAM_INT);
                            $stmt->bindValue(":user", $id, PDO::PARAM_STR);
                            $stmt->bindValue(":times", $times, PDO::PARAM_STR);
                            $stmt->bindValue(":tw_text", $value, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    }
                }
                $this->pdo = null;
                return true;
            } catch (\Throwable $th) {
                //throw $th;
                return false;
            }
        }
    }
    function tweets_load(string $id = "")
    {
        if (!$id) return false;
        try {
            //code...
            $value = null;
            if ($this->db_connection()) {
                $stmt = $this->pdo->prepare("select * from tweets where user = :user order by times,tw_id asc;");
                $stmt->bindValue(":user", $id, PDO::PARAM_STR);
                $res = $stmt->execute();
                $value = $res ? $stmt->fetchAll() : false;
                $this->pdo = null;
            }
            return $value;            
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    function tweets_update(int $key = 0, int $timeonoff = 0, mixed $times = null, string $id = "",mixed $tw_text="")
    {
        try {
            //code...
            if(!preg_replace("/[ |　]{0,}/","",$tw_text))return false;
            if ($this->db_connection()) {
                $stmt = $this->pdo->prepare("update tweets set tw_text = :tw_text where tw_id = :tw_id and user = :user and times = :times");
                $stmt->bindValue(":tw_id", $key, PDO::PARAM_INT);
                $stmt->bindValue(":user", $id, PDO::PARAM_STR);
                $stmt->bindValue(":times", $times, PDO::PARAM_STR);
                $stmt->bindValue(":tw_text", $tw_text, PDO::PARAM_STR);
                $stmt->execute();
                $this->pdo = null;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
        return true;

    }

    function tweets_delete(int $key = 0, int $timeonoff = 0, mixed $times = null, string $id = "")
    {
        try {
            //code...
            if ($this->db_connection()) {
                $stmt = $this->pdo->prepare("delete from tweets where tw_id = :tw_id and user = :user and times = :times");
                $stmt->bindValue(":tw_id", $key, PDO::PARAM_INT);
                $stmt->bindValue(":user", $id, PDO::PARAM_STR);
                $stmt->bindValue(":times", $times, PDO::PARAM_STR);
                $stmt->execute();
                $this->pdo = null;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
        return true;
    }

    function bat_tweets(mixed $value = null)
    {
        if (!$value) return false;
        $obj = (object)[];
        $t = "";
        foreach ($value as $key => $val) {
            if ($this->timecheck(1, $val["times"])) {
                $obj = ($val["times"]<>$t)? ($this->connection->post("tweets", ["text" => $val["tw_text"]], true)
                ) : ($this->connection->post("tweets", ["reply" => ["in_reply_to_tweet_id" => $obj->data->id], "text" => $val["tw_text"]], true)
                );
                $this->tweets_delete($val["tw_id"], 1, $val["times"], $val["user"]);
                $t = $val["times"];
            } else {
              //  var_dump($val);
              //  break;
            }
        }
    }
}

if ($argv[0]) {
    $tw = new tw();
    $value = $tw->tweets_load(xss_d($argv[1]));
    $tw->bat_tweets($value);
}
function xss_d($val = false)
{
    if (is_array($val)) {
        foreach ($val as $key => $value) {
            $val[$key]  = strip_tags($value);
            $val[$key]  = htmlspecialchars($val[$key]);
        }
    } else {
        $val  = strip_tags($val);
        $val  = htmlspecialchars($val);
    }
    return $val;
}
