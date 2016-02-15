<?php
/*
function.php
通常/home/kass1/to_html/function.phpに配置されます。
*/
session_start();
date_default_timezone_set('Asia/Tokyo');
header("Content-Type: text/html; charset=UTF-8");
ini_set( 'display_errors', 1 );
function sidebar($mode = "login"){
   /*
   初期設定
   サイドバーを読み込み場所を選んでください。
   1.ファイル
   2.このファイルの中で設定
   */
   $sidebar_load = 2;
   //1を選択したならば読み込むファイルを指定してください。
   $sidebar_load_url = "";
   //2を選択したならば表示する文章を<<<htmlからhtml;の間に入力してください。

if($mode == "login"){
$sidebar_load_data=<<<html
            <div id="right">
                <div class="sub-content">
                    <a href="http://minesever.kassi-hp.info/account/login.php" id="login">ログイン</a>

                </div>
            </div>
html;
}else if($mode == "logout"){
$sidebar_load_data=<<<html
                <div id="right">
                    <div class="sub-content">
                        <a href="http://minesever.kassi-hp.info/account/login.php?mode=logout" id="login">ログアウト</a>
                    </div>
html;
}
   
   //サイドバーの奴
   //初期設定の入力を確認
   
   switch ($sidebar_load){
   case 1:
     if($sidebar_load_url == ""){die("ServerError. function file Inadequacy");}
     // ファイルの場合
        if($file = file_get_contents($sidebar_load_url)){
           return $file;
        }else{
           return "サーバー側でエラーが発生しました。<br />サイドバーファイルにアクセスできません。";
        }
        
     break;
   case 2:
     // このファイル上で完結
     if($sidebar_load_data == ""){die("ServerError. function file Inadequacy");}
     return $sidebar_load_data;
     
     break;
   default:
     // 処理
     die("ServerError. function file Inadequacy");
     break;
   }
} //sidebar();

function top(){
   /*
   初期設定
   ヘッダーを読み込み場所を選んでください。
   1.ファイル
   2.このファイルの中で設定
   */
   $top_load = 2;
   //1を選択したならば読み込むファイルを指定してください。
   $top_load_url = "";
   //2を選択したならば表示する文章を<<<htmlからhtml;の間に入力してください。
   
$top_load_data=<<<html
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <!--
            Twitter @Hirotaisou2012 フォローよろしく
        -->
        <!-- デザイン -->
        <meta name="viewport" content="width=device-width" />
        <!-- Twitter meta -->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@kassi_0123_bot" />
        <meta name="twitter:title" content="kassi-hp.tk MinecraftServer" />
        <meta name="twitter:description" content="kassi-hp.tk MinecraftServerは、他にはない独特の世界観を持ったプレイヤー達が、他にはない独特の世界観の町を作っていく、不思議なクリエイティブサーバーです。" />
        <meta name="twitter:image" content="http://minesever.kassi-hp.info/img/icon.png" />
        <!-- OGP -->
        <meta property="og:title" content="kassi-hp.tk MinecraftServer">
        <meta property="og:type" content="website">
        <meta property="og:description" content="kassi-hp.tk MinecraftServerは、他にはない独特の世界観を持ったプレイヤー達が、他にはない独特の世界観の町を作っていく、不思議なクリエイティブサーバーです。">
        <meta property="og:url" content="http://minesever.kassi-hp.info/">
        <meta property="og:image" content="http://minesever.kassi-hp.info/img/icon.png">
        <meta property="og:site_name" content="kassi-hp.tk Minecraft Server">
        <meta property="og:email" content="kassi.minecraft@gmail.com">
        <meta name="Keywords" content="kassi-hp.tk,Minecraft,マイクラ,かっしー" />
        <title>kassi-hp.tk MinecraftServer</title>
        <style>
            <!--
            html{font-family:'Lucida Grande','Hiragino Kaku Gothic ProN','Meiryo',sans-serif;
                font-size: 10px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
            body{width: 100%;margin: 0;color:#333;}
            article,aside,footer,header,main,nav,section,p{margin: 0;padding: 0;
                display: block;box-sizing: border-box;}
            img{vertical-align: bottom;}
            -->
        </style>
        <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
        <link rel="stylesheet" href="http://minesever.kassi-hp.info/css/main.css" />
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            
            ga('create', 'UA-67531529-1', 'auto');
            ga('require', 'displayfeatures');
            ga('send', 'pageview');
            
            var dimensionValue = 'SEM Technology Referrer Spam Avoidance';
            ga('set', 'dimension1', dimensionValue);
            
        </script>
    </head>
    <body>
        <header>
            <div id="headerMain">
                <h1>kassi-hp.tk MinecraftServer</h1>
                <div id="search"></div>
            </div>
            <nav id="global">
                <label for="globalnavi-button"></label>
                <input id="globalnavi-button" type="checkbox" />
                <ul>

                    <li><a href="http://minesever.kassi-hp.info/">TOP</a></li>

                    <li><a href="http://minesever.kassi-hp.info/rule/">ルール</a></li>

                    <li><a href="http://minesever.kassi-hp.info/community/">コミュニティ</a></li>

                    <li><a href="http://minesever.kassi-hp.info/server-info/">仕様</a></li>

                    <li><a href="http://goo.gl/forms/irZHe6f8qj" target="_blank">お問い合わせ</a></li>

                </ul>

            </nav>
            <div id="mainVisual">
                <img src="http://minesever.kassi-hp.info/img/main/index.php" alt="" />
            </div>
        </header>
html;
   
   //ヘッダーの奴
   //初期設定の入力を確認
   
   switch ($top_load){
   case 1:
     if($top_load_url == ""){die("ServerError. function file Inadequacy");}
     // ファイルの場合
        if($file = file_get_contents($top_load_url)){
           return $file;
        }else{
           return "サーバー側でエラーが発生しました。<br />ヘッダーファイルにアクセスできません。";
        }
        
     break;
   case 2:
     // このファイル上で完結
     if($top_load_data == ""){die("ServerError. function file Inadequacy");}
     return $top_load_data;
     
     break;
   default:
     // 処理
     die("ServerError. function file Inadequacy");
     break;
   }
} //top();

function footer(){
   /*
   初期設定
   サイドバーを読み込み場所を選んでください。
   1.ファイル
   2.このファイルの中で設定
   */
   $footer_load = 2;
   //1を選択したならば読み込むファイルを指定してください。
   $footer_load_url = "";
   //2を選択したならば表示する文章を<<<htmlからhtml;の間に入力してください。
   
$footer_load_data=<<<html
        <footer>
            <p>&copy;&ensp;2014-2015&ensp;&ensp;kassi-hp.tk MinecraftServer</p>
        </footer>
        <!--
        <script src="http://minesever.kassi-hp.info/js/jquery-1.11.1.js"></script>
        -->
    </body>
</html>
html;
   
   //サイドバーの奴
   //初期設定の入力を確認
   
   switch ($footer_load){
   case 1:
     if($footer_load_url == ""){die("ServerError. function file Inadequacy");}
     // ファイルの場合
        if($file = file_get_contents($sidebar_load_url)){
           return $file;
        }else{
           return "サーバー側でエラーが発生しました。<br />フッターファイルにアクセスできません。";
        }
        
     break;
   case 2:
     // このファイル上で完結
     if($footer_load_data == ""){die("ServerError. function file Inadequacy");}
     return $footer_load_data;
     
     break;
   default:
     // 処理
     die("ServerError. function file Inadequacy");
     break;
   }
} //footer();

class MyDB {
  private $host; //接続先ホスト
  private $user; //MySQLユーザー
  private $password; //MySQLパスワード
  private $db; //MySQLデータベース
  public $mysqli; //mysqliオブジェクト
  
  // コンストラクタ(new時に処理される)
  function __construct(){
    //初期設定
    $this->host = "minesever.kassi-hp.info";
    $this->user = "toma";
    $this->password = "V43hPz6UDvRZ77dQ";
    $this->db = "toma";
     // DB接続を開始
    $this->mysqli = new mysqli("minesever.kassi-hp.info", "toma",  "V43hPz6UDvRZ77dQ", "toma");
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
// require_once("./function.php");

/*
絶対忘れるからsessionはstartさせとく。
dateもTokyoにしとく。

file_get_contexts();
file_get_contentsを打ち間違えるからね？ね？

file_put_contexts();
file_put_contentsを打ち間違えるからね？ね？

make_security_code();
セキュリティコードを生成

check_security_code($check_security_code);
引数にPOSTあたりで取ったコードを入れると認証する。
なにかを表示する前に。

h($str);
htmlspecialcharsの略称

Author : kassi-hp.tk Minecraft Server 宣伝部
*/
session_start();
date_default_timezone_set('Asia/Tokyo');
header("Content-Type: text/html; charset=UTF-8");
ini_set( 'display_errors', 1 );
//クリックジャッキング対策(フレームに表示させない)
header("X-FRAME-OPTIONS:DENY");
if(!function_exists("file_get_contexts")){
    function file_get_contexts($url){
        $result = file_get_contents($url);
        return $result;
    }
}
if(!function_exists("file_put_contexts")){
    function file_put_contexts($url,$data){
        $result = file_put_contents($url,$data);
        return $result;
    }
}
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

if(!function_exists("login")){
    function login($session_code){
        if(!isset($_SESSION["Minecraftuuid"])){header("Location: http://minesever.kassi-hp.info/account/login.php");}
        
        $link_ = mysql_connect("minesever.kassi-hp.info", "toma", "V43hPz6UDvRZ77dQ");
        $db_selected_ = mysql_select_db("toma", $link_);
        mysql_set_charset("utf8");
        $result_ = mysql_query("SELECT * FROM account WHERE uuid = \"".$_SESSION["Minecraftuuid"]."\"");
        $row_ = mysql_fetch_assoc($result_);
        if($_SESSION["Minecraft_session_code"] !== $session_code){ header("Location: http://minesever.kassi-hp.info/account/error.php"); }
        if($row_["auth"] == "ice"){
            die("このアカウントは凍結されています。凍結の基準は<a href=\"http://mc.4096.xyz/account/ice.php\">こちら</a>");
        }
        mysql_close($link);
    }
}
