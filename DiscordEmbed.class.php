<?php
/**
 * Discord Embed Class
 */
class DiscordEmbed {
    private $title; // title of embed(string)
    private $type; // type of embed(string)
    private $description; // description of embed(string)
    private $url; // url of embed(string)
    private $timestamp; // timestamp of embed content(ISO8601)
    private $color; // color code of the embed(integer)
    private $footer; // footer infomation(footer object)
    private $image; // image infomation(image object)
    private $thumbnail; // thumbnail infomation(thumbnail object)
    private $video; // video infomation(video object)
    private $provider; // provider infomation(provider object)
    private $author; // author infomation(author object)
    private $fields; // fields infomation(fields object)

    function __construct(){
        // Default Setting
        $this->title = "";
        $this->type = "rich";
        $this->description = ""; 
        $this->url = "https://jaoafa.com/"; 
        $this->timestamp = date(DATE_ISO8601);
        $this->color = 65280;
        $this->footer = null;
        $this->image = null;
        $this->thumbnail = null;
        $this->video = null;
        $this->provider = null;
        $this->author = null;
        $this->fields = array();
    }

    function getTitle(){
        return $this->title;
    }

    function setTitle($title){
        $this->title = $title;
    }

    function getType(){
        return $this->type;
    }

    function setType($type){
        $this->type = $type;
    }

    function getDescription(){
        return $this->description;
    }

    function setDescription($description){
        $this->description = $description;
    }

    function getUrl(){
        return $this->url;
    }

    function setUrl($url){
        $this->url = $url;
    }

    function getTimestamp(){
        return $this->timestamp;
    }

    function setTimestamp($timestamp){
        $this->timestamp = $timestamp;
    }

    function setTimestamp_Date($date){
        if(is_int($date)){
            // unixtime
            $date = date(DATE_ISO8601, $date);
        }else{
            // other
            $date = strtotime($date);
            $date = date(DATE_ISO8601, $date);
        }
        setTimestamp($date);
    }

    function getColor(){
        return $this->color;
    }

    function setColor($color){
        $this->color = $color;
    }

    function getFooter(){
        return $this->footer;
    }

    function setFooter($text, $icon_url, $proxy_icon_url){
        $footer = array(
            "text" => $text,
            "icon_url" => $icon_url,
            "proxy_icon_url" => $proxy_icon_url,
        );
        $this->footer = $footer;
    }

    function getImage(){
        return $this->image;
    }

    function setImage($url, $proxy_url, $height, $width){
        $image = array(
            "url" => $url,
            "proxy_url" => $proxy_url,
            "height" => $height,
            "width" => $width,
        );
        $this->image = $image;
    }

    function getThumbnail(){
        return $this->thumbnail;
    }

    function setThumbnail($url, $proxy_url, $height, $width){
        $thumbnail = array(
            "url" => $url,
            "proxy_url" => $proxy_url,
            "height" => $height,
            "width" => $width,
        );
        $this->thumbnail = $thumbnail;
    }

    function getVideo(){
        return $this->video;
    }
    
    function setVideo($url, $height, $width){
        $video = array(
            "url" => $url,
            "height" => $height,
            "width" => $width,
        );
        $this->video = $video;
    }

    function getProvider(){
        return $this->provider;
    }

    function setProvider($name, $url){
        $provider = array(
            "name" => $name,
            "url" => $url,
        );
        $this->provider = $provider;
    }

    function getAuthor(){
        return $this->author;
    }

    function setAuthor($name, $url, $icon_url, $proxy_icon_url){
        $author = array(
            "name" => $name,
            "url" => $url,
            "icon_url" => $icon_url,
            "proxy_icon_url" => $proxy_icon_url,
        );
        $this->author = $author;
    }

    function getFields(){
        return $this->fields;
    }

    function addFields($name, $value, $inline){
        $field = array(
            "name" => $name,
            "value" => $value,
            "inline" => $inline,
        );
        $this->fields[] = $field;
    }

    function Export(){
        $export = array(
            "title" => $this->title,
            "type" => $this->type,
            "description" => $this->description,
            "url" => $this->url,
            "timestamp" => $this->timestamp,
            "color" => $this->color
        );
        if(!is_null($this->footer)){
            $export["footer"] = $this->footer;
        }
        if(!is_null($this->image)){
            $export["image"] = $this->image;
        }
        if(!is_null($this->thumbnail)){
            $export["thumbnail"] = $this->thumbnail;
        }
        if(!is_null($this->video)){
            $export["video"] = $this->video;
        }
        if(!is_null($this->provider) ){
            $export["provider"] = $this->provider;
        }
        if(!is_null($this->author)){
            $export["author"] = $this->author;
        }
        return $export;
    }
}
