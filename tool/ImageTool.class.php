<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-19
 * Time: 上午10:43
 */

defined('ACC') || exit('ACC Denied');

class ImageTool{

    /*
        imageInfo 分析图片信息
        return array
    */

    public static function imageInfo($image){
        if(!file_exists($image)){
            return false;
        }

        $info = getimagesize($image);

        if($info == false){
            return false;
        }

        $img = array();
        $img['width'] = $info[0];
        $img['height'] = $info[1];
        $img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);

        return $img;
    }


    /*
        加水印功能：
        parm string $dst   待操作图片
        parm string $water   水印图片
        parm string $save   不填则默认替代原图
        parm int pos  水印位置（左上，右上，右下，左下）
        parm int alpha 水印透明程度
    */

    public static function water($dst,$water,$save = NULL,$pos = 2,$alpha = 50){
        if(!file_exists($dst) || !file_exists($water)){
            return false;
        }

        //考虑大小问题，缩略图不能比操作图片大

        $dinfo = self::imageInfo($dst);
        $winfo = self::imageInfo($water);

        if($dinfo['width'] < $winfo['width'] || $dinfo['height'] < $winfo['height']){
            return false;
        }

        /*
        bool imagecopymerge  ( resource $dst_im  , resource $src_im  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $src_w  , int $src_h  , int $pct  )
        */

        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $wfunc = 'imagecreatefrom' . $winfo['ext'];

        $dim = $dfunc($dst);
        $wim = $wfunc($water);

        switch($pos){
            case 0:
                $dstx = 0;
                $dsty = 0;
                break;
            case 1:
                $dstx = $dinfo['width'] - $winfo['width'];
                $dsty = 0;
                break;
            case 3:
                $dstx = 0;
                $dsty = $dinfo['height'] - $winfo['height'];
                break;
            default:
                $dstx = $dinfo['width'] - $winfo['width'];
                $dsty = $dinfo['height'] - $winfo['height'];
                break;
        }

        imagecopymerge($dim,$wim,$dstx,$dsty,0,0,$winfo['width'],$winfo['height'],$alpha);

        $func = 'image' . $dinfo['ext'];

        if($save==NULL){
            $save = $dst;
        }
        $func($dim,$save);

        imagedestroy($dim);
        imagedestroy($wim);

        return true;
    }


    /*

    生成缩略图
    等比例缩放，两边留白

    parm string $dst
    parm string $save 为NULL则代替原图
    parm int $width
    parm int $height
    */

    public static function thumb($dst,$save=NULL,$width = 200,$height = 200){
        if(!file_exists($dst)){
            return false;
        }

        $dinfo = self::imageInfo($dst);
        if($dinfo==false)
            return false;

        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $dim = $dfunc($dst);

        $im = imagecreatetruecolor($width,$height);

        $bg = imagecolorallocate($im,255,255,255);

        imagefill($im,0,0,$bg);

        $size = min($width/$dinfo['width'],$height/$dinfo['height']);

        /*
        bool imagecopyresampled  ( resource $dst_image  , resource $src_image  , int $dst_x  , int $dst_y  , int $src_x  , int $src_y  , int $dst_w  , int $dst_h  , int $src_w  , int $src_h  )
        */

        $srcw = (int)$dinfo['width'] * $size;
        $srch = (int)$dinfo['height'] * $size;

        $posx = (int)($width - $srcw)/2;
        $posy = (int)($height - $srch)/2;

        imagecopyresampled($im,$dim,$posx,$posy,0,0,$srcw,$srch,$dinfo['width'],
            $dinfo['height']);


        if($save==NULL){
            $save = $dst;
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($im,$save);

        imagedestroy($dim);
        imagedestroy($im);

        return true;

    }

    //写验证码

    public static function captcha($width = 50,$height = 25){
        $im = imagecreatetruecolor($width,$height);

        $bg = imagecolorallocate($im,200,200,200);
        //字体的随机颜色
        $color = imagecolorallocate($im,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
        //干扰线随机颜色
        $lcolor1 = imagecolorallocate($im,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
        $lcolor2 = imagecolorallocate($im,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));
        $lcolor3 = imagecolorallocate($im,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));


        imagefill($im,0,0,$bg);

        $str = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWSYZabcdefghijklmnopqrstuvwsyz0123456789'),0,4);
        imagestring($im,5,7,5,$str,$color);

        //干扰线
        imageline($im,0,mt_rand(0,$height),$width,mt_rand(0,$height),$lcolor1);
        imageline($im,0,mt_rand(0,$height),$width,mt_rand(0,$height),$lcolor2);
        imageline($im,0,mt_rand(0,$height),$width,mt_rand(0,$height),$lcolor3);

        header('content-type:image/jpeg');
        imagejpeg($im);

        imagedestroy($im);

    }
    /*
     * parm $offset      最大波动的像素
     * parm $round        多少个周期
     * *    *
     *  */

    public static function circlecaptche($width = 60,$height = 25,$offset = 4,$round = 2){
        $dim = imagecreatetruecolor($width,$height);
        $sim = imagecreatetruecolor($width,$height);

        $dbg = imagecolorallocate($dim,200,200,200);
        $sbg = imagecolorallocate($sim,200,200,200);

        $randcolor = imagecolorallocate($sim,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));

        imagefill($sim,0,0,$sbg);
        imagefill($dim,0,0,$dbg);

        $str = substr(str_shuffle('abcdefghijklmnopqrstuvwsyzABCDEFGHIJKLMNOPQRSTUVWSYZ0123456789'),0,4);

        imagestring($sim,5,5,4,$str,$randcolor);

        for($i = 0;$i<$width;$i++){
            $posy = round(sin($i * M_PI * 2 * $round/$width) * $offset);
            imagecopy($dim,$sim,$i,$posy,$i,0,1,$height);
        }

        header('content-type:image/jpeg');
        imagejpeg($dim);

        imagedestroy($sim);
        imagedestroy($dim);
    }

}

ImageTool::circlecaptche();