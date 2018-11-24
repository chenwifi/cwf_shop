<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-30
 * Time: 下午8:33
 */

define('ACC',true);
include('../includes/init.php');
$goods = new GoodsModel();

if(isset($_GET['act']) && $_GET['act']=='show'){
    $goodsinfo = $goods->getTrash();
    require(Root . 'view/admin/templates/goodslist.html');
}else{
    $goods_id = $_GET['goods_id'] + 0;

    $goods = new GoodsModel();
    if($goods->trash($goods_id)){
        echo '已经加入回收站';
    }else{
        echo '加入回收站失败';
    }
}