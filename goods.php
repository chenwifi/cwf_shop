<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-26
 * Time: 下午10:34
 */

define('ACC',true);
require('./includes/init.php');

$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;

$goods = new GoodsModel();
$g = $goods->find($goods_id);

if(empty($g)){
    header('location:index.php');
    exit;
}
//echo $goods_id;exit;
$cat = new CatModel();
$nav = $cat->getTree($g['cat_id']);
include(Root . 'view/front/shangpin.html');