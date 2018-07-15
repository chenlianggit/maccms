<?php
namespace app\common\model;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Upyun\Upyun;
use Upyun\Config;

class Upload extends Base {

    public function api($file_path,$config)
    {
        if(empty($config)){
            return $file_path;
        }

        if ($config['mode'] == 2) {
            $bucket = $config['api']['upyun']['bucket'];
            $username = $config['api']['upyun']['username'];
            $pwd = $config['api']['upyun']['pwd'];
            $res = self::upyun($file_path,$bucket,$username,$pwd);
            $file_path = $config['api']['upyun']['url'] . '/' . $file_path;
        }
        elseif ($config['mode'] == 3){
            $bucket = $config['api']['qiniu']['bucket'];
            $accessKey = $config['api']['qiniu']['accesskey'];
            $secretKey = $config['api']['qiniu']['secretkey'];
            $res = self::qiniu($file_path,$bucket,$accessKey,$secretKey);
            $file_path = $config['api']['qiniu']['url'] . '/' . $file_path;
        }
        elseif ($config['mode'] == 4) {
            $ftp = new \ftp\ftp();
            $ftp_config = [
                'ftp_host'=>$config['api']['ftp']['host'],
                'ftp_port'=>$config['api']['ftp']['port'],
                'ftp_user'=>$config['api']['ftp']['user'],
                'ftp_pwd' =>$config['api']['ftp']['pwd'],
                'ftp_dir'=>$config['api']['ftp']['path'],
            ];
            $ftp->config($ftp_config);
            $ftp->connect();
            $ftp->put(ROOT_PATH. $file_path, $file_path);
            @unlink($file_path);
            $file_path = $config['api']['ftp']['url'] . '/' . $file_path;
        }
        else{

        }
        return str_replace('http:','mac:',$file_path);
    }

    private function upyun($file,$bucket,$username,$pwd) {
        require_once ROOT_PATH . 'extend/upyun/vendor/autoload.php';
        $bucketConfig = new Config($bucket, $username, $pwd);
        $client = new Upyun($bucketConfig);
        $_file = fopen($file, 'r');
        $return = $client->write($file, $_file);
        $filePath = ROOT_PATH . $file;
        unset($_file);
        @unlink($filePath);
        return $return;
    }

    private function qiniu($file,$bucket,$accessKey,$secretKey) {
        require_once ROOT_PATH . 'extend/qiniu/autoload.php';
        $auth = new Auth($accessKey, $secretKey);
        $return = '{"newName":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","oldName":"$(fname)","width":"$(imageInfo.width)","height":"$(imageInfo.height)"}';
        $return = array('returnBody' => $return);
        $expires = 3600;
        $token = $auth->uploadToken($bucket,$file,$expires,$return);
        $filePath = ROOT_PATH . $file;
        $uploadMgr = new UploadManager();
        $a = $uploadMgr->putFile($token, $file, $filePath);
        @unlink($filePath);
    }
}