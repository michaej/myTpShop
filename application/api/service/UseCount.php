<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/14
 * Time: 10:38
 */

namespace app\api\service;


use app\api\model\UseCount as mUsecount;

class UseCount
{


    public static function decCount()
    {
        $uid = Token::getCurrentUid();
        $count = mUsecount::where(['user_id' => $uid])->find();
        if ($count['use_number'] > 0) {
            mUsecount::where('user_id', '=', $uid)
                ->setDec('use_number', '1');
        }

        mUsecount::where('user_id', '=', $uid)
            ->setInc('old_number', '1');



    }

    public static function IncCount()
    {
        $uid = Token::getCurrentUid();
        $count = mUsecount::where(['user_id' => $uid])->find();
        mUsecount::where('user_id', '=', $uid)
            ->setInc('use_number', '1');

    }

    public static function passCount()
    {
        $uid = Token::getCurrentUid();
        $count = mUsecount::where(['user_id' => $uid])->find();
        mUsecount::where('user_id', '=', $uid)
            ->setInc('pass_number', '1');

    }


    public static function StopCount($number)
    {
        $uid = Token::getCurrentUid();
        $count = mUsecount::where(['user_id' => $uid])->find();
        if ($number >$count['stop_number']){
            return mUsecount::where('user_id', '=', $uid)->update(['stop_number'=>$number]);


        }

    }



}