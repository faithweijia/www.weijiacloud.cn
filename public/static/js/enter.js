	//校验验证码
	$(function () {
		function checkVerify()
		{
			$.post("/index/User/doCheckVerify",{captcha:$("#captcha").val()},function (data) {

				if(data.status) {

					$("#succ").html('<font color="green">验证码正确</font');
				} else {

					$("#succ").html('<font color="red">验证码错误</font');
				}
			});
		}

		$("#captcha").blur(function () {

			checkVerify();
		});
	});

	//刷新验证码函数
    function refreshVerify() {
        var ts = Date.parse(new Date())/1000;
        var img = document.getElementById('verify_img');
        img.src = "/captcha?id="+ts;
    }

	    
	    