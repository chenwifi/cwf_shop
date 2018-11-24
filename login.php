<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-25
 * Time: 上午10:18
 */

//用户登录页面

define('ACC',true);
require('./includes/init.php');

if(isset($_POST['act'])){
    $u = $_POST['username'];
    $p = $_POST['passwd'];

    if($_POST['username'] == ''){
        $msg = '用户名为空';
        include(Root . 'view/front/msg.html');
        exit;
    }
    if($_POST['passwd'] == ''){
        $msg = '密码错误';
        include(Root.'view/front/msg.html');
        exit;
    }

    $user = new UserModel();
    $row = $user->checkLogin($_POST['username'],$_POST['passwd']);
    //print_r($row);exit;

    if(empty($row)){
        $msg = '用户密码不匹配';
    }else{
        $msg = '登录成功';
        $_SESSION = $row;
        if(isset($_POST['remember'])){
            setcookie('username',$row['username'],time()+300);
        }else{
            setcookie('username','',0);
        }
    }
    include(Root.'view/front/msg.html');
    exit;
}else{
    if(isset($_COOKIE['username']) && !empty($_COOKIE['username'])){
        $remuser = $_COOKIE['username'];
    }else{
        $remuser = '';
    }
    include(Root . 'view/front/denglu.html');
}


