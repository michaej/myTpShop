<?php
/**
 * Created by 七月
 * Author: 七月
 * 微信公号: 小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/28
 * Time: 18:12
 */

namespace app\api\service;


use app\api\model\Order;
use app\api\model\Integral;
use app\api\model\UseCount;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
//use app\lib\order\OrderStatus;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

//Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

//Loader::import('WxPay.WxPay', EXTEND_PATH, '.Data.php');


class WxNotify
{

    public function NotifyProcess($data)
    {
//        $data = $this->data;
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = Order::where('order_no', '=', $orderNo)->lock(true)->find();
                if ($order->status == 1) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($order->user_id,$order->count);
                    $this->addIntegral($order->user_id,$order->integral);

                }
                Db::commit();
            } catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                // 如果出现异常，向微信返回false，请求重新发送通知
                return false;
            }
        }
        return true;
    }


    private function reduceStock($uid,$count)
    {
            UseCount::where('user_id', '=', $uid)
                ->setInc('use_number',$count );
    }
    private function addIntegral($uid,$count)
    {
        Integral::where('user_id', '=', $uid)
            ->setInc('integral_count',$count );
    }

    private function updateOrderStatus($orderID, $success)
    {
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        Order::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }
}