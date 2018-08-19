<?php

function p($arr){
    echo "<pre>";
    print_r($arr);die;
}

function curl($url,$cookie=null,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'PlayM3u8/Ver17.11.16 (Player)');
    curl_setopt( $ch, CURLOPT_TIMEOUT , 5);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    $headers = array(
        'Accept-Encoding:gzip',
        'Client-IP: '.(empty($_SERVER['HTTP_CLIENT_IP'])? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CLIENT_IP']),
        'X-Forwarded-For: '.(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR']), 
    );
    curl_setopt($ch, CURLOPT_ENCODING ,'gzip');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if (substr($url, 0, 5) == 'https'); {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if(!empty($cookie)) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if( $ispost ) {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    } else{
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

function parse_string($s) {
    if (is_array($s)) {
        return $s;
    }
    parse_str($s, $r);
    return $r;
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


function Curls($url,$cookie="",$data="",$ref=""){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $host = GetIP_();
    $header = array(
        "X-FORWARDED-FOR: ".$host,
        "CLIENT-IP: ".$host
    );
    if($ref != '')
        $header[] = 'Referer: '.$ref;
    if($cookie != '')
        $header[] = 'Cookie: '.$cookie;
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.87 Safari/537.36 QQBrowser/9.2.5584.400';
    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    if($data != ''){
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    curl_setopt($ch,CURLOPT_TIMEOUT,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

function play_verify(){
    $svp  = "gw3xTd45_pWtOwyhblItcO4qyiztT8ldX5NQwxpQsHs";
    $data = S($svp);
    if($data == false){
        $html = Curls('https://pv.sohu.com/suv/?t?='.time().'501084_400_700?r?=https://tv.sohu.com/20171122/n600265964.shtml');
        $uid  = strzhong($html, 'SUV=', ';');
        $cookie = "_muid_=1512003214650009; IPLOC=CN3302; SUV=".$uid."; SOHUSVP=".$svp."; MTV_SRC=11040001";
        Curls("https://z.m.tv.sohu.com/h5_cc.gif?t=".time()."000&uid=".$uid."&position=play_verify&op=click&details=%7B%7D&nid=&url=https%253A%252F%252Fwx.m.tv.sohu.com%252F20171122%252Fn600265964.shtml%253Flandingrefer%253Dhttps%25253A%25252F%25252Ftv.sohu.com%25252F&refer=https%3A%2F%2Ftv.sohu.com%2F&screen=400x700&os=android&platform=android&passport=&vid=4247535&pid=9411683&channeled=1211110001&MTV_SRC=11040001",$cookie);
        Curls("https://z.m.tv.sohu.com/h5_cc.gif?t=".time()."000&uid=".$uid."&position=play_verify&op=click&details=%7B%7D&nid=&url=https%253A%252F%252Fwx.m.tv.sohu.com%252F20171122%252Fn600265964.shtml%253Flandingrefer%253Dhttps%25253A%25252F%25252Ftv.sohu.com%25252F&refer=https%3A%2F%2Ftv.sohu.com%2F&screen=400x700&os=android&platform=android&passport=&vid=4247535&pid=9411683&channeled=1211110001&MTV_SRC=11040001",$cookie);
        $data = array($uid,$svp);
        S($svp , $data, 2);
    }
    return $data;
}

function GetIP_() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP") , "unknown")) $ip = getenv("HTTP_CLIENT_IP");
    elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR") , "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
    elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR") , "unknown")) $ip = getenv("REMOTE_ADDR");
    elseif (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) $ip = $_SERVER["REMOTE_ADDR"];
    else $ip = "unknown";
    return ($ip);
}


function strzhong($str, $leftStr, $rightStr){
    if (!empty($str)) {
        $left = strpos($str, $leftStr);
        if ($left === false) {
            return '';
        }
        $right = strpos($str, $rightStr, $left + strlen($leftStr));
        if ($left === false or $right === false) {
            return '';
        }
        return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
    }
}
