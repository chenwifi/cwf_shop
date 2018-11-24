<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-7-27
 * Time: 下午1:36
 */

/**
 *      购物车类：
 *
 *      分析：无论打开多少个页面，都是一样的结果，
 *   即：可以放在数据库，或者session里面
 *
 *           表明要用单例模式。
 *
 * */



defined('ACC') || exit('Acc Deined');

class CarTool{
    private static $ins = null;
    private $items = array();

    protected final function __construct(){
    }

    protected final function __clone(){

    }

    //获取单个实例
    protected static function getIns(){
        if(!(self::$ins instanceof self)){
            self::$ins = new CarTool();
        }

        return self::$ins;
    }

    public static function getCar(){
        if(!isset($_SESSION['car']) || !($_SESSION['car'] instanceof self)){
            $_SESSION['car'] = self::getIns();
        }

        return $_SESSION['car'];
    }

    /*
     *      添加商品
     * parm int $id商品主键
     * parm string $name  商品名称
     *  parm flost $price       商品价格
     * parm int $num        商品数量
     *     *  *
     * */

    public function addItem($id,$name,$price,$num){
        //如果商品存在，就直接增加它的数量。
        if($this->hasItem($id)){
            $this->items[$id]['num'] += $num;
        }else{
            $arr = array();
            $arr['name'] = $name;
            $arr['price'] = $price;
            $arr['num'] = $num;
            $this->items[$id] = $arr;
        }
    }

    //修改购物车的商品数量
    //parm int $id商品主键
   //parm int $num修改后商品的数量

    public function modNum($id,$num){
        if(!$this->hasItem($id)){
            return false;
        }

        $this->items[$id]['num'] = $num;
    }

    //判断某个商品是否存在
    public function hasItem($id){
        return array_key_exists($id,$this->items);
    }

    //商品数量加1
    public function incNum($id,$num = 1){
        if(!$this->hasItem($id)){
            return false;
        }

        $this->items[$id]['num'] += $num;
    }

    //商品数量减少1
    public function decNum($id,$num = 1){
        if(!$this->hasItem($id)){
            return false;
        }

        $this->items[$id]['num'] -= $num;
        //数量为零，则删掉此商品
        if($this->items[$id]['num']<1){
            $this->delItem($id);
        }
    }

    //删除商品
    public function delItem($id){
        unset($this->items[$id]);
    }

    //查询购物车中的商品种类
    public function getCnt(){
        return count($this->items);
    }

    //查询购物车中的商品个数
    public function getNum(){

        if($this->getCnt()==0)
            return 0;
        $num = 0;
        foreach($this->items as $v){
            $num += $v['num'];
        }
        return $num;
    }

    //查询购物车的总金额
    public function getPrice(){
        if($this->getCnt() == 0)
            return 0;

        $sum = 0.0;
        foreach($this->items as $v){
            $sum += $v['num'] * $v['price'];
        }
        return $sum;
    }

    //返回购物车的所有商品
    public function all(){
        return $this->items;
    }

    //清空购物车
    public function clear(){
        //注意此处不可以用unset,用了之后就会没有items
        //unset($this->items);
        $this->items = array();
    }

}

//print_r(CarTool::getCar());


//session_start();

// print_r(CartTool::getCart());

/*
$cart = CarTool::getCar();


if(!isset($_GET['test'])) {
   $_GET['test'] = '';
}

if($_GET['test'] == 'addwangba') {
    $cart->addItem(1,'王八',23.4,1);
    echo 'add wangba ok';
} else if($_GET['test'] == 'addfz') {
    $cart->addItem(2,'方舟',2347.56,1);
    echo 'add fangzhou ok';
} else if($_GET['test'] == 'clear') {
    $cart->clear();
} else if($_GET['test'] == 'show') {
    print_r($cart->all());
    echo '<br />';
    echo '共',$cart->getCnt(),'种',$cart->getNum(),'个商品<br />';
    echo '共',$cart->getPrice(),'元';
} else {
    echo 'aaa';
    print_r($cart);
}

*/


