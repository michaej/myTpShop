<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2018/1/12
 * Time: 19:53
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\PriceList as mPriceList;


class PriceList extends BaseController
{
    public function getPriceList(){
        $priceList = mPriceList::all();
        return $priceList;


    }



}