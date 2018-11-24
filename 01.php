<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-6-13
 * Time: 下午3:52
 */


define('ACC',true);

require('./includes/init.php');
require(Root . 'tool/UpTool.class.php');


print_r(Root);exit;
print_r($_POST);
$uptool = new UpTool();
if($dir = $uptool->up('avatar')){
    echo $dir,'<br />';
    echo 'ok';
}else{
    echo $uptool->getErr();
    echo 'false';
}