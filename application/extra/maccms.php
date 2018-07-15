<?php
return array (
  'db' => 
  array (
    'type' => 'mysql',
    'path' => '',
    'server' => '127.0.0.1',
    'port' => '3306',
    'name' => 'maccms10',
    'user' => 'root',
    'pass' => 'root',
    'tablepre' => 'mac_',
    'backup_path' => './application/data/backup/database/',
    'part_size' => 10485760,
    'compress' => 1,
    'compress_level' => 4,
  ),
  'site' => 
  array (
    'site_name' => '免费电影网 - 苹果CMS V10',
    'site_url' => 'localhost',
    'site_wapurl' => 'www.1.cn',
    'site_keywords' => '最新最快免费电影',
    'site_description' => '国内最大的免费电影网站',
    'site_icp' => '备案XXXXXX号',
    'site_qq' => '12346',
    'site_email' => '123456@qq.com',
    'install_dir' => '/',
    'template_dir' => 'default_pc',
    'html_dir' => 'html',
    'mob_status' => '0',
    'mob_template_dir' => 'default_wap',
    'mob_html_dir' => 'html',
    'site_tj' => '',
    'site_status' => '1',
    'site_close_tip' => '网站维护中，请稍后访问。',
    'ads_dir' => 'ads',
    'mob_ads_dir' => 'ads',
  ),
  'app' => 
  array (
    'pathinfo_depr' => '/',
    'popedom_filter' => '0',
    'cache_type' => '0',
    'cache_host' => '',
    'cache_port' => '',
    'cache_password' => '',
    'cache_flag' => 'a3f702e191',
    'cache_core' => '0',
    'cache_time' => '3600',
    'cache_page' => '0',
    'cache_time_page' => '3600',
    'compress' => '0',
    'search' => '1',
    'search_timespan' => '1',
    'collect_timespan' => '3',
    'pagesize' => '20',
    'makesize' => '20',
    'suffix' => 'html',
    'search_hot' => '战狼,红海行动,复仇者联盟,变形金刚,奔跑吧,极限挑战',
    'art_extend_class' => '段子手,私房话,八卦精,爱生活,汽车迷,科技咖,美食家,辣妈帮',
    'vod_extend_class' => '爱情,动作,喜剧,战争,科幻,剧情,武侠,冒险,枪战,恐怖,微电影,其它',
    'vod_extend_state' => '正片,预告片,花絮',
    'vod_extend_version' => '高清版,剧场版,抢先版,OVA,TV,影院版',
    'vod_extend_area' => '大陆,香港,台湾,美国,韩国,日本,泰国,新加坡,马来西亚,印度,英国,法国,加拿大,西班牙,俄罗斯,其它',
    'vod_extend_lang' => '国语,英语,粤语,闽南语,韩语,日语,法语,德语,其它',
    'vod_extend_year' => '2018,2017,2016,2015,2014,2013,2012,2011,2010,2009,2008,2007,2006,2005,2004,2003,2002,2001,2000',
    'vod_extend_weekday' => '一,二,三,四,五,六,日',
    'player_sort' => '1',
    'encrypt' => '0',
    'filter_words' => '',
  ),
  'user' => 
  array (
    'status' => '1',
    'reg_open' => '1',
    'reg_status' => '1',
    'reg_points' => '10',
    'invite_reg_points' => '10',
    'trysee' => '0',
    'portrait_status' => '0',
    'portrait_size' => '100x100',
    'filter_words' => '',
  ),
  'gbook' => 
  array (
    'status' => '1',
    'audit' => '0',
    'login' => '0',
    'verify' => '1',
    'pagesize' => '20',
    'timespan' => '3',
  ),
  'comment' => 
  array (
    'status' => '1',
    'audit' => '0',
    'login' => '0',
    'verify' => '1',
    'pagesize' => '20',
    'timespan' => '3',
  ),
  'upload' => 
  array (
    'thumb' => '0',
    'thumb_size' => '300x300',
    'thumb_type' => '1',
    'watermark' => '0',
    'watermark_location' => '7',
    'watermark_content' => 'maccms.com',
    'watermark_size' => '25',
    'watermark_color' => '#FF0000',
    'mode' => '0',
    'remoteurl' => 'http://pic.maccms.com/',
    'api' => 
    array (
      'upyun' => 
      array (
        'bucket' => '',
        'username' => '',
        'pwd' => '',
        'url' => '',
      ),
      'qiniu' => 
      array (
        'bucket' => '',
        'accesskey' => '',
        'secretkey' => '',
        'url' => '',
      ),
      'ftp' => 
      array (
        'host' => '',
        'port' => '',
        'user' => '',
        'pwd' => '',
        'path' => '',
        'url' => '',
      ),
    ),
  ),
  'interface' => 
  array (
    'status' => '1',
    'pass' => 'MXQ8CQ',
    'vodtype' => '动作片=动作',
    'arttype' => '公告=站内公告#头条=电影资讯',
  ),
  'pay' => 
  array (
    'min' => '10',
    'scale' => '1',
    'card' => 
    array (
      'url' => '',
    ),
    'alipay' => 
    array (
      'account' => '',
      'appid' => '',
      'appkey' => '',
    ),
    'weixin' => 
    array (
      'appid' => '',
      'mchid' => '',
      'appkey' => '',
    ),
    'codepay' => 
    array (
      'appid' => '',
      'appkey' => '',
      'type' => '',
      'act' => '',
    ),
  ),
  'collect' => 
  array (
    'vod' => 
    array (
      'status' => '1',
      'hits_start' => '1',
      'hits_end' => '999',
      'updown_start' => '1',
      'updown_end' => '999',
      'score' => '1',
      'pic' => '0',
      'tag' => '0',
      'psernd' => '1',
      'psesyn' => '1',
      'inrule' => ',b',
      'uprule' => ',a,b,n',
      'filter' => '色戒,色即是空',
      'thesaurus' => '',
      'words' => '',
    ),
    'art' => 
    array (
      'status' => '0',
      'hits_start' => '1',
      'hits_end' => '888',
      'updown_start' => '1',
      'updown_end' => '888',
      'score' => '0',
      'pic' => '0',
      'tag' => '0',
      'psernd' => '0',
      'psesyn' => '0',
      'inrule' => ',b',
      'uprule' => ',a',
      'filter' => '无奈的人',
      'thesaurus' => '',
      'words' => '',
    ),
    'actor' => 
    array (
      'status' => '0',
      'hits_start' => '1',
      'hits_end' => '777',
      'updown_start' => '1',
      'updown_end' => '777',
      'score' => '0',
      'pic' => '0',
      'psernd' => '0',
      'psesyn' => '0',
      'inrule' => ',b',
      'uprule' => ',a',
      'filter' => '无奈的人',
      'thesaurus' => '',
      'words' => '',
    ),
  ),
  'api' => 
  array (
    'vod' => 
    array (
      'status' => '0',
      'charge' => '0',
      'pagesize' => '20',
      'imgurl' => 'http://img.maccms.com/',
      'typefilter' => 'and type_status=1',
      'datafilter' => '',
      'from' => '',
      'auth' => 'maccms.com#163.com',
    ),
    'art' => 
    array (
      'status' => '0',
      'charge' => '0',
      'pagesize' => '20',
      'imgurl' => 'http://img2.maccms.com/',
      'typefilter' => 'and type_status=1',
      'datafilter' => '',
      'auth' => 'qq.com#baidu.com',
    ),
  ),
  'connect' => 
  array (
    'qq' => 
    array (
      'status' => '0',
      'id' => '111',
      'key' => '222',
    ),
  ),
  'weixin' => 
  array (
    'status' => '0',
    'duijie' => 'wx.maccms.com',
    'sousuo' => 'wx.maccms.com',
    'token' => 'qweqwe',
    'guanzhu' => '欢迎关注',
    'wuziyuan' => '没找到资源，请更换关键词或等待更新',
    'wuziyuanlink' => 'demo.maccms.com',
    'bofang' => '1',
    'gjc1' => '关键词1',
    'gjcm1' => '长城',
    'gjci1' => 'http://img.aolusb.com/im/201610/2016101222371965996.jpg',
    'gjcl1' => 'http://www.loldytt.com/Dongzuodianying/CC/',
    'gjc2' => '关键词2',
    'gjcm2' => '生化危机6',
    'gjci2' => 'http://img.aolusb.com/im/201702/20172711214866248.jpg',
    'gjcl2' => 'http://www.loldytt.com/Kehuandianying/SHWJ6ZZ/',
    'gjc3' => '关键词3',
    'gjcm3' => '湄公河行动',
    'gjci3' => 'http://img.aolusb.com/im/201608/201681719561972362.jpg',
    'gjcl3' => 'http://www.loldytt.com/Dongzuodianying/GHXD/',
    'gjc4' => '关键词4',
    'gjcm4' => '王牌逗王牌',
    'gjci4' => 'http://img.aolusb.com/im/201601/201612723554344882.jpg',
    'gjcl4' => 'http://www.loldytt.com/Xijudianying/WPDWP/',
  ),
  'view' => 
  array (
    'index' => '0',
    'map' => '0',
    'search' => '0',
    'rss' => '0',
    'label' => '0',
    'vod_type' => '0',
    'vod_show' => '0',
    'art_type' => '0',
    'art_show' => '0',
    'topic_index' => '0',
    'topic_detail' => '0',
    'vod_detail' => '0',
    'vod_play' => '0',
    'vod_down' => '0',
    'art_detail' => '0',
  ),
  'path' => 
  array (
    'topic_index' => 'topic/index',
    'topic_detail' => 'topic/{id}/index',
    'vod_type' => 'vodtypehtml/{id}/index',
    'vod_detail' => '{type_pen}/{type_en}/{en}/detail',
    'vod_play' => 'vodplayhtml/{id}/index',
    'vod_down' => 'voddownhtml/{id}/index',
    'art_type' => 'arttypehtml/{id}/index',
    'art_detail' => 'arthtml/{id}/index',
    'page_sp' => '-',
    'suffix' => 'html',
  ),
  'rewrite' => 
  array (
    'route_status' => '0',
    'status' => '0',
    'vod_id' => '0',
    'art_id' => '0',
    'type_id' => '0',
    'topic_id' => '0',
    'route' => 'map   => map/index
rss   => rss/index

gbook-<page?>   => gbook/index
gbook=> gbook/index

topic-<page?>   => topic/index
topic  => topic/index
topicdetail-<id>   => topic/detail

actor-<page?>   => actor/index
actor=> actor/index
actordetail-<id>   => actor/detail

role-<page?>   => role/index
role=> role/index
roledetail-<id>   => role/detail

vodtype/<id>-<page?>   => vod/type
vodtype/<id>   => vod/type
voddetail-<id>   => vod/detail
vodrss-<id>   => vod/rss
vodplay/<id>-<sid>-<nid>   => vod/play
voddown/<id>-<sid>-<nid>   => vod/down
vodshow/<id>-<area?>-<by?>-<class?>-<lang?>-<letter?>-<level?>-<order?>-<page?>-<state?>-<tag?>-<year?>   => vod/show
vodsearch/<wd?>-<actor?>-<area?>-<by?>-<class?>-<director?>-<lang?>-<letter?>-<level?>-<order?>-<page?>-<state?>-<tag?>-<year?>   => vod/search


arttype/<id>-<page?>   => art/type
arttype/<id>   => art/type
artshow-<id>   => art/show
artdetail-<id>-<page?>   => art/detail
artdetail-<id>   => art/detail
artrss-<id>-<page>   => art/rss
artshow/<id>-<by?>-<class?>-<level?>-<letter?>-<order?>-<page?>-<tag?>   => art/show
artsearch/<wd?>-<by?>-<class?>-<level?>-<letter?>-<order?>-<page?>-<tag?>   => art/search

label-<file> => label/index',
  ),
  'email' => 
  array (
    'host' => '',
    'port' => '',
    'username' => '',
    'password' => '',
    'test' => '',
  ),
  'play' => 
  array (
    'width' => '0',
    'height' => '660',
    'widthmob' => '0',
    'heightmob' => '660',
    'widthpop' => '0',
    'heightpop' => '600',
    'second' => '5',
    'prestrain' => '//union.maccms.com/html/prestrain.html',
    'buffer' => '//union.maccms.com/html/buffer.html',
    'parse' => '//api.maccms.com/parse/?url=',
    'autofull' => '0',
    'showtop' => '1',
    'showlist' => '1',
    'flag' => '0',
    'colors' => '000000,F6F6F6,F6F6F6,333333,666666,FFFFF,FF0000,2c2c2c,ffffff,a3a3a3,2c2c2c,adadad,adadad,48486c,fcfcfc',
  ),
);
?>