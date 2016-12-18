<?php
namespace app\admin\controller;

use think\Controller;

use app\admin\model\User as UserModel;

class User extends Controller
{
	public function userControl()
	{
		return $this->fetch();
	}
}