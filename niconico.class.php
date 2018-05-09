class niconico {
    private $user_session;
    function __construct($mail, $password){
        $url = "https://secure.nicovideo.jp/secure/login?site=niconico"; // ログインURL
        $data = array(
            "next_url" => "",
            "mail" => $mail,
            "password" => $password,
            "submit" => ""
        );
        $data = http_build_query($data);
        $option = array(
            "http" => array(
                "method" => "POST",
                "content" => $data
            )
        );
        $context = stream_context_create($option);
        $fp = @fopen($url, "rb", false, $context);
        $response = @stream_get_meta_data($fp);

        preg_match("(user_session=[a-z0-9_]+)",$response["wrapper_data"][8], $a);
        if(count($a) == 0){
            throw new Exception("ログインに失敗しました。");
        }
        $this->user_session = $a[0];

    }
    function getContext($type, $ContentType = "text/xml"){
        return stream_context_create(
            array(
                "http"=> array(
                    "method" => strtoupper($type),
                    "header" => "Content-type: " . $ContentType . "\r\nCookie:".$this->user_session.";"
                )
            )
        );
    }
    function getString($url){
        $res = file_get_contents(
            $url,
            false,
            $this->getContext("get")
        );
        return $res;
    }
    function getXML($url){
        $res = $this->getString($url);
        $xml = simplexml_load_string($res);
        return $xml;
    }
    function getJSON($url){
        $res = $this->getString($url);
        $json = json_decode($res, true);
        return $json;
    }
    function getJSONFromXML($url){
        $res = $this->getString($url);
        $xml = simplexml_load_string($res);
        $json = json_encode($xml);
        $json = json_decode($json, true);
        return $json;
    }

    function postString($url){
        $res = file_get_contents(
            $url,
            false,
            $this->getContext("post")
        );
        return $res;
    }
}
