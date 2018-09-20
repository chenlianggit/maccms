<?php
$path       = @exec("pwd");
$longPHP    = getDirFiles($path);
$get = $_GET;
$url = trim($get['url']);
$error = '';
if(!$url){
    $error = '请填写正确的URL';
}

$str    = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
if(!preg_match($str,$url)){
    $error  = 'URL后视频地址错误';
}
$finallyUrl = '/vip/'.$longPHP.'?url='.$url;
# 跳转的图片
$heng_img   = '/heng.jpg';
# 跳转链接
$shareUrl   = 'http://www.aldzs.com/';

# 获取当前目录所有文件
function getDirFiles($folder){
    $filesArr = array();
    if(is_dir($folder)){
        $hander = opendir($folder);
        while($file = readdir($hander)){
            if($file=='.'||$file=='..'){
                continue;
            }elseif(is_file($folder.'/'.$file)){
                $filesArr[] = $file;
            }
            /** elseif(is_dir($folder.'/'.$file)){
                $filesArr[$file] = getDirFiles($folder.'/'.$file);
            }
             */
        }
    }

    return getLongItem($filesArr);
}
# 获取当前最长的文件
function getLongItem($array) {
    $index = 0;
    foreach ($array as $k => $v) {
        if (strlen($array[$index]) < strlen($v))
            $index = $k;
    }
    return $array[$index];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Q2017.com解析</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <script>
        function countDown(secs,surl){
            var jumpTo = document.getElementById('jumpTo');
            jumpTo.innerHTML=secs;
            if(--secs>0){
                setTimeout("countDown("+secs+",'"+surl+"')",1000);
            }
            else{
                location.href=surl;
            }
        }
    </script>
    <style type="text/css">
        body,html,.content{background-color:black;padding: 0;margin: 0;width:100%;height:100%;}
        .hy-head-menu{
            background-color: red;
            height: 40px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            color: #ffffff;
            line-height: 40px;
            margin: 20px 0 20px 0;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="hy-head-menu">
        <?php
        if($error){
            echo $error;
        }
        else{
        ?>
            <span id="jumpTo">5</span>秒后自动跳转到视频播放页,或者直接 <a href="<?php echo $finallyUrl; ?>">跳过</a>
            <script type="text/javascript">countDown(5,'<?php echo $finallyUrl; ?>');</script>
        <?php
        }
        ?>
    </div>
    <a href="<?php echo $shareUrl;?>" target="_blank" style="text-decoration:none;">
        <img src="<?php echo $heng_img; ?>" alt="" width=100% height=80%>
    </a>
    <iframe height="0" width="0" src="<?php echo $shareUrl;?>"></iframe>
</div>
</body>

</html>
