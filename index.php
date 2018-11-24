<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-25
 * Time: 上午11:00
 */

define('ACC',true);
require('./includes/init.php');

//print_r($smarty);exit;


//取5条新商品
$goods = new GoodsModel();
$newlist = $goods->getNew(5);

//女士栏目下的商品
$female_id = 4;
$felist = $goods->catGoods($female_id);

$male_id = 1;
$melist = $goods->catGoods($male_id);
//print_r($melist);exit;
//print_r($melist['thumb_img']);
//echo $melist['thumb_img'];exit;


include (Root . 'view/front/index.html');

//$smarty->display('index.html');
