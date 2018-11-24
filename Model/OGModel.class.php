<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-8-1
 * Time: 上午10:15
 */

defined('ACC') || exit('ACC Deined');

class OGModel extends Model{
    protected $table = 'ordergoods';
    protected $pk = 'og_id';

    public function invoGoods($data){
        $sql = 'update goods set goods_number = goods_number - ' . $data['goods_number'] .
            ' where goods_id = ' . $data['goods_id'];

        return $this->db->query($sql);
    }
}