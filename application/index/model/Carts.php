<?php

namespace app\index\model;

use think\Model;

use traits\model\SoftDelete;


class Carts extends Model
{
	//软删除
	use SoftDelete;

	protected static $deleteTime = 'delete_time';
}