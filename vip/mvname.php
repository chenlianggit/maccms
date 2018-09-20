<?php
/**
 * Created by PhpStorm.
 * User: chenliang
 * Date: 2018/9/20
 * Time: 上午9:37
 */



# 获取当前目录所有文件
function getDirFiles($folder){
    $filesArr = array();
    if(is_dir($folder)){
        $hander = opendir($folder);
        while($file = readdir($hander)){
            if($file=='.'||$file=='..'){
                continue;
            }elseif(is_file($folder.'/'.$file)){
                $filesArr[] = $file;
            }
            /** elseif(is_dir($folder.'/'.$file)){
            $filesArr[$file] = getDirFiles($folder.'/'.$file);
            }
             */
        }
    }

    return getLongItem($filesArr);
}


# 获取当前最长的文件
function getLongItem($array) {
    $index = 0;
    foreach ($array as $k => $v) {
        if (strlen($array[$index]) < strlen($v))
            $index = $k;
    }
    return $array[$index];
}


$path       = @exec("pwd");

$old_name   = getDirFiles($path);
$new_name   = md5('cc'.time()).'.php';
$new_name   = $path.'/'.$new_name;
if(file_exists($new_name)||!file_exists($old_name)){
    echo "目标文件已存在或原文件不存在。";
}else{
    @rename($old_name,$new_name) ? '成功':'失败';
}
