<?php
//文件名称
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// 网站根目录
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
//加载配置文件
require_once FCPATH.'config.php';

$get = $_GET;
if($get['a'] == 'setswf'){
    header("Content-Type: text/xml");
    $get = parse_string(base64_decode($get['data']));
    if($get['site'] == 'acfun'){
        $hds = array(1=>1,2=>2,3=>3);
    } else {
        $hds = array(1=>'mp4hd',2=>'mp4hd2',3=>'mp4hd3');
    }
    $hdb = array('mp4hd3' => 3,'mp4hd2' => 2,'mp4hd' => 1);
    foreach ($hds as $key => $value) {
        $defa[$key] = YOU_URL."xml.php?a=setswf&data=".base64_encode("ccode=".$get['ccode']."&vid=".$get['vid']."&site=".$get['site']."&playtype=".$get['playtype']."&sign=".$get['sign']."&stype=".$value."&weparser_swf_url=".$get['weparser_swf_url']);
    }
    $xml='<ckplayer><flashvars><![CDATA[{s->3}{h->3}{f->'.$get['weparser_swf_url'].'}{a->'.$defa[$hdb[$get['stype']]].'}{defa->'.implode('|',$defa).'}';
    $xml.='{deft->标清|高清|超清}{site->'.$get['site'].'}{playtype->'.$get['playtype'].'}{sign->'.$get['sign'].'}{vid->'.$get['vid'].'}{stype->'.$get['stype'].'}{ccode->'.$get['ccode'].'}';
    $xml.=']]></flashvars>';
    $xml.='<videos><file><![CDATA[]]></file></videos>';
    $xml.='</ckplayer>';
    $xml='<?xml version="1.0" encoding="utf-8"?>'.$xml;
    exit($xml);
} elseif($get['a'] == 'setxml'){
    header("Content-Type: text/xml");
    $xml  = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<ckplayer>';
    $xml .= '<flashvars>{f-><![CDATA['.base64_decode($get['url']).']]>}</flashvars>';
    $xml .= '<video>';
    $xml .= '<file><![CDATA['.base64_decode($get['url']).']]></file>';
    $xml .= '<size><![CDATA[0]]></size>';
    $xml .= '<seconds><![CDATA[0]]></seconds>';
    $xml .= '</video>';
    $xml .= '</ckplayer>';
    exit($xml);

} else if(substr($get['a'],0,4) == 'm3u8'){
	header('Content-type: application/vnd.apple.mpegurl; charset=UTF-8');
	header('Content-disposition: attachment; filename=playm3u8.m3u8');
	$get = parse_string(base64_decode(substr($get['a'],4,strlen($get['a']))));
	$m3u8 = geturl($get['url']);
    preg_match("|http://(.*?)\/|", $m3u8, $host);
	if (strpos($m3u8,'dx.data.video.iqiyi.com')!== false) {
    $m3u8 = str_replace('http://'.$host[1],'//dx.data.video.iqiyi.com',$m3u8);
	}else{
    $m3u8 = str_replace('http://'.$host[1],'//data.video.iqiyi.com',$m3u8);
	}
    exit($m3u8);
} else if(substr($get['a'],0,7) == 'qy_m3u8'){
	header('Content-type: application/vnd.apple.mpegurl; charset=UTF-8');
	header('Content-disposition: attachment; filename=playm3u8.m3u8');
	$get = parse_string(base64_decode(substr($get['a'],7,strlen($get['a']))));
	$m3u8 = geturl($get['url']);
   preg_match_all("|http://(.*?)\n|", $m3u8, $host);
	if (strpos($m3u8,'dx.data.video.iqiyi.com')!== false) {
	foreach ($host[1] as $data) {
	$m3u8 = str_replace('http://'.$data,'ts.php?sign='.base64_encode($data),$m3u8);
	}}else{
   foreach ($host[1] as $data) {
    $m3u8 = str_replace('http://'.$data,'ts.php?sign='.base64_encode($data),$m3u8);
	}}
    exit($m3u8);
}

function parse_string($s) {
    if (is_array($s)) {
        return $s;
    }
    parse_str($s, $r);
    return $r;
}

function p($arr){
	echo "<pre>";
	print_r($arr);die;
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
function uuid($prefix = '',$f = '') {
   $chars = md5(uniqid(mt_rand(), true));
   $uuid  = substr($chars,0,8) . $f;
   $uuid .= substr($chars,8,4) . $f;
   $uuid .= substr($chars,12,4) . $f;
   $uuid .= substr($chars,16,4) . $f;
   $uuid .= substr($chars,20,12);
   return $prefix . $uuid;
}
?>