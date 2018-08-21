<?php
/**
 * Created by PhpStorm.
 * User: chenliang
 * Date: 2018/8/22
 * Time: 上午1:05
 */

//require __DIR__.'/vendor/autoload.php';
class upload {

    public $pdo;
    public $cookie;

    function __construct()
    {
        $this->pdo      = new pdo('mysql:dbname=vip_95uk_com;host=127.0.0.1','vip_95uk_com','YTHXt65AFa',array(PDO::ATTR_PERSISTENT => true));
        $this->cookie   = "SINAGLOBAL=4659216704418.871.1520910520578; UM_distinctid=1626219463b3aa-01becee071bbf7-336c7b05-1aeaa0-1626219463c33d; login_sid_t=5a48602b23eee8df9a5ef9e72a1c0112; cross_origin_proto=SSL; YF-Ugrow-G0=ad83bc19c1269e709f753b172bddb094; YF-V5-G0=5468b83cd1a503b6427769425908497c; _s_tentry=-; Apache=7889803278028.195.1534868030236; wb_view_log=1680*10502; ULV=1534868031252:25:5:3:7889803278028.195.1534868030236:1534303319894; TC-Ugrow-G0=968b70b7bcdc28ac97c8130dd353b55e; WBtopGlobal_register_version=d9afa2e497e6a8df; SCF=Avhu3tu0EcQZwCk5O-NnDrt3QOllirFHhem-dnypkPuLmefDJ3UVJV1nLu7uxyHlVj6F3Sh3AUHiO5n3sMwfLOo.; SUB=_2A252eEuoDeRhGeBK61AU9CvOyTuIHXVVDDpgrDV8PUNbmtAKLXPEkW9NRBCwdgZzufTt_t8aGdkJ6ee9o2Zpu8nN; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WhzC8bYi5ydZbgX539Oh4D35JpX5K2hUgL.FoqXehzfSh-EeoM2dJLoIEBLxKnL1K5L12eLxK.LBKqL1K.LxKnL12qL1hMLxK.L1h-LBo2t; SUHB=0G0uO_qQj0btvN; ALF=1535473272; SSOLoginState=1534868472; un=13148376456; TC-Page-G0=1ac1bd7677fc7b61611a0c3a9b6aa0b4; TC-V5-G0=2bdac3b437dd23e235b79a3d6922ea06; wb_view_log_6402545227=1680*10502; YF-Page-G0=854ebb7f403eecfa60ed1f0e977c6825; UOR=,,51.ruyo.net; wvr=6";

    }

    public function updateImg($i){
        $result     = $this->pdo->query("select d_id,d_pic from mac_vod limit ".$i.",1;")->fetch();

        if(!$result['d_pic']){
            return '查询错误';
        }

        if(preg_match('/^tu/',$result['d_pic'])){
            $url = 'http://'.explode('tu=',$result['d_pic'])[1];
        }elseif( preg_match('/^http:\/\/wx4/',$result['d_pic']) ){
            return "无需更新";
        }else{
            $url = $result['d_pic'];
        }
        $img = $this->upload($url);

        if(!$img){
            return "失败";
        }
        $sql    = "UPDATE mac_vod set d_pic='{$img}' WHERE d_id={$result['d_id']}";
        $res    = $this->pdo->exec($sql);
        if(!$res){
            return '更新失败'.$result['d_id'].'_'.$img;
        }
        return '更新成功:';
    }


    /**
     * 上传图片到微博图床
     * @param $file 图片文件/图片url
     * @param $multipart 是否采用multipart方式上传
     * @return 返回的json数据
     */
    public function upload($file, $multipart = false) {
        $url = 'http://picupload.service.weibo.com/interface/pic_upload.php'
            .'?mime=image%2Fjpeg&data=base64&url=0&markpos=1&logo=&nick=0&marks=1&app=miniblog';

        if($multipart) {
            $url .= '&cb=http://weibo.com/aj/static/upimgback.html?_wv=5&callback=STK_ijax_'.time();
            if (class_exists('CURLFile')) {     // php 5.5
                $post['pic1'] = new CURLFile(realpath($file));
            } else {
                $post['pic1'] = '@'.realpath($file);
            }
        } else {
            $post['b64_data'] = base64_encode(file_get_contents($file));
        }

        // Curl提交
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Cookie: $this->cookie"),
            CURLOPT_POSTFIELDS => $post,
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        // 正则表达式提取返回结果中的json数据
        preg_match('/({.*)/i', $output, $match);

        if(!isset($match[1])) return '';
        $pid = json_decode($match[1],true)['data']['pics']['pic_1']['pid'];
        if(!$pid){
            return '';
        }
        $img = 'http://wx4.sinaimg.cn/mw690/'.$pid.'.jpg';
        return $img;
    }




    /**
     * 获取图片链接(本函数修改自 https://github.com/consatan/weibo_image_uploader)
     *
     * @param string $pid 微博图床pid，或者微博图床链接。传递的是链接的话，
     *     仅是将链接的尺寸更改为目标尺寸而已。
     * @param string $size 图片尺寸
     * @param bool $https (true) 是否使用 https 协议
     * @return string 图片链接
     * 当 $pid 既不是 pid 也不是合法的微博图床链接时返回空值
     */
    function getImageUrl($pid, $size = 0, $https = true)
    {
        $sizeArr = array('large', 'mw1024', 'mw690', 'bmiddle', 'small', 'thumb180', 'thumbnail', 'square');
        $pid = trim($pid);
        $size = $sizeArr[$size];
        // 传递 pid
        if (preg_match('/^[a-zA-Z0-9]{32}$/', $pid) === 1) {
            return ($https ? 'https' : 'http') . '://' . ($https ? 'ws' : 'ww')
                . ((crc32($pid) & 3) + 1) . ".sinaimg.cn/" . $size
                . "/$pid." . ($pid[21] === 'g' ? 'gif' : 'jpg');
        }
        // 传递 url
        $url = $pid;
        $imgUrl = preg_replace_callback('/^(https?:\/\/[a-z]{2}\d\.sinaimg\.cn\/)'
            . '(large|bmiddle|mw1024|mw690|small|square|thumb180|thumbnail)'
            . '(\/[a-z0-9]{32}\.(jpg|gif))$/i', function ($match) use ($size) {
            return $match[1] . $size . $match[3];
        }, $url, -1, $count);
        if ($count === 0) {
            return '';
        }
        return $imgUrl;
    }
}

$upload = new upload();
for($i=0;$i<200000;$i++){
    $res = $upload->updateImg($i);
    echo ($res)."\n";
}




