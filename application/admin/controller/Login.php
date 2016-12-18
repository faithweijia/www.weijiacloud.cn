<?php

namespace app\admin\controller;

use think\Controller;

use think\Loader;

use app\admin\model\Admin;

class Login extends Controller
{
	public function index()
	{
	}
	//后台管理员登录
	public function login()
	{
		return $this->fetch();
	}

	//处理管理员登录
	public function doLogin(Admin $admin)
	{
		$validate = Loader::validate('User');

		if(!$validate->check(input('post.'))) {

			$this->error($validate->getError());
		}

		$data = $admin->where('username',input('post.username'))->find();

		if($data) {

			if($data->password == md5(input('post.password'))) {

				session('user',input('post.username'));

				session('pass',input('post.password'));

				$this->success('恭喜您，登录成功了哟','admin/Index/index');
			} else {
				
				$this->error('您的密码输错了哟');				
			}
		} else {

			$this->error('该用户不是管理员哟');
		}
	}
}