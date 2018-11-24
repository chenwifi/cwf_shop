<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-23
 * Time: 下午8:47
 */

defined('ACC') || exit('ACC Denied');

class CatModel extends Model{
    protected $table = 'category';

    /*
             给我一个关联数组，键值对应列，值对应值
             add函数自动插入改行数据。
    */
    public function add($data){
        return $this->db->autoExecute($this->table,$data);
    }

    public function select(){
        $sql = 'select cat_id,cat_name,parent_id from ' . $this->table;
        return $this->db->getAll($sql);
    }

    public function findsons($id){
        $sql = 'select cat_id,cat_name,parent_id from ' . $this->table . ' where parent_id = ' . $id;
        return $this->db->getAll($sql);
    }

    public function getTree($id){
        $tree = array();
        $cat = $this->select();
        while($id>0){
            foreach($cat as $v){
                if($v['cat_id'] == $id){
                    $tree[] = $v;
                    $id = $v['parent_id'];
                    break;
                }
            }
        }
        return array_reverse($tree);
    }

    public function getCatTree($arr,$id = 0,$lev = 0){
        //print_r($id);
        //print_r($arr);
        $tree = array();
        foreach($arr as $v){
            if($v['parent_id'] == $id){
                $v['lev'] = $lev;
                $tree[] = $v;
                $tree = array_merge($tree,$this->getCatTree($arr,$v['cat_id'],$lev+1));
            }
        }
        return $tree;
    }

    public function delete($cat_id){
        $sql = 'delete from ' . $this->table . ' where cat_id = ' . $cat_id;
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function find($cat_id){
        $sql = 'select * from ' . $this->table . ' where cat_id = ' . $cat_id;
        return $this->db->getRow($sql);
    }

    public function insert($data,$cat_id = 0){
        $this->db->autoExecute($this->table,$data,'update','where cat_id = '. $cat_id );
        return $this->db->affected_rows();
    }
}