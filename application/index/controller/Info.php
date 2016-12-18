<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Db;
use think\Request;
use think\Route;
use think\Loader;

use app\index\model\Sale;

class Info extends Controller
{
	//头部显示
	public function top()
	{
		$data = Session::get('username');
        $this->assign('data',$data);
        return $this->fetch();
	}
	//充值
	public function recharge()
	{
		return $this->fetch();
	}

	public function doRecharge()
	{	
		$name = Session::get('username');
		$data['reUser'] = $name;
		$data['reName'] = input('post.userTrueName');
		$data['reScore'] = input('post.email');
		$data['rePhone'] = input('post.userPhone');
		$data['reBanque'] = input('post.userQQ');
		$list = Db::name('recharge')->insert($data);
		if($list){
			$this->success('充值请求成功','Info/integral');
		} else {
			$this->error('充值请求失败','Info/integral');
		}
	}
	//寄卖
	public function sale()
	{
		$list = Db::name('category')->where('cateParentId',0)->select();

		$result = Db::name('category')->where('cateParentId','<>',0)->select();
		$this->assign('list',$list);
		$this->assign('result',$result);
		return $this->fetch();
	}
	public function addGoods()
	{
		$file = request()->file('detPicture');
		$info = $file->move('./upload/');
		$p = $info->getSaveName();
		$path = config('__INDSITE__').str_replace('\\','/',$p);
		$data['salePicture'] = $path;
		$data['saleUser'] = Session::get('username');
		$data['saleName'] = input('post.detName');
		$data['salePrice'] = input('post.detPrice');
		$data['saleBigCate'] = input('post.detBigCate');
		$data['saleSmallCate'] = input('post.detSmallCate');
		$data['saleDesc'] = input('post.detDesc');
		$data['saleNumber'] = input('post.detNumber');
		$data['salePicture'] = $path;

		$goods = new Sale();

		$result = $goods->save($data);

		

		if($result) {

			$this->success('添加成功','Info/ergodic');
		} else {

			$this->error('添加失败');
		}
	}
	//寄卖的商品
	public function ergodic()
	{
		$name = Session::get('username');

		$list = Db::name('goods')
				->where('detUser',$name)
				->select();
				
		$this->assign('list',$list);
		return $this->fetch();
	}
	//修改密码
	public function accountSec()
	{
		$data = Session::get('username');
		$list = Db::name('user')->where('userName','=',$data)->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function checkPassword()
	{	
		$result = Db::name('user')->where('password',md5(input("post.oldpassword")))->find();
		if($result) {

			return ['status' => 1,'msg' => '原密码正确'];

		} else {

			return ['status' => 0,'msg'=>'原密码错误!'];
		}
	}
	public function doAccount()
	{
		$data = Session::get('username');
		$password = md5(input("post.password"));
		$update = Db::name('user')->where('userName','=',$data)->update(['password'=>$password]);
		if($update){
			$this->success('修改成功','Info/accountSec') ;
		} else {
			$this->error('修改失败','Info/accountSec') ;
		}
	}

	//收货地址
	public function addr()
	{
		$name = Session::get('username');
		$username = Db::name('user')->where('username','=',$name)->field('userId')->find();
		$Id = $username['userId'];
		$list = Db::name('receipt')->where('userReceipt',$Id)->select();
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function doAddr()
	{
		$name = Session::get('username');
		$username = Db::name('user')->where('username','=',$name)->field('userId')->find();

		$data['userReceipt'] = $username['userId'];
		$data['receiptName'] = input('post.receiptName');
		$data['province'] = input('post.province');
		$data['city'] = input('post.city');
		$data['district'] = input('post.district');
		$data['receiptStreet'] = input('post.receiptStreet');
		$data['receiptZip'] = input('post.receiptZip');
		$data['receiptPhone'] = input('post.receiptPhone');
		$data['receiptFix'] = input('post.receiptFix');
		$data['receiptFp'] = input('post.receiptFp');
		$list = Db::name('receipt')->insert($data);
		if($list){
			$this->success('添加成功','Info/addr');
		} else {
			$this->error('添加失败');
		}
	}
	public function doAlter()
	{
		$Id = $_GET['id'];
		$data['receiptName'] = input('post.receiptName');
		$data['province'] = input('post.province');
		$data['city'] = input('post.city');
		$data['district'] = input('post.district');
		$data['receiptStreet'] = input('post.receiptStreet');
		$data['receiptZip'] = input('post.receiptZip');
		$data['receiptPhone'] = input('post.receiptPhone');
		$data['receiptFix'] = input('post.receiptFix');
		$data['receiptFp'] = input('post.receiptFp');
		$list = Db::name('receipt')->where('receiptId',$Id)->update($data);
		if($list){
			$this->success('修改成功','Info/addr');
		} else {
			$this->error('修改失败');
		}
	}
	public function doDelete()
	{
		$Id = $_GET['id'];
		$list = Db::name('receipt')->where('receiptId', $Id)->delete();
		if($list){
			$this->success('删除成功','Info/addr');
		} else {
			$this->error('删除失败');
		}
	}

	public function doFirst()
	{
		$id = $_GET['id'];
		$name = Session::get('username');
		$username = Db::name('user')->where('username','=',$name)->field('userId')->find();

		$data = Db::name('receipt')->where('receiptFirst','=',1,'userReceipt','=',$username['userId'])->select();
		if($data){
			Db::name('receipt')->where('receiptFirst',1)->where('userReceipt',$username['userId'])->update(['receiptFirst'=>0]);
			$list = Db::name('receipt')->where('receiptId',$id)->update(['receiptFirst'=>1]);
			if($list){
				$this->success('设置成功','Info/addr');
			} else {
				$this->error('设置失败','Info/addr');
			}

		} else {
			$list = Db::name('receipt')->where('receiptId',$id)->update(['receiptFirst'=>1]);
			if($list){
				$this->success('设置成功','Info/addr');
			} else {
				$this->error('设置失败','Info/addr');
			}
		}
	}

	//我的评价
	public function comment()
	{
		$name = Session::get('username');

		$list = Db::name('goods')
				->alias('g')
				->join('__COMMENT__ c','c.commentDet = g.detId')
				->where('c.commentuser',$name)
				->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function doOmit()
	{
		$id = $_GET['id'];;
		$list = Db::name('comment')->where('commentId', $id)->delete();
		if($list){
			$this->success('删除成功','Info/comment');
		} else {
			$this->error('删除失败','Info/comment');
		}
	}
	//评价商品
	
	public function discuss()
	{
		$id = $_GET['id'];
		$list = Db::name('goods')
		->alias('g')
		->join('__POKE__ p', 'p.pokeId = detId')
		->where('p.pokeOrder',$id)
		->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function doComment()
	{
		$time = date('Y-m-d H:i:s',time());
		$data['commentUser'] = Session::get('username');
		$data['commentContent'] = input('post.content');
		$data['commentDet'] = $_GET['id'];
		$data['commentAddtime'] = $time;

		$list = Db::name('comment')->insert($data);

		if($list){
			$this->success('评论成功','Index/index');
		} else {
			$this->error('评论失败','Info/order');
		}
	}
	//我的积分
	public function integral()
	{
		$name = Session::get('username');
		$list = Db::name('user')->where('username',$name)->select();
		$this->assign('list',$list);
		return $this->fetch();
	}
	//订单的详情页
	public function particulars()
	{
		$id = $_GET['id'];
		$list = Db::name('poke')
				->alias('p')
				->join('__GOODS__ g','g.detId = p.pokeGoods')
				->where('pokeOrder',$id)
				->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	//我的订单
	public function order()
	{

		$name = Session::get('username');
		$list = Db::name('order')->where(['orderUser'=>$name , 'orderFlag'=> 0])->paginate(3);
		$li = Db::name('order')->where(['orderUser'=> $name,'orderStatus'=>0,'orderFlag'=> 0])->select();
		foreach ($li as $val) {
			$pokeOrder = $val['orderId'];
			$poke = Db::name('poke')->where('pokeOrder','=',0)->update(['pokeOrder'=>$pokeOrder]);
		}
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function doPay()
	{
		$name = Session::get('username');
		$list = Db::name('order')->where(['orderUser'=> $name,'orderStatus'=>0,'orderFlag'=> 0])->paginate(3);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch('order');
	}

	public function doDeliver()
	{
		$name = Session::get('username');
		$list = Db::name('order')->where(['orderUser'=> $name , 'orderStatus'=> 1 , 'orderFlag'=> 0])->paginate(3);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch('order');
	}
	public function doTake()
	{
		$name = Session::get('username');
		$list = Db::name('order')->where(['orderUser'=> $name , 'orderStatus'=> 2 , 'orderFlag'=> 0])->paginate(3);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch('order');
	}
	public function doRaise()
	{
		$name = Session::get('username');
		$list = Db::name('order')->where(['orderUser'=> $name , 'orderStatus'=>3 , 'orderFlag'=> 0])->paginate(3);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch('order');
	}

	public function doHide()
	{
		$id = $_GET['id'];;
		$list = Db::name('order')->where('orderId', $id)->update(['orderFlag' => 1]);
		if($list){
			$this->success('删除成功','Info/order');
		} else {
			$this->error('删除失败','Info/order');
		}
	}
	public function doAffirm()
	{
		$id = $_GET['id'];;
		$list = Db::name('order')->where('orderId', $id)->update(['orderStatus' => 3]);
		if($list){
			$this->success('确认成功','Info/order');
		} else {
			$this->error('确认失败','Info/order');
		}
	}
	//完善个人信息
	public function userInfo()
	{
		return $this->fetch();
	}

	public function doUser()
	{
		$user = Session::get('username');

		$file = request()->file('userPicture');
		$info = $file->move('./upload/');
		$p = $info->getSaveName();
		$path = config('__INDSITE__').str_replace('\\','/',$p);

		$data['userPicture'] = $path;
		$data['userTrueName'] = input('post.userTrueName');
		$data['userBrithday'] = input('post.userBrithday');
		$data['userEmail'] = input('post.email');
		$data['userPhone'] = input('post.userPhone');
		$data['userSex'] = input('post.userSex');
		$data['userQQ'] = input('post.userQQ');
		$data['userGraph'] = input('post.userGraph');
		$list = Db::name('user')->where('userName',$user)->update($data);
		if($list){
			$this->success('设置成功','Info/userInfo');
		} else {
			$this->error('设置失败');
		}
	}
	//我的收藏
	public function collect()
	{
		$name = Session::get('username');
		$username = Db::name('user')->where('username','=',$name)->field('userId')->find();
		$list = Db::name('goods')
		->alias('g')
		->join('__COLLECT__ c','c.goodsId = g.detId')
		->where('c.collectUser','=',$username['userId'])
		->select();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function doDele()
	{
		$id = $_GET['id'];
		$list = Db::name('collect')->where('goodsId','=',$id)->delete();
		if($list){
			$this->success('取消成功','Info/collect');
		} else {
			$this->error('取消失败','Info/collect');
		}
	}

	//会员申请
	public function member()
	{
		return $this->fetch();
	}
	public function doMember()
	{
		$name = Session::get('username');
		$username = Db::name('user')->where('username','=',$name)->field('userId')->find();
		$list = Db::name('member')->where('memberUser',$username['userId'])->select();
		if($list){
			$this->error('你已经申请了','Index/index');
		} else {
		$data['memberUser'] = $username['userId'];
		$data['memberName'] = input('post.userTrueName');
		$data['memberEmail'] = input('post.email');
		$data['memberPhone'] = input('post.userPhone');
		$data['memberSex'] = input('post.userSex');
		$data['memberQQ'] = input('post.userQQ');
		$list = Db::name('member')->insert($data);
		if($list){
			$this->success('申请成功','Info/member');
		} else {
			$this->error('申请失败','Info/member');
		}
		}
	}
	//尾部显示 
	public function bottom()
	{
		return $this->fetch();
	}
}