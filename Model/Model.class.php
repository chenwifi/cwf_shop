<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-18
 * Time: 上午11:18
 */

defined('ACC') || exit('ACC Denied');

class Model{
    protected $table = null;
    protected $db = null;

    protected $pk;

    protected $fields = array();
    protected $_auto = array();

    protected $_valid = array();
    protected $error = array();

    public function __construct(){
        $this->db = mysql::getIns();
    }
    /*
     *       自动过滤函数
     *       负责把传来的数组清除掉不用的单元
     *        留下与表对应的字段
     *          表的字段可以用desc表名来分析（开始的时候，表列仍需改变的时候）
     *      也可以手动写好（项目稳定的时候） *      *
     *
     *                   * */

    public function _facade($data = array()){
        $arr = array();
        foreach($data as $k=>$v){
            if(in_array($k,$this->fields)){
                $arr[$k] = $v;
            }
        }
        return $arr;
    }
    /*
     *       自动填充，把表中需要的数据
     *       但是$_POST没有传过来的数据附上值
     *   如radio，checkbox没有选上就没有传值
     *
     *
     *
     * */

    public  function _autoFill($data){
        foreach($this->_auto as $v){
            if(!array_key_exists($v[0],$data)){
                switch ($v[1]){
                    case 'value':
                     $data[$v[0]] = $v[2];
                        break;
                    case 'function';
                        $data[$v[0]] = call_user_func($v[2]);
                        break;
                }
            }
        }
        return $data;
    }

    /*
     *       自动判断函数*
     *
     *      格式：$this->_valid = array(
     *              array('验证的字段名','0/1/2(验证场景)','报错提示','require/in(某几种情况)/
     *          between(范围)','length(范围),number','参数')
     *      )     *
     *
         *  array('goods_name',1,'必须有商品名','require'),
            array('cat_id',1,'栏目id必须为整型值','number'),
            array('is_new',0,'is_new只能是0或1','in','0,1'),
            array('goods_brief',2,'商品简介就在10到100字符','length','10,100')

    0:有就检查（规则），无则通过   1：必须要有   2：可以无，可以空，但如果有的话就是要符合规定，如字数。
     *
     *
     * */

    public function _validata($data){
        if(empty($this->_valid)){
            return true;
        }

        foreach($this->_valid as $v){
            switch($v[1]){
                case 1:
                    if(!array_key_exists($v[0],$data)){
                        $this->error[] = $v[2];
                        return false;
                    }

                    if(!$this->check($data[$v[0]],$v)){
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;
                case 0:
                    if(array_key_exists($v[0],$data)){
                        if(!$this->check($data[$v[0]],$v)){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;
                case 2:
                    if(array_key_exists($v[0],$data) && !empty($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v)){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;

            }
        }
        return true;
    }
/*
 * switch($rule) {
            case 'require':
                return !empty($value);

            case 'number':
                return is_numeric($value);

            case 'in':
                $tmp = explode(',',$parm);
                return in_array($value,$tmp);
            case 'between':
                list($min,$max) = explode(',',$parm);
                return $value >= $min && $value <= $max;
            case 'length':
                list($min,$max) = explode(',',$parm);
                return strlen($value) >= $min && strlen($value) <= $max;

            default:
                return false;//更加简练
 *
 * */
    protected function check($data,$v){
        switch($v[3]){
            case "require":
                if(empty($data)){
                    return false;
                }
                break;
            case "in":
                $arr = explode(',',$v[4]);
                if(!in_array($data,$arr)){
                    return false;
                }
                break;

            case "between":
                $arr = explode(',',$v[4]);
                if($data<$arr[0] || $data>$arr[1]){
                    return false;
                }
                break;

            case "number":
                if(!is_numeric($data)){
                    return false;
                }
                break;
            case "length":
                $arr = explode(',',$v[4]);
                if(strlen($data)<$arr[0] || strlen($data)>$arr[1]){
                    return false;
                }
                break;
            case "email":
                return (filter_var($data,FILTER_VALIDATE_EMAIL))!==false;
            default:
                return false;
        }
        return true;
    }

    public function getErr(){
        return $this->error;
    }

    public function table($table){
        $this->table = $table;
    }

    public function add($data){
        return $this->db->autoExecute($this->table,$data);
    }

    public function delete($id){
        $sql = 'delete from ' . $this->table . ' where ' . $this->pk . '=' . $id;
        if($this->db->query($sql)){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }

    public function update($data,$id){
        $rs = $this->db->autoExecute($this->table,$data,'update',' where ' . $this->pk . '=' . $id);
        if($rs){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }

    public function select(){
        $sql = 'select * from ' . $this->table;
        return $this->db->getAll($sql);
    }

    public function find($id){
        $sql = 'select * from ' . $this->table . ' where ' . $this->pk . '=' . $id;
        return $this->db->getRow($sql);
    }

    public function insert_id(){
        return $this->db->insert_id();
    }
}
