<?php
header("Content-Type: image/jpeg;text/html; charset=utf-8");
$url = 'http://'.$_GET['tu'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$FH= curl_exec($ch);
curl_close($ch);
echo $FH;
exit; 