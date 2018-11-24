<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-26
 * Time: 下午10:24
 */

//print_r($_POST);

define('ACC',true);
require('../includes/init.php');


/*
$data['goods_name'] = trim($_POST['goods_name']);
if(empty($data['goods_name'])){
    exit('商品名不能为空');
}

$data['goods_sn'] = trim($_POST['goods_sn']);
$data['cat_id'] = $_POST['cat_id'] + 0;
$data['shop_price'] = $_POST['shop_price'] + 0;
$data['market_price'] = $_POST['market_price'];
$data['goods_desc'] = $_POST['goods_desc'];
$data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'];
$data['is_best'] = isset($_POST['is_best'])?1:0;
$data['is_new'] = isset($_POST['is_new'])?1:0;
$data['is_hot'] = isset($_POST['is_hot'])?1:0;
$data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
$data['goods_brief'] = trim($_POST['goods_brief']);

$data['add_time'] = time();
*/

$data = array();
$data = $_POST;
/*
print_r($data);
$goods = new GoodsModel();
$arr = $goods->_facade($data);
$arr['goods_weight'] = $data['goods_weight'] * $data['weight_unit'];
print_r($arr);
$arr = $goods->_autoFill($arr);
print_r($arr);
*/
$goods = new GoodsModel();
$data['goods_weight'] = $data['goods_weight'] * $data['weight_unit'];
$data = $goods->_facade($data);//自动过滤
$data = $goods->_autoFill($data);//自动填充

if(empty($data['goods_sn'])){
    $data['goods_sn'] = $goods->createSn();
}

if(!$goods->_validata($data)){
    print_r($goods->getErr());
    echo '数据不合法';
    exit;
}

$uptool = new UpTool();
$dir = $uptool->up('ori_img');

if($dir){
    $data['ori_img'] = $dir;
}

//生成中等大小缩略图 300*400
//根据原始图地址定中等图地址
//如aa.jpeg->goods_aa.jpeg
//echo $dir,'<br />';
$ori_img = Root . $dir;
//print_r($ori_img);
//exit;
$goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);

if(ImageTool::thumb($ori_img,$goods_img,300,400)){
    $data['goods_img'] = str_replace(Root,'',$goods_img);
}


//生成更小的缩略图160*220
//地址转变为
//aa.jpeg->thumb_aa.jpeg

$thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);
if(ImageTool::thumb($ori_img,$thumb_img,160,220)){
    $data['thumb_img'] = str_replace(Root,'',$thumb_img);
}

print_r($data);

if($goods->add($data)){
    echo '商品发布成功';
}else{
    echo '商品发布失败';
}