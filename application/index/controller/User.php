<?php
namespace app\index\controller;

use think\Controller;

use app\index\model\User as UserModel;

use think\Db;

use think\Session;

use app\index\model\Webinfo;

use app\index\model\Forbid;

use think\Loader;

class User extends Controller
{


	//发送邮箱验证
	public function coned(UserModel $user)
	{
		$list = $user->where('username',input('post.username'))->find();

		if($list) {

		$subject = 'secooler网账号密码服务中心';

		$email = input('post.email');
		
		$pass = md5(time());

		$user->where('username',input('post.username'))->update(['userReturn'=>$pass]);

		$passPath = 'http://www.weijiacloud.cn/index/User/setNewpass/pass=' . $pass;

		$con = "<style class='fox_global_style'>
		div.fox_html_content {
		    line-height: 1.5;
		}


		/* 一些默认样式 */

		blockquote {
		    margin-Top: 0px;
		    margin-Bottom: 0px;
		    margin-Left: 0.5em
		}

		ol,
		ul {
		    margin-Top: 0px;
		    margin-Bottom: 0px;
		    list-style-position: inside;
		}

		p {
		    margin-Top: 0px;
		    margin-Bottom: 0px
		}
		</style>
		<table style='-webkit-font-smoothing: antialiased;font-family:' 微软雅黑 ', 'Helvetica Neue ', sans-serif, SimHei;padding:35px 50px;margin: 25px auto; background:rgb(247,246, 242); border-radius:5px' border='0' cellspacing='0' cellpadding='0' width='640' align='center'>
		    <tbody>
		        <tr>
		            <td style='color:#000;'> </td>
		        </tr>
		        <tr>
		            <td style='padding:0 20px'>
		                <hr style='border:none;border-top:1px solid #ccc;'>
		            </td>
		        </tr>
		        <tr>
		            <td style='padding: 20px 20px 20px 20px;'> Hi 尊敬的secooler用户你好 </td>
		        </tr>
		        <tr>
		            <td valign='middle' style='line-height:24px;padding: 15px 20px;'> 感谢您注册secooler账户
		                <br> 请点击以下链接修改您的密码： </td>
		        </tr>
		        <tr>
		            <td style='height: 50px;color: white;' valign='middle'>
		                <div style='padding:10px 20px;border-radius:5px;background: rgb(64, 69, 77);margin-left:20px;margin-right:20px'> <a style='word-break:break-all;line-height:23px;color:white;font-size:15px;text-decoration:none;' href='$passPath'>$passPath</a> </div>
		            </td>
		        </tr>
		        <tr>
		            <td style='padding: 20px 20px 20px 20px'> 请勿回复此邮件，如果有疑问，请联系我们：<a style='color:#5083c0;text-decoration:none' href='1049535354@qq.com'>1049535354@qq.com
		    // </a> </td>
		        </tr>
		    </tbody>
		</table>";

		$status = send($email,$subject,$con);

		if($status) {

			echo '<meta http-equiv="Refresh" content="0; url=findPass">';
		} else {

			echo 'error';
		}

		}else {

			$this->error('用户名不存在');
		}

		
	}

	//设置新密码
	public function setNewpass(UserModel $user)
	{
		$url = strrchr($_SERVER["QUERY_STRING"],'=');

		$str = substr($url,1);
		
		$list = $user->where('userReturn',$str)->find();

		$this->assign('list',$list);

		return $this->fetch();
	}

	//重置密码
	public function reset(UserModel $user)
	{
		$result = $user->where('username',input('post.reback'))->update(['password'=>md5(input('post.password'))]);

		if($result) {

			$this->success('密码找回成功','index/User/login');

		} else {

			$this->error('密码找回失败');
		}
				
	}
	//找回密码
	public function findPass()
	{
		return $this->fetch();
	}

	//用户注册
	public function register()
	{
		return $this->fetch();
	}

	//验证用户名是否注册
	public function checkUser()
	{	
		//处理用户名
		$result = Db::name('user')->where('username',input("post.username"))->find();
		
		if($result) {

			return ['status' => 1,'msg' => '用户名已存在'];

		} else {

			return ['status' => 0,'msg'=>'用户名合法'];
		}
	}

	//处理用户注册
	public function doRegister()
	{	
		$list = Db::name('user')->where('username',input('post.username'))->find();

		if($list) {

			return ['status' => 0,'msg'=>'用户名已注册'];

		} else {

			//处理用户注册
			$user = new UserModel(input('post.'));

			$data = $user->allowField(true)->save();

			if($data) {

				return ['status' => 1];
			} else {

				return ['status' => 0];
			}
		}
		
	}

	//校验验证码
	public function checkVerify()
	{
		if(!captcha_check(input('post.captcha'))){
		
			return ['status' => 0];

		} else {

			return ['status' => 1];
		}
	}

	//用户登录
	public function login()
	{
		return $this->fetch();
	}

	//处理用户登录
	public function doLogin(UserModel $user,Webinfo $webinfo,Forbid $forbid)
	{	
		$validate = Loader::validate('User');

		if(!$validate->check(input('post.'))) {

			$this->error($validate->getError());

		}else {

		//检验站点是否关闭
		$list = $webinfo->where('webId','>',0)->find();

		if($list->webSwitch) {

			//用户是否被锁定
			$flag = $user->where('username',input('post.username'))->find();

			if($flag->userStatus) {

				$this->error('用户被锁定');

			} else {

				//判断ip是否被禁止
				$ip = Request()->ip();

				$res = $forbid->where('forbidIp',$ip)->find();

				if($res) {

					$this->error('该IP已被禁止');

				} else {

					//查询用户名是否存在
					$data = $user->where('username',input('post.username'))->find();

					if($data) {

						if(md5(input('post.password')) == $data->password) {

							Session::set('username',input('post.username'));
							Session::set('password',input('post.password'));
							$time = date('Y-m-d H:i:s',time());
							Db::name('user')->where('username',input('post.username'))->update(['update_time'=>$time]);

							$this->success('登录成功','__SITE__/index/Index/index');

						} else {
							$this->error('密码错误',"__SITE__/index/User/login");
						}

					} else {

						$this->error('用户名不存在',"__SITE__/index/User/login");
					}

				}
			}

		} else {

			$this->error('网站已关闭');
		}
	}
}

	public function doCheckVerify()
	{
		//验证
		if(!captcha_check($_POST['captcha'])) {

			return ['status' => 0, 'msg' => '验证码错误','data' => []];
		} else {

			return ['status' => 1, 'msg' => '验证码正确','data' => []];
		}
	}
}