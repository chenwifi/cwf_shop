<?php

/*
file conf.class.php
配置文件读写类

*/

defined('ACC') || exit('ACC Denied');
class conf{
    protected $data = array();
    protected static $ins = null;

    final protected function __construct(){
        include(Root.'includes/config.inc.php');
        $this->data = $_CFG;
    }

    protected final function __clone(){
    }

    public static function getIns(){
        if(self::$ins instanceof self){
            return self::$ins;
        }else{
            self::$ins = new self();
            return self::$ins;
        }
    }

    public function __get($v){
        //if(isset($this->data[$v]))
        if(array_key_exists($v,$this->data))
            return $this->data[$v];
        else
            return null;
    }

    public function __set($k,$v){
        $this->data[$k] = $v;
    }
}


$conf = conf::getIns();


/*

print_r($conf);
echo $conf->host,'<br />';
echo $conf->pp;

$conf->aa = 'shabi';

*/






















