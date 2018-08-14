<?php
/**
  * wechat php test
  */
//此为新视界提供修改，可随意删除
//必读：修改完毕请进行转码设置需要转为UTF-8编码notepad++转码方式编码--转为UTF-8编码格式或者更改之前进行设置，方式为编码--以UTF-8格式编码
//define your token
//weixinabc是一个token,是一个令牌
define("TOKEN", "chendada");//token需要与微信后台保持一致
$wechatObj = new wechatCallbackapiTest();

$wechatObj->responseMsg();
//$wechatObj->valid();
//exit;

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
												$contentStr = "可更改，自由发挥
1.电影目录请输入【1】 
 
2.电视目录请输入【2】 
 
3.动漫目录请输入【3】 
 
4.伦理目录请输入【4】 
 
5.加群交流请输入【5】";


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
 
									$con = mysqli_connect("127.0.0.1:3306","vip_95uk_com","YTHXt65AFa");	//修改数据库数据							
									mysqli_query("SET NAMES UTF8");
									mysqli_query("set character_set_client=utf8"); 
									mysqli_query("set character_set_results=utf8");
									mysqli_select_db("vip_95uk_com", $con);//修改数据库数据
									$sql = "SELECT * FROM `mac_vod` WHERE `d_name` like '%".$keyword."%'  LIMIT 0 , 10";

									$result = mysqli_query($sql);
									$itemCount = 0;
								if(mysqli_num_rows($result)>0){
								while($row = mysqli_fetch_assoc($result))
								{

									$title = "".$row['d_name']."";
									$des ="";
									//下面为内容页
									$url ="http://www.q2017.com/?m=vod-detail-id-".$row['d_id'].".html";//后面为不可更改?m=vod-detail-id-为网站内容页地址d_id为网站内容页id不可更改
									$picUrl1 ="内容页图片地址".$row['v_pic']."";//v_pic为图片代码不可更改
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
										$title = '不好意思，没找到哟，请留言！';
										
										$des1 ="";
										
										$picUrl1 ="图片代码";//为搜索返回图片代码
										
										$url="http://www.q2017.com/index.php?m=vod-search";//此为你网站的搜索页地址，一般不需要更改

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
 						if($keyword=="1")
						{
										$title = '电影目录：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="图片地址";
										//跳转链接
										$url="http://www.q2017.com/?m=vod-type-id-1.html";//此为分类目录地址

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="2")
						{
										$title = '电视目录：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="图片地址";
										//跳转链接
										$url="http://www.q2017.com/?m=vod-type-id-2.html";//此为分类目录地址

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="3")
						{
										$title = '动漫目录：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="图片地址";
										//跳转链接
										$url="http://www.q2017.com/?m=vod-type-id-4.html";//此为分类目录地址

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="4")
						{
										$title = '午夜大片：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="图片地址";
										//跳转链接
										$url="http://www.q2017.com/?m=vod-type-id-16.html";//此为分类目录地址

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="5")
						{
										$title = '加群留言：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="图片地址";
										//跳转链接
										$url="此为跳转链接";//可随意更改

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
												$contentStr = "可以更改回复消息显示";//更改可随意


							$msgType = 'text';
							$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $resultStr;
						}					
						
						
						break;
					default:
						break;
				}						

        }else {
        	echo "随意更改111111111";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
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