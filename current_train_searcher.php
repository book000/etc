<?php
/*
    時刻表から乗れる電車とその前後の電車を出力する current_train_searcher.php
    事前にtimetable_to_json.phpで時刻表からtimetable.jsonを生成。
*/
date_default_timezone_set("Asia/Tokyo");
if(!file_exists(__DIR__ . "/timetable.json")){
    echo "Please run timetable_to_json.php\n";
    exit;
}
function get_current_train(){
    $timetable = file_get_contents(__DIR__ . "/timetable.json");
    $timetable = json_decode($timetable, true);
    $hour = (int)date("H");
    $minute = (int)date("i");
    $canRideMinute = null;
    $canRideMinute_key = null;
    if(isset($timetable[$hour])){
        foreach($timetable[$hour] as $key => $one){
            if($minute <= $one){
                $canRideMinute = $one;
                $canRideMinute_key = $key;
                break;
            }
        }
    }
    while($canRideMinute == null){
        $hour = $hour + 1;
        if($hour > 23){
            $hour = 0;
        }
        if(isset($timetable[$hour][0])){
            $canRideMinute = $timetable[$hour][0];
            $canRideMinute_key = 0;
        }
    }
    $response = [];

    $response["canRideTime"] = str_pad($hour, 2, 0, STR_PAD_LEFT) . ":" . str_pad($canRideMinute, 2, 0, STR_PAD_LEFT);
    $response["otherTime"] = [];
    // 前
    if($canRideMinute_key >= 1){
        $response["otherTime"][0] = str_pad($hour, 2, 0, STR_PAD_LEFT) . ":" . str_pad($timetable[$hour][$canRideMinute_key - 1], 2, 0, STR_PAD_LEFT);
    }else{
        $_hour = $hour - 1;
        while(!isset($response["otherTime"][0])){
            if(isset($timetable[$_hour]) && count($timetable[$_hour]) != 0){
                $response["otherTime"][0] = str_pad($_hour, 2, 0, STR_PAD_LEFT) . ":" . str_pad($timetable[$_hour][count($timetable[$_hour]) - 1], 2, 0, STR_PAD_LEFT);
                break;
            }else{
                $_hour -= 1;
                if($_hour < 0){
                    $_hour = 23;
                }
            }
        }
    }

    // 後
    if(isset($timetable[$hour][$canRideMinute_key + 1])){
        $response["otherTime"][1] = str_pad($hour, 2, 0, STR_PAD_LEFT) . ":" . str_pad($timetable[$hour][$canRideMinute_key + 1], 2, 0, STR_PAD_LEFT);
    }else{
        $_hour = $hour + 1;
        while(!isset($response["otherTime"][1])){
            if(isset($timetable[$_hour]) && count($timetable[$_hour]) != 0){
                $response["otherTime"][1] = str_pad($_hour, 2, 0, STR_PAD_LEFT) . ":" . str_pad($timetable[$_hour][0], 2, 0, STR_PAD_LEFT);
                break;
            }else{
                $_hour += 1;
                if($_hour > 23){
                    $_hour = 0;
                }
            }
        }
    }
    return $response;
}
$res = get_current_train();
echo "Before: " . $res["otherTime"][0] . "\n"; // 一つ前の電車
echo "Next: " . $canRideTime = $res["canRideTime"] . "\n"; // 次に乗れる電車
echo "After: " . $otherTime_after = $res["otherTime"][1] . "\n"; // 次の次の電車