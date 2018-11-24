<?php



/*
file log.class.php
作业：记录信息到日志


思路：
给定内容，写入文件，
如果文件大于1M，
重新写一份。

传给我一个内容，
判断当前日志的大小，
如果大于1M
备份，
否则
写入

*/


defined('ACC') || exit('ACC Denied');
class Log{
    const LOGFILM = 'curr.log';
    
    //写日志
    public static function write($cont){
        $cont .= "\r\n";
        $log = self::isBak();
        $handle = fopen($log,'ab');
        fwrite($handle,$cont);
        fclose($handle);

    }

    //备份日志
    public static function bak(){
        $log = Root.'data/log/'.self::LOGFILM;
        $bak = Root.'data/log/'.date('ymd').mt_rand(10000,99999).'bak';
        return rename($log,$bak);
    }


    //读取并判断日志大小
    public static function isBak(){
        $log = Root.'data/log/'.self::LOGFILM;
        if(!file_exists($log)){
            touch($log);
            return $log;
        }

        clearstatcache(true,$log);
    
        if(filesize($log)<=1024*1024){
            return $log;
        }

        if(self::bak()){
            touch($log);
            return $log;
        }else{
            return $log;
        }
    }

}

























