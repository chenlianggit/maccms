<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $get = I('');
        $get['query'] = parse_string(str_replace("&amp;", "&", $get['query']));
        if($get['query'] == false){
            exit('404');
        }
        $get['purl'] = $get['scheme']."://".$get['host'].$get['path']."?".merge_string($get['query']);
        $cacid = md5($get['path']);
        $get['query']['t2'] = ($get['query']['t2'] <= 0)? 300 : $get['query']['t2'];
        if($get['query']['decache'] == 1) S($cacid, null);
        $str_m3u8 = S($cacid);
        if(!$str_m3u8){
            $str_m3u8 = curl($get['purl']);
            if(substr($str_m3u8,0,7) != '#EXTM3U') exit('404');
            S($cacid, $str_m3u8, $get['query']['t2']);
        }
        header('Content-Type: application/vnd.apple.mpegurl');
        header('Content-disposition: attachment; filename=video.m3u8');
        if(strstr($str_m3u8,'data.vod.itc.cn')){
            $str_m3u8 = str_replace("http://", "app.php?a=url&path=", $str_m3u8);
        }
        echo $str_m3u8;
    }

    public function url(){
        $get = I('get.');
        $url = "http://".$get['path'];
        unset($get['path']);
        $location = $url.'&'.merge_string($get);
        if(strstr($location,'data.vod.itc.cn')){
            $suid = play_verify();
            $location = str_replace(strzhong($location,"&uid=","&"), $suid[0], $location);
            $location = str_replace(strzhong($location,"&SOHUSVP=","&"), $suid[1], $location);
        }
        header('HTTP/1.1 301 Moved Permanently');
        Header("Location: {$location}"); 
    }
}