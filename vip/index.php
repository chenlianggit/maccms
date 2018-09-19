<?php

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
$finallyUrl = '/vip/8923482aebbccd445db4549377613015.php?url='.$url;
# 跳转的图片
$heng_img   = '/heng.jpg';
# 跳转链接
$shareUrl   = 'http://www.aldzs.com/';
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
    <?php
    if($error){
        ?>
        <div class="hy-head-menu">
            <?php echo $error; ?>
        </div>
        <a href="<?php echo $shareUrl;?>" target="_blank" style="text-decoration:none;">
            <img src="<?php echo $heng_img; ?>" alt="" width=100% height=100%></a>
    <?php
    }
    else{
    ?>

        <div class="hy-head-menu">
            <span id="jumpTo">5</span>秒后自动跳转到视频播放页,或者直接 <a href="<?php echo $finallyUrl; ?>">跳过</a>
        </div>
        <script type="text/javascript">countDown(5,'<?php echo $finallyUrl; ?>');</script>
        <a href="<?php echo $shareUrl;?>" target="_blank" style="text-decoration:none;">
            <img src="<?php echo $heng_img; ?>" alt="" width=100% height=85%></a>
        <?php

    }
    ?>
</div>
</body>

</html>