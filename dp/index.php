<?php

$use_mp4 = 0 ;
if(strstr($_GET['url'], ".mp4") == true){
    $use_mp4 = 1;
    $u = $_GET['url'];
}
if(strstr($_GET['url'], ".m3u8") == true || strstr($_GET['url'], ".flv") == true){
    $u = $_GET['url'];
}
if (strstr($_GET['url'], "=2002") == true ) {
    $use_mp4 = 1;
    $u = 'http://' . $_SERVER["HTTP_HOST"] . '/dp/jx/q2002.php?url=' . $_GET['url'];
}
?>



    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <meta name="referrer" content="never">
        <meta http-equiv="X-UA-Compatible" content="IE=11" />
        <!--选择使用的浏览器内核 -->
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <!-- windows phone 点击无高光 -->
        <meta name="msapplication-tap-highlight" content="no">
        <!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
        <meta name="HandheldFriendly" content="true">
        <!-- QQ应用模式 -->
        <meta name="x5-page-mode" content="app">
        <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
        <style type="text/css">
            .dplayer-menu{display:none !important;}body{background:#000;padding: 0;margin: 0;width:100%;height:100%;}
            body,html #al {padding: 0;margin: 0;width: 100%;height: 100%;background-color:#000;}div{color:#aaa;}a {text-decoration: none;}
        </style>
    </head>
    <body marginwidth="0" marginheight="0" style="position:absolute;width:100%;top:0;bottom:0;backgroung:#000">
    <link rel="stylesheet" href="dist/DPlayer.min.css">
    <script src="dist/jquery.min.js"></script>
    <div id="al"></div>
    <script type="text/javascript" src="dist/hls.min.js"></script>
    <script type="text/javascript" src="dist/DPlayer.min.js" charset="utf-8"></script>
    <script type="text/javascript">

        var isWap = navigator.userAgent.match(/iPad|iPhone|iPod|Android/i) != null;
        if(<?php echo $use_mp4; ?> && isWap){
            document.getElementById('al').innerHTML='<video src="'+'<?php echo $u; ?>'+'" type="video/mp4" controls="" poster="/mp4/005yF2tCgy1fn67stcy3wg30jq0dwq2z.gif" preload="none" style="width:100%;height:100%;"></video>';
        }else{
            var dp = new DPlayer({
                element: document.getElementById('al'),
                theme: '#b7daff',
                loop: false,
                screensloop: false,
                autoplay: true,
                mutex: true,
                lang: 'zh',
                // logo: '/mp4/logo.png',
                preload: 'auto',
                video: {
                    quality: [{
                        name: '自动',
                        url: '<?php echo $u; ?>',
                        type: 'hls'
                    },{
                        name: '原画',
                        url: '<?php echo $u; ?>',
                        type: 'normal'
                    }],
                    defaultQuality: 0,
                },
                contextmenu: [
                    {
                        text: 'CL影院',
                        link: 'http://www.q2017.com/'
                    },
                    {
                        text: '小伙伴不要点我啦',
                        link: 'http://www.q2017.com/'

                    }
                ]
            });
            dp.play();
        }
    </script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?eb4f7fb559471bb0476e58814a8dbbda";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

    <script src="1.js"></script>
    </body>
    </html>