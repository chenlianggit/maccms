//<!-- 免费下载本源码： http://www.9if.cn/  -->
String.prototype.replaceAll  = function(s1,s2){ return this.replace(new RegExp(s1,"gm"),s2); }
String.prototype.trim=function(){ return this.replace(/(^\s*)|(\s*$)/g, ""); }
var base64EncodeChars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var base64DecodeChars=new Array(-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1);function base64encode(str){var out,i,len;var c1,c2,c3;len=str.length;i=0;out="";while(i<len){c1=str.charCodeAt(i++)&0xff;if(i==len){out+=base64EncodeChars.charAt(c1>>2);out+=base64EncodeChars.charAt((c1&0x3)<<4);out+="==";break}c2=str.charCodeAt(i++);if(i==len){out+=base64EncodeChars.charAt(c1>>2);out+=base64EncodeChars.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));out+=base64EncodeChars.charAt((c2&0xF)<<2);out+="=";break}c3=str.charCodeAt(i++);out+=base64EncodeChars.charAt(c1>>2);out+=base64EncodeChars.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));out+=base64EncodeChars.charAt(((c2&0xF)<<2)|((c3&0xC0)>>6));out+=base64EncodeChars.charAt(c3&0x3F)}return out}function base64decode(str){var c1,c2,c3,c4;var i,len,out;len=str.length;i=0;out="";while(i<len){do{c1=base64DecodeChars[str.charCodeAt(i++)&0xff]}while(i<len&&c1==-1);if(c1==-1)break;do{c2=base64DecodeChars[str.charCodeAt(i++)&0xff]}while(i<len&&c2==-1);if(c2==-1)break;out+=String.fromCharCode((c1<<2)|((c2&0x30)>>4));do{c3=str.charCodeAt(i++)&0xff;if(c3==61)return out;c3=base64DecodeChars[c3]}while(i<len&&c3==-1);if(c3==-1)break;out+=String.fromCharCode(((c2&0XF)<<4)|((c3&0x3C)>>2));do{c4=str.charCodeAt(i++)&0xff;if(c4==61)return out;c4=base64DecodeChars[c4]}while(i<len&&c4==-1);if(c4==-1)break;out+=String.fromCharCode(((c3&0x03)<<6)|c4)}return out}function utf16to8(str){var out,i,len,c;out="";len=str.length;for(i=0;i<len;i++){c=str.charCodeAt(i);if((c>=0x0001)&&(c<=0x007F)){out+=str.charAt(i)}else if(c>0x07FF){out+=String.fromCharCode(0xE0|((c>>12)&0x0F));out+=String.fromCharCode(0x80|((c>>6)&0x3F));out+=String.fromCharCode(0x80|((c>>0)&0x3F))}else{out+=String.fromCharCode(0xC0|((c>>6)&0x1F));out+=String.fromCharCode(0x80|((c>>0)&0x3F))}}return out}function utf8to16(str){var out,i,len,c;var char2,char3;out="";len=str.length;i=0;while(i<len){c=str.charCodeAt(i++);switch(c>>4){case 0:case 1:case 2:case 3:case 4:case 5:case 6:case 7:out+=str.charAt(i-1);break;case 12:case 13:char2=str.charCodeAt(i++);out+=String.fromCharCode(((c&0x1F)<<6)|(char2&0x3F));break;case 14:char2=str.charCodeAt(i++);char3=str.charCodeAt(i++);out+=String.fromCharCode(((c&0x0F)<<12)|((char2&0x3F)<<6)|((char3&0x3F)<<0));break}}return out}
function pagego($url,$total){
	$page=$('#page').val();
	if($page>0&&($page<=$total)){
		if($page>1 || document.URL.indexOf('?')>-1){
			$url=$url.replace('{pg}',$page);
		}
		else{
			if($page==1){
				$url=$url.replace('-{pg}','').replace('{pg}','');
			}
		}
		location.href=$url;
	}
	return false;
}

var MAC={
	'Url': document.URL,
	'Title': document.title,
	'Fav': function(u,s){
		try{ window.external.addFavorite(u, s);}
		catch (e){
			try{window.sidebar.addPanel(s, u, "");}catch (e){alert("加入收藏出错，请使用键盘Ctrl+D进行添加");}
		}
	},
	'Open': function(u,w,h){
		window.open(u,'macopen1','toolbars=0, scrollbars=0, location=0, statusbars=0,menubars=0,resizable=yes,width='+w+',height='+h+'');
	},
	'Cookie': {
		'Set': function(name,value,days){
			var exp = new Date();
			exp.setTime(exp.getTime() + days*24*60*60*1000);
			var arr=document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
			document.cookie=name+"="+escape(value)+";path=/;expires="+exp.toUTCString();
		},
		'Get': function(name){
			var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
			if(arr != null){ return unescape(arr[2]); return null; }
		},
		'Del': function(name){
			var exp = new Date();
			exp.setTime(exp.getTime()-1);
			var cval = this.Get(name);
			if(cval != null){ document.cookie = name+"="+escape(cval)+";path=/;expires="+exp.toUTCString(); }
		}
	},
	'History': {
		'Limit':10,
		'Days':7,
		'Json': '',
		'Display': true,
		'List': function($id){
			if($("#"+$id).length==0){ return; }
			this.Create($id);
			$('#'+$id).hover(function(){
				MAC.History.Show();
			}, function(){
				MAC.History.FlagHide();
			});
		},
		'Clear': function(){
			MAC.Cookie.Del('mac_history');
			$('#history_box').html('<li class="hx_clear">已清空观看记录。</li>');
		},	
		'Show': function(){
			$('#history_box').show();
		},
		'Hide': function(){
			$('#history_box').hide();
		},
		'FlagHide': function(){
			$('#history_box').hover(function(){
				MAC.History.Display = false;
				MAC.History.Show();
			}, function(){
				MAC.History.Display = true;
				MAC.History.Hide();
			});
			if(MAC.History.Display){
				MAC.History.Hide();
			}
		},
		'Create': function($id){
			var jsondata = [];
			if(this.Json){
				jsondata = this.Json;
			}else{
				var jsonstr = MAC.Cookie.Get('mac_history');
				if(jsonstr != undefined){
					jsondata = eval(jsonstr);
				}
			}
			html = '<dl class="drop-box history_box" id="history_box" style="display:none;position:absolute;">';
			html +='<dt><a target="_self" href="javascript:void(0)" onclick="MAC.History.Clear();">清空</a> | <a target="_self" href="javascript:void(0)" onclick="MAC.History.Hide();">关闭</a></dt>';
			if(jsondata.length > 0){
				for($i=0; $i<jsondata.length; $i++){
					if($i%2==1){
						html +='<dd class="odd">';
					}else{
						html +='<dd class="even">';
					}
					html +='<a href="'+jsondata[$i].link+'" class="hx_title">'+jsondata[$i].name+'</a></dd>';
				}
			}else{
				html +='<dd class="hide">暂无观看记录</dd>';
			}
			html += '</dl>';
			$('#'+$id).after(html);
			
			var w = $('#'+$id).width();
			var h = $('#'+$id).height();
			var position = $('#'+$id).position();
			$('#history_box').css({'left':position.left,'top':(position.top+h)});
		},	
		'Insert': function(name,link,typename,typelink,pic){
			var jsondata = MAC.Cookie.Get('mac_history');
			if(jsondata != undefined){
				this.Json = eval(jsondata);
				for($i=0;$i<this.Json.length;$i++){
					if(this.Json[$i].link == link){
						return false;
					}
				}
				if(!link){ link = document.URL; }
				jsonstr = '{video:[{"name":"'+name+'","link":"'+link+'","typename":"'+typename+'","typelink":"'+typelink+'","pic":"'+pic+'"},';
				for($i=0; $i<=this.Limit; $i++){
					if(this.Json[$i]){
						jsonstr += '{"name":"'+this.Json[$i].name+'","link":"'+this.Json[$i].link+'","typename":"'+this.Json[$i].typename+'","typelink":"'+this.Json[$i].typelink+'","pic":"'+this.Json[$i].pic+'"},';
					}else{
						break;
					}
				}
				jsonstr = jsonstr.substring(0,jsonstr.lastIndexOf(','));
				jsonstr += "]}";
			}else{
				jsonstr = '{video:[{"name":"'+name+'","link":"'+link+'","typename":"'+typename+'","typelink":"'+typelink+'","pic":"'+pic+'"}]}';
			}
			this.Json = eval(jsonstr);
			MAC.Cookie.Set('mac_history',jsonstr,this.Days);
		}
	},
	'Score': {
		'ajaxurl': 'inc/ajax.php?ac=score',
		'Show':function($f,$tab,$id){
			var str = '';
			if($f==1){
				str = '<div style="padding:5px 10px;border:1px solid #CCC"><div style="color:#000"><strong>我要评分(感谢参与评分，发表您的观点)</strong></div><div>共 <strong style="font-size:14px;color:red" id="star_count"> 0 </strong> 个人评分， 平均分 <strong style="font-size:14px;color:red" id="star_pjf"> 0 </strong>， 总得分 <strong style="font-size:14px;color:red" id="star_all"> 0 </strong></div><div>';
				for(var i=1;i<=10;i++){ str += '<input type="radio" name="score" id="rating'+i+'" value="1"/><label for="rating'+i+'">'+i+'</label>'; }
				str += '&nbsp;<input type="button" value=" 评 分 " id="scoresend" style="width:55px;height:21px"/></div></div>';
			}
			else{
				str += '<div class="star score"><ul><li class="star_current"></li>';
				for(var i=1;i<=10;i++){ str += '<li><a value="'+i+'" class="star_'+i+'">'+i+'</a></li>'; }
				str += '<span id="star_tip"></span><span id="star_hover"></span></ul>';
				str +='<p class="branch"><span id="star_shi">0</span><span id="star_ge">.0</span><span class="star_no">(已有<label id="star_count">0</label>人评分)</span></p></div>';
			}
			document.write(str);
			$.ajax({type: 'get',url: SitePath + this.ajaxurl + '&tab='+$tab+'&id='+$id,timeout: 5000,
				error: function(){
					alert('评分加载失败');
					$(".score").html('评分加载失败');
				},
				success: function($r){
					MAC.Score.View($r);
					if($f==1){
						$("#scoresend").click(function(){
							var rc=false;
							for(var i=1;i<=10;i++){ if( $('#rating'+i).get(0).checked){ rc=true; break; } }
							if(!rc){alert('你还没选取分数');return;}
							MAC.Score.Send( '&tab='+$tab+'&id='+$id+'&score='+i );
						});
					}
					else{
						
						var tip=new Array("","很差，浪费生命","很差，浪费生命","不喜欢","不喜欢","一般，不妨一看","一般，不妨一看","一般，不妨一看","喜欢，值得推荐","喜欢，值得推荐","非常喜欢，不容错过");
						$(".score>ul>li>a").mouseover(function(){
							$("#star_hover").html($(this).attr('value')+"分 ");
							$("#star_tip").html(tip[$(this).attr('value')]);
							$(".star_current").css("display","none");
						});
						$(".score>ul>li>a").mouseout(function(){
							$("#star_hover").html("");
							$("#star_tip").html("");
							$(".star_current").css("display","block");
						});
						$(".score>ul>li>a").click(function(){
							MAC.Score.Send( '&tab='+$tab+'&id='+$id+'&score='+$(this).attr('value') );
						});
					}
				}
			});
		},
		'Send':function($u){
			$.ajax({type: 'get',url: SitePath + this.ajaxurl + $u,timeout: 5000,
				error: function(){
					$(".star").html('评分加载失败');
				},
				success: function($r){
					if($r=="haved"){
						alert('你已经评过分啦');
					}else{
						MAC.Score.View($r);
					}
				}
			});
		},
		'View':function($r){
			$r = eval('(' + $r + ')');
			$("#rating"+Math.floor($r.score)).attr('checked',true);
			$("#star_count").text( $r.scorenum );
			$("#star_all").text( $r.scoreall );
			$("#star_pjf").text( $r.score );
			$("#star_shi").text( parseInt($r.score) );
			$("#star_ge").text( "." +  ($r.score.toString().split('.')[1]==undefined ? '0' : $r.score.toString().split('.')[1]) );
			$(".star_current").width($r.score*10);
		}
	},
	'Suggest': {
		'Show': function($id,$limit,$ajaxurl,$jumpurl){
			try{
			$("#"+$id).autocomplete($ajaxurl,{
				width: 175,scrollHeight: 300,minChars: 1,matchSubset: 1,max: $limit,cacheLength: 10,multiple: true,matchContains: true,autoFill: false,dataType: "json",
				parse:function(obj) {
					if(obj.status){
						var parsed = [];
						for (var i = 0; i < obj.data.length; i++) {
							parsed[i] = {
								data: obj.data[i],value: obj.data[i].d_name,result: obj.data[i].d_name
							};
						}
						return parsed;
					}else{
						return {data:'',value:'',result:''};
					}
				},
				formatItem: function(row,i,max) {
					return row.d_name;
				},
				formatResult: function(row,i,max) {
					return row.d_name;
				}
			}).result(function(event, data, formatted) {
				location.href = $jumpurl + encodeURIComponent(data.d_name);
			});
			}catch(e){}
		}
	}
}

$(function(){
	//历史记录初始化
	MAC.History.List('history');
	//搜索联想初始化
	MAC.Suggest.Show('wd',10, SitePath+'inc/ajax.php?ac=suggest&aid='+SiteAid, SitePath+'index.php?m=vod-search-wd-');
	//
	var swiper = new Swiper('.hy-slide', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 3000,
        loop: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
    });
    var swiper = new Swiper('.hy-switch', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        slidesPerView: 5,
        spaceBetween: 0,
        loop: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        breakpoints: {
            1200: {
                slidesPerView: 4,
                spaceBetween: 0
            },
            767: {
                slidesPerView: 3,
                spaceBetween: 0
            }
        }
    });
    $(function() {
        $(".videopic.lazy").lazyload({
            effect: "fadeIn"
        });
    });
    $(window).on("scroll", function() {
        var a = $(this).scrollTop();
        a > 200 ? $(".backup").addClass("go") : $(".backup").removeClass("go")
    });
    $(document).on("click", ".backup", function() {
        $("body,html").animate({
            scrollTop: 0
        }, 800)
    })
});
