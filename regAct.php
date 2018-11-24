<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-20
 * Time: 上午10:05
 */

//print_r($_POST);

define('ACC',true);
require('./includes/init.php');

$user = new UserModel();

$repasswd = $_POST['repasswd'];

$data = $user->_facade($_POST);
$data = $user->_autoFill($data);

if(!$user->_validata($data)){
    $msg = $user->getErr();
    $msg = $msg[0];
    //print_r($msg);
    include(Root . 'view/front/msg.html');
    exit;
}

if(empty($repasswd) || $data['passwd']!==$repasswd){
    $msg = '密码和确认密码不一致';
    include(Root . 'view/front/msg.html');
    exit;
}

if($user->checkUser($data['username'])){
    $msg = '用户名已经存在';
    include(Root . 'view/front/msg.html');
    exit;
}


//var_dump($user->reg($data));
//exit;
if($user->reg($data)){
    $msg = '用户注册成功';
}else{
    $msg = '用户注册失败';
}

include(Root . 'view/front/msg.html');