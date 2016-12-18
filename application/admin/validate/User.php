<?php
namespace app\admin\validate;
//引入验证器的类库
use think\Validate;

class User extends Validate
{
	//验证规则
	protected $rule = [

		
		'username|用户名' => 'require|length:5,12',
		'password|密码' => 'require|length:6,12',
		'captcha|验证码' => 'captcha|require',
	];
	//验证错误信息
	protected $message = [

		'username.require' => '用户名不能为空',
		'username.length' => '用户名长度必须为6至12位',
		'password.require' => '密码不能为空',
		'password.length' => '密码长度必须为6至12位',
		''
	];


}