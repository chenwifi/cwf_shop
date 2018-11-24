<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-26
 * Time: 下午3:08
 */

define('ACC',true);
require('./includes/init.php');


$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page<1){
    $page=1;
}

$goods = new GoodsModel();
$total = $goods->catGoodsCount($cat_id);

//每页取两条
$perpage = 2;

if($page>ceil($total/$perpage)){
    $page = 1;
}

$offset = ($page-1)*$perpage;

$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();

$cat = new CatModel();
$category = $cat->find($cat_id);
//var_dump($category);exit;


if(empty($category)){
    header('location:index.php');
    exit;
}

//取出树状导航：
//发现一个惊天bug：就是CatModel里面如果使用的是static数组
//那么，在同一个页面内使用同一个数组都会保留数组的结果。
$arr = $cat->select();
$sort = $cat->getCatTree($arr,0,1);

//print_r($sort);exit;

//取出面包屑导航：
$nav = $cat->getTree($cat_id);

//取出栏目下的所有商品
$goods = new GoodsModel();
$goodslist = $goods->catGoods($cat_id,$offset,$perpage);


include(Root . 'view/front/lanmu.html');