<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-24
 * Time: 下午3:20
 */

/*
      file       栏目的删除    页面
*/

define('ACC',true);
require('../includes/init.php');

$cat_id = $_GET['cat_id'] + 0;

$cat = new CatModel();
$sons = $cat->findsons($cat_id);
if(!empty($sons)){
    exit('有子栏目，不能删除');
}

if($cat->delete($cat_id)){
    echo '删除成功';
}else{
    echo '删除失败';
}




