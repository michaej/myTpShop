<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/18
 * Time: 00:31
 */

namespace app\api\controller\v1;

use app\api\service\Integral as sIntegral;
use app\api\validate\ProductBePositiveInt;


class Integral
{

    /**
     * @param $product_id
     * @param $id    $id = 1 积分兑换  $id = 2 通关兑换；
     * @return array
     */
    public function extchange($product_id ,$id)
    {
        $validate = new ProductBePositiveInt();
        $validate->goCheck();

        if ($id == 1){
            return  sIntegral::placeIntOrder($product_id);
        }else if ($id == 2){
            return  sIntegral::placePassOrder($product_id);
        }



    }



}