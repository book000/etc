<?php
$X = $argv[1];
$Y = $argv[2];
sleep(1);
exec("adb shell input touchscreen tap " . $X . " " . $Y);
