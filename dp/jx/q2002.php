<?php
function http_curl($url){
	$curl = curl_init();
    $cookie='bdshare_firstime=1532701255959; Hm_lvt_cfa4cd501115045a07b25ebf27550ea5=1533043111; Hm_lvt_3444244853ad9dcad28f4f6965e07942=1533043111; Hm_lpvt_3444244853ad9dcad28f4f6965e07942=1533044127; Hm_lpvt_cfa4cd501115045a07b25ebf27550ea5=1533044127';
    $header[] = "";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,30);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
    curl_setopt($curl, CURLOPT_COOKIE, $cookie); //使用上面获取的cookies
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    $response=curl_exec($curl);
    curl_close($curl);
    return $response;
}

$url    = $_GET['url'];
$url    = str_replace("=2002","",$url);  //去掉第*集

$s2             = http_curl("http://api.tianxianle.com/jx/dapi.php?id=".$url);
$json_api222    = explode('scrolling="no" src="',$s2);
# 美拍模式
if(preg_match("/flashvars/",$json_api222[0])){
    $json_apitype3  = explode('flashvars={f:\'',$s2);
    $mp4            = explode('\'',$json_apitype3[1]);
    if(empty($mp4[0])){
        echo "视频已失效，我会尽快修复";
    }
    header("Location:".$mp4[0]);exit();
}


$mp45           = explode('"',$json_api222[1]);

# 平民播放器
if(isset($json_api222[1]) && preg_match("/^https:\/\/apis.tianxianle.com\/youku/",$json_api222[1]) ){
    echo "<iframe border=\"0\" frameborder=\"0\" height=\"560px\"  marginheight=\"0\" marginwidth=\"0\" scrolling=\"no\" src=\"{$mp45[0]}\" width=\"100%\"></iframe>";
    exit;
}


$s3             = file_get_contents($mp45[0]);
$json_api222    = explode('vid="',$s3);
$mp4            = explode('"',$json_api222[1]);

if (empty($mp4[0])){
    $s2         = http_curl("https://apis.tianxianle.com/dapi.php?id=".$url);
    $json_api222= explode("url: '",$s2);
    $mp4        = explode("'",$json_api222[1]);
}

header("Location:".$mp4[0]);

?>