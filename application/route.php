<?php
/**
 * 路由注册
 *
 * 以下代码为了尽量简单，没有使用路由分组
 * 实际上，使用路由分组可以简化定义
 * 并在一定程度上提高路由匹配的效率
 */

// 写完代码后对着路由表看，能否不看注释就知道这个接口的意义
use think\Route;

//Sample
Route::get('api/:version/sample/:key', 'api/:version.Sample/getSample');
Route::post('api/:version/sample/test3', 'api/:version.Sample/test3');
Route::post('api/:version/getShareInfo', 'api/:version.Share/getShareInfo');

//Miss 404
//Miss 路由开启后，默认的普通模式也将无法访问
//Route::miss('api/v1.Miss/miss');

//Banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');


Route::get('api/:version/getUseCount', 'api/:version.UseCount/getUserCount');
Route::get('api/:version/decCount', 'api/:version.UseCount/decCount');
Route::get('api/:version/getUseCount', 'api/:version.UseCount/getUseCount');
Route::get('api/:version/priceList', 'api/:version.PriceList/getPriceList');


//Theme
// 如果要使用分组路由，建议使用闭包的方式，数组的方式不允许有同名的key
//Route::group('api/:version/theme',[
//    '' => ['api/:version.Theme/getThemes'],
//    ':t_id/product/:p_id' => ['api/:version.Theme/addThemeProduct'],
//    ':t_id/product/:p_id' => ['api/:version.Theme/addThemeProduct']
//]);

Route::group('api/:version/theme', function () {
    Route::get('', 'api/:version.Theme/getSimpleList');
    Route::get('/:id', 'api/:version.Theme/getComplexOne');
    Route::get('/getAllTheme/all', 'api/:version.Theme/getAllTheme');
    Route::post(':t_id/product/:p_id', 'api/:version.Theme/addThemeProduct');
    Route::delete(':t_id/product/:p_id', 'api/:version.Theme/deleteThemeProduct');
});

//Route::get('api/:version/theme', 'api/:version.Theme/getThemes');
//Route::post('api/:version/theme/:t_id/product/:p_id', 'api/:version.Theme/addThemeProduct');
//Route::delete('api/:version/theme/:t_id/product/:p_id', 'api/:version.Theme/deleteThemeProduct');

//Product

Route::get('api/:version/product/AllInProduct', 'api/:version.Product/getAllInProduct');
Route::post('api/:version/product', 'api/:version.Product/createOne');
Route::post('api/:version/product/deleteOne', 'api/:version.Product/deleteOne');
Route::get('api/:version/product/by_category/paginate', 'api/:version.Product/getByCategory');
Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
Route::get('api/:version/product/getThirdOne/:id', 'api/:version.Product/getThirdOne', [], ['id' => '\d+']);
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
Route::post('api/:version/product/editproduct', 'api/:version.Product/editproduct');
Route::post('api/:version/product/addNewProduct', 'api/:version.Product/addNewProduct');
Route::post('api/:version/product/deletepro', 'api/:version.Product/deletepro');
Route::post('api/:version/product/editMorePic', 'api/:version.Product/editMorePic');




//Category
Route::get('api/:version/category', 'api/:version.Category/getCategories');
Route::post('api/:version/category/editCategory', 'api/:version.Category/editCategory');

Route::post('api/:version/category/delCategory', 'api/:version.Category/deletCategory');



// 正则匹配区别id和all，注意d后面的+号，没有+号将只能匹配个位数
//Route::get('api/:version/category/:id', 'api/:version.Category/getCategory',[], ['id'=>'\d+']);
//Route::get('api/:version/category/:id/products', 'api/:version.Category/getCategory',[], ['id'=>'\d+']);
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

//Token
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

//Address
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address', 'api/:version.Address/getUserAddress');

//Order
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');

//不想把所有查询都写在一起，所以增加by_user，很好的REST与RESTFul的区别
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');

//订单支付接口
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
//微信支付成功回调接口
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');

//小程序发货消息提醒
Route::post('api/:version/message/delivery', 'api/:version.Message/sendDeliveryMsg');
//获取用户列表接口
Route::get('api/:version/getAllUser', 'api/:version.Users/getAllUser');
//上传图片
Route::post('api/:version/upload', 'api/:version.Upload/upload');
//增加管理用户账号
Route::post('api/:version/addAdminUser', 'api/:version.Users/addAdminUser');


Route::get('api/:version/getjson', 'api/:version.Users/getjson');


Route::post('api/:version/stopNumber', 'api/:version.UseCount/stopNumber');
Route::post('api/:version/passCount', 'api/:version.UseCount/passCount');
Route::post('api/:version/getPassCount', 'api/:version.UseCount/getPassCount');
Route::post('api/:version/extchange', 'api/:version.Integral/extchange');







//return [
//        ':version/banner/[:location]' => 'api/:version.Banner/getBanner'
//];

//Route::miss(function () {
//    return [
//        'msg' => 'your required resource are not found',
//        'error_code' => 10001
//    ];
//});



