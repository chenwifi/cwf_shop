<?php


/*

file init.php
作用：框架初始化


*/

defined('ACC') || exit('ACC Denied');

define('Root',str_replace('\\','/',dirname(dirname(__FILE__))).'/');
//echo __FILE__,'<br />';//E:\wnmp\nginx\html\bool\includes\init.php
//echo Root;exit;//E:/wnmp/nginx/html/bool/
define('Debug',true);

/*
require(Root . 'includes/db.class.php');
require(Root . 'includes/conf.class.php');
require(Root . 'includes/log.class.php');
require(Root . 'includes/lib_base.php');
require(Root. 'includes/mysql.class.php');
require(Root. 'Model/Model.class.php');
require(Root. 'Model/TestModel.class.php');
*/
//改为自动加载

require(Root . 'includes/lib_base.php');

require(Root . 'includes/mysmarty.class.php');

function __autoloadBool($class){
    if(strtolower(substr($class,-5))=='model'){
        require(Root. 'Model/' . $class . '.class.php');
    }else if(strtolower(substr($class,-4))=='tool'){
        require(Root . 'tool/' . $class . '.class.php');
    }else{
        require(Root . 'includes/' . $class . '.class.php');
    }
}

spl_autoload_register('__autoloadBool');


$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);

//开启session
session_start();


//过滤参数，用递归方法实现$_GET,$_POST,$COOKIE 



//设置报错级别：

if(defined('Debug')){
    Error_reporting(E_ALL);
}else{
    Error_reporting(0);
}

$smarty = new MySmarty();





















