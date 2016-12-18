<?php
namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

class User extends Model
{	//软删除
	use SoftDelete;

	protected static $deleteTime = 'delete_time';

}