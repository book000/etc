<?php
$items = file_get_contents("https://minecraft-ids.grahamedgecombe.com/items.json");
$items = json_decode($items, true);

$itemList = array();
$itemNameList = array();
foreach($items as $item){
    $id = $item["type"];
    $durability = $item["meta"];
    $name = $item["text_type"];
    $itemList[$id . ":" . $durability] = ["name" => $name, "id" => $id, "durability" => $durability];
    $itemNameList[$name] = ["name" => $name, "id" => $id, "durability" => $durability];
}


$material = file_get_contents(__DIR__ . "/Materials_1.12.2.txt");

$materials = array();
$materialNameList = array();

preg_match_all("/(.+)\(([0-9]+)\)\,/", $material, $m); // Material(int id)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $materials[$id . ":" . "0"] = ["name" => $name, "id" => $id];
    $materialNameList[$name] = ["name" => $name, "id" => $id];
    $material = str_replace($text, "", $material);
}

preg_match_all("/(.+)\(([0-9]+)\, [0-9]+\)\,/", $material, $m); // Material(int id, int stack)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $materials[$id . ":" . "0"] = ["name" => $name, "id" => $id];
    $materialNameList[$name] = ["name" => $name, "id" => $id];
    $material = str_replace($text, "", $material);
}

preg_match_all("/(.+)\(([0-9]+)\, [0-9]+\, ([0-9]+)\)\,/", $material, $m); // Material(int id, int stack, int durability)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $durability = $m[3][$key];
    $materials[$id . ":" . $durability] = ["name" => $name, "id" => $id, "durability" => $durability];
    $materialNameList[$name] = ["name" => $name, "id" => $id, "durability" => $durability];
    $material = str_replace($text, "", $material);
}

preg_match_all("/(.+)\(([0-9]+)\, [0-9]+\, [0-9]+\, .+\.class\)\,/", $material, $m); // Material(int id, int stack, int durability, Class<? extends MaterialData> data)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $durability = $m[3][$key];
    $materials[$id . ":" . $durability] = ["name" => $name, "id" => $id, "durability" => $durability];
    $materialNameList[$name] = ["name" => $name, "id" => $id, "durability" => $durability];
    $material = str_replace($text, "", $material);
}

preg_match_all("/(.+)\(([0-9]+)\, [0-9]+\, .+\.class\)\,/", $material, $m); // Material(int id, int stack, Class<? extends MaterialData> data)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $materials[$id . ":" . "0"] = ["name" => $name, "id" => $id];
    $materialNameList[$name] = ["name" => $name, "id" => $id];
    $material = str_replace($text, "", $material);
}

preg_match_all("/(.+)\(([0-9]+)\, .+\.class\)\,/", $material, $m); // Material(int id, Class<? extends MaterialData> data)
foreach($m[2] as $key => $id){
    $text = $m[0][$key];
    $name = $m[1][$key];
    $name = strtolower($name);
    $materials[$id . ":" . "0"] = ["name" => $name, "id" => $id];
    $materialNameList[$name] = ["name" => $name, "id" => $id];
    $material = str_replace($text, "", $material);
}

foreach($materials as $key => $arr){
    $name = $arr["name"];
    if(isset($itemList[$key])){
        //echo $name . "(" . $key . ") : FOUND\n";
    }else{
        //echo $name . "(" . $key . ") : NOTFOUND\n";
    }
}

//echo "--------------------------------------------------------------\n";

$b = array();

foreach($materialNameList as $key => $arr){
    $name = $arr["name"];
    $id = $arr["id"];
    if(isset($arr["durability"])){
        $durability = $arr["durability"];
    }else{
        $durability = 0;
    }
    if(isset($itemNameList[$key])){
        //echo $name . "(" . $key . ") : FOUND\n";
    }else{
        if(isset($itemList[$id . ":" . $durability])){
            $a = $itemList[$id . ":" . $durability];
            echo $name . "(" . $key . " | " . $id . ":" . $durability . ") : NOTFOUND";
            echo " -> " . $a["name"] . "\n";
            $b[] = "if(MaterialName.equalsIgnoreCase(\"" . $name . "\")) MaterialName = \"" . $a["name"] . "\";";
        }else if(isset($itemList[$id . ":0"])){
            $a = $itemList[$id . ":0"];
            echo " -> " . $a["name"] . "\n";
            $b[] = "if(MaterialName.equalsIgnoreCase(\"" . $name . "\")) MaterialName = \"" . $a["name"] . "\";";
        }else{
            echo $name . "(" . $key . " | " . $id . ":" . $durability . ") : NOTFOUND";
            echo "\n";
        }
    }
}
echo "--------------------------------------------------------------\n";
foreach($b as $one){
    echo $one . "\n";
}
