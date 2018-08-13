<?php
//文件名称
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// 网站根目录
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
//加载配置文件
require_once FCPATH.'config.php';
//判断防盗链
if(!is_referer()){
	 header('HTTP/1.1 403 Forbidden');
     exit(ERROR);
}
$get = $_GET;
if(strstr($get['url'],'v.youku.com')){
    $get['type'] = 'youku';
} elseif(strstr($get['url'],'v.qq.com')){
    $get['type'] = 'qq';
} elseif(strstr($get['url'],'iqiyi.com')){
    $get['type'] = 'iqiyi';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=11" />
<title><?=USER_TITLE?USER_TITLE:'Q2017.com解析';?></title>
<style type="text/css">body,html,.content{background-color:black;padding: 0;margin: 0;width:100%;height:100%;color:#999;}.divs{width:100%;height:auto;position:fixed;left:0;top:0;z-index:999}</style>
<script type="text/javascript">
    var xfsub = [];
    xfsub.player_skin = '1';
    xfsub.public_path = '<?php echo YOU_URL;?>ckplayer';
</script>
<script src="<?php echo YOU_URL;?>ckplayer/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo YOU_URL;?>ckplayer/ckplayer.js?v=1" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo YOU_URL;?>ckplayer/xfjs.js?v=xf" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo YOU_URL;?>dplayer/DPlayer.min.css">
<script type="text/javascript" src="<?php echo YOU_URL;?>dplayer/DPlayer.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo YOU_URL;?>dplayer/hls.min.js"></script>
</head>
<body style="overflow-y:hidden;">
<div id="loading" class="content" style="font-weight:bold;padding-top:90px;" align="center"><strong><span class="tips">正在加载播放中,请稍等....<font class="timemsg">0</font>s</span></strong><span class="timeout" style="display:none;color:#f90;">解析响应超时，请刷新重试！</span></div>
<div id="a1" class="content" style="display:none;"></div>
<div id="error" class="content" style="display:none;font-weight:bold;padding-top:90px;" align="center">视频加载失败，请稍候再试...</div>
<input type="hidden" id="hdMd5" value="EFBFCB31D5BACE6BB5793A529254424E" />
<script type="text/javascript">
function player(){
    $.post("api.php", {"referer":"<?php echo $_SERVER['HTTP_REFERER']?>", "time":"<?php echo TIMES;?>", "key": "<?php echo md5(TIMES.$key);?>", "url": "<?php echo $_GET['url'];?>","type": "<?php echo $get['type'];?>"},
    function(data){
        if(data['success'] == 1){
            var isiPad = navigator.userAgent.match(/iPad|iPhone|Linux|Android|iPod/i) != null;
            if(data['play'] == 'url'){
                 $('#a1').html('<iframe width="100%" height="100%" allowTransparency="true" frameborder="0" scrolling="no" src="'+data['url']+'"></iframe>');
            } else if(data['play'] == 'ajax'){
                if(data.type == 'youku'){
                    var get = getQuery(data.url);
                    data.ccode = get.ccode;
                    data.site  = data.type;
                    data.stype = get.stype;
                    data.vid   = get.vid;
                    data.weparser_js_url  = BASE64.de(decodeURIComponent(get.js_url));
                    data.weparser_swf_url = BASE64.de(decodeURIComponent(get.swf_url));
                    if(isiPad){
                        weParserParams = data;
                        var weParserJS = document.createElement("script");
                        weParserJS.type = "text/javascript";
                        weParserJS.src = data.weparser_js_url;
                        document.getElementsByTagName("head")[0].appendChild(weParserJS);
                    } else {
                        data.ext = 'xml';
                        data.url = '<?php echo YOU_URL?>xml.php?a=setswf&data='+BASE64.en($.param(data));
                        ckplayer_(data);
                    }
                } else if(data.type == 'qq'){
                    var get = getQuery(data.url);
                    data.site  = data.type;
                    data.stype = get.stype;
                    data.vid   = get.vid;
                    data.weparser_js_url  = BASE64.de(decodeURIComponent(get.js_url));
                    data.weparser_swf_url = BASE64.de(decodeURIComponent(get.swf_url));
                    if(isiPad){
                        weParserParams = data;
                        var weParserJS = document.createElement("script");
                        weParserJS.type = "text/javascript";
                        weParserJS.src = data.weparser_js_url;
                        document.getElementsByTagName("head")[0].appendChild(weParserJS);
                    } else {
                        data.ext = 'xml';
                        data.url = '<?php echo YOU_URL?>xml.php?a=setswf&data='+BASE64.en($.param(data));
                        ckplayer_(data);
                    }
                } else if(data.type == 'iqiyi'){
                    $.ajax({
                        url: data.url.replace('http:', ''),
                        dataType: 'html',
                        success: function(json) {
                            json = eval("("+json.substring(17,json.length - 15)+")");
                            if (json.code == 'A00000') {
                                if (isiPad) {
                                    data.url = json.data.m3u;
                                    data.ext = 'h5';
                                    ckplayer_(data)
                                } else {
                                    var array = {};
                                    for (var i = json.data.vidl.length - 1; i >= 0; i--) {
                                        if (json.data.vidl[i].fileFormat != "H265") {
                                            array[json.data.vidl[i].vd] = json.data.vidl[i]
                                        }
                                    };
                                    if (array[4] != undefined) {
                                        data.url = array[4].m3u
                                    } else if (array[3] != undefined) {
                                        data.url = array[3].m3u
                                    } else if (array[2] != undefined) {
                                        data.url = array[2].m3u
                                    } else if (array[1] != undefined) {
                                        data.url = array[1].m3u
                                    } else if (array[96] != undefined) {
                                        data.url = array[96].m3u
                                    }
                                    data.url = 'xml.php?a=m3u8'+BASE64.en($.param(data));
                                    data.play = 'hls';
                                    var dplayer = new DPlayer({
                                        element: document.getElementById("a1"),
                                        autoplay: true,
                                        video: {
                                            url: data['url'],
                                            type: (data.play == 'h5mp4')? 'normal':data.play,
                                        }
                                    });
                                }
                            } else {
                                $('#loading').hide();
                                $('#a1').hide();
                                $('#error').html('<br><br><br><br><br>解析失败或资源不存在。');
                                $('#error').show();
                                return;
                            }
                        }
                    })
                }
            } else if(isiPad || data['play'] == 'html5'){
                 $('#a1').html('<video src="'+data['url']+'" controls="controls" preload="preload" poster="<?php echo YOU_URL;?>loading/<?=USER_LOADING?USER_LOADING:'loading.png';?>" width="100%" height="100%"></video>');
            } else if(data['play'] == 'h5mp4' || data['play'] == 'hls'){
                var dplayer = new DPlayer({
                    element: document.getElementById("a1"),
                    autoplay: true,
                    video: {
                        url: data['url'],
                        type: (data.play == 'h5mp4')? 'normal':data.play,
                    }
                });
            } else {
                ckplayer_(data);
            }
            $('#loading').hide();
            $('#a1').show();
        }else{
            $('#loading').hide();
            $('#a1').hide();
            if(data.msg == undefined){
                $('#error').html('<br><br><br><br><br>'+data['m']);
            } else {
                $('#error').html('<br><br><br><br><br>'+data['msg']);
            }
            $('#error').show();
        }
    },"json");
}
player();


(function(){

    var BASE64_MAPPING = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

    var __BASE64 = {
        // 解密
        de:function (r) {
            var o = String(r).replace(/=+$/, "");
            if (o.length % 4 == 1)throw new t("'atob' failed: The string to be decoded is not correctly encoded.");
            for (var n, a, i = 0, c = 0, d = ""; a = o.charAt(c++); ~a && (n = i % 4 ? 64 * n + a : a, i++ % 4) ? d += String.fromCharCode(255 & n >> (-2 * i & 6)) : 0)a = BASE64_MAPPING.indexOf(a);
            return d
        },
        // 加密
        en:function (r) {
            for (var o, n, a = String(r), i = 0, c = BASE64_MAPPING, d = ""; a.charAt(0 | i) || (c = "=", i % 1); d += c.charAt(63 & o >> 8 - i % 1 * 8)) {
                if (n = a.charCodeAt(i += .75), n > 255)throw new t("'btoa' failed: The string to be encoded contains characters outside of the Latin1 range.");
                o = o << 8 | n
            }
            return d
        }
    };
    window.BASE64 = __BASE64;
})();
function getQuery(url) {
    if (typeof url !== 'string') {
        return null
    }
    var query = url.match(/[^\?]+\?([^#]*)/, '$1');
    if (!query || !query[1]) {
        return null
    }
    var kv = query[1].split('&');
    var map = {};
    for (var i = 0, len = kv.length; i < len; i++) {
        var result = kv[i].split('=');
        var key = result[0],
            value = result[1];
        map[key] = value || (typeof value == 'string' ? null : true)
    }
    return map
}

function ckplayer_(data){
    if(data.ext == 'h5'){
        $('#a1').html('<video src="'+data.url+'" controls="controls" preload="preload" poster="<?php echo YOU_URL;?>loading/<?=USER_LOADING?USER_LOADING:'loading.png';?>" width="100%" height="100%"></video>');
    } else {
        if(data['play'] == 'm3u8'){
             var flashvars={f:'<?php echo YOU_URL;?>ckplayer/m3u8.swf',a:data['url'],c:0,p:1,s:4,lv:0};
        } else {
             var flashvars={f:data['url'],c:0,s:2,p:1,b:1};
        }
        var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
        CKobject.embedSWF('<?php echo YOU_URL;?>ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','100%',flashvars,params);
    }
}
function tipstime(count){$('.timemsg').text(count);if(count==40){$('.tips').hide();$('.timeout').show()}else{count+=1;setTimeout(function(){tipstime(count)},1000)}}tipstime(0);
</script>
<?php if(USER_TONGJI!=''){$tongji=USER_TONGJI;echo'<span style="display:none;">'.$tongji.'</span>';}
if(USER_AD!=''){$ad=explode(',',USER_AD);$ads='';foreach($ad as $i =>$value){$ads.='document.writeln("<script type=\'text/javascript\' src=\'//'.$value.'\'><\/script>");';}echo '<div class="divs"><script type="text/javascript">'.$ads.'</script></div>';}
?></body></html>