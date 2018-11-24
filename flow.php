<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-29
 * Time: 上午10:10
 */

//购物流程页面，商城的核心部分。

define('ACC',true);
include('./includes/init.php');

//设置一个动作参数，判断用户想干什么
$act = isset($_GET['act'])?$_GET['act'] : 'buy';

$cart =CarTool::getCar();
$goods = new GoodsModel();



if($act == 'buy'){
    $goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
    $num = isset($_GET['num'])?$_GET['num']+0:1;

    if($goods_id){
        $g = $goods->find($goods_id);

        if(!empty($g)){
            //判断此商品是否下架或者是否在回收站
            if($g['is_delete']==1 || $g['is_on_sale'] == 0){
                $msg = '此商品不能买';
                include(Root . 'view/front/msg.html');
                exit;
            }

            //判断库存够不够与加入购物车的顺序

            $items = $cart->all();
            //print_r($items);exit;


            if(($items[$goods_id]['num']+$num)>$g['goods_number']){
                $msg = '库存不足';
                include(Root . 'view/front/msg.html');
                exit;
            }

            $cart->addItem($goods_id,$g['goods_name'],$g['shop_price'],$num);
            $items = $cart->all();

            //print_r($items);exit;

        }
    }

    if(empty($items)){
        header('location:index.php');
        exit;
    }

    $items = $goods->getCartGoods($items);

    //print_r($items);exit;

    $total = $cart->getPrice(); //获取购物车中的商品总价格
    $market_total = 0.0;
    foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;
    $rate = round(100 * $discount/$total,2);

    include(Root . 'view/front/jiesuan.html');

}else if($act == 'clear') {
    $cart->clear();
    $msg = '购物车已清空';
    include(Root . 'view/front/msg.html');
}else if($act == 'tijiao') {

    $items = $cart->all(); // 取出购物车中的商品

    //防止跳转回去会出现$total=0的bug。
    if(empty($items)){
        header('location:index.php');
        exit;
    }

    // 把购物车里的商品详细信息取出来
    $items = $goods->getCartGoods($items);

    //print_r($items);exit;

    $total = $cart->getPrice(); //获取购物车中的商品总价格
    $market_total = 0.0;
    foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;
    $rate = round(100 * $discount/$total,2);


    include(Root . 'view/front/tijiao.html');
}else if($act == 'done'){
    //订单入库,从表单读取送货地址，手机等信息，从购物车读取总价格信息，
    //写入orderinfo表

    //print_r($_POST);

    $OI = new OIModel();

    $data = $OI->_facade($_POST);
    $data = $OI->_autoFill($data);
    if(!$OI->_validata($data)){
        $msg = $OI->getErr()[0];
        include(Root . 'view/front/msg.html');
        exit;
    }

    $total = $data['order_amount'] = $cart->getPrice();
    $data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
    $data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';
    $order_sn = $data['order_sn'] = $OI->orderSn();
    //print_r($data);exit;

    if(!$OI->add($data)){
        $msg = '下订单失败';
        include(Root . 'view/front/msg.html');
        exit;
    }

    //echo '订单下定成功';

    /*要使订单入库，还要订单对应的货物入库
    *此外，货物表中的商品数量要减少，
    *此外，清空购物车
     * */

    //获取刚刚产生的id值
    $order_id = $OI->insert_id();

    $items = $cart->all();
    $cnt = 0;//$cnt用来记录插入ordergoods成功次数.

    $OG = new OGModel();

    foreach($items as $k=>$v){
        $data = array();

        $data['order_id'] = $order_id;
        $data['order_sn'] = $order_sn;
        $data['goods_id'] = $k;
        $data['goods_name'] = $v['name'];
        $data['goods_number'] = $v['num'];
        $data['shop_price'] = $v['price'];
        $data['subtotal'] = $v['num'] * $v['price'];

        if($OG->add($data)){
            $cnt += 1;
        }
    }

        if(count($items) !== $cnt){
            $OI->invoke($order_id);
            $msg = '下订单失败';
            include(Root . 'view/front/msg.html');
            exit;
        }

        //商品表的数据要减少
        //个人感觉还是需要事物功能
        foreach($items as $k=>$v){
            $data = array();

            $data['order_id'] = $order_id;
            $data['order_sn'] = $order_sn;
            $data['goods_id'] = $k;
            $data['goods_name'] = $v['name'];
            $data['goods_number'] = $v['num'];
            $data['shop_price'] = $v['price'];
            $data['subtotal'] = $v['num'] * $v['price'];
            $OG->invoGoods($data);
         // 减少库存
        }

        $cart->clear();

        //模拟第三方支付平台
    $v_url = 'http://localhost/bool/receive.php';
    $md5key = '#(%#WU)(UFGDKJGNDFG';
    $v_md5info = md5($total . 'CNY' . $order_sn . '1009001' . $v_url . $md5key);

    $v_md5info = strtoupper($v_md5info);


        include(Root . 'view/front/order.html');


}



