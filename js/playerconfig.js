var mac_flag=1;         //播放器版本
var mac_second=5;       //播放前预加载广告时间 1000表示1秒
var mac_width=0;      //播放器宽度0自适应
var mac_height=580;     //播放器高度
var mac_widthmob=0;      //手机播放器宽度0自适应
var mac_heightmob=400;     //手机播放器高度
var mac_widthpop=704;   //弹窗窗口宽度
var mac_heightpop=566;  //弹窗窗口高度
var mac_showtop=1;     //美化版播放器是否显示头部
var mac_showlist=1;     //美化版播放器是否显示列表
var mac_autofull=0;     //是否自动全屏,0否,1是
var mac_buffer="//union.maccms.net/html/buffer.html";     //缓冲广告地址
var mac_prestrain="//union.maccms.net/html/prestrain.html";  //预加载提示地址
var mac_colors="000000,F6F6F6,F6F6F6,333333,666666,FFFFF,FF0000,2c2c2c,ffffff,a3a3a3,2c2c2c,adadad,adadad,48486c,fcfcfc";   //背景色，文字颜色，链接颜色，分组标题背景色，分组标题颜色，当前分组标题颜色，当前集数颜色，集数列表滚动条凸出部分的颜色，滚动条上下按钮上三角箭头的颜色，滚动条的背景颜色，滚动条空白部分的颜色，滚动条立体滚动条阴影的颜色 ，滚动条亮边的颜色，滚动条强阴影的颜色，滚动条的基本颜色
var mac_show={},mac_show_server={}; //播放器名称,服务器组地址
//缓存开始
mac_show["mgtv"]="芒果视频";mac_show["27pan"]="27盘";mac_show["pptv"]="pptv";mac_show["letv"]="乐视视频";mac_show["qiyi"]="奇艺视频";mac_show["sohu"]="搜狐视频";mac_show["qq"]="腾讯视频";mac_show["youku"]="优酷视频";mac_show_server["webplay"]="maccmsc.com";
//缓存结束