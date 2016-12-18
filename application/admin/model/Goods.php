<?php

namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

class Goods extends Model
{
	use SoftDelete;
	protected static $deleteTime = 'delete_time';

	public function Category()
	{
		return $this->hasOne('Category','cateId');
	}
}