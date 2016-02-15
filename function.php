<?php
/*
function.php
Create By Tomachi. @book000
require_once("./function.php");
*/
session_start();
date_default_timezone_set('Asia/Tokyo');
header("Content-Type: text/html; charset=UTF-8");
ini_set( 'display_errors', 1 );
class MyDB {
  private $host; //接続先ホスト
  private $user; //MySQLユーザー
  private $password; //MySQLパスワード
  private $db; //MySQLデータベース
  public $mysqli; //mysqliオブジェクト
  
  // コンストラクタ(new時に処理される)
  function __construct(){
     // DB接続を開始
    $this->mysqli = new mysqli("HOST", "USER",  "PASS", "DB");
    if($this->mysqli->connect_error){
        die($this->mysqli->connect_error);
    }else{
        $this->mysqli->set_charset("utf8");
    }
  }
  
  //デストラクタ(終了時に処理される？)
  function __destruct(){
      //接続を閉じる
      $this->mysqli->close();
  }
  
  // 文字列をエスケープする
  function escape($str){
    return $this->mysqli->real_escape_string($str);
  }
  
  //クエリ実行
  function query($sql){
    //エスケープ
    //$sql = $this->escape($sql);
    //クエリ実行
    $result = $this->mysqli->query($sql);
    if($result === false){
      //エラー
      $return = array("status"=>false,
                                 "count"=>0,
                                 "result"=>"",
                                 "errno"=>$this->mysqli->errno,
                                 "error"=>$this->mysqli->error);
      return $return;
    }
    
    if($result === true){
      //Select文以外？
      //影響行数
      $this->count = $this->mysqli->affected_rows;
      $return = array("status"=>true,
                                 "count"=>$this->count,
                                 "result"=>"",
                                 "errno"=>"",
                                 "error"=>"");
      return $return;
    }else{
      //Select文？
      //selectの行数
      $this->count = $result->num_rows;
      $data = array();
      //連想配列にぶち込んでやるぜ
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
      //もういらないから閉じる
      $result->close();
      $return = array("status"=>true,
                                 "count"=>$this->count,
                                 "result"=>$data,
                                 "errno"=>"",
                                 "error"=>"");
    }
  }
}
//クリックジャッキング対策(フレームに表示させない)
header("X-FRAME-OPTIONS:DENY");
if(!function_exists("make_security_code")){
    function make_security_code(){
        //$lengthにセキュリティコードの文字長を入力
        $length = 16;
        
        static $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; ++$i) {
            $str .= $chars[mt_rand(0, 61)];
        }
        $str = md5($str);
        $_SESSION["account_security_code"] = $str;
        return $str;
    }
}
if(!function_exists("check_security_code")){
    function check_security_code($check_security_code){
/*
  $check_security_codeにはPOSTなりで飛んできたデータが入ってるので認証する
*/
        $session_security_code = $_SESSION["account_security_code"];
        if($session_security_code === $check_security_code){
            return true;
        }else{
            die("<!DOCTYPE html><html lang=\"ja\"><head><meta charset=\"UTF-8\" /><title>不正アクセスが検出されました</title></head><body><h1>ERROR</h1><p>不正アクセスが検出されました。<br><a href=\"index.php\">ホームに戻る</a></p></body></html>");
            return false;
        }
    }
}

if(!function_exists("h")){
    function h($str){
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }
}
