//JSON数据获取
function create(name,obj){
	var ulStr = '';
	$UL = $("<ul class='shop_list'></ul>");
//	每次循环之前先清理上次加载的数据
	$UL.html("");
	$.each(name,function(i,vl){
		//console.log(name);
		//拼接字符串
		var ulDom = "<li index="+vl.id +">";
        var imgli = "<div class='shop_img'><img src=" + vl.img + "/></div>";
        var nameli = "<a class='info' href='javascript:;;'>" + vl.name + "</a>";
        var priceli = "<p><b>¥</b>" + vl.price + "</p>";
        var optionTd = "</li>";
        var header = "<ul>";
        var footer = "</ul>";
        ulDom += imgli + nameli + priceli + optionTd ;
        ulStr += ulDom;
	})
//	写入数据
	$UL.html(ulStr);
	obj.html($UL);
}
$(function(){
	$.get('../js/activity_shop.js',function(){
		//console.log(data);
		create(data1,$('.product-list1'));
		create(data2,$('.product-list2'));
		create(data3,$('.product-list3'));
		create(data4,$('.product-list4'));
		create(data5,$('.product-list5'));
		create(data6,$('.product-list6'));
		create(data7,$('.product-list7'));
		create(data8,$('.product-list8'));
		create(data9,$('.product-list9'));
		create(data10,$('.product-list10'));
	})
})
//悬浮滚动
$(window).scroll(function(){
	var top = $(window).scrollTop();//获取窗口滚动的距离
    //console.log(top);
	if(top>=3588&&top<=9350){
		$('#trend_1').addClass("fixation");
	}else{
		$('#trend_1').removeClass("fixation")
	}
	if(top>=9540&&top<=15400){
		$('#trend_2').addClass("fixation");
	}else{
		$('#trend_2').removeClass("fixation")
	}
})
//---------------------楼梯----------------------
function storey(demo1,demo2){
	var mark = 1;
    demo1.click(function () { //导航的点击事件
        mark = 2;
        var index = $(this).index();//获取点击的索引
        demo1.removeClass("underline");
        $(this).addClass("underline");
        var top = demo2.eq(index).offset().top-100;//相应的楼层 距离顶部的位置
        $("body,html").animate({//然后滚动下拉
        	"scrollTop": top
        }, 300, function () {
            mark = 1;
        });
    });
}
$(function () {
    storey($("#trend_1 ul li"),$("#mans .product-list"));
    storey($("#trend_2 ul li"),$("#womans .product-list"));
})
//---------------------滑过变大----------------------
$(function(){
	$('.boom_img').hover(function(){
		var $img = $(this).find("img")//找到标签下的图片元素
		$img.stop().animate({
			"top":"-15px",
			"left":"-37px",
			"width":"770px",
			"height":"450px"
		},1000)
	},function(){
		var $img = $(this).find("img")
		$img.stop().animate({
			"top":"0",
			"left":"0",
			"width":"690px",
			"height":"400px"
		},1000)
	})
})
//数字倒计时
//function timer(intDiff){
//	window.setInterval(function(){
//	var day=0,
//		hour=0,
//		minute=0,
//		second=0;//时间默认值		
//	if(intDiff > 0){
//		day = Math.floor(intDiff / (60 * 60 * 24));
//		hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
//		minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
//		second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
//	}
//	if (day <= 9) day = '<strong>0</strong><strong>'+ day +'</strong>';
//	if (hour <= 9) hour = '<strong>0</strong><strong>' + hour + '</strong>';
//	if (minute <= 9) minute = '<strong>0</strong><strong>' + minute + '</strong>';
//	if (second <= 9) second = '<strong>0</strong><strong>' + second + '</strong>';
//	$('#day_show').html(day);
//	$('#hour_show').html(hour);
//	$('#minute_show').html(minute);
//	$('#second_show').html(second);
//	intDiff--;
//	}, 1000);
//} 
//
//$(function(){
//	var endTime = new Date('2016/8/19 21:30:00').getTime();
//	var nowTime = new Date().getTime();
//	var intDiff = parseInt((endTime-nowTime)/1000);
//	timer(intDiff);
//});	









