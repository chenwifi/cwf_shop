<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-25
 * Time: 下午3:07
 */

define('ACC',true);
require('../includes/init.php');

$cat_id = $_GET['cat_id'] + 0;

$cat = new CatModel();
$cateinfo = $cat->find($cat_id);
//print_r($cateinfo);exit;

$catelist = $cat->select();
$catelist = $cat->getCatTree($catelist);


require('../view/admin/templates/catedit.html');
