<?php 
namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

class Order extends Model
{
	//软删除
	use SoftDelete;

	protected static $deleteTime = 'delete_time';
}