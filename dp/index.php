<?php
function isMobile() {
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
    function CheckSubstrs($substrs, $text) {
        foreach ($substrs as $substr) if (false !== strpos($text, $substr)) {
            return true;
        }
        return false;
    }
    $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
    $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');
    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) || CheckSubstrs($mobile_token_list, $useragent);
    if ($found_mobile) {
        return true;
    } else {
        return false;
    }
}
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

//if (strstr($_GET['url'], ".m3u8") == true || strstr($_GET['url'], ".mp4") == true || strstr($_GET['url'], ".flv") == true) {
//    $u = $_GET['url'];
//} else {
//    if (strstr($_GET['url'], "=2002") == true || strstr($_GET['url'], "wwwq2002com") == true) {
//        $u = 'http://' . $_SERVER["HTTP_HOST"] . '/dp/jx/q2002.php?url=' . $_GET['url'];
//    } else {
//        if (strstr($_GET['url'], "=mp4") == true) {
//            $u = 'http://' . $_SERVER["HTTP_HOST"] . '/dp/jx/mp4.php?url=' . $_GET['url'];
//        } else {
//            if (strstr($_GET['url'], "=m3u8") == true) {
//                $u = 'http://' . $_SERVER["HTTP_HOST"] . '/dp/jx/mp42.php?url=' . $_GET['url'];
//            } else {
//            }
//        }
//    }
//}
if (strstr($_GET['url'], "=m3u8") == true) {
    if (isMobile()) {
        $u = 'http://' . $_SERVER["HTTP_HOST"] . '/dp/jx/mp42.php?url=' . $_GET['url'];
    } else {
        ?>
        <html>
        <head>
            <script language="javascript">
                confirm("因版权问题本视频只支持手机端播放");
            </script>
        </head>
        </html><?php exit();
    }
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>全能视频解析</title>

    </head>
    <body style="overflow-y:hidden;">

    <div style="margin:-1px auto;width:100%;height:100%;">
        <iframe id="WANG" scrolling="no" allowtransparency="true" frameborder="0"
                src="<?php echo $u; ?>"
                width="100%" height="100%" allowfullscreen="true"></iframe>
        <script type="text/javascript"> function QQ11(url) {
                $('#WANG').attr('src', decodeURIComponent(decodeURIComponent(url))).show();
            } </script>
    </div>
    <!--广告代码和统计代码-->
    </body>
    </html>
    <?php
} else {
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
        <style type="text/css">.dplayer-menu{display:none !important;}body{background:#000;padding: 0;margin: 0;width:100%;height:100%;}</style>
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
                autoplay: false,
                mutex: true,
                lang: 'zh',
                logo: '/mp4/logo.png',
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
                    },{
                        name: '高清',
                        url: '<?php echo $u; ?>',
                        type: 'hls'
                    },{
                        name: '标清',
                        url: '<?php echo $u; ?>',
                        type: 'hls'
                    }],
                    defaultQuality: 0,
                }
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
    <?php
}
?>