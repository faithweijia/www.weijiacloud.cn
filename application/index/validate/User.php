<?php
namespace app\index\validate;
//引入验证器的类库
use think\Validate;

class User extends Validate
{
	//验证规则
	protected $rule = [

		'captcha|验证码' => 'captcha|require',
	];

	//验证错误信息
	protected $message = [

	];
}