<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-23
 * Time: 下午4:06
 */

define('ACC',true);
include('../includes/init.php');

$cat = new CatModel();
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);

require(Root . 'view/admin/templates/catelist.html');