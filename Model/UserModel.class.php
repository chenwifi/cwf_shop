<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-20
 * Time: 上午10:57
 */

defined('ACC') || exit('ACC Deined');

class UserModel extends Model{
    protected $table = 'user';
    protected $pk = 'user_id';

    protected $fields = array('username','email','passwd','regtime','lastlogin');
    protected $_auto = array(
        array('regtime','function','time')
    );
    protected $_valid = array(
        array('username',1,'用户名必须在4到16个字符','length','4,16'),
        array('email',1,'email非法','email'),
        array('passwd',1,'必须填写密码','require')

    );

    public function checkUser($user){
        $sql = 'select count(*) from ' . $this->table . " where username = '" . $user . "'";
        //echo $this->db->getOne($sql);
        return $this->db->getOne($sql);
    }

    public function checkLogin($user,$passwd){
        $sql = 'select user_id,username,email,passwd from ' . $this->table . " where username = '" . $user . "'";

        $row = $this->db->getRow($sql);
        //print_r($row);exit;

        if(!$row){
            return false;
        }

        if($this->encPasswd($passwd) != $row['passwd']){
            return false;
        }

        unset($row['passwd']);
        return $row;
    }

    public function reg($data){
        if($data['passwd'])
            $data['passwd'] = $this->encPasswd($data['passwd']);
        return $this->add($data);
    }

    protected function encPasswd($p){
        return md5($p);
    }
}