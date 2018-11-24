<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 16-8-1
 * Time: 下午8:55
 */

/*
 * 分页类
*分页类参数:总条数，当前页，每页条数
 *思路：导航后的路径有许多，我们需要保存下来，但是
 *要去除当前页数，因为是根据地址栏的信息
 *链接到目标页数
 *
 */
//print_r($_SERVER);exit;

defined('ACC') || exit('ACC Deined');

class PageTool {
    protected $total = 0;
    protected $perpage = 10;
    protected $page = 1;

    public function __construct($total,$page=false,$perpage=false){
        $this->total = $total;
        if($page){
            $this->page = $page;
        }

        if($perpage){
            $this->perpage = $perpage;
        }
    }

    //主要函数，创建分页导航

    public function show(){
        //得到分页数
        $cnt = ceil($this->total/$this->perpage);
        $uri = $_SERVER['REQUEST_URI'];///bool/category.php?cat_id=1
        //print_r($uri);exit;
        //var_dump($_SERVER);exit;

        $parse = parse_url($uri);//Array ( [path] => /bool/category.php [query] => cat_id=1 ) 
        //print_r($parse);exit;

        $param = array();
        if(isset($parse['query'])){
            parse_str($parse['query'],$param);
        }
        //print_r($param);exit;
        //unset掉page，因为这是通过地址栏传过来的
        unset($param['page']);

        $url = $parse['path'] . '?';
        if(!empty($param)){
            $url = $url . http_build_query($param) . '&';
        }
        //print_r($url);exit;

        //计算页码导航
        $nav = array();
        $nav[0] = '<span class="page_now">' . $this->page . '</span>';
        //echo $cnt;exit;
        //print_r($this->page);exit;
        for($left=$this->page-1,$right=$this->page+1;($left>=1||$right<=$cnt) && count($nav)<=5;){
            if($left>=1){
                array_unshift($nav,'<a href="' . $url . 'page=' . $left . '">[' . $left . ']</a>');
                $left--;
            }

            if($right<=$cnt){
                array_push($nav,'<a href="' . $url . 'page=' . $right . '">[' . $right . ']</a>');
                $right++;
            }
        }
        //echo $left;echo $right;echo $cnt;

        //print_r($nav);
        return implode('',$nav);

    }
}

/*
$page = new PageTool(100,9,10);
$page->show();
*/