<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/18
 * Time: 15:35
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\exception\UserException;
use app\api\model\Integral as mIntegral;
use app\api\model\UseCount;


class Integral
{

    public static function placeIntOrder($product_id)
    {
        $uid = Token::getCurrentUid();
        $integral = mIntegral::where(['user_id' => $uid])->find();
        $Product = Product::where(["id" => $product_id])->find();
        if (!$integral || $integral['integral_count'] < $Product['integral']) {
            throw new UserException([
                'msg' => '积分不足',
                'errorCode' => 90001
            ]);
        }

        $orderNo = Order::makeOrderNo();
        $create = IntegralOrder::createOrder($orderNo, $uid, $product_id);
        if ($create) {
            $value = mIntegral::where('user_id', '=', $uid)
                ->setDec('integral_count', $Product['integral']);
            if ($value == 1) {
                return [
                    'msg' => '兑换成功',
                    'errorCode' => 0
                ];
            }else{
                return [
                    'msg' => '兑换失败',
                    'errorCode' => 90002
                ];
            }
        }


    }

    public static function placePassOrder($product_id)
    {
        $uid = Token::getCurrentUid();
        $Pass = UseCount::where(['user_id' => $uid])->find();
        $Product = Product::where(["id" => $product_id])->find();
        if (!$Pass || $Pass['pass_number'] < 1) {
            throw new UserException([
                'msg' => '未通关',
                'errorCode' => 90001
            ]);
        }

        $orderNo = Order::makeOrderNo();
        $create = IntegralOrder::createOrder($orderNo, $uid, $product_id);
        if ($create) {
            $value = UseCount::where('user_id', '=', $uid)
                ->setDec('pass_number', 1);
            if ($value == 1) {
                return [
                    'msg' => '兑换成功',
                    'errorCode' => 0
                ];
            }else{
                return [
                    'msg' => '兑换失败',
                    'errorCode' => 90002
                ];
            }
        }


    }

}