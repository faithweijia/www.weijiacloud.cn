<?php
namespace app\index\controller;

use think\Controller;
use think\Route;
use think\Session;
use think\Db;
use app\index\model\Column;
use app\index\model\Goods;
use app\index\model\Carts;
use app\index\model\Order;
//主页
class Index extends Controller
{
     //退出
    public function loginout()
    {
        Session::delete('username');
        $this->redirect('index\Index\index');
    }
    
    //立即抢购
    public function fightBuy(Order $order)
    {
        

        $data['orderSn'] = substr(md5(time()), 0,9);

        $data['orderMoney'] = input('post.detNumber')*input('post.detPrice');

        $data['orderUser'] = session('username');

        $data['orderScore'] = input('post.detScore');

        $list = $order->save($data);

        if($list) {

            $poke['pokeGoods'] = input('post.detId');

            $poke['pokeUser'] = session('username');

            $poke['pokeNumber'] = input('post.detNumber');

            $p = Db::name('poke')->insert($poke);

            return ['status'=>1,'msg'=>'抢购成功'];
        } else {

            return ['status'=>0,'msg'=>'抢购失败'];
        }
    }

    //加入购物车
    public function shopCar(Carts $carts)
    {
            if(session('username')) {

            $flag = input('post.cartId');

            $list = $carts->where('cartGoodsId',$flag)->find();
            
            if($list) {

                $list->cartGoodsNum += input('post.cartNumber');

                $list->save();

            } else {

                $data['cartGoodsId'] = input('post.cartId');

                $data['cartGoodsNum'] = input('post.cartNumber');

                $data['cartGoodsPrice'] = input('post.cartPrice');

                $data['cartGoodsScore'] = input('post.score');

                $data['cartUser'] = session('username');

                $result = $carts->save($data); 
            }
                
                
            }else {

                return ['status' => 1];
            }
        
    }

    //检验当前商品数量是否足够
    public function checkNumber(Goods $goods)
    {
        $list = $goods->where('detId',input('post.detId'))->find();
        
        if(input('post.detNumber')+1 > $list->detNumber) {

            return ['status' => 1];
        } else {

            return ['status' => 0];           
        }

    }
    //主页
    public function index()
    {

        $data = Session::get('username');
        $this->assign('data',$data);
        $userId = Db::name('user')->where('username',$data)->find();
        $member = Db::name('member')->where('memberUser',$userId['userId'])->find();
        $this->assign('member',$member);

    	$cate = Db::name('category')->select();
    	$this->assign('cate',$cate);
    	$groy = Db::name('category')->select();
    	$this->assign('groy',$groy);
    	
        $good = Db::name('goods')->select();
        $this->assign('good', $good);
    	
        $logo = Db::name('webinfo')->select();
        $this->assign('logo',$logo);
        $turn = Db::name('turn')->select();
        $this->assign('turn',$turn);
        return $this->fetch();
    }
    //查找
    public function doSeek()
    {
        $cate = Db::name('category')->select();
        $this->assign('cate',$cate);
        $groy = Db::name('category')->select();
        $this->assign('groy',$groy);
        $data = Session::get('username');
        $this->assign('data',$data);
        $good = Db::name('goods')->select();
        $this->assign('good', $good);

        $logo = Db::name('webinfo')->select();
        $this->assign('logo',$logo);
        $turn = Db::name('turn')->select();
        $this->assign('turn',$turn);
        $name = $_POST['name'];
        $data = Db::name('category')->where('cateName',$name)->select();
        $list = Db::name('goods')->where('detName',$name)->select();
        if(!empty($data)){
            $arr = $data[0];
            $Id = $arr['cateId'];
            if($arr['cateParentId'] == 0){
                $big = Db::name('goods')->where('detBigCate',$Id)->select();
                $this->assign('big',$big);
                return $this->fetch();
            } else {
                $big = Db::name('goods')->where('detSmallCate',$Id)->select();
                $this->assign('big',$big);
                return $this->fetch();
            }

        } else if(!empty($list)){
            
            $arr = $list[0];
            $Id = $arr['detId'];
            $big = Db::name('goods')->where('detId',$Id)->select();
            $this->assign('big',$big);
            return $this->fetch();
        } else {
            $this->error('查找不到','index/Index/index');
        }
    }


   
     //商品列表页
    public function show()
    {
        $id = $_GET['id'];
        $cate = Db::name('category')->select();
        $this->assign('cate',$cate);
        $cat = Db::name('category')->where('cateId','=',$id)->select();
        $this->assign('cat',$cat);
        $groy = Db::name('category')->select();
        $this->assign('groy',$groy);
        $data = Session::get('username');
        $this->assign('data',$data);
        $good = Db::name('goods')->where('detBigCate','=',$id)->select();
        $this->assign('good', $good);
        return $this->fetch();
    }
    //商品分类列表
    public function sort()
    {
        $id = $_GET['id'];
        $cate = Db::name('category')->select();
        $this->assign('cate',$cate);
        $groy = Db::name('category')->select();
        $this->assign('groy',$groy);
        $gory = Db::name('category')->where('cateId','=',$id)->select();
        $this->assign('gory',$gory);
        $data = Session::get('username');
        $this->assign('data',$data);
        $good = Db::name('goods')->where('detSmallCate','=',$id)->select();
        $this->assign('good', $good);
        return $this->fetch();
    }

    //商品详情页
    public function particulars()
    {
        $id = $_GET['id'];
        $list = Db::name('goods')->where('detId','=',$id)->select();
        $this->assign('list',$list);

        $data = Db::name('category')->select();
        $this->assign('data',$data);

        $cate = Db::name('category')->select();
        $this->assign('cate',$cate);

        $sale = Db::name('goods')->order('detSaleNum','desc')->limit(0,5)->select();
        $this->assign('sale',$sale);

        $collect = Db::name('collect')
                    ->where('goodsId','=',$id)
                    ->where('collectUser',session('username'))
                    ->select();
        $this->assign('collect',$collect);

        $comment = Db::name('comment')->where('commentDet',$id)->select();
        $this->assign('comment',$comment);

        return $this->fetch();
    }

    //收藏商品
    public function doCollect()
    {
        $name = Session::get('username');
        if($name) {
        $id = $_GET['id'];
        $data = time();
        $time = date('Y-m-d H:i:s', $data);
        $username = Db::name('user')->where('username','=',$name)->field('userId')->find();
        $collect = Db::name('collect')->where('goodsId','=',$id)->find();
        if($collect){
           $this->error('收藏失败','Index/index'); 
       } else{
        $list = Db::name('collect')->insert(['collectUser'=>$username['userId'], 'update_time'=>$time,'goodsId'=>$id]);
        if($list){
            $this->success('收藏成功','Index/index');
        } else {
            $this->error('收藏失败','Index/index');
        }
        }

        }else {

            $this->error("请先登录哟");
        }
        
    }
}
