<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-25
 * Time: 上午11:10
 */

define('ACC',true);
require('./includes/init.php');

session_destroy();

$msg = '退出成功';

include(Root . 'view/front/msg.html');