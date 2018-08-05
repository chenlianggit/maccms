<?php
header("Content-type:text/html;charset=utf-8"); 
$data = file_get_contents('php://input'); 


function post_ren_test($url,$data){
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl); 
        if (curl_errno($curl)) {
            echo '错误信息;'.curl_error($curl);
        }
        curl_close($curl); 
        return $result; 
    }
     
    $url="http://www.i97wan.cn/ppyun/ppapi/url.php";
    //提交数据
    $data= $data;
     
    $content =   post_ren_test($url,$data);
	preg_match('#webcfg\s*=\s*{"id":(\d+),#',$content,$vids);
    echo $content;
//print_r(urldecode($data)); 
?>
