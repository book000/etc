<?php
if(isset($argv[1]) && isset($argv[2])){
    $X = $argv[1];
    $Y = $argv[2];
    sleep(2);// 783 1833
    exec("adb shell input touchscreen tap " . $X . " " . $Y);
}else{
    sleep(2);
/*
    exec("adb shell input keyevent 22");
    usleep(500);
    exec("adb shell input keyevent 23");
    */
    
    exec("adb shell input keyevent 23"); // 入力モードに。テキストボックスにフォーカスをあてる
    exec("adb shell input keyevent 61"); // TABキー。「バックアップしない」にフォーカスを移動する
    exec("adb shell input keyevent 61"); // TABキー。「データをバックアップ」にフォーカスを移動する
    exec("adb shell input keyevent 66"); // Enterキー。セレクトしているキーをエンターする
}
