$(function(){

    if(!window.base.getLocalStorage('token')){
        window.location.href = 'login.html';
    }

    var pageIndex=1;
    var moreDataFlag=true;
    var theadlist =['订单号','商品名称','商品总数','商品总价格','订单状态','下单时间','操作'];
    var userlist =['用户id','用户名称','用户地址','用户头像'];
    var categorylist =['分类id','分类名称','分类图片','操作'];
    var productlist =['商品id','分类名称','商品价格','商品库存','商品图片','操作'];
     getOrders(pageIndex,theadlist);
    // getUsers(userlist);


    /*
    * 获取数据 分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */

    function getOrders(pageIndex,theadlist){
        var params={
            url:'order/paginate',
            data:{page:pageIndex,size:20},
            tokenFlag:true,
            sCallback:function(res) {
                console.log(res);
                var str = getOrderHtmlStr(res);
                var thead = getTheadstr(theadlist);
                $('#order-table').append(str);
                $('#thead-div').append(thead);
            }
        };
        window.base.getData(params);
    }
    /*
     * 获取用户数据
     */

    function getUsers(list){
        var params={
            url:'getAllUser',
            data:{},
            tokenFlag:true,
            sCallback:function(res) {
                console.log(res);
                var str = getheadHtmlStr(res);
                $('#order-table').append(str);
                var user = getTheadstr(list);
                $('#thead-div').append(user);

            }
        };
        window.base.getData(params);
    }
    /*
     * 获取分类数据
     */

    function getcategory(list){
        var params={
            url:'category/all',
            data:{},
            tokenFlag:true,
            sCallback:function(res) {
                console.log(res);
                var str = getCategoryStr(res);
                $('#order-table').append(str);
                var categorylist = getTheadstr(list);
                $('#thead-div').append(categorylist);

            }
        };
        window.base.getData(params);
    }

    /*
     * 获取商品数据
     */

    function getAllProduct(list){
        var params={
                url:'AllInProduct',
            data:{},
            tokenFlag:true,
            sCallback:function(res) {
                console.log(res);
                var str = getProductStr(res);
                $('#order-table').append(str);
                var categorylist = getTheadstr(list);
                $('#thead-div').append(categorylist);

            }
        };
        window.base.getData(params);
    }


    /*拼接html字符串*/
    function getOrderHtmlStr(res){
        var data = res.data;
        if (data){
            var len = data.length,
                str = '', item;
            if(len>0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    str += '<tr>' +
                        '<td>' + item.order_no + '</td>' +
                        '<td>' + item.snap_name + '</td>' +
                        '<td>' + item.total_count + '</td>' +
                        '<td>￥' + item.total_price + '</td>' +
                        '<td>' + getOrderStatus(item.status) + '</td>' +
                        '<td>' + item.create_time + '</td>' +
                        '<td data-id="' + item.id + '">' + getBtns(item.status) + '</td>' +
                        '</tr>';
                }
            }
            else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }
    /*拼接html字符串*/
    function getTheadstr(theadlist){
        var datas = theadlist;
        var len = datas.length,
                str = '', item;
                for (var i = 0; i < len; i++) {
                   var item = datas[i];
                    str +=
                    '<td>' + item + '</td>'

                }
                console.log(str)
            return str;
    }




    /*拼接html字符串*/
    function getheadHtmlStr(res){
        console.log(res)
        var data = res;
        if (data){
            var len = data.length,
                str = '', item;
            if(len>0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    var address = '';
                    if(item.address !=null){
                         address = item.address.province +item.address.city+item.address.country+item.address.detail
                    }else {
                        address = '';
                    }

                    str += '<tr>' +
                        '<td>' + item.id + '</td>' +
                        '<td>' + item.nickName + '</td>' +
                        '<td>' + address+ '</td>' +
                        '<td><img style="width: 50rpx; height: 50px; margin-top:10px" src="'+item.avatarUrl+'"></td>' +
                        // '<td>' + getOrderStatus(item.status) + '</td>' +
                        // '<td>' + item.create_time + '</td>'+
                        '</tr>';
                }
            }
            else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }

    /*拼接html字符串*/
    function getCategoryStr(res){
        console.log(res)
        var data = res;
        if (data){
            var len = data.length,
                str = '', item;
            if(len>0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];

                    str += '<tr>' +
                        '<td>' + item.id + '</td>' +
                        '<td>' + item.name + '</td>' +
                        // '<td>' + item.address.detail+ '</td>' +
                        '<td><img style="width: 100rpx; height: 100px; margin-top:10px" src="'+item.img.url+'"></td>' +
                        // '<td>' + getOrderStatus(item.status) + '</td>' +
                        '<td><span class="order-status-txt">编辑</span></td>'+
                        '</tr>';
                }
            }
            else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }

    /*拼接html字符串*/
    function getProductStr(res){
        console.log(res)
        var data = res;
        if (data){
            var len = data.length,
                str = '', item;
            if(len>0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];

                    str += '<tr>' +
                        '<td>' + item.id + '</td>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.price+ '</td>' +
                        '<td>' + item.stock+ '</td>' +
                        '<td><img style="width: 100rpx; height: 100px; margin-top:10px" src="'+item.main_img_url+'"></td>' +
                        // '<td>' + getOrderStatus(item.status) + '</td>' +
                        '<td><span class="order-status-txt">编辑</span></td>'+
                        '</tr>';
                }
            }
            else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }

    /*根据订单状态获得标志*/
    function getOrderStatus(status){
        var arr=[{
            cName:'unpay',
            txt:'未付款'
        },{
            cName:'payed',
            txt:'已付款'
        },{
            cName:'done',
            txt:'已发货'
        },{
            cName:'unstock',
            txt:'缺货'
        }];
        return '<span class="order-status-txt '+arr[status-1].cName+'">'+arr[status-1].txt+'</span>';
    }

    /*根据订单状态获得 操作按钮*/
    function getBtns(status){
        var arr=[{
            cName:'done',
            txt:'发货'
        },{
            cName:'unstock',
            txt:'缺货'
        }];
        if(status==2 || status==4){
            var index=0;
            if(status==4){
                index=1;
            }
            return '<span class="order-btn '+arr[index].cName+'">'+arr[index].txt+'</span>';
        }else{
            return '';
        }
    }

    /*控制加载更多按钮的显示*/
    function ctrlLoadMoreBtn(){
        if(moreDataFlag) {
            $('.load-more').hide().next().show();
        }
    }

    /*加载更多*/
    $(document).on('click','.load-more',function(){
        if(moreDataFlag) {
            pageIndex++;
            getOrders(pageIndex,'');
        }
    });
    $(document).on('click','.orderlist',function(){
        $('#order-table').empty();
        $('#thead-div').empty();
        getOrders(pageIndex,theadlist);

      console.log("orderlist")
    });
    $(document).on('click','.userlist',function(){
        $('#order-table').empty();
        $('#thead-div').empty();
        $('.load-more').hide().next().show();
        getUsers(userlist);
        console.log("userlist")
    });
    $(document).on('click','.category',function(){
        $('#order-table').empty();
        $('#thead-div').empty();
        $('.load-more').hide().next().show();
        getcategory(categorylist);
        console.log("category")
    });
    $(document).on('click','.product',function(){
        $('#order-table').empty();
        $('#thead-div').empty();
        $('.load-more').hide().next().show();
        getAllProduct(productlist);
        console.log("category")
    });


    /*发货*/
    $(document).on('click','.order-btn.done',function(){
        var $this=$(this),
            $td=$this.closest('td'),
            $tr=$this.closest('tr'),
            id=$td.attr('data-id'),
            $tips=$('.global-tips'),
            $p=$tips.find('p');
        var params={
            url:'order/delivery',
            type:'put',
            data:{id:id},
            tokenFlag:true,
            sCallback:function(res) {
                if(res.code.toString().indexOf('2')==0){
                   $tr.find('.order-status-txt')
                       .removeClass('pay').addClass('done')
                       .text('已发货');
                    $this.remove();
                    $p.text('操作成功');
                }else{
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback:function(){
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });

    /*退出*/
    $(document).on('click','#login-out',function(){
        window.base.deleteLocalStorage('token');
        window.location.href = 'login.html';
    });
});