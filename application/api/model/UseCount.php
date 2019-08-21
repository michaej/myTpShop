<?php

namespace app\api\model;

use think\Model;

class UseCount extends BaseModel
{

    protected $hidden =['create_time',"update_time","delete_time"];

    public function user()
    {
        return $this->hasMany('User', 'id', 'user_id');
    }

    public function integral()
    {
        return $this->hasMany('Integral', 'user_id', 'user_id');
    }

    public static function getPassCount($count)
    {
        $passdCount = self::limit($count)
            ->order('pass_number desc')
            ->with("user")
            ->select();
        $oldCount = self::limit($count)
            ->order('old_number desc')
            ->with("user")
            ->select();
        return [
            "passList"=>$passdCount,
            "oldList"=>$oldCount
        ];
    }

    public static  function getUseCount($uid){

        $count = self::with("integral")
        ->where(['user_id' => $uid])->find();
        return $count;

    }

}
