<?php

namespace app\api\model;

use think\Model;

class PriceList extends BaseModel
{
    protected $hidden = [ 'delete_time', 'create_time', 'update_time'];

}
