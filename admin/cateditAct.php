<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-25
 * Time: 下午8:44
 */

define('ACC',true);
require('../includes/init.php');

$data = array();
$cat_id = $_POST['cat_id'] + 0;

if(empty($_POST['cat_name'])){
    exit('栏目名不能为空');
}

$data['cat_name'] = $_POST['cat_name'];

if(empty($_POST['intro'])){
    exit('栏目简介不能为空');
}

$data['intro'] = $_POST['intro'];
//var_dump($_POST['parent_id']);
if($_POST['parent_id']<0 ){
    exit('栏目父栏目不合法');
}
$data['parent_id'] = $_POST['parent_id'] + 0;

$cat = new CatModel();
$cat->getTree($_POST['parent_id']);

/*
echo '原来的栏目是',$cat_id;
echo '需要选定的父栏目是',$_POST['parent_id'];
echo '选定父栏目的家谱树是';
print_r($cat->getTree($data['parent_id']));
exit;
*/
$tree = $cat->getTree($data['parent_id']);
$flag = true;
foreach($tree as $v){
    if($v['cat_id'] == $cat_id)
        $flag = false;
}

if(!$flag){
    exit('父栏目指定错误');
}

if($cat->insert($data,$cat_id)){
    echo '修改成功';
}else{
    echo '修改失败';
}

