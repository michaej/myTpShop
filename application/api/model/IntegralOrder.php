<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/18
 * Time: 00:55
 */

namespace app\api\model;


class IntegralOrder extends BaseModel
{

    public function product()
    {
        return $this->hasMany('Product', 'id', 'product_id');
    }


    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {
        $pagingData = self::with('product')
            ->where('user_id', '=', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }


}