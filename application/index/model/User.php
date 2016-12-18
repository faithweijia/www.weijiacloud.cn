<?php
namespace app\index\model;

use think\Model;

use traits\model\SoftDelete;

class User extends Model
{	//软删除
	use SoftDelete;

	protected static $deleteTime = 'delete_time';
	//数据自动完成
	protected $auto = ['userRegIp','password'];

	protected $insert = [];

	protected $update = [];

	public function setuserRegIpAttr()
	{
		return Request()->ip();
	}

	public function setPasswordAttr($value)
	{
		return md5($value);
	}
}