<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Carts as CartsModel;
use think\Db;
use think\Session;
use app\index\model\Goods;
use app\index\model\Order;
use app\index\model\User;

use app\index\model\Receipt;

class Carts extends Controller
{
		public function orderPayment(Order $order,Receipt $receipt)
		{
			$data = Session::get('username');

			$this->assign('data',$data);

			$name = $_GET['name'];

			//根据订单号去订单表查询订单
			$list = $order->where('orderSn',$name)
						  ->where('orderStatus',0)
			              ->find();
			$vo = $receipt->where('receiptPhone',session('username'))
						  ->where('receiptFirst',1)
						  ->find();
			$this->assign('vo',$vo);
			 
			$this->assign('list',$list);
			return $this->fetch();
		}

	public function orderOver($orderSn)
	{
		$list = Db::name('order')->where('orderSn',$orderSn)->find();

		$this->assign('list',$list);

		return $this->fetch();
	}

	public function test()
	{
		return $this->fetch();
	}

	public function addCarts()
	{
		$s = new Carts();
		$re = $s->addCart();
		return $re;
	}

	//处理订单支付
	public function orderPay(User $user,Goods $goods,Order $order,CartsModel $carts)
	{
		$result = $user->where('username',session('username'))->find();

		//处理支付成功的一系列事情
		if($result['userMoney'] > input('post.money')) {

			$pokeGood = Db::name('poke')
						->alias('p')
						->join('__GOODS__ g','p.pokeGoods = g.detId')
						->select();

			foreach ($pokeGood as $val) {
				if($val['detUser'] == null){

				} else {
				$detUser = Db::name('user')->where('username',$val['detUser'])->field('userMoney')->find();
					$Money = $detUser + $val['detPirce']*$val['pokeNum']*9/10;
					$u = Db::name('user')->where('username',$val['detUser'])->update(['userMoney'=>$Money]);
				}
			}

			//减金钱
			$result['userMoney'] -= input('post.money');

			//加酷币
			$result['CB'] += input('post.score');

			$result->save();

			//订单状态
			$order = $order->where('orderUser',session('username'))->where('orderStatus',0)->find();

			$order->orderStatus = 1;

			$order->save();
			$pokeOrder = $order['orderId'];
			$poke = Db::name('poke')->where('pokeOrder','=',0)->update(['pokeOrder'=>$pokeOrder]);
			//订单支付成功后减除对应的商品
			$list = Db::name('carts')
					->alias('c')
					->join('__GOODS__ g','c.cartGoodsId = g.detId')
					->select();

			foreach($list as $val) {

				$val['detNumber'] = $val['detNumber']-$val['cartGoodsNum'];	

				$val['detSaleNum'] = $val['detSaleNum']+$val['cartGoodsNum'];
				
					$goods->where('detId',$val['detId'])->update(['detNumber'=>$val['detNumber'],'detSaleNum'=>$val['detSaleNum']]);
				
				
			}

			return ['status'=>1,'msg'=>'支付成功'];

			} else {

				return ['status'=>0,'msg'=>'啊哦,您的余额不足'];
			}		
	}

	//订单处理页
	public function makeOrder(Order $order,Receipt $receipt)
	{
		$data = Session::get('username');

		$this->assign('data',$data);

		//根据用户去订单表查询订单
		$time = date('Y-m-d H:i:s',time()-30);
		$list = $order->where('orderUser',session('username'))
					  ->where('orderStatus',0)
					  ->where('create_time','>',$time)
		              ->find();
		$vo = $receipt->where('receiptPhone',session('username'))
					  ->where('receiptFirst',1)
					  ->find();

		$this->assign('vo',$vo);
		 
		$this->assign('list',$list);

		return $this->fetch();
	}

	//点击结算生成订单
	public function orderBy(Order $order,CartsModel $carts)
	{
		//点击立即结算就删除购物车里内容
		$list = Db::name('carts')->where('cartUser',session('username'))->select();

		foreach ($list as $key) {
			if($key['delete_time'] == null){
			$pokeNum = $key['cartGoodsNum'];
			$pokeGoods = $key['cartGoodsId'];
			$pokeUser = session('username');
			$p = Db::name('poke')->insert(['pokeNumber'=>$pokeNum,'pokeGoods'=>$pokeGoods, 'pokeUser'=>$pokeUser]);
			}
		}

		$goods = [];
		foreach ($list as $val) {
	
			$goods[] = $val['cartId'];
		}


		$str = join(',' ,$goods);

		$rel = $carts->destroy($str);

		$flag = 'sn'.substr(md5(time()),0,12);

		//检测数据库中订单是否存在
		$list = $order->where('orderSn',$flag)->find();

		if(!$list) {

			$data['orderMoney'] = input('post.money');
			$data['orderSn'] = $flag;
			$data['orderUser'] = session('username');
			$data['orderScore'] = input('post.score');

			$list = $order->save($data);

			if($list) {

				return ['status'=>1];

			} else {

				return ['status'=>0];
			}
		} else {

			return ['status'=>1];
		}

		
	}

	//是否勾选购物车商品
	public function mul(CartsModel $carts)
	{
		$list = Db::name('carts')->where('cartGoodsId',input('post.detId'))->find();

		if($list) {

			return ['status'=>1,'msg'=>'取消成功','data'=>$list];

		} else {

			return ['status'=>0];
		}
	}

	//删除购物车中的物品
	public function delGoods($detId,CartsModel $carts)
	{
		$list = $carts->where('cartGoodsId',$detId)->delete();

		if($list) {

			$this->success('删除成功');
		} else {

			$this->error('删除失败');
		}
	}

	public function cartts(CartsModel $carts,Goods $goods)
	{
		$data = Session::get('username');
		$this->assign('data',$data);

		$list = Db::name('carts')
		        ->alias('c')
		        ->join('__GOODS__ g','c.cartGoodsId = g.detId')
		        ->where('c.cartUser',$data)
		        ->where('c.delete_time',null)
		        ->select();
		//计算出商品包装数量
		$num = count($list);
		$this->assign('num',$num);

		//计算商品总数
		$number = [];
		foreach($list as $val) {

			$number[] = $val['cartGoodsNum'];
		}
		$all = 0;
		for($k=0; $k<count($number); $k++) {

			$all += $number[$k];
		}
		$this->assign('all',$all);

		//计算商品总价格
		$arr = [];
		$cool = [];
		foreach($list as $val) {

			$arr[] = $val['cartGoodsNum']*$val['cartGoodsPrice'];
			$cool[] = $val['cartGoodsNum']*$val['detScore'];
		}

		//循坏计算出商品总价格
		$price = 0;
		for($i=0;$i<count($arr);$i++) {

			$price += $arr[$i];
		}
		$this->assign('price',$price);

		$score = 0;
		for($j=0;$j<count($cool);$j++) {

			$score += $cool[$j];
		}

		$this->assign('score',$score);
		$this->assign('list',$list);
		return $this->fetch();
	}

	
}