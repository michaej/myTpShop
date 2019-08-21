<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/14
 * Time: 10:20
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\service\UseCount;

class share
{
    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function getShareInfo($encryptedData, $iv,$code)
    {
        $sessionkey = UserToken::getSessionKey($code);

        $aesKey = base64_decode($sessionkey);
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if(count($dataObj)>0){
            UseCount::IncCount();
            return ['msg' => '分享成功，跳转机会+1','code'=>'success'];


        }else{
            return ['msg' => '分享失败','code'=>'false'];
        }

    }

}