<?php
/*
log_format main '$request_time $sent_http_x_f_cache $sent_http_x_b_cache '
                 '$remote_addr - $remote_user [$time_local] "$request" '
                 '$status $body_bytes_sent "$http_referer" '                                     '"$http_user_agent" "$http_x_forwarded_for"';
*/
// 0.000 - - 00.000.00.000 - - [01/Jan/2019:00:00:00 +0900] "GET / HTTP/1.1" 200 100 "-" "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0" "-"
preg_match("/([0-9.]+) (.+) (.+) (.+) - (.+) \[(.+)\] \"(.+)\" ([0-9]+) ([0-9]+) \"(.+)\" \"(.+)\" \"(.+)\"/", $line, $m);

$request_time = $m[1];
$sent_http_x_f_cache = $m[2];
$sent_http_x_b_cache = $m[3];
$host = $m[4];
$remote_user = $m[5];
$time = $m[6];
$req = $m[7];
$status = $m[8];
$bytes = $m[9];
$http_referer = $m[10];
$user_agent = $m[11];
$http_forwarded = $m[12];

/* --------------------------------------------------------------------------------------------------- */

/*
log_format ltsv "time:$time_local"
					 "\thost:$remote_addr"
					 "\tforwardedfor:$http_x_forwarded_for"
					 "\treq:$request"
					 "\tstatus:$status"
					 "\tsize:$body_bytes_sent"
					 "\treferer:$http_referer"
					 "\tua:$http_user_agent"
					 "\treqtime:$request_time"
					 "\tcache:$upstream_http_x_cache"
					 "\truntime:$upstream_http_x_runtime"
					 "\tvhost:$host";
*/
// 0.000 - - 00.000.00.000 - - [01/Jan/2019:00:00:00 +0900] "GET / HTTP/1.1" 404 548 "-" "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0" "-"

$line = explode("\t", $line);
$array = [];
foreach($line as $one){
    $key = substr($one, 0, strpos($one, ":"));
    $value = substr($one, strpos($one, ":") + 1);
    $$key = $value;
}
