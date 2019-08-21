<?php
namespace app\api\controller\v1;
/**
 *
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/12
 * Time: 16:40
 */
use app\api\controller\BaseController;
use app\api\model\User;
use app\api\service\Token;
use app\api\model\UseCount as mUsecount;
use app\api\service\UseCount as sUsecount;
use app\api\validate\IDMustBePositiveInt;


class UseCount extends BaseController
{

    public function getUserCount()
    {
        $uid = Token::getCurrentUid();
        $count = mUsecount::getUseCount($uid);
        return $count;
    }

    public function decCount(){
       return sUsecount::decCount();


    }

    public function IncCount(){
        return sUsecount::IncCount();
    }

    public function passCount(){
        return sUsecount::passCount();
    }
    public function getPassCount($id){
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        return mUsecount::getPassCount($id);
    }


    public function stopNumber($id){
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        return sUsecount::StopCount($id);
    }

    public function getUseCount(){

        $uid = Token::getCurrentUid();
        return  mUsecount::getUseCount($uid);
    }

}