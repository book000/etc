<?php
/* コマンドブロックひとつで複数のコマンドを連続して実行するコマンドを生成するプログラム
   list変数に「$」区切りで実行したいコマンドを入力してください。
   よくわからないけどおすすめは50コマンド以内。100とかするとなんか止まってしまう。
   ソースコードの汚さはいつも通り。
   作者:mine_book000
*/
header("Content-Type: text/html; charset=UTF-8");
$list = "/say 0$/say 1$/say 2$/say 3$/say 4$/say 5$/say 6$/say 7$/say 8";
$commands = explode("$", $list);
$arr = array();
$c = 1;
$fill = 0;
krsort($commands);
reset($commands);
foreach($commands as $command){
    re:
    if($c < 2){
        // command_block(cb)
        if($command == ""){continue;}
        $arr[] = "cb:".$command;
        $c++;
        $fill++;
    }else{
        // redstone_block(rb)
        $arr[] = "rb:";
        $c = 0;
        $fill++;
        goto re;
 
   }
}
$fill++;
$fill++;
if($c == 1){
    $fill++;
}else if($c == 2){
    $fill++;
}
if($c == 0){
    $text = "/summon FallingSand ~2 ~0 ~0 {Time:127b,Block:minecraft:command_block,Data:0b,TileEntityData:{TrackOutput:1b,Command:\"\"},";
    $textend = "}";
}else if($c == 1){
    $text = "/summon FallingSand ~2 ~0 ~0 {Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{TrackOutput:1b},";
    $textend = "}";
}else if($c == 2){
    $text = "/summon FallingSand ~2 ~0 ~0 {Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{TrackOutput:1b},";
    $textend = "}";
}
    //$text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{TrackOutput:1b},";
//$textend .= "}";
    $text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:command_block,Data:0b,TileEntityData:{TrackOutput:1b,Command:\"/fill ~ ~2 ~ ~ ~-".$fill." ~ air\"},";
    $textend .= "}";

foreach ($arr as $data) {
    $data = explode(":", $data);
    if($data[0] == "cb"){
        $text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:command_block,Data:0b,TileEntityData:{TrackOutput:1b,Command:\"".$data[1]."\"},";
    }else if($data[0] == "rb"){
        $text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{},";
    }
    $textend .= "}";
}
$text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{TrackOutput:1b},";
$textend .= "}";
$text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:command_block,Data:0b,TileEntityData:{TrackOutput:1b,Command:\"\"},";
$textend .= "}";
if($data[0] == "cb"){
    $text .= "Riding:{id:FallingSand,Time:127b,Block:minecraft:redstone_block,Data:0b,TileEntityData:{TrackOutput:1b},";
    $textend .= "}";
}

$text = substr($text, 0, -1);
$all = $text.$textend;
echo $all;
