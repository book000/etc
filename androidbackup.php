<?php
/*
  Android バックアッププログラム
  APKファイル(apkディレクトリ)、アプリケーションデータ(appbackupディレクトリ)、本体のデータ(dataディレクトリ)、SDカードデータ(sdcarddataディレクトリ内)をダウンロードできる

  実行前に端末のUSBデバッグをオンにしてください。
  端末接続されていなくても特にチェックせず動作するため、事前にadb devicesコマンドでチェックしてから！
  複数端末接続非対応
 */

$playstore_use_flag = true; // アプリ名の取得にプレイストアを利用するか？

function pp($text){
    $text = trim($text);
    echo date("Y/m/d H:i:s") . " | " . $text . "\n";
}

/**
 * Windowsでファイル名に使用できない文字列をアンダーバーに置き換える
 * @param $str ファイル名
 * @return 置き換えた後のファイル名
 */
function replaceFilenameillegalChar($str)
{
    return str_replace(["\\", "/", "", "*", "?", "\"", "<", ">", "|", ":"], "_", $str);
}

/**
 * 指定したディレクトリを削除する
 *
 * @param string $dir
 * @return boolean
 */
function rmrf(string $dir)
{
    if (!is_dir($dir))
    {
        return false;
    }
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($file == "." || $file == ".."){
                continue;
            }
            if(is_file($dir . DIRECTORY_SEPARATOR . $file)){
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }else if(is_dir($dir . DIRECTORY_SEPARATOR . $file)){
                rmrf($dir . DIRECTORY_SEPARATOR . $file);
            }
        }
        closedir($dh);
    }
    return rmdir($dir);
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR);
}
if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR);
}
if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR);
}
if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR);
}
if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR);
}

// モデル名取得
$model = shell_exec("adb shell getprop ro.product.model");
$model = trim($model);

if ($model == "") {
    echo "Model Name is not found!\n";
    exit;
}

pp("Model: " . $model);

$size = exec("adb shell wm size");
preg_match("/Physical size: ([0-9]+)x([0-9]+)/", $size, $m);
$win_x = $m[1];
$win_y = $m[2];

$x = $win_x - 300;
$y = $win_y - 100;

pp("Window Size: " . $win_x . " x " . $win_y);

exec("aapt > nul 2>&1", $_out, $return_var);
$aapt_enable = ($return_var != 1);
if($aapt_enable){
    pp("aapt: Available");
}else{
    pp("aapt: Unavailable");
}

// プレイストアアクセス時のエラー抑制
$context = stream_context_create(array(
    "http" => array("ignore_errors" => true)
));

system("adb shell mkdir /storage/emulated/0/APK");

// 端末にインストールされているアプリのリストを取得
$adblist = shell_exec("adb shell pm list packages -f");
$adblist = str_replace("package:", "", $adblist); // 各行の最初のpackage:を消す
$adblists = explode("\n", $adblist); // 改行で分割

$backupList = array();

foreach ($adblists as $key => $adb) {
    $pos = mb_strrpos($adb, "="); // 行の最後に出てくる=を探す
    if ($pos === false) {
        continue; // =がなかったら次へ
    }
    $id = mb_substr($adb, $pos + 1); // =以降がパッケージ名
    $id = trim($id);
    $apkFile = mb_substr($adb, 0, $pos); // =以前がapkファイルのパス
    $apkFile = trim($apkFile);

    pp("[" . ($key + 1) . "/" . count($adblists) . "] ---------- " . $id . " -----");

    // プレイストアにアクセス
    if($playstore_use_flag){
        $html = file_get_contents("https://play.google.com/store/apps/details?hl=ja_JP&id=" . $id, false, $context);

        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $status_code = $matches[1]; // ステータスコード取得
    }else{
        $status_code = -1;
    }
    
    unset($name);
    if ($status_code == 200) { // ステータスコードが200だったら
        pp("[" . ($key + 1) . "/" . count($adblists) . "] GooglePlay StatusCode: 200");
        preg_match("/<h1.* itemprop=\"name\"><span >(.+)<\/span><\/h1>/", $html, $m);
        if(isset($m[1])){
            $name = html_entity_decode($m[1]); // アプリ名取得
            $nameORId = replaceFilenameillegalChar(
                html_entity_decode(
                    $m[1]
                )
            );
            if (replaceFilenameillegalChar($m[1]) != $nameORId && file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . replaceFilenameillegalChar($m[1]))) {
                rename(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . replaceFilenameillegalChar($m[1]), __DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId);
                pp("[" . ($key + 1) . "/" . count($adblists) . "] " . "RENAME FROM " . "apk" . DIRECTORY_SEPARATOR . replaceFilenameillegalChar($m[1]) . " TO " . "apk" . DIRECTORY_SEPARATOR . $nameORId . "!");
            }
        }
    }
    if(!isset($name) && $aapt_enable) {
        // apkを引っ張ってきてaaptで調べる
        pp("[" . ($key + 1) . "/" . count($adblists) . "] Try to get data from aapt...");
        system("adb shell cp \"" . $apkFile . "\" /storage/emulated/0/APK");
        $filename = substr($apkFile, strrpos($apkFile, "/") + 1);
        $output = exec("adb pull -p \"/storage/emulated/0/APK/" . $filename . "\" -p \"" . __DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "tmp.apk\"");
        pp($output);
        if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "tmp.apk")){
            // ok
            $packageaaptData = shell_exec("aapt dump badging " . __DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "tmp.apk");
                unset($m);
            preg_match("/application-label-ja:'(.+)'/", $packageaaptData, $m);
            if(isset($m[1])){
                $name = html_entity_decode($m[1]); // アプリ名取得
                $nameORId = replaceFilenameillegalChar(
                    html_entity_decode(
                        $m[1]
                    )
                );
                pp("[" . ($key + 1) . "/" . count($adblists) . "] Successful data acquisition from aapt! (ja)");
            }else{
                unset($m);
                preg_match("/application-label:'(.+)'/", $packageaaptData, $m);
                if(isset($m[1])){
                    $name = html_entity_decode($m[1]); // アプリ名取得
                    $nameORId = replaceFilenameillegalChar(
                        html_entity_decode(
                            $m[1]
                        )
                    );
                    pp("[" . ($key + 1) . "/" . count($adblists) . "] Successful data acquisition from aapt! (other lang)");
                }
            }
            system("adb shell rm \"/storage/emulated/0/APK/" . $filename . "\"");
            unlink(__DIR__ . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "tmp.apk");
        }
    }
    if(!isset($name) || $name == null){
        pp("[" . ($key + 1) . "/" . count($adblists) . "] Failed to get application name. Use application ID.");
        $name = "null"; // プレイストアもaaptからも取得できなかったらnull
        $nameORId = $id;
    }

    // 端末からアプリのバージョンを取得する
    $packageData = shell_exec("adb shell dumpsys package " . $id);
    preg_match("/versionName=(.+)/", $packageData, $m);
    $versionName = $m[1];

    pp("[" . ($key + 1) . "/" . count($adblists) . "] Application Name: " . $name);
    pp("[" . ($key + 1) . "/" . count($adblists) . "] Version: " . $versionName);
    //echo __DIR__ . "/apk/" . $id . "_" . $versionName . ".apk";

    $backupList[] = [
        "name" => $name,
        "id" => $id,
        "path" => $apkFile,
        "version" => $versionName
    ];
    //break;

    if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId)) {
        mkdir(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId);
    }

    $versionName = replaceFilenameillegalChar($versionName);

    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId . DIRECTORY_SEPARATOR . $versionName . ".apk")) {
        pp("[" . ($key + 1) . "/" . count($adblists) . "] APK File is found!");
        continue;
    }

    // APKファイルダウンロード
    pp("[" . ($key + 1) . "/" . count($adblists) . "] APK File Downloading...");

    system("adb shell cp \"" . $apkFile . "\" /storage/emulated/0/APK");
    $filename = substr($apkFile, strrpos($apkFile, "/") + 1);
    $output = exec("adb pull -p \"/storage/emulated/0/APK/" . $filename . "\" -p \"" . __DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId . DIRECTORY_SEPARATOR . $versionName . ".apk\"");
    pp($output);
    if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $nameORId . DIRECTORY_SEPARATOR . $versionName . ".apk")){
        pp("[" . ($key + 1) . "/" . count($adblists) . "] APK File Download Successful! (ApplicationName)");
    }else{
        // download error
        if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $id)) {
            mkdir(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $id);
        }
        $output = exec("adb pull -p \"/storage/emulated/0/APK/" . $filename . "\" -p \"" . __DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $versionName . ".apk\"");
        pp($output);
        if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "apk" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $versionName . ".apk")){
            pp("[" . ($key + 1) . "/" . count($adblists) . "] APK File Download Successful! (ApplicationID)");
        }
    }
    system("adb shell rm \"/storage/emulated/0/APK/" . $filename . "\"");

    if (($key + 1) % 20 == 0) {
        // 20アプリずつ画面をタッチして起動させておく
        $fp = popen('start /b "" php ' . __DIR__ . '/touch.php 0 0', 'r');
        pclose($fp);
    }
}
pp(count($backupList) . " apps backupped!");
system("adb shell rm -rf /storage/emulated/0/APK");

$DATE = date("Ymd");

foreach ($backupList as $key => $one) {
    $name = $one["name"];
    $id = $one["id"];
    $path = $one["path"];
    $versionName = $one["version"];

    if ($name != null) {
        $nameORId = $name;
    } else {
        $nameORId = $id;
    }

    pp("[" . ($key + 1) . "/" . count($backupList) . "] " . $name . " " . $id . " " . $versionName);

    if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model)) {
        mkdir(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model);
    }

    if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)) {
        mkdir(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
    }

    $file = __DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . DIRECTORY_SEPARATOR . $nameORId . ".ab";

    if (file_exists($file)) {
        pp("App backup File is found!");
        continue;
    }

    // アプリバックアップ
    // 端末でバックアップの確認がされるので端末操作必要

    //$fp = popen('start /b "" php ' . __DIR__ . '/touch.php ' . $x . ' ' . $y, 'r');
    $fp = popen('start /b "" php ' . __DIR__ . '/touch.php', 'r');
    pclose($fp);

    pp(exec("adb backup -f \"" . $file . "\" -apk -obb " . $id));
}
if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)){
    system("7z a " . __DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . ".7z " . __DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE, $ret);
}
if($ret == 0){
    echo "ReturnCode: " . $ret . ". Delete dir.";
    rmrf(__DIR__ . DIRECTORY_SEPARATOR . "appbackup" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}else{
    echo "ReturnCode: " . $ret . ".";
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model);
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}

// 本体のデータをダウンロード
pp("start data backup...");

system("adb pull /sdcard/ \"" . __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . "\"");

if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)){
    system("7z a " . __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . ".7z " . __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE, $ret);
}
if($ret == 0){
    echo "ReturnCode: " . $ret . ". Delete dir.";
    rmrf(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}else{
    echo "ReturnCode: " . $ret . ".";
}

pp("end data backup.");

// SDカードがある場合それをバックアップ
pp("start SDCard data backup...");

exec("adb shell cat /proc/mounts", $output);
$sddrives = [];
foreach($output as $one){
    $one = explode(" ", $one);
    if(substr($one[1], 0, strlen("/storage/")) == "/storage/"){
        if($one[1] == "/storage/emulated"){
            continue;
        }
        $sddrives[] = $one[1];
    }
}

if(count($sddrives) == 0){
    pp("SDCard is not found.");
    exit;
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model);
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}

foreach($sddrives as $sddrive){
    $sdName = substr($sddrive, strrpos($sddrive, "/") + 1);
    system("adb pull " . $sddrive . " \"" . __DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . "\"");
}

if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE)){
    system("7z a " . __DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE . ".7z " . __DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}
if($ret == 0){
    echo "ReturnCode: " . $ret . ". Delete dir.";
    rmrf(__DIR__ . DIRECTORY_SEPARATOR . "sdcarddata" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR . $DATE);
}else{
    echo "ReturnCode: " . $ret . ".";
}

pp("end SDCard data backup.");
