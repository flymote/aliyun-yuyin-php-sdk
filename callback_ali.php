<?php
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
$p = file_get_contents('php://input');
file_put_contents(__DIR__.'/GETdebug', $p."---".date("Y-m-d H:i:s")." \n",FILE_APPEND);
die("");