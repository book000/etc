<?php
// 自動でGoogleFormを回答する。悪用厳禁。

// フォームID
// https://docs.google.com/forms/d/e/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/viewform のxxx...部分を入力
$form_id = "";

// 解答を入力。ページがまたがっていても関係なし。
// ソースコードを検索し<input type="hidden" name="entry.0000000001" jsname="xxxxxxxx" disabled>を探す。
$answer = [
    "entry.0000000001" => "1",
    "entry.0000000002" => "2",
];

// -------------------------------------------------------------- //

$url = "https://docs.google.com/forms/d/e/" . $form_id . "/formResponse";
$query = http_build_query($answer, '', '&');

$url = $url . "?" . $query;

file_get_contents($url);
