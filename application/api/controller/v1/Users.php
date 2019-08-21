<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2017/9/14
 * Time: 23:52
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\ThirdApp;
use app\api\model\User;
use app\api\model\UserThirdApp;
use app\api\model\Order;
use app\api\model\Product;
use app\lib\exception\MissException;
use think\response\Json;


class Users extends BaseController
{

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getAllUser()
    {

        $userAddress = User::getAllUser();
        return $userAddress;

    }


    /**新增用户管理账号
     * @param string $username
     * @param string $password
     */

    public function addAdminUser($username = '', $password = '')
    {

        if (empty($username)) {
            throw new MissException([
                'msg' => '用户名不能为空',
                'errorCode' => 50000
            ]);
        }
        if (empty($password)) {
            throw new MissException([
                'msg' => '密码不能为空',
                'errorCode' => 50000
            ]);
        }
        $user = new ThirdApp();
        $ison = $user->checkUser($username);
        if (!empty($ison)) {
           return [
                'msg' => '用户已经存在',
                'Code' => 0
            ];
        } else {
            $user->app_id = $username;
            $user->app_secret = $password;
            $user->app_description = 'CMS';
            $user->scope = '32';
            $user->save();
            return [
                'msg' => '注册成功',
                'Code' => 1
            ];
        }


    }

    public function getJson()
    {
//        $gift_code_str = preg_replace('/((\s)*(\n)+(\s)*) /i ',',',file_get_contents('api.json'));
        $str = file_get_contents('api.json');
        return $str;

    }


}