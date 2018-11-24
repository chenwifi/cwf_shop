<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-6-13
 * Time: 下午3:09
 */

/*
 * 单文件上传类
 */

defined('ACC') || exit('ACC Denied');

/*
上传文件
配置允许的后缀
配置允许的大小
随机生成目录
随机生成文件名

获取文件后缀
判断文件的后缀.

良好的报错的支持
*/

class UpTool{
    protected $allowExt = 'gif,bmp,jpg,png,jpeg';
    protected $MaxSize = 1;//M为单位
    protected $errno = 0;
    protected $error = array(
        0=>'没有错误',
        1=>'上传文件超出系统限制',
        2=>'上传文件超出网页表单限制',
        3=>'文件只有部分被上传',
        4=>'没有文件上传',
        6=>'找不到临时文件',
        7=>'文件写入失败',
        8=>'不允许的文件后缀',
        9=>'文件大小超出类的允许范围',
        10=>'创建目录失败',
        11=>'移动失败'
    );

    public function up($key){
        //var_dump($_FILES);exit;
        if(!isset($_FILES[$key])){
            return false;
        }

        $f = $_FILES[$key];

        //检验上传是否成功
        if($f['error']){
            $this->errno = $f['error'];
            return false;
        }

        //获取后缀
        $ext = $this->getExt($f['name']);

        //检查后缀
        if(!$this->isAllowExt($ext)){
            $this->errno = 8;
            return false;
        }

        //检查大小
        if(!$this->isAllowSize($f['size'])){
            $this->errno = 9;
            return false;
        }

        $dir = $this->mk_dir();
        //一下判断最好不要用！，用全等于。
        if($dir==false){
            $this->errno = 10;
            return false;
        }

        $name = $this->randName();
        $newname = $name . '.' . $ext;



        $newdir = $dir .'/'. $newname;

        if(!move_uploaded_file($f['tmp_name'],$newdir)){
            //echo $dir;
            //echo $newname;
            //echo $newdir;
            $this->errno = 11;
            return false;
        }

        return str_replace(Root,'',$newdir);
    }

    public function setExt($ext){
        $this->allowExt = $ext;
    }

    public function setSize($size){
        $this->MaxSize = $size;
    }

    public function getErr(){
        return $this->error[$this->errno];
    }

    /*
     * parm string $file
     * return string $ext        后缀
     *
     */
    protected function getExt($file){
        $tmp = explode('.',$file);
        return end($tmp);
    }

    /*
     * parm $ext
     * return bool
     *      问题，要考虑大小问题
     *  */
    protected function isAllowExt($ext){
        //echo $ext;
        $arr = explode(',',strtolower($this->allowExt));
        /*
        if(in_array(strtolower($ext),($arr))){
            return true;
        }else{
            return false;
        }
        */
        return in_array($ext,$arr);//更加简单方便.
    }

    protected function isAllowSize($mes){
        /*
        if($mes['size']>$this->MaxSize * 1024 * 1024)
            return false;
        else
            return true;
        */
        return $mes['size'] <= $this->MaxSize * 1024 * 1024;
    }

    protected function randName($length = 6){
        $str = 'abcdefghijklmnopqrstuvwsyz0123456789';
        return substr(str_shuffle($str),0,$length);
    }

    //if判断语句十分巧妙。
    protected function mk_dir(){
        $dir = Root . 'data/images/'. date('ym/d');
        if(is_dir($dir) || mkdir($dir,0777,true)){
            return $dir;
        }else{
            return false;
        }

    }
}