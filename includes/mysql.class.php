<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-11
 * Time: 下午4:32
 */

defined('ACC') || exit('ACC Denied');
/*
class mysql extends db{
    protected static $ins = null;
    protected  $conn;

    protected function __construct(){
        //require(Root.'includes/conf.class.php');
        $conf = conf::getIns();
        $this->connect($conf->host,$conf->user,$conf->passwd);
        $this->switchdb($conf->db);
        $this->setChar($conf->char);
    }

    public function switchdb($db){
        $sql = 'use '.$db;
        $this->query($sql);
    }

    public function setChar($charset){
        $sql = 'set names '.$charset;
        $this->query($sql);
    }

    public static function getIns(){
        if(self::$ins instanceof self){
            return self::$ins;
        }else{
            return self::$ins = new mysql();
        }
    }

    public function connect($h,$u,$p){
        $this->conn = mysql_connect($h,$u,$p);
        //echo self::$conn;
        if(!$this->conn){
            log::write(mysql_error());
            return false;
        }
    }

    public function query($sql){
        log::write($sql);
        return mysql_query($sql,$this->conn);
    }

    public function getAll($sql){
        $list = array();
        $ros = $this->query($sql);
        while($row = mysql_fetch_assoc($ros)){
            $list[] = $row;
        }
        return $list;
    }

    public function getRow($sql){
        $ros = $this->query($sql);
        return mysql_fetch_assoc($ros);
    }

    public function getOne($sql){
        $ros = $this->query($sql);
        $row = mysql_fetch_row($ros);
        return $row[0];
    }

    public function autoExecute($table,$data,$act = 'insert',$where){
        $data = _addslashes($data);//这个可以省略，因为数据是从$_GET中来的。
        $cols = implode(',',array_keys($data));
        $values = implode(',',array_values($data));
        if($act=='insert'){
            $sql = $act . 'into '.$table.'('.$cols.') values ('.$values.')';
            $this->query($sql);
        }elseif($act=='update'){
            $sql = $act . $table . 'set '.$data.' '. $where;
            $this->query($sql);
        }
    }
}
*/

class mysql extends db{
    private static $ins = null;//一下三个属性用私有比较好一点，否则
    private $conn;//继承的时候会被更改。
    private $conf = array();

    protected function __construct(){
        //require(Root.'includes/conf.class.php');//外部调用，可以不用这句话。
        $this->conf = conf::getIns();
        $this->connect($this->conf->host,$this->conf->user,$this->conf->passwd);
        $this->select_db($this->conf->db);
        $this->setChar($this->conf->char);
    }

    public function __destruct(){

    }

    public function select_db($db){
        $sql = 'use '.$db;
        $this->query($sql);
    }

    public function setChar($charset){
        $sql = 'set names '.$charset;
        $this->query($sql);
    }

    public static function getIns(){
        if(self::$ins instanceof self){
            return self::$ins;
        }else{
            return self::$ins = new mysql();
        }
    }

    public function connect($h,$u,$p){
        $this->conn = mysql_connect($h,$u,$p);
        //echo self::$conn;
        /*
        if(!$this->conn){
            log::write(mysql_error());
            return false;
        }*/
        if(!$this->conn){
            $e = new Exception('连接失败');
            throw $e;
        }
    }

    public function query($sql){
        log::write($sql);
        return mysql_query($sql,$this->conn);
    }

    public function getAll($sql){
        $list = array();
        $ros = $this->query($sql);
        while($row = mysql_fetch_assoc($ros)){
            $list[] = $row;
        }
        return $list;
    }

    public function getRow($sql){
        $ros = $this->query($sql);
        return mysql_fetch_assoc($ros);
    }

    public function getOne($sql){
        $ros = $this->query($sql);
        $row = mysql_fetch_row($ros);
        return $row[0];
    }

    public function autoExecute($table,$data,$act = 'insert',$where = 'where 1 limit 1'){
        /*
        $data = _addslashes($data);
        $cols = implode(',',array_keys($data));
        $values = implode(',',array_values($data));
        if($act=='insert'){
            $sql = $act . 'into '.$table.'('.$cols.') values ('.$values.')';
            $this->query($sql);
        }elseif($act=='update'){
            $sql = $act . $table . 'set '.$data.' '. $where;
            $this->query($sql);
        }
    }
        */

        if(!is_array($data)){
            return false;
        }

        if($act=='update'){
            $sql = 'update '. $table . ' set ';
            foreach($data as $k=>$v){
                $sql .= $k . "='" . $v . "',";
            }
            $sql = rtrim($sql,',');
            $sql .= $where;
            //var_dump($sql);
            return $this->query($sql);
        }

        $sql = 'insert into ' . $table . '(' . implode(',',array_keys($data)) . ') values (\'';
        $sql .= implode("','",array_values($data)) . '\')';
        //var_dump($sql);
        return $this->query($sql);
    }

    public function affected_rows(){
        return mysql_affected_rows($this->conn);
    }

    public function insert_id(){
        return mysql_insert_id($this->conn);
    }
}