<?php
namespace app\admin\controller;

use think\Controller;

use think\Request;

use think\Session;

use think\Route;

use think\Db;

use app\admin\model\Column;

use app\admin\model\Category;

use app\admin\model\Goods;

use app\admin\model\User;

use app\admin\model\Forbid;

use app\admin\model\Webinfo;

use app\admin\model\Links;

use app\admin\model\Admin;

use app\admin\model\Turn;

use app\admin\model\Order;

use app\admin\model\Member;

use app\admin\model\Comment;

use app\admin\model\Sale;

use app\admin\model\Recharge;

class Index extends Controller
{
	//充值记录
	public function userChong(Recharge $recharge)
	{
		$list = $recharge->where('reId','>',0)->where('delete_time','>',0)->select();
		
		$this->assign('list',$list);

		return $this->fetch();
	}

	//商品寄卖
	public function userSale(Sale $sale)
	{
		$list = $sale->where('saleId','>',0)->where('delete_time','>',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}
	
	//用户充值
	public function userRe()
	{
		$list = Db::name('recharge')
				->where('delete_time',null)
				->paginate(10);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function delRe()
	{
		$list = Db::name('recharge')
				->where('reId',$_GET['id'])
				->delete();
		if($list){
			$this->success('删除成功','admin/Index/userRe');
		} else {
			$this->error('删除失败','admin/Index/userRe');
		}
	}

	public function modRe()
	{
		$time = date('Y-m-d H:i:s',time());
		$list = Db::name('recharge')
				->where('reId',$_GET['id'])
				->find();
		$name = $list['reUser'];
		$score = $list['reScore'];
		$data = Db::name('user')
				->where('username',$name)
				->find();
		$money = $data['userMoney'];
		$m = $money + $score;

		$add = Db::name('user')
				->where('username',$name)
				->update(['userMoney'=>$m]);
		if($add){
			$list = Db::name('recharge')
				->where('reId',$_GET['id'])
				->update(['delete_time'=>$time]);
			$this->success('同意成功','admin/Index/userRe');
		} else {
			$this->error('同意失败','admin/Index/userRe');
		}
	}
	//寄卖的商品
	public function goodsSale()
	{
		$list = Db::name('sale')
				->alias('s')
				->join('__CATEGORY__ c','c.cateId = s.saleSmallCate')
				->where('s.delete_time',null)
				->paginate(10);
		$page = $list->render();
		$this->assign('page', $page);
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function delSale()
	{
		$list = Db::name('sale')
				->where('saleId',$_GET['id'])
				->delete();
		if($list){
			$this->success('删除成功','admin/Index/goodsSale');
		} else {
			$this->error('删除失败','admin/Index/goodsSale');
		}
	}

	public function modSale()
	{
		$time = date('Y-m-d H:i:s',time());
		 $list = Db::name('sale')
				->where('saleId',$_GET['id'])
				->find();
		$data['detName'] = $list['saleName'];
		$data['detPrice'] = $list['salePrice'];
		$data['detBigCate'] = $list['saleBigCate'];
		$data['detSmallCate'] = $list['saleSmallCate'];
		$data['detDesc'] = $list['saleDesc'];
		$data['detScore'] = 0;
		$data['update_time'] = $time;
		$data['create_time'] = $time;
		$data['detUser'] = $list['saleUser'];
		$data['detNumber'] = $list['saleNumber'];
		$data['detPicture'] = $list['salePicture'];

		$add = Db::name('goods')->insert($data);
		if($add){
			$list = Db::name('sale')
				->where('saleId',$_GET['id'])
				->update(['delete_time'=>$time]);
			$this->success('同意成功','admin/Index/goodsSale');
		} else {
			$this->error('同意失败','admin/Index/goodsSale');
		}
	}

	//后台首页
	public function index()
	{
		if(empty(Session('user'))) {

			$this->redirect('__ADSITE__/admin/Login/login');

		}else {

			return $this->fetch();
		}
		
	}
	//退出登录
	public function loginout()
	{
		Session::delete('user');
		$this->redirect('admin\Login\login');
	}

	//屏蔽商品评价
	public function delComment($commentId)
	{
		$list = Db::name('comment')
				->where('commentId',$commentId)
				->update(['commentIsShow'=>1]);
		if($list) {

			$this->success('屏蔽中···');
		} else {

			$this->error('屏蔽失败');
		}
	}

	//商品评价
	public function comment(Comment $comment)
	{
		$list = Db::name('comment')
				->alias('c')
				->join('__GOODS__ g','c.commentDet = g.detId')
				->where('c.commentIsShow',0)
				->order('c.commentAddtime','desc')
				->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//删除认证会员
	public function delVerify($memId,Member $member)
	{
		$list = $member->where('memId',$memId)->delete();

		if($list) {

			$this->success('删除成功');
		} else {

			$this->error('删除失败');
		}
	}

	//处理认证商城会员
	public function verify($memId,Member $member)
	{
		$list = $member->where('memId',$memId)->update(['memberFlag'=>1]);

		if($list) {

			$this->success('认证成功');
		} else {

			$this->error('认证失败');
		}
	}

	//认证商城会员
	public function verifyMem(Member $member)
	{
		$list = $member->where('memId','>',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//恢复订单
	public function orderRecover($orderId)
	{
		$list = Db::name('order')->where('orderId',$orderId)->update(['delete_time'=>null]);

		if($list) {

			$this->success('恢复中···','index/userOrder');
		} else {

			$this->error('恢复失败');
		}
	}

	//订单回收站
	public function OrderRecycle(Order $order)
	{	
		$list = Db::name('order')
				->alias('o')
				->join('__USER__ u','o.orderUser = u.username')
				->where('o.orderStatus','>',0)
				->where('o.delete_time','<>',0)
				->select();
		
		$this->assign('list',$list);

		return $this->fetch();
	}

	//订单详情
	public function orderDetail($orderId)
	{
		$list = Db::name('poke')
				->alias('p')
				->join('__GOODS__ g','p.pokeGoods = g.detId' )
				->where('p.pokeOrder',$orderId)
				->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//删除订单
	public function delOrder($orderId,Order $order)
	{
		$list = $order->destroy($orderId);

		if($list) {

			$this->success('订单销毁中···');
		} else {

			$this->error('订单销毁失败');
		}
	}

	//订单发货
	public function handleOrder($orderId,Order $order)
	{
		$list = $order->where('orderId',$orderId)->update(['orderStatus' => 2]);

		if($list) {

			$this->success('发货中···');
		} else {

			$this->error('发货失败');
		}
	}

	//订单管理
	public function userOrder(Order $order)
	{	

		$list = Db::name('order')
				->alias('o')
				->join('__USER__ u','o.orderUser = u.username')
				->where('o.orderStatus','>',0)
				->where('o.delete_time',null)
				->select();
		
		$this->assign('list',$list);

		return $this->fetch();
	}

	//添加首页轮播图
	public function turnPicture(Turn $turn)
	{
		$file = request()->file('imgturn');

		$info = $file->move('./upload/');

		$path = config('__WEBSITE__').str_replace('\\','/',$info->getSaveName());

		$data['slogan'] = input('post.slogan');

		$data['sort'] = input('post.sort');

		$data['picture'] = $path;

		$list = $turn->save($data);

		if($list) {

			$this->success('添加成功');

		} else {

			$this->error('添加失败');
		}
	}

	//删除首页轮播
	public function delTurn($id,Turn $turn)
	{
		$list = $turn->where('id',$id)->delete();

		if($list) {

			$this->success('删除成功');

		} else {

			$this->error('删除失败');
		}
	}

	//首页轮播
	public function turn(Turn $turn)
	{
		$list = $turn->where('id','>',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}
	//处理添加管理员
	public function addedAdmin(Admin $admin)
	{
		$data['username'] = input('post.username');

		$data['password'] = md5(input('post.password'));

		$list = $admin->save($data);

		if($list) {

			$this->success('添加成功');

		} else {

			$this->error('添加失败');
		}
	}

	//添加管理员
	public function addAdmin()
	{
		return $this->fetch();
	}

	//修改密码
	public function modifyPassword(Admin $admin)
	{
		$result = $admin->where('username',session('username'))->find();
		
		$list  = $admin->where('id',$result->id)->update(['password'=>md5(input('post.newpass'))]);

		if($list) {

			return ['status'=>1,'msg'=>'修改成功'];

		} else {

			return ['status'=>0,'msg'=>'修改失败'];
		}
	}

	//验证旧密码是否正确
	public function checkPass()
	{	
		$list = Db::name('admin')->where('username',input('post.username'))->find();
		
		if($list) {

			if($list['password'] == md5(input('post.password'))) {

				return ['status'=>1,'msg'=>'原始密码正确'];

			} else {

				return ['status'=>0,'msg'=>'原始密码不正确'];
			}

		} else {
			return ['status'=>0];
		}
	}

	//修改管理员密码
	public function modPass()
	{
		return $this->fetch();
	}

	//删除友情链接
	public function delLink($friendId,Links $links)
	{
		$list = $links->where('friendId',$friendId)->delete();

		if($list) {

			$this->success('删除成功','index/webLink');

		} else {

			$this->error('删除失败');
		}
	}

	//添加友情链接
	public function addLink()
	{
		$link = new Links(input('post.'));

		$list = $link->allowField(true)->save();

		if($list) {

			$this->success('添加成功','index/webLink');

		} else {

			$this->error('添加失败');
		}
	}

	//友情链接
	public function webLink(Links $links)
	{
		$list = $links->where('friendId','>',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//处理站点信息
	public function webHandle(Webinfo $webinfo)
	{

		$file = Request()->file('webLogo');

		$info = $file->move('./upload/');

		$mes = $info->getSaveName();

		$path = config('__WEBSITE__').str_replace('\\','/',$mes);

		$data['webTitle'] = input('post.webTitle');
		$data['webLink'] = input('post.webLink');
		$data['webOwner'] = input('post.webOwner');
		$data['webPhone'] = input('post.webPhone');
		$data['webSwitch'] = input('post.webSwitch'); 
		$data['webLogo'] = $path;

		$list = $webinfo->where('webId',input('post.webId'))->update($data);

		if($list) {

			$this->success('设置成功','index/webInfo');

		} else {

			$this->error('设置失败');
		}
	}

	//站点信息管理
	public function webInfo(Webinfo $webinfo)
	{
		$list = $webinfo->where('webId','>',0)->find();

		if($list) {

			$this->assign('list',$list);

			return $this->fetch();
		}

		
	}

	//后台管理员修改密码
	public function pass()
	{
		return $this->fetch();
	}

	//后台单页管理
	public function page()
	{
		return $this->fetch();
	}

	//后台管理首页轮播
	public function adv()
	{
		return $this->fetch();
	}

	//留言管理
	public function book()
	{
		return $this->fetch();
	}

	//用户详细信息
	public function userInfo()
	{
		return $this->fetch();
	}

	//处理禁止IP
	public function forbidHandle(Forbid $forbid)
	{
		$data['ip1'] = input('post.ip1');
		$data['ip2'] = input('post.ip2');
		$data['ip3'] = input('post.ip3');
		$data['ip4'] = input('post.ip4');
		$str = join('.',$data);

		$num['forbidIp'] = $str;

		$date = time()+(60*60*24*input('post.valid'));

		$num['over_time'] = date('Y-m-d,H:i:s',$date);

		$list = $forbid->save($num);

		if($list) {

			$this->success('禁止成功','index/ForbidIp');
		} else {

			$this->error('禁止失败');
		}	
	}

	//解禁IP
	public function forbidDel($forbidId,Forbid $forbid)
	{
		$list = $forbid->where('forbidId',$forbidId)->delete();

		if($list) {

			$this->success('解禁成功','index/ForbidIp');
		} else {

			$this->error('解禁失败');
		}
	}

	//禁止IP
	public function forbidIp(Forbid $forbid)
	{	
		$list = $forbid->where('forbidId','>',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//恢复用户
	public function userBack($userId)
	{
		$list = Db::name('user')->where('userId',$userId)->update(['delete_time'=>null]);

		if($list) {

				$this->success('恢复成功','index/userList');
			} else {

				$this->error('解锁失败');
			}
	}

	//用户硬删除
	public function userRealDel($userId,User $user)
	{
		//判断是否是批量删除
		$res = input('post.');

		if($res) {

			$arr = $res['userId'];

			$list = Db::name('user')->where('userId','in',$arr)->delete();

			if($list) {

		 		$this->success('删除成功');
		 	}else {

		 		$this->error('失败了哟');
		 	}

		} else {

			$list = Db::name('user')->where('userId',$userId)->delete();

			if($list) {

		 		$this->success('删除成功','index/userDel');
		 	}else {

		 		$this->error('失败了哟','index/userDel');
		 	}	
		}
		
	}

	//用户软删除
	public function userMod($userId,User $user)
	{
		$res = input('post.');

		if($res) {

			$arr = $res['userId'];

			$str = join(',',$arr);

			$list = $user->destroy($str);

			if($list) {

		 		$this->success('删除成功','index/userDel');
		 	}else {

		 		$this->error('失败了哟','index/userList');
		 	}
		} else {

			$list = $user->destroy($userId);

		 	if($list) {

		 		$this->success('删除成功','index/userDel');
		 	}else {

		 		$this->error('失败了哟','index/userList');
		 	}
		}

	 	
	}
	//用户锁定
	public function userLock($userId,User $user)
	{
		$result = $user->where('userId',$userId)->field('userStatus')->find();

		if( $result->userStatus == 1) {

			$result->userStatus = 0;

			$list = $result->save();

			if($list) {

				$this->success('解锁成功');
			} else {

				$this->error('解锁失败');
			}
		} else {

			$result->userStatus = 1;

			$list = $result->save();

			if($list) {

				$this->success('锁定成功');
			} else {

				$this->error('锁定失败');
			}
		}
	}

	//用户黑名单
	public function userDel(User $user)
	{	
		$list = Db::name('user')->where('delete_time','<>',0)->paginate(5);

		$page = $list->render();

		$this->assign('list',$list);

		$this->assign('page',$page);

		return $this->fetch();
	}

	//用户列表
	public function userList(User $user)
	{
		$list = $user->where('delete_time',null)->order('userConsume','desc')->paginate(5);

		$page = $list->render();

		$this->assign('list',$list);

		$this->assign('page',$page);

		return $this->fetch();
	}

	//处理商品修改信息
	public function modedGoods(Goods $goods)
	{
		$file = request()->file('gp');

		$info = $file->move('./upload/');

		$path = config('__WEBSITE__').str_replace('\\','/',$info->getSaveName());

		$data['detPicture'] = $path;

		$data['detName'] = input('post.detName');

		$data['detPrice'] = input('post.detPrice');

		$data['detNumber'] = input('post.detNumber');

		$data['detIsTop'] = input('post.detIsTop');

		$list = $goods->where('detId',input('post.detId'))->update($data);

		if($list) {
			$this->success('修改成功','index/goodsList');
		} else {

			$this->error('修改失败');
		}
	}

	//修改商品信息
	public function modGoods($detId,Goods $goods)
	{
		$list = $goods->where('detId',$detId)->find();

		$this->assign('list',$list);

		return $this->fetch();
	}
	//硬删除
	public function deleteGoods($detId,Goods $goods)
	{
		$list = Db::name('goods')->where('detId',$detId)->delete();

		if($list) {

				$this->success('删除成功');
			} else{

				$this->error('失败了哟');
			}
	}

	//软删除商品
	public function delGoods($detId)
	{
		$post = input('post.');

		if($post) {
			$arr = $post['detId'];
			$goods = new Goods();

			$str = join(',',$arr);

			$list = $goods->destroy($str);

			if($list) {

				$this->success('成功下架');
			} else{

				$this->error('失败了哟');
			}

		} else {

			$goods = new Goods();

			$list = $goods->destroy($detId);

			if($list) {

				$this->success('成功下架');
			} else{

				$this->error('失败了哟');
			}
		}
		
	}

	//恢复商品
	public function changeGoods($detId,Goods $goods)
	{

		$list = Db::name('goods')->where('detId',$detId)->update(['delete_time'=> null]);

		if($list) {

			$this->success('上线成功','index/goodsList');
		} else {

			$this->error('上线失败');
		}
	}

	//商品回收站
	public function goodsRecyle()
	{
		$list = Db::name('category')
				->alias('c')
				->join('__GOODS__ g','c.cateId = g.detSmallCate')
				->where('g.delete_time','<>',0)
				->order('g.update_time','desc')
				->paginate(5);

		$page = $list->render();
		$this->assign('page',$page);
		$this->assign('list',$list);
		return $this->fetch();
		return $this->fetch();
	}

	//显示商品列表
	public function goodsList(Goods $goods,Category $category)
	{
		if(input('post.keywords') || input('post.cid')) {
			$flag = input('post.keywords');
			$num = input('post.cid');
			$list = Db::name('category')
				->alias('c')
				->join('__GOODS__ g','c.cateId = g.detSmallCate')
				->where('g.delete_time',null)
				->where('g.detBigCate','like',"%$num%")
				->where('c.cateName','like',"%$flag%")
				->order('g.update_time','desc')
				->paginate(15);

			$page = $list->render();
			$result = $category->where('cateId','>',0)
							->where('cateParentId',0)
							->select();
			$this->assign('result',$result);
			$this->assign('page',$page);
			$this->assign('list',$list);
			return $this->fetch();

		} else {

		$list = Db::name('category')
				->alias('c')
				->join('__GOODS__ g','c.cateId = g.detSmallCate')
				->where('g.delete_time',null)
				->order('g.update_time','desc')
				->paginate(10);
		$page = $list->render();

		$result = $category->where('cateId','>',0)
							->where('cateParentId',0)
							->select();
		$this->assign('result',$result);
		$this->assign('page',$page);
		$this->assign('list',$list);
		return $this->fetch();
		}
	}

	//添加商品
	public function addGoods(Category $category)
	{
		$list = $category->where('cateParentId',0)->select();

		$result = $category->where('cateParentId','<>',0)->select();

		return view('',compact('list','result'));
	}

	//处理添加商品
	public function addedGoods()
	{

		//上传商品图片
		$list = request()->file('detPicture');

		$status = $list->move('./upload/');

		$p = $status->getSaveName();
		$path = config('__WEBSITE__').str_replace('\\','/',$p);

		$data['detName'] = input('post.detName');
		$data['detPrice'] = input('post.detPrice');
		$data['detBigCate'] = input('post.detBigCate');
		$data['detSmallCate'] = input('post.detSmallCate');
		$data['detDesc'] = input('post.detDesc');
		$data['detScore'] = input('post.detScore');
		$data['detNumber'] = input('post.detNumber');
		$data['detPicture'] = $path;

		$goods = new Goods();

		$result = $goods->save($data);

		

		if($result) {

			$this->success('添加成功','index/goodsList');
		} else {

			$this->error('添加失败');
		}
	}

	//添加商品
	public function add()
	{
		return $this->fetch();
	}
	//商品版块
	public function category(Category $category)
	{
		$list = $category->where('cateId','>',0)->order('cateParentId','asc')->paginate(5);

		$page = $list->render();

		return view('',compact('list','page'));
	}

	//删除版块
	public function delCategory(Category $category,$cateId,$cateParentId)
	{	
		if(!$cateParentId) {

			$result = $category->where(['cateId'=>$cateId])->delete();


			if($result) {
				$this->success('删除成功');
			} else {

				$this->error('删除失败');
			}

		}else {

			$result = $category->where(['cateId'=>$cateId])->delete();

			$list = $category->where(['cateParentId'=>$cateId])->delete();

			if($result && $list) {

				$this->success('删除成功');
			} else {

				$this->error('删除失败');
			}
		}
		
	}

	//修改版块信息
	public function modCategory(Category $category,$cateId)
	{
		$list = $category->where('cateId',$cateId)->find();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//执行版块信息修改
	public function modedCategory(Category $category)
	{
		$list = $category->
				where('cateId',input('post.cateId'))
				->update(['cateName'=>input('post.cateName'),'cateParentId'=>input('post.cateParentId'),'cateSort'=>input('post.cateSort')]);

		if($list) {

			$this->success('修改成功','index/category');
		} else {

			$this->error('修改失败','index/category');
		}
	}

	//添加版块
	public function addCategory(Category $category)
	{


		$list = $category->where('cateParentId',0)->select();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//处理添加版块
	public function addHandleCate(Category $category)
	{
		if(empty(input('post.bigCategory'))) {

			$list = $category->save(['cateParentId'=>'0', 'cateName'=>input('post.cateName'), 'cateSort'=>input('post.cateSort')]);

			if($list) {

				$this->success('添加版块成功','index/Category');
			} else {

				$this->error('添加版块失败');
			}
		} else {

			$list = $category->save(['cateParentId'=>input('post.bigCategory'),'cateName'=>input('post.cateName'), 'cateSort'=>input('post.cateSort')]);

			if($list) {

				$this->success('添加版块成功','index/addCategory');
			} else {

				$this->error('添加版块失败');
			}
		}
	}

	//产品分类
	public function cate()
	{
		return $this->fetch();
	}

	//编辑用户
	public function edit()
	{
		return $this->fetch();
	}

	//编辑用户页
	public function doEdit()
	{
		return $this->fetch();
	}

	
}