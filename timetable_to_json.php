<?php
/*
    時刻表JSON化プログラム timetable_to_json.php

    $stationCodeにYahoo!路線情報の駅コード(例: 東京駅であれば22828)と路線コード(例: JR中央線快速 高尾・青梅方面であれば1091)を入力するか、$wordに検索する文字列(例: 東京駅)を設定。
    入力しなければ対話形式で処理。
    その後実行すればtimetable.jsonに出力されるので、current_train_searcher.phpとかで利用。

    既にtimetable.jsonに東京駅 JR中央線快速 高尾・青梅方面(駅コード: 22828 / 路線コード: 1091)の時刻表が出力済み
*/
$stationCode = ""; // 駅コード
$lineCode = ""; // 路線コード

// 上か下か、もしくはどちらも入力せずphp timetable_to_json.phpなど

$word = ""; // 検索ワード

// ---------------------------------------------------------- //


if($stationCode == "" && $lineCode == ""){
    // 検索
    if($word == ""){
        echo "Input search word: ";
        $word = trim(fgets(STDIN));
        if($word == ""){
            echo "failed.\n";
            exit;
        }
    }
    $html = file_get_contents("https://transit.yahoo.co.jp/station/search?q=" . urlenCode($word) . "&done=sta");
    preg_match("/<ul class=\"elmSearchItem quad\">([\s\S]+?)<\/ul>/", $html, $m);
    if(!isset($m[1])){
        echo "0 matched.\n";
        echo "exit\n";
        exit;
    }
    $html = $m[1];
    preg_match_all("/<li><a href=\"\/station\/top\/([0-9]+)\/.*?\">(.+?)<\/a><\/li>/", $html, $m, PREG_SET_ORDER);
    echo count($m) . " matched.\n";
    if(count($m) == 0){
        echo "exit\n";
        exit;
    }
    $stationCode = $m[0][1];
    $stationName = $m[0][2];
    echo "Selected \"" . $stationName . " (" . $stationCode . ")\"\n";

    $html = file_get_contents("https://transit.yahoo.co.jp/station/rail/" . $stationCode . "/");
    preg_match("/<div id=\"mdSearchLine\">([\s\S]+)<\/div><!-- \/#mdSearchLine -->/", $html, $m);

    $lines = [];
    preg_match_all("/<dt>(.+?)<\/dt>[\s\S]+?<ul>([\s\S]+?)<\/ul>/", $m[1], $m, PREG_SET_ORDER);
    foreach($m as $one){
        $lineName = $one[1];
        $directions = [];
        preg_match_all("/<a href=\"\/station\/time\/[0-9]+\/\?gid=([0-9]+)\">(.+?)<\/a>/", $one[2], $m_, PREG_SET_ORDER);
        foreach($m_ as $direction){
            $directionCode = $direction[1];
            $directionName = $direction[2];

            $directions[$directionCode] = $directionName;
        }
        $lines[$lineName] = $directions;
    }
    // Select Line and Direction
    $line_inputlist = [];
    $i = 0;
    foreach($lines as $Name => $directions){
        echo $i . ": " . $Name . "\n";
        $line_inputlist[$i] = ["directions" => $directions, "Name" => $Name];
        $i++;
    }
    echo "Select Line id: ";
    $select_line_id = trim(fgets(STDIN));
    if($select_line_id == "" || !isset($line_inputlist[$select_line_id])){
        echo "failed.\n";
        exit;
    }

    $direction_inputlist = [];
    $i = 0;
    foreach($line_inputlist[$select_line_id]["directions"] as $Code => $Name){
        echo $i . ": " . $Name . "\n";
        $direction_inputlist[$i] = ["Code" => $Code, "Name" => $Name];
        $i++;
    }
    
    echo "Select Direction id: ";
    $select_direction_id = trim(fgets(STDIN));
    if($select_direction_id == "" || !isset($direction_inputlist[$select_direction_id])){
        echo "failed.\n";
        exit;
    }
    $lineCode = $direction_inputlist[$select_direction_id]["Code"];
    $directionName = $direction_inputlist[$select_direction_id]["Name"];
    echo "Selected Station: \"" . $stationName . " (" . $stationCode . ")\"\n";
    echo "Selected Line and Direction: \"" . $line_inputlist[$select_line_id]["Name"] . " (" . $directionName . ")\"\n";
}

$url = "https://transit.yahoo.co.jp/station/time/" . $stationCode . "/";
if(!empty($lineCode)){
    $url .= "?gid=" . $lineCode;
}
$html = file_get_contents($url);

$timetable = [];

preg_match_all("/<td class=\"hour\">([0-9]+)<\/td>([\s\S]+?)<\/td>/", $html, $m, PREG_SET_ORDER);
foreach($m as $hour_data){
    $hour = $hour_data[1];
    $timetable[$hour] = [];

    $data = $hour_data[2];
    preg_match_all("/<dt>([0-9]+).*<\/dt>/", $data, $m_);
    foreach($m_[1] as $minute){
        $timetable[$hour][] = $minute;
    }
}
file_put_contents(
    __DIR__ . "/timetable.json",
    json_enCode($timetable)
);
echo "finished.\n";