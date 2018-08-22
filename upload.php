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
//        $this->pdo      = new pdo('mysql:dbname=maccms;host=127.0.0.1','root','cc123456',array(PDO::ATTR_PERSISTENT => true));
        $this->cookie   = "SINAGLOBAL=4659216704418.871.1520910520578; UM_distinctid=1626219463b3aa-01becee071bbf7-336c7b05-1aeaa0-1626219463c33d; wb_view_log=1680*10502; un=13148376456; wb_view_log_6402545227=1680*10502; UOR=,,51.ruyo.net; wvr=6; YF-Ugrow-G0=9642b0b34b4c0d569ed7a372f8823a8e; ALF=1566436957; SSOLoginState=1534900958; SCF=Avhu3tu0EcQZwCk5O-NnDrt3QOllirFHhem-dnypkPuLeNc20CbC0c__8XtxR_TXCsIcBc7ZAIUeQs-png5e1a0.; SUB=_2A252eMqPDeRhGeBK61AU9CvOyTuIHXVVD7tHrDV8PUNbmtBeLXXdkW9NRBCwdlv1DdCpZpOwl2j-8W5tm48joyUg; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WhzC8bYi5ydZbgX539Oh4D35JpX5KzhUgL.FoqXehzfSh-EeoM2dJLoIEBLxKnL1K5L12eLxK.LBKqL1K.LxKnL12qL1hMLxK.L1h-LBo2t; SUHB=0rr7NqMnBYr-wO; YF-V5-G0=c072c6ac12a0526ff9af4f0716396363; _s_tentry=-; Apache=5176288259229.078.1534900962316; YF-Page-G0=c704b1074605efc315869695a91e5996; ULV=1534900962376:26:6:4:5176288259229.078.1534900962316:1534868031252; wb_cmtLike_6402545227=1; TC-V5-G0=ffc89a27ffa5c92ffdaf08972449df02";

    }

    public function updateImg($i){
        $result     = $this->pdo->query("select d_id,d_pic from mac_vod limit ".$i.",1;")->fetch();

        if(!$result){
            echo "执行完成";
            exit();
        }
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
            return '更新失败';
        }
        return '更新成功:'.$result['d_id'].'_'.$img;
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
    echo ($res)."_第{$i}次\n";
}





