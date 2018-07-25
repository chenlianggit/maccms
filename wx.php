<?php
define("TOKEN", "chendada");//自己定义的token 就是个通信的私钥
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();    //验证  初次对接时不能注释掉 否则不能通过
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }


    public function responseMsg()
    {

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


        if (!empty($postStr)){

            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);

            $event = $postObj->Event;
            $time = time();
            $textTpl = "<xml>
                                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                                        <CreateTime>%s</CreateTime>
                                                        <MsgType><![CDATA[%s]]></MsgType>
                                                        <Content><![CDATA[%s]]></Content>
                                                        <FuncFlag>0</FuncFlag>
                                                        </xml>";



            switch($postObj->MsgType)
            {
                case 'event':

                    if($event == 'subscribe')
                    {
                        //关注后的回复
                        $contentStr = "谢谢关注[寻片]公众号！本公众号提供各种资源搜索。输入电影名，我将回复你电影观看或下载地址。例：【速度与激情】
";


                        $msgType = 'text';
                        $textTpl = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $textTpl;

                    }
                    break;
                case 'text':
                    if(preg_match('/[\x{4e00}-\x{9fa5}]+/u',$keyword))
                    {

                        $newsTplHeader = "<xml>
                                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                                        <CreateTime>%s</CreateTime>
                                                        <MsgType><![CDATA[news]]></MsgType>
                                                        <ArticleCount>%s</ArticleCount>
                                                        <Articles>";

                        $newsTplItem = "<item>
                                                        <Title><![CDATA[%s]]></Title> 
                                                        <Description><![CDATA[%s]]></Description>
                                                        <PicUrl><![CDATA[%s]]></PicUrl>
                                                        <Url><![CDATA[%s]]></Url>
                                                        </item>";
                        $newsTplFooter="</Articles>
                                                        </xml>";

                        $con = mysqli_connect("127.0.0.1:3306","vip_95uk_com","YTHXt65AFa");
                        mysqli_query("SET NAMES UTF8");
                        mysqli_query("set character_set_client=utf8");
                        mysqli_query("set character_set_results=utf8");
                        mysqli_select_db("vip_95uk_com", $con);
                        $sql = "SELECT * FROM `phome_ecms_news` WHERE `title` like '%".$keyword."%'  LIMIT 0 , 1";

                        $result = mysqli_query($sql);
                        $itemCount = 0;
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result))
                            {

                                $title = "".$row['title']."";
                                $des ="";
                                $url ="http://vip.ty2050.com".$row['titleurl'];
                                $picUrl1 ="http://vip.ty2050.com".$row['titlepic']."";
                                $contentStr .= sprintf($newsTplItem, $title, $des, $picUrl1, $url);
                                ++$itemCount;
                            }
                            $newsTplHeader = sprintf($newsTplHeader, $fromUsername, $toUsername, $time, $itemCount);
                            $resultStr =  $newsTplHeader. $contentStr. $newsTplFooter;
                            echo $resultStr;
                        }
                        else
                        {
                            $newsTpl = "<xml>
                                                                                <ToUserName><![CDATA[%s]]></ToUserName>
                                                                                <FromUserName><![CDATA[%s]]></FromUserName>
                                                                                <CreateTime>%s</CreateTime>
                                                                                <MsgType><![CDATA[news]]></MsgType>
                                                                                <ArticleCount>1</ArticleCount>
                                                                                <Articles>
                                                                                <item>
                                                                                <Title><![CDATA[%s]]></Title> 
                                                                                <Description><![CDATA[%s]]></Description>
                                                                                <PicUrl><![CDATA[%s]]></PicUrl>
                                                                                <Url><![CDATA[%s]]></Url>
                                                                                </item>                                                        
                                                                                </Articles>
                                                                                </xml>";

                            //没有查找到的时候的回复
                            $title = '未找到结果，请确认有无错别字，或尝试精简搜索字（如您回复的是【速度与激情10】，请尝试【速度与激情】），此外，依次点击两次右上角，选择推荐给朋友，此后，你将可享受神秘特权哦！';

                            $des1 ="";

                            $picUrl1 ="http://vip.ty2050.com/d/weizhaodao.jpg";

                            $url="http://vip.ty2050.com/misc/message/";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;

                        }
                        mysqli_close($con);

                    }
                    else
                    {
                        $newsTpl = "<xml>
                                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                                        <CreateTime>%s</CreateTime>
                                                        <MsgType><![CDATA[news]]></MsgType>
                                                        <ArticleCount>1</ArticleCount>
                                                        <Articles>
                                                        <item>
                                                        <Title><![CDATA[%s]]></Title> 
                                                        <Description><![CDATA[%s]]></Description>
                                                        <PicUrl><![CDATA[%s]]></PicUrl>
                                                        <Url><![CDATA[%s]]></Url>
                                                        </item>                                                        
                                                        </Articles>
                                                        </xml>";
                        if($keyword=="help")
                        {
                            $title = '输入你要找的电影名,或点击进入寻片网主页';

                            $des1 ="";
                            //图片地址
                            $picUrl1 ="http://vip.ty2050.com/help.jpg";
                            //跳转链接
                            $url="http://vip.ty2050.com";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;
                        }
                        if($keyword=="菜单")
                        {
                            $title = '点击进入主页，右边 点击 菜单图标 “三” 选择你想看的栏目';

                            $des1 ="";
                            //图片地址
                            $picUrl1 ="http://vip.ty2050.com/help.jpg";
                            //跳转链接
                            $url="http://vip.ty2050.com";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;
                        }
                        if($keyword=="联系客服")
                        {
                            $title = '联系客服';

                            $des1 ="";
                            //图片地址
                            $picUrl1 ="http://vip.ty2050.com/d/help.jpg";
                            //跳转链接
                            $url="http://vip.ty2050.com/misc/message/";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;
                        }
                        if($keyword=="自助更新")
                        {
                            $title = '自助更新入口';

                            $des1 ="";
                            //图片地址
                            $picUrl1 ="http://vip.ty2050.com/d/help.jpg";
                            //跳转链接
                            $url="http://vip.ty2050.com/misc/message/";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;
                        }
                        if($keyword=="留言")
                        {
                            $title = '看片留言：点击进入';

                            $des1 ="";
                            //图片地址
                            $picUrl1 ="http://vip.ty2050.com/d/help.jpg";
                            //跳转链接
                            $url="http://vip.ty2050.com/misc/message/";

                            $resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;

                            echo $resultStr;
                        }
                        $contentStr = "\r\n 输入电影名如：我不是药神 如果没有具体想看的，请点击进入主页：vip.ty2050.com";


                        $msgType = 'text';
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }


                    break;
                default:
                    break;
            }

        }else {
            echo "你好！欢迎进微信公众号";
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token =TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>