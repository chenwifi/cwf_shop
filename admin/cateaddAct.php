<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-23
 * Time: 下午8:34
 */

define('ACC',true);
require('../includes/init.php');

//print_r($_POST);
$data = array();
if(empty($_POST['cat_name'])){
    exit('栏目名不能为空');
}
$data['cat_name'] = $_POST['cat_name'];

if( $_POST['parent_id']<0 ){
    exit('上级分类不正确。');
}
$data['parent_id'] = $_POST['parent_id'];

if(empty($_POST['intro'])){
    exit('栏目简介不能为空');
}
$data['intro'] = $_POST['intro'];


$cat = new CatModel();
if($cat->add($data)){
    echo '栏目添加成功';
    exit;
}else{
    echo '栏目添加失败';
    exit;
}


