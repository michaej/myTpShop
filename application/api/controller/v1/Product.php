<?php
/**
 * Created by 七月.
 * User: 七月
 * Date: 2017/2/15
 * Time: 1:00
 */

namespace app\api\controller\v1;

use app\api\model\Image;
use app\api\model\Product as ProductModel;
use app\api\model\ProductImage;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\lib\exception\ParameterException;
use app\lib\exception\ProductException;
use app\lib\exception\ThemeException;
use app\api\controller\BaseController;
use app\lib\exception\MissException;

class Product extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'editproduct,editMorePic,addNewProduct'],
        'checkSuperScope' => ['only' => 'editproduct,editMorePic,addNewProduct']
    ];

    /**
     * 根据类目ID获取该类目下所有商品(分页）
     * @url /product?id=:category_id&page=:page&size=:page_size
     * @param int $id 商品id
     * @param int $page 分页页数（可选)
     * @param int $size 每页数目(可选)
     * @return array of Product
     * @throws ParameterException
     */
    public function getByCategory($id = -1, $page = 1, $size = 30)
    {
        (new IDMustBePositiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $pagingProducts = ProductModel::getProductsByCategoryID(
            $id, true, $page, $size);
        if ($pagingProducts->isEmpty()) {
            // 对于分页最好不要抛出MissException，客户端并不好处理
            return [
                'current_page' => $pagingProducts->currentPage(),
                'data' => []
            ];
        }
        //数据集对象和普通的二维数组在使用上的一个最大的区别就是数据是否为空的判断，
        //二维数组的数据集判断数据为空直接使用empty
        //collection的判空使用 $collection->isEmpty()

        // 控制器很重的一个作用是修剪返回到客户端的结果

        //        $t = collection($products);
        //        $cutProducts = collection($products)
        //            ->visible(['id', 'name', 'img'])
        //            ->toArray();

//        $collection = collection($pagingProducts->items());
        $data = $pagingProducts
            ->hidden(['summary'])
            ->toArray();
        // 如果是简洁分页模式，直接序列化$pagingProducts这个Paginator对象会报错
        //        $pagingProducts->data = $data;
        return [
            'current_page' => $pagingProducts->currentPage(),
            'total' => $pagingProducts->total(),
            'data' => $data
        ];
    }

    /**
     * 获取某分类下全部商品(不分页）
     * @url /product/all?id=:category_id
     * @param int $id 分类id号
     * @return \think\Paginator
     * @throws ThemeException
     */
    public function getAllInCategory($id = -1)
    {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID(
            $id, false);
        if ($products->isEmpty()) {
            throw new ThemeException();
        }
        $data = $products
            ->hidden(['summary'])
            ->toArray();
        return $data;
    }

    /**
     * 获取某分类下全部商品(不分页）
     * @url /product/all?id=:category_id
     * @param int $id 分类id号
     * @return \think\Paginator
     * @throws ThemeException
     */
    public function getAllInProduct( $page_size , $page_no)
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');


        $products = ProductModel::getAllInProduct(  $page_size , $page_no);
        if ($products->isEmpty()) {
            throw new MissException([
                'msg' => '暂无数据',
                'errorCode' => 200
            ]);
        }
        $data = $products
            ->hidden(['summary'])
            ->toArray();
        return $products;
    }

    /**
     * 获取指定数量的最近商品
     * @url /product/recent?count=:count
     * @param int $count
     * @return mixed
     * @throws ParameterException
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {

        }
        $products = $products->hidden(
            [
                'summary'
            ])
            ->toArray();
        return $products;
    }

    /**
     * 获取商品详情
     * 如果商品详情信息很多，需要考虑分多个接口分布加载
     * @url /product/:id
     * @param int $id 商品id号
     * @return Product
     * @throws ProductException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }
        return $product;
    }

    /**
     * 后台获取商品详情
     */
    public function getThirdOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getThirdProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }
        return $product;
    }




    public function deletepro($id){
       $res  =  ProductModel::destroy($id);
    }

    /**
     * 修改商品信息
     */

    public function editproduct($id, $name, $price, $stock,$category_id, $unit, $main_img_url)
    {
        $product = new ProductModel();
        if (!empty($main_img_url)) {
            $res = $product->where('id', $id)->update([
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'unit' => $unit,
                'category_id'=>$category_id,
                'main_img_url' => $main_img_url
            ]);
        } else {
            $res = $product->where('id', $id)->update([
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'unit' => $unit,
                'category_id'=>$category_id
            ]);
            return $res;

        }
    }

    /**
     * 修改商品信息
     */

    public function editMorePic($url, $order, $product_id)
    {
        $image = new Image();
        $image->url = $url;
        $image->save();
        $productimg = new ProductImage();
        $productimg->img_id = $image->id;
        $productimg->order = $order;
        $productimg->product_id = $product_id;
        $res = $productimg->save();
        return $res;

    }

    /**
     * 修改商品信息
     */

    public function addNewProduct($name, $price, $stock, $unit,$category_id, $main_img_url, $image_list)
    {
        $product = new ProductModel();
        $product->name = $name;
        $product->price = $price;
        $product->stock = $stock;
        $product->unit = $unit;
        $product->category_id = $category_id;
        $product->main_img_url = $main_img_url;
        $res = $product->save();
        $id = $product->id;


        for ($i=0; $i<count($image_list); $i++) {
            $image = new Image();
            $image->url = $image_list[$i];
            $image->save();
            $productimg = new ProductImage();
            $productimg->img_id = $image->id;
//            $productimg->order = $order;
            $productimg->product_id = $id;
            $res = $productimg->save();

        }
        echo $res;

    }


}