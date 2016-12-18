$(function () {


	//验证密码长度
	function checkPassword()
	{
		var reg = /^[a-z0-9A-Z]{6,16}$/;

		if(!reg.test($("#password").val())) {

			$("#pass").html('<font color="red">密码长度不合法</font>');
		}else {

			$("#pass").html('<font color="green">密码合法</font>');
		}
	}

	$("#password").blur(function () {

		checkPassword();
	});

	//验证两次密码是否一致
	function checkrePassword()
	{
		if($("#repassword").val()) {

			if($("#password").val() == $("#repassword").val()) {

				$("#repass").html('<font color="green">两次密码一致</font>');

			} else {

				$("#repass").html('<font color="red">两次密码不一致</font>');

			}
		}
	}

	$("#repassword").blur(function () {

		checkrePassword();
	});

	//检测验证码
	function checkVerify()
	{
		$.post("/index/User/checkVerify",{captcha:$("#captcha").val()},function (data) {

			if(data.status) {

				$("#ver").html('<font color="green">验证码正确</font>');
			} else {

				$("#ver").html('<font color="red">验证码错误</font>');
			}

		},'json');
	}
	$("#captcha").blur(function () {

		checkVerify();
	});


});

function refreshVerify() {
    var ts = Date.parse(new Date())/1000;
    var img = document.getElementById('verify_img');
    img.src = "/captcha?id="+ts;
}
