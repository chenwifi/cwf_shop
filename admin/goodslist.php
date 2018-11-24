<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-30
 * Time: 下午4:07
 */

define('ACC',true);
include('../includes/init.php');

$goods = new GoodsModel();
$goodsinfo = $goods->getGoods();


require(Root . 'view/admin/templates/goodslist.html');