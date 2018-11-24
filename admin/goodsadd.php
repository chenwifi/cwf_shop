<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-26
 * Time: 下午10:18
 */

define('ACC',true);
require('../includes/init.php');

$cat = new CatModel();
$catlist = $cat->select();
//print_r($catlist);//exit;
//echo "<br />";
$catlist = $cat->getCatTree($catlist);
//print_r($catlist);exit;

require(Root . 'view/admin/templates/goodsadd.html');