<?php
class MCBansLookup
{
    private $apikey = "APIKEY";
    private $player;
    private $uuid;
    private $total;
    private $reputation;
    private $local;
    private $global;
    private $other;
    private $pid;

    function __construct($player_or_uuid, $isUUID = true){
        $ch = curl_init();

        $data = [
            "exec" => "playerLookup"
        ];
        if($isUUID){
            $data["player_uuid"] = $player_or_uuid;
        }else{
            $data["player"] = $player_or_uuid;
        }
        $data = http_build_query($data, "", "&");

        curl_setopt($ch, CURLOPT_URL, 'http://api.mcbans.com/v3/' . $this->apikey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception("Curl error: " . curl_error($ch));
        }

        $json = json_decode($result, true);
        if(json_last_error() != 0){
            throw new Exception("json error: " . json_last_error_msg());
        }

        $this->player = $json["player"];
        $this->uuid = $json["uuid"];
        $this->total = $json["total"];
        $this->reputation = $json["reputation"];
        $this->local = $json["local"];
        $this->global = $json["global"];
        $this->other = $json["other"];
        $this->pid = $json["pid"];

        curl_close($ch);
    }

    function getPlayer(){
        return $this->player;
    }

    function getUUID(){
        return $this->uuid;
    }

    function getTotal(){
        return $this->total;
    }

    function getReputation(){
        return $this->reputation;
    }

    function getLocal(){
        return $this->local;
    }

    function getGlobal(){
        return $this->global;
    }

    function getOther(){
        return $this->other;
    }

    function getPid(){
        return $this->pid;
    }
}