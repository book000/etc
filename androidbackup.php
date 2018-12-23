<?php
/*
  Android バックアッププログラム
  APKファイル(apkディレクトリ)、アプリケーションデータ(appbackupディレクトリ)、本体のデータ(dataディレクトリ)をダウンロードできる

  実行前に端末のUSBデバッグをオンにしてください。
  端末接続されていなくても特にチェックせず動作するため、事前にadb devicesコマンドでチェックしてから！
  複数端末接続非対応
 */

/**
 * Windowsでファイル名に使用できない文字列をアンダーバーに置き換える
 * @param $str ファイル名
 * @return 置き換えた後のファイル名
 */
function replaceFilenameillegalChar($str)
{
    return str_replace(["\\", "/", "", "*", "?", "\"", "<", ">", "|", ":"], "_", $str);
}

if (!file_exists(__DIR__ . "/apk/")) {
    mkdir(__DIR__ . "/apk/");
}
if (!file_exists(__DIR__ . "/appbackup/")) {
    mkdir(__DIR__ . "/appbackup/");
}
if (!file_exists(__DIR__ . "/data/")) {
    mkdir(__DIR__ . "/data/");
}

// モデル名取得
$model = shell_exec("adb shell getprop ro.product.model");
$model = trim($model);
echo "Model: " . $model . "\n";

// プレイストアアクセス時のエラー抑制
$context = stream_context_create(array(
    "http" => array("ignore_errors" => true)
));

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

    // プレイストアにアクセス
    $html = file_get_contents("https://play.google.com/store/apps/details?hl=ja_JP&id=" . $id, false, $context);

    preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
    $status_code = $matches[1]; // ステータスコード取得

    if ($status_code == 200) { // ステータスコードが200だったら
        preg_match("/<h1.* itemprop=\"name\"><span >(.+)<\/span><\/h1>/", $html, $m);
        $name = $m[1]; // アプリ名取得
        $nameORId = replaceFilenameillegalChar($m[1]);
    } else {
        $name = null; // プレイストアから取得できなかったらnull
        $nameORId = $id;
    }
    
    // 端末からアプリのバージョンを取得する
    $packageData = shell_exec("adb shell dumpsys package " . $id);
    preg_match("/versionName=(.+)/", $packageData, $m);
    $versionName = $m[1];

    echo "[" . ($key + 1) . "/" . count($adblists) . "] " . $name . " " . $id . " " . $versionName . "\n";
    //echo __DIR__ . "/apk/" . $id . "_" . $versionName . ".apk";

    $backupList[] = [
        "name" => $name,
        "id" => $id,
        "path" => $apkFile,
        "version" => $versionName
    ];

    if (!file_exists(__DIR__ . "/apk/" . $nameORId)) {
        mkdir(__DIR__ . "/apk/" . $nameORId);
    }

    $versionName = replaceFilenameillegalChar($versionName);

    if (file_exists(__DIR__ . "/apk/" . $nameORId . "/" . $versionName . ".apk")) {
        echo "APK File is found!\n";
        continue;
    }

    // APKファイルダウンロード
    $output = exec("adb pull -p \"" . $apkFile . "\" -p \"" . __DIR__ . "/apk/" . $nameORId . "/" . $versionName . ".apk\"");
    echo $output . "\n";
}
echo count($backupList) . " apps backupped!\n";

$DATA = date("Ymd");

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

    echo "[" . ($key + 1) . "/" . count($backupList) . "] " . $name . " " . $id . " " . $versionName . "\n";

    if (!file_exists(__DIR__ . "/appbackup/" . $model)) {
        mkdir(__DIR__ . "/appbackup/" . $model);
    }

    if (!file_exists(__DIR__ . "/appbackup/" . $model . "/" . $DATA)) {
        mkdir(__DIR__ . "/appbackup/" . $model . "/" . $DATA);
    }

    $file = __DIR__ . "/appbackup/" . $model . "/" . $DATA . "/" . $nameORId . ".adb";

    if (file_exists($file)) {
        echo "App backup File is found!\n";
        continue;
    }

    // アプリバックアップ
    // 端末でバックアップの確認がされるので端末操作必要
    echo exec("adb backup -f \"" . $file . "\" -apk -obb " . $id) . "\n";
}

if (!file_exists(__DIR__ . "/data/" . $model)) {
    mkdir(__DIR__ . "/data/" . $model);
}

if (!file_exists(__DIR__ . "/data/" . $model . "/" . $DATA)) {
    mkdir(__DIR__ . "/data/" . $model . "/" . $DATA);
}

// 本体のデータ(実はsdcardではない)をダウンロード
echo "start sdcard backup...\n";

system("adb pull /sdcard/ \"" . __DIR__ . "/data/" . $model . "/" . $DATA . "\"");

echo "end sdcard backup...\n";
