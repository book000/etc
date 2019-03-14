<?php
class SteamGame {
    private $appid = null;
    private $gameData = null;
    function __construct($appid){
        $this->appid = $appid;
        $context = stream_context_create(
            array(
                "http"=> array(
                    "method" => "GET",
                    "ignore_errors" => true
                )
            )
        );
        $json = file_get_contents("https://store.steampowered.com/api/appdetails?appids=" . $appid, false, $context);
        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $status_code = $matches[1];
        if($status_code != 200){
            throw new Exception("Failed to get SteamGameData. (" . $http_response_header[0] . ")");
        }
        $array = json_decode($json, true);
        $this->gameData = $array[$appid];
        if(!$this->gameData["success"]){
            throw new Exception("Failed to get SteamGameData.");
            $this->gameData = null;
        }
    }
    // ゲーム名
    function getName(){
        if($this->gameData == null){
            throw new Exception("Failed to get SteamGameData.");
        }
        return $this->gameData["data"]["name"];
    }
    // 定価
    function getInitialPrice(){
        if($this->gameData == null){
            throw new Exception("Failed to get SteamGameData.");
        }
        return $this->gameData["data"]["price_overview"]["initial"] / 100;
    }
    // 現在価格
    function getCurrencyPrice(){
        if($this->gameData == null){
            throw new Exception("Failed to get SteamGameData.");
        }
        return $this->gameData["data"]["price_overview"]["final"] / 100;
    }
    // JPY 価格単位
    function getCurrencyName(){
        if($this->gameData == null){
            throw new Exception("Failed to get SteamGameData.");
        }
        return $this->gameData["data"]["price_overview"]["currency"];
    }
    // 割引
    function getDiscountPercent(){
        if($this->gameData == null){
            throw new Exception("Failed to get SteamGameData.");
        }
        return $this->gameData["data"]["price_overview"]["discount_percent"];
    }
}

