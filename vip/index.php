<?php
$path       = @exec("pwd");
$longPHP    = getDirFiles($path);
$get = $_GET;
$url = trim($get['url']);
$error = '';
if(!$url){
    $error = '请填写需要解析的视频URL';
}

$str    = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
if(!$error  && !preg_match($str,$url)){
    $error  = '视频URL错误,请检查！';
}
$finallyUrl = '/vip/'.$longPHP.'?url='.$url;
if(!$error){
    header("Location:".$finallyUrl);
    exit();
}
$q2017  = '/';


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
            background-color: royalblue;
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
        ?>
    </div>
    <div class="hy-head-menu">
        <span id="jumpTo">10000</span>秒后无操作，自动跳转到很骚很骚的网站
        <script type="text/javascript">countDown(10000,'/');</script>
    </div>
    <div class="hy-head-menu">
        或者直接 <a href="/" style="color: red">跳转</a>
    </div>
</div>
</body>

</html>
