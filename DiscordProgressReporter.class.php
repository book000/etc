<?php
class DiscordProgressReporter
{
    private $token = "TOKEN";
    private $channel; // Channel ID
    private $loggerFile = null;
    private $reportmsg = "";

    public function __construct($channel){
        $this->channel = $channel;
    }
    public function setLogger($file){
        $this->loggerFile = $file;
    }
    public function pp($str = "", $n = "\n", $date = true){
        if ($date) {
            $date = date("[Y/m/d H:i:s] ");
        } else {
            $date = "";
        }
        if((mb_strlen($this->reportmsg) + mb_strlen($date . $str . $n)) >= 1950){
            $this->send($this->reportmsg);
            $this->reportmsg = "";
        }
        cli_set_process_title($date . $str);
        echo $date . $str . $n;
        if($this->loggerFile != null){
            file_put_contents($this->loggerFile, $date . $str . PHP_EOL, FILE_APPEND);
        }
        $this->reportmsg .= $date . $str . $n;
    }
    public function send($str, $channel = null){
        if($channel == null){
            $channel = $this->channel;
        }
        $data = array(
            "content" => $str
        );
        $header = array(
            "Content-Type: application/json",
            "Content-Length: " . strlen(json_encode($data)),
            "Authorization: Bot " . $this->token,
            "User-Agent: DiscordBot (https://jaoafa.com, v0.0.1)"
        );

        $context = array(
            "http" => array(
                "method" => "POST",
                "header" => implode("\r\n", $header),
                "content" => json_encode($data),
                "ignore_errors" => true
            )
        );
        $context = stream_context_create($context);
        $contents = file_get_contents("https://discordapp.com/api/channels/" . $channel . "/messages", false, $context);
        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $status_code = $matches[1];
        if ($status_code != 200) {
            echo "\n\nDiscordSend Error: " . $status_code . " : " . $contents . "\n\n";
        }
        sleep(1);
    }
    public function __destruct(){
        if(mb_strlen($this->reportmsg) != 0){
            $this->send($this->reportmsg);
        }
    }
}
