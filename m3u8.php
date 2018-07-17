
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<meta name="referrer" content="never">
<meta http-equiv="X-UA-Compatible" content="IE=11" />
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
</head>
<body marginwidth="0" marginheight="0" style="position:absolute;width:100%;top:0;bottom:0;backgroung:#000">
<link rel="stylesheet" href="/js/m3u8/DPlayer.min.css">
<script src="/js/m3u8/jquery.min.js"></script>
<div id="player1"></div>
<script type="text/javascript" src="/js/m3u8/hls.min.js"></script>
<script type="text/javascript" src="/js/m3u8/DPlayer.min.js" charset="utf-8"></script>
<script>
var dp = new DPlayer({
    element: document.getElementById('player1'),
    autoplay:false,
    video: {
        url: "<?php echo $_GET['url'];?>"
    }
});
dp.play();
</script>

</body>
</html>
