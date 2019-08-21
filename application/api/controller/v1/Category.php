<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/19
 * Time: 11:28
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\api\model\Image;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\MissException;
use think\Controller;

class Category extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'deletCategory,editCategory'],
        'checkSuperScope' => ['only' => 'deletCategory,editCategory']
    ];

    /**
     * 获取全部类目列表，但不包含类目下的商品
     * Request 演示依赖注入Request对象
     * @url /category/all
     * @return array of Categories
     * @throws MissException
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');
        if (empty($categories)) {
            throw new MissException([
                'msg' => '还没有任何类目',
                'errorCode' => 50000
            ]);
        }
        return $categories;
    }


    /**
     * 这里没有返回类目的关联属性比如类目图片
     * 只返回了类目基本属性和类目下的所有商品
     * 返回什么，返回多少应该根据团队情况来考虑
     * 为了接口通用性可以返回大量的无用数据
     * 也可以只返回客户端需要的数据，但这会造成有大量重复接口
     * 接口应当和业务绑定还是和实体绑定需要团队自己抉择
     * 此接口主要是为了返回分类下面的products，请对比products中的
     * 接口，这是一种不好的接口设计
     * @url /category/:id/products
     * @return Category single
     * @throws MissException
     */
    public function getCategory($id)
    {
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        $category = CategoryModel::getCategory($id);
        if (empty($category)) {
            throw new MissException([
                'msg' => 'category not found'
            ]);
        }
        return $category;
    }

    /**
     * @param $id
     * @param $name
     * @param $topic_img_id
     * @param $isnew 0 新增  1 修改
     * 修改分类商品
     *
     *
     */
    public static function editCategory($id, $name, $topic_img_id, $isnew)
    {

        //图片url等于空时
        if (!empty($topic_img_id)) {
            $image = new Image();
            $image->url = $topic_img_id;
            $backId = $image->save();
            $imgId = $image->id;
            if ($backId == 1 && $isnew == 1) {
                $CategoryModel = new CategoryModel;
                $res = $CategoryModel->save([
                    'name' => $name,
                    'topic_img_id' => $imgId
                ], ['id' => $id]);
                if ($res == 1) {
                    echo $res;
                }
            } else if ($backId == 1 && $isnew == 0) {
                $CategoryModel = new CategoryModel;
                $res = $CategoryModel->save([
                    'name' => $name,
                    'topic_img_id' => $imgId
                ]);
                if ($res == 1) {
                    echo $res;
                }
            } else {
                echo '保存失败';
            }
        } else {
            $CategoryModel = new CategoryModel;
            $res = $CategoryModel->save([
                'name' => $name,
            ], ['id' => $id]);
            if ($res == 1) {
                echo $res;
            }
        }
    }

    /*
     * 删除分类
     */
    public function deletCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $CategoryModel = new CategoryModel;
        $res = $CategoryModel->destroy($id);
        if($res == 1){
            $result = [
                'msg'  => '修改成功',
                'code' => '1',
            ];
            return json($result);

        }else{

        }

    }
}