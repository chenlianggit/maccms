<?php
//文件名称
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// 网站根目录
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
//加载配置文件
require_once FCPATH.'config.php';
//判断防盗链
if(!is_referer()){
	 header('HTTP/1.1 403 Forbidden');
     exit(ERROR);
}
//接收参数
$url = $_POST['url'];
$type = $_POST['type'];
$keys = $_POST['key'];
$time = $_POST['time'];
$xml = (int)$_GET['xml'];
$m3u8 = (int)$_GET['m3u8'];
$hd = (int)$_GET['hd'];
if(empty($url)) $url = $_GET['url'];
if(empty($type)) $type = $_GET['type'];
if(empty($keys)) $keys = $_GET['key'];
if(empty($time)) $time = $_GET['time'];
if($hd==0) $hd=VOD_HD;
$url = rawurlencode($url);
if($type == 'baidupan'){
	//组装清晰度切换URL
	$param_url='key='.md5(TIMES.$key).'&time='.TIMES.'&url='.$url.'&type='.$type.'&xml=1';
	//组装URL参数
	$param = 'token='.USER_TOKEN.'&url='.$url.'&type='.$type.'&hd='.$hd.'&wap='.$wap.'&bdyun='.USER_BDYUN;
}else if($type == 'tyyun'){
	//组装清晰度切换URL
	$param_url='key='.md5(TIMES.$key).'&time='.TIMES.'&url='.$url.'&type='.$type.'&xml=1';
	//组装URL参数
	$param = 'token='.USER_TOKEN.'&url='.$url.'&type='.$type.'&hd='.$hd.'&wap='.$wap.'&tyyun='.USER_TYYUN;
}else{
	//组装清晰度切换URL
	$param_url='key='.md5(TIMES.$key).'&time='.TIMES.'&url='.$url.'&type='.$type.'&xml=1';
	//组装URL参数
	if(in_array($type, array('youku','iqiyi','qq'))){
        $param = 'token='.USER_TOKEN.'&url='.$url.'&type='.$type.'&hd='.$hd.'&wap='.$wap.'&ext=ajax';
        if($type == 'iqiyi') $param = $param."&cupid=qc_100001_100102";
	} else {
		$param = 'token='.USER_TOKEN.'&url='.$url.'&type='.$type.'&hd='.$hd.'&wap='.$wap;
	}
}
$filemd5=FCPATH.'cache/'.md5($param.USER_ID).'.txt';
//下面为XML输出
if($xml==1){
	if(md5($time.$key)!=$keys) exit('Key错误，非法操作~');
	if(file_exists($filemd5)){
         $json = file_get_contents($filemd5);
		 $arr = json_decode($json,1);
		 echo xml($arr,$param_url);exit;
	}else{ //不存在

	     $json = geturl(API_URL.'?uid='.USER_ID.'&'.$param);
	     $arr = json_decode($json,1);
	     if($arr['success']==1){
			 //file_put_contents($filemd5,$json);
		 }else{
             exit($json);
		 }
		header("Content-type:text/xml");
		 echo xml($arr,$param_url);exit;
	}
}

//输出M3U8列表
if($m3u8==1){
	if(md5($time.$key)!=$keys) exit('Key错误，非法操作~');
	if(file_exists($filemd5)){
         $json = file_get_contents($filemd5);
	     $arr = json_decode($json,1);
		 $m3u8 = base64_decode($arr['url']);
	}else{ //不存在
	     $json = geturl(API_URL.'?uid='.USER_ID.'&'.$param);
	     $arr = json_decode($json,1);
		 $m3u8 = base64_decode($arr['url']);
		 if(strpos($m3u8,'#EXTM3U') === FALSE) exit('解析失败~');
         
		// file_put_contents($filemd5,$json);
	}
	header('Content-Type:vnd.apple.mpegurl;');
    header('Content-disposition: attachment; filename=yun.m3u8');
    echo $m3u8;exit;
}

//状态
$data['success'] = 0;
//判断参数
if(empty($url) || empty($time)){
    $data['m'] = 'URL地址不能为空！';
}elseif(md5($time.$key)!=$keys){
    $data['m'] = 'Key错误，非法操作~';
}else{
    $cache=0;
    if(file_exists($filemd5)){
         $json = file_get_contents($filemd5);
	     $arr = json_decode($json,1);
	     if($arr['ctime'] > time()) $cache++;
    }
    if($cache==0){
	     $json = geturl(API_URL.'?uid='.USER_ID.'&'.$param);
	     $arr = json_decode($json,1);
	     if($arr['success']==1){
			// file_put_contents($filemd5,$json);
		 }else{
             exit($json);
		 }
    }
    //状态
    $data['success'] = $arr['success'];
    $data['type'] = $type;
    $data['play'] = $arr['ext']=='mp4' ? 'xml' : $arr['ext'];
    $data['url'] = $arr['url'];
    $data['version'] = VERSION;
    // 更新或添加时间(2017.11.16)(行数：下1行)
    $data['url'] = (strstr($data['play'], 'm3u8') && strstr($data['url'], '/files/'))? YOU_URL."app.php?".merge_string(parse_url($data['url'])): $data['url'];$data['url'] = str_replace('&port=',':',$data['url']);
    if(!$wap){
		if($arr['ext']=='m3u8_list'){ //M3U8列表
            $data['url'] = rawurlencode(YOU_URL.'api.php?'.str_replace('&xml=1','&m3u8=1',$param_url));
			$data['play'] = 'm3u8';
		}elseif($arr['ext']=='m3u8'){ //M3U8
            // 更新或添加时间(2017.11.16)(行数：下4行)
		    $data['url'] = rawurlencode($data['url']);
        }elseif($arr['ext']=='hls_m3u8' || $arr['ext']=='hls'){ //M3U8
            $data['play'] = 'hls';
            $data['url']  = $data['url'];
        }elseif($data['play'] == 'xml'){ //PC XML
        	$data['url'] = YOU_URL.'api.php?'.$param_url;
		}
    }else{
        if($arr['ext']=='m3u8_list'){
            $data['url'] = YOU_URL.'api.php?'.str_replace('&xml=1','&m3u8=1',$param_url);
		}
	}
}
echo json_encode($data);