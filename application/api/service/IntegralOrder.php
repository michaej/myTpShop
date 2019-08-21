<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/18
 * Time: 15:27
 */

namespace app\api\service;

use think\Db;
use think\Exception;
use app\api\model\IntegralOrder as mIntegralOrder;

class IntegralOrder
{

    // 创建订单时没有预扣除库存量，简化处理
    // 如果预扣除了库存量需要队列支持，且需要使用锁机制
    public static function createOrder($orderNo,$uid,$product_id)
    {
        Db::startTrans();
        try {

            mIntegralOrder::create(
                [
                    'user_id' => $uid,
                    'product_id' => $product_id,
                    'order_no'=>$orderNo
                ]);
            Db::commit();
            return true ;
        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }

}