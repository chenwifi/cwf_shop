<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-5-30
 * Time: 下午2:49
 */

class GoodsModel extends Model{//同样会自动加载
    protected $table = 'goods';
    protected $pk = 'goods_id';

    protected $fields = array( 'goods_id','goods_sn','cat_id','brand_id','goods_name',
        'shop_price','market_price','goods_number','click_count','goods_weight',
        'goods_brief','goods_desc','thumb_img','goods_img','
     ori_img','is_on_sale','is_delete','is_best','is_new','is_hot',
        'add_time','last_update');

    protected $_auto = array(
        array('is_best','value',0),
        array('is_hot','value',0),
        array('is_new','value',0),
        array('is_on_sale','value',0),
        array('add_time','function','time')
    );

    protected $_valid = array(
        array('goods_name',1,'必须有商品名','require'),
        array('cat_id',1,'栏目id必须为整型值','number'),
        array('is_new',0,'is_new只能是0或1','in','0,1'),
        array('goods_brief',2,'商品简介就在10到100字符','length','10,100')
    );

    public function trash($id){
        return $this->update(array('is_delete'=>1),$id);
    }

    public function getGoods(){
        $sql = 'select * from ' . $this->table . ' where is_delete = 0';
        return $this->db->getAll($sql);
    }

    public function getTrash(){
        $sql = 'select * from ' . $this->table . ' where is_delete = 1';
        return $this->db->getAll($sql);
    }

    public function createSn(){
        $sn = 'BL' . date('Ymd') . mt_rand(10000,99999);

        $sql = 'select count(*) from ' . $this->table . 'where goods_sn=' ."'". $sn."'";

        return $this->db->getOne($sql)?$this->createSn():$sn;
    }

    public function getNew($n = 5){
        $sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from '
             . $this->table . ' where is_new=1 limit ' . $n;

        return $this->db->getAll($sql);
    }

    public function catGoods($cat_id,$offset=0,$limit=5){
        $category = new CatModel();
        $cat = $category->select();
        $sons =  $category->getCatTree($cat,$cat_id);
        //print_r($sons);exit;
        //echo $cat_id;

        $subs[] = $cat_id;
        if(!empty($sons)){
            foreach($sons as $v){
                $subs[] = $v['cat_id'];
            }
        }
        //print_r($subs);exit;

        $sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from ' . $this->table .
            ' where cat_id in(' . implode(',',$subs) . ') order by add_time limit '. $offset . ',' . $limit;

        return $this->db->getAll($sql);
    }

    public function catGoodsCount($cat_id) {
        $category = new CatModel();
        $cats = $category->select(); // 取出所有的栏目来
        $sons = $category->getCatTree($cats,$cat_id);  // 取出给定栏目的子孙栏目

        $sub = array($cat_id);

        if(!empty($sons)) { // 有子孙栏目
            foreach($sons as $v) {
                $sub[] = $v['cat_id'];
            }
        }

        $in = implode(',',$sub);

        $sql = 'select count(*) from goods where cat_id in (' . $in . ')';
        return $this->db->getOne($sql);
    }

    public function getCartGoods($items){
        foreach($items as $k=>$v){
            $sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' . $this->table . ' where goods_id =' . $k;
            $row = $this->db->getRow($sql);

            $items[$k]['thumb_img'] = $row['thumb_img'];
            $items[$k]['market_price'] = $row['market_price'];
        }

        return $items;
    }
}