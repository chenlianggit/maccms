<?php 
error_reporting(0);
header("Content-Type: application/octet-stream;charset=utf-8");
$get = $_GET;
if (strpos($get['skuid'],$get['skuid'])!== false) {
if(strstr($skuid,'qiyi.com')){
	$get = $_GET;
    $url = "//".$get['skuid'];
    unset($get['skuid']);
    $url = $url.'&'.merge_string($get);
} else {
	$url=convert_uudecode(base64url_decode($skuid));
}
header('Location:'.$url);
$url=null;
exit();
}
elseif (strpos($get['sign'],$get['sign'])!== false) {
if(strstr($get['sign'],'aHR')){
	$url=base64_decode($get['sign']);
}else if(strstr($get['sign'],'ZGF')){
	$url="//".base64_decode($get['sign']);
}
header('Location:'.$url);
$url=null;
exit();
}
//自定义64加密
function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '_-'), '='); 
}

//自定义64解密
function base64url_decode($data) { 
  return base64_decode(str_pad(strtr($data, '_-', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

function merge_string($a) {
    if (!is_array($a) && !is_object($a)) {
        return (string) $a;
    }
    return http_build_query(to_array($a));
}

function to_array($a) {
    $a = (array) $a;
    foreach ($a as &$v) {
        if (is_array($v) || is_object($v)) {
            $v = to_array($v);
        }
    }
    return $a;
}
?>