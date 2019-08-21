<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

class ProductBePositiveInt extends BaseValidate
{
    protected $rule = [
        'product_id' => 'require|isPositiveInteger',
        'id' =>'require|isPositiveInteger'
    ];
}
