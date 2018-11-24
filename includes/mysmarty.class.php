<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-8-12
 * Time: 上午10:20
 */

defined('ACC') || exit('ACC Deined');

require(Root . 'lib/Smarty3/Smarty.class.php');

class MySmarty extends Smarty{
    public function __construct(){
        parent::__construct();
        $this->template_dir = Root . 'view/front';
        $this->compile_dir = Root . 'data/comp';
        $this->cache_dir = Root . 'data/cache';

        $this->caching = true;
    }
}



