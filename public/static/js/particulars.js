$(function() {
	$("#first").hover(function() {
		$("#second").css("display", "block");
	}, function() {
		$("#second").css("display", "none");
	});
});
/*------------------遮罩层加入购物车-----------------*/
$(function(){
	$('#account').click(function(){
		$('#mask_layer').fadeIn(1000);
		$('#tooltip').fadeIn(1000);
	})
	$('#close,#mask_layer').click(function(){
		$('#mask_layer').fadeOut(1000);
		$('#tooltip').fadeOut(1000);
	})
})
/*------------------遮罩层立即抢购-----------------*/
$(function(){
	$('#fight').click(function(){
		$('#mask_laye').fadeIn(1000);
		$('#toolti').fadeIn(1000);
	})
	$('#close,#mask_laye').click(function(){
		$('#mask_laye').fadeOut(1000);
		$('#toolti').fadeOut(1000);
	})
})
/*------------------放大镜-----------------*/
$(function(){
	$.fn.magnifying = function(){
		var that = $(this),
		 $imgCon = that.find('#magnifier'),//正常图片容器
		 	$Img = $imgCon.find('img'),//正常图片，还有放大图片集合
		   $Drag = that.find('#slider'),//拖动滑动容器
		   $show = that.find('#lImg'),//放大镜显示区域
		$showIMg = $show.find('img'),//放大镜图片
		$ImgList = that.find('.small li img'),
		multiple = $show.width()/$Drag.width();

		$imgCon.mousemove(function(e){
			$Drag.css('display','block');
			$show.css('display','block');
		    //获取坐标的两种方法
		   	// var iX = e.clientX - this.offsetLeft - $Drag.width()/2,
		   	// 	iY = e.clientY - this.offsetTop - $Drag.height()/2,	
		   	var iX = e.pageX - $(this).offset().left - $Drag.width()/2,
		   		iY = e.pageY - $(this).offset().top - $Drag.height()/2,	
		   		MaxX = $imgCon.width()-$Drag.width(),
		   		MaxY = $imgCon.height()-$Drag.height();
				
  	   	    /*这一部分可代替下面部分，判断最大最小值
		   	var DX = iX < MaxX ? iX > 0 ? iX : 0 : MaxX,
		   		DY = iY < MaxY ? iY > 0 ? iY : 0 : MaxY;
		   	$Drag.css({left:DX+'px',top:DY+'px'});	   		
		   	$showIMg.css({marginLeft:-3*DX+'px',marginTop:-3*DY+'px'});*/

		   	iX = iX > 0 ? iX : 0;
		   	iX = iX < MaxX ? iX : MaxX;
		   	iY = iY > 0 ? iY : 0;
		   	iY = iY < MaxY ? iY : MaxY;	
		   	$Drag.css({left:iX+'px',top:iY+'px'});	   		
		   	$showIMg.css({marginLeft:-multiple*iX+'px',marginTop:-multiple*iY+'px'});
		   	//return false;
		});
		$imgCon.mouseout(function(){
		   	$Drag.css('display','none');
			$show.css('display','none');
		});
		$ImgList.hover(function(){
			var NowSrc = $(this).data('bigimg');
			$Img.attr('src',NowSrc);
//			$(this).parent().addClass('.active_small').siblings().removeClass('.active_small');
		});
	}
	$("#album").magnifying();
});

	
	/*------------------购物数量增减框-----------------*/
window.onload = function() {
	var oLeft = document.getElementById("left");
	var oRight = document.getElementById("right");
	var oTxt = document.getElementById("txt");
	oRight.onclick = function() {
		oTxt.value == 10 ? oTxt.value = 10 : oTxt.value++;
		//oTxt.value == 20 || oTxt.value++;
	};

	oLeft.onclick = function() {
		//oTxt.value == 0?0:oTxt.value--;
		oTxt.value == 1 || oTxt.value--;
	};
	
	/*------------------倒计时-----------------*/
	var time = setInterval(function(){
		var endTime = new Date('2016/12/16 18:30:00').getTime();
		var nowTime = new Date().getTime();
		var endT = parseInt((endTime-nowTime)/1000);
		var day = Math.floor(endT/(3600*24));
		var hour = Math.floor((endT/3600)%24);
		var minute = Math.floor((endT%3600)/60);
		var seconds = endT%60;
		if(endT < 0){
        	clearInterval(time);
        	document.getElementById('result_count').innerHTML= "倒计时已经结束";
    	}
		document.getElementById('day').innerHTML = day;
		document.getElementById('hour').innerHTML = hour;
		document.getElementById('minute').innerHTML = minute;
		document.getElementById('seconds').innerHTML = seconds;
	},1000)
	/*------------------购物车-----------------*/
	var List = document.getElementById('account'),
		listName = document.getElementById('name'),
		listPrice = document.getElementById('price'),
		listImg = document.getElementById('active_small');
	List.onclick =function(){
		var product = new Object();
		product.name = listName.innerHTML;
		product.price = listPrice.innerHTML;
		product.img = listImg.innerHTML;
		product.count = oTxt.value;
		product.total = product.price*product.count;
//		console.log(product.total);
		products = getCookie("cartcookie");
		var arr = [];
		if(product.length>0){
			arr = JSON.parse(product);
		}
		var flag = false;
		for(var j=0;j<arr.length;j++){
			if(arr[j].name == product,name){
				arr[j].count += 1;
				flag = true;
			}
		}
		if(!flag){
			arr.push(product);
		}
		var strProduct = JSON.stringify(arr);
		setCookie("cartcookie",strProduct,30);
	}
}
//滑动悬浮
window.onscroll = function(){
	var div2 = document.getElementById("navigation");
	var _scroll = document.body.scrollTop || document.documentElement.scrollTop;
	if(_scroll >= 762){
		div2.style.position = "fixed";
		div2.style.top = 0;
		div2.style.left = '262px';
	} else {
		div2.style.position = "absolute";
		div2.style.left = 0;
	}
}
