<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-30
 * Time: 下午4:32
 */

define('ACC',true);
include('../includes/init.php');

$goods_id = $_GET['goods_id'] + 0;
if(empty($goods_id)){
    exit('商品不存在');
}

$goods = new GoodsModel();
$g = $goods->find($goods_id);

if(empty($g)){
    exit('商品不存在');
}

print_r($g);