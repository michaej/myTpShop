<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2017/9/21
 * Time: 10:02
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;


class Upload extends BaseController
{

    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'upload'],
        'checkSuperScope' => ['only' => 'upload']
    ];
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'images');
            if ($info) {
                echo $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}