<?php
//错误提示
error_reporting(0);
//默认时区
date_default_timezone_set("Asia/Shanghai");
//强制编码
header('Content-type:text/html;charset=utf-8');
//加载文件
require_once FCPATH .'xfuser.php';
//用户授权UID
define('USER_ID', $user['uid']);
//用户授权TOKEN
define('USER_TOKEN', $user['token']);
//用户设置解析名称
define('USER_TITLE', $user['title']);
//用户设置的清晰度
define('VOD_HD', $user['hdd']);
//用户设置页面统计
define('USER_TONGJI', $user['tongji']);
//用户设置广告
define('USER_AD', $user['ad']);
//手机端加载视频等待图片
define('USER_LOADING', $user['loading']);
//用户设置的百度网盘COOKIE
define('USER_BDYUN', base64_encode($user['bdyun']));
//用户设置的天翼网盘COOKIE
define('USER_TYYUN', base64_encode($user['tyyun']));
require_once FCPATH . 'url.php';
//当前时间，不能修改
define('TIMES', time());
//当前解析版本，请勿随意修改，否则会造成解析失败
define('VERSION', 'v1.7.1.20180803');
define('Ver', base64_encode(VERSION));
//当前插件目录，无需修改
define('YOU_URL', web_path());
function web_path(){
    $uri = 'http://xfsub'.$_SERVER['REQUEST_URI'];
    $arr = parse_url($uri);
    return str_replace(SELF,'',$arr['path']);
}
//生成加密KEY
$key = md5(USER_ID.YOU_URL.REFERER_URL);
//判断手机客户端
$wap = preg_match("/(iPhone|iPad|iPod|Linux|Android)/i", strtoupper($_SERVER['HTTP_USER_AGENT']));
//判断防盗链域名
function is_referer(){
    //没有设置防盗链
    if(REFERER_URL=='') return true; 
    //获取来路域名
    $uriarr = parse_url($_SERVER['HTTP_REFERER']);
    $host = $uriarr['host'];
    $ymarr = explode("|",REFERER_URL);
    if(in_array($host,$ymarr)){
        return true;
    }
    return false;
}
//获取远程内容
function geturl($url) {
    $headers1 = array(
        'referer' => $_POST['referer'],
        'Client-IP' => (empty($_SERVER['HTTP_CLIENT_IP'])? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CLIENT_IP']),
        'X-Forwarded-For' => (empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR']), 
    );
    $url = $url.'&lref='.rawurlencode($_SERVER['HTTP_REFERER'])."&headers1=".rawurlencode(merge_string($headers1))."&ver=".Ver."&User-Agent=".base64_encode(base64_encode($_SERVER['HTTP_USER_AGENT']));
    // 判断是否支持CURL
    if (!function_exists('curl_init') || !function_exists('curl_exec')) {
        exit('您的主机不支持Curl，请开启~');
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Cloud Parse');
    curl_setopt($curl, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
//数组转XML
function xml($str,$param){
    global $hd;
    $param = str_replace('&','&amp;',$param);
    $USER_TITLE = USER_TITLE?USER_TITLE:'Q2017.com解析';
    $xml='<ckplayer><!-- '.$USER_TITLE.' --><flashvars>{lv->0}{v->80}{e->0}{p->1}{q->start}{h->3}{f->'.YOU_URL.'api.php?'.$param.'&amp;[$pat]}{a->hd='.$hd.'}{defa->hd=1|hd=2|hd=3|hd=4}{deft->标清|高清|超清|原画}</flashvars>
    <video>';
    $arr = $str['url'];
    if(is_array($arr)){
             for($i=0;$i<count($arr);$i++){
                 $xml.='<file><![CDATA['.$arr[$i]['purl'].']]></file>';
                 if(isset($arr[$i]['size'])) $xml.='<size>'.$arr[$i]['size'].'</size>';
                 if(isset($arr[$i]['sec'])) $xml.='<seconds>'.$arr[$i]['sec'].'</seconds>';     
             }
    }else{
             $xml.='<file><![CDATA['.$str['url'].']]></file>';
             if(isset($str['size'])) $xml.='<size>'.$str['size'].'</size>';
             if(isset($str['sec'])) $xml.='<seconds>'.$str['sec'].'</seconds>';  
    }
    $xml.='</video></ckplayer>';
    return $xml;
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