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
//        $this->pdo      = new pdo('mysql:dbname=sinaav;host=127.0.0.1','sinaav','sinaav',array(PDO::ATTR_PERSISTENT => true));
//        $this->pdo      = new pdo('mysql:dbname=maccms;host=127.0.0.1','root','cc123456',array(PDO::ATTR_PERSISTENT => true));
        $this->cookie   = "SINAGLOBAL=4659216704418.871.1520910520578; UM_distinctid=1626219463b3aa-01becee071bbf7-336c7b05-1aeaa0-1626219463c33d; wb_cmtLike_6402545227=1; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WhzC8bYi5ydZbgX539Oh4D35JpX5KMhUgL.FoqXehzfSh-EeoM2dJLoIEBLxKnL1K5L12eLxK.LBKqL1K.LxKnL12qL1hMLxK.L1h-LBo2t; ULV=1535351328193:30:10:1:1074010113125.9272.1535351328110:1535165540041; UOR=,,news.ifeng.com; TC-V5-G0=31f4e525ed52a18c5b2224b4d56c70a1; ALF=1567351494; SSOLoginState=1535815495; SCF=Avhu3tu0EcQZwCk5O-NnDrt3QOllirFHhem-dnypkPuLDAsETvkGA9WtkLy_GRQkUttOwlJLWR7Pm3w4VSw1QAY.; SUB=_2A252jt8XDeRhGeBK61AU9CvOyTuIHXVV_bffrDV8PUNbmtAKLVetkW9NRBCwdjOhF40vuhLI0ZWHt3szIDQPpjbP; SUHB=00xpwxjNrvrQZs; wvr=6";

    }

    // d_sina 0 未操作 1 success 2生成失败 3 操作过
    public function updateImg(){
        $result     = $this->pdo->query("select d_id,d_pic from mac_vod where d_sina = 0 order by d_time desc limit 1;")->fetch();

        if(!$result){
            echo "执行完成";
            exit();
        }
        $sql3    = "UPDATE mac_vod set d_sina = 3  WHERE d_id={$result['d_id']}"; //只要操作过就是3
        $this->pdo->exec($sql3);
        if(!$result['d_pic']){

            return '查询错误';
        }
        if( preg_match('/^http:\/\/wx4/',$result['d_pic']) ){
            return '无需更新';
        }

        if(preg_match('/^tu/',$result['d_pic'])){
            $url = 'http://'.explode('tu=',$result['d_pic'])[1];
        }elseif (preg_match('/^upload/',$result['d_pic'])){
            $url = 'http://www.q2017.com/'.$result['d_pic'];
        } else{
            $url = $result['d_pic'];
        }
        $img = $this->upload($url);

        if(!$img){
            $sql2    = "UPDATE mac_vod set d_sina = 2  WHERE d_id={$result['d_id']}";
            $this->pdo->exec($sql2);
            return "生成失败";
        }
        $sql1    = "UPDATE mac_vod set d_pic='{$img}',d_sina = 1  WHERE d_id={$result['d_id']}";
        $res    = $this->pdo->exec($sql1);
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
    $res = $upload->updateImg();
    if($res){
        echo ($res)."_第{$i}次\n";
    }
}





