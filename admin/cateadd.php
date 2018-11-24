<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-23
 * Time: 下午4:10
 */

define('ACC',true);
include('../includes/init.php');

$cat = new CatModel();
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);
//print_r($catlist);

require(Root . 'view/admin/templates/cateadd.html');