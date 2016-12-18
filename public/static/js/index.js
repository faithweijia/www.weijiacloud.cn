/*---------------------header----------------------------*/
//箭头旋转180度
$(function(){
	$(".list1>li").eq(4).hover(function(){
		$(".list1 span").eq(1).addClass('hover-up');
		$(".list3").slideDown(300);
	},function(){
		$(".list1 span").eq(1).removeClass('hover-up').addClass('hover-down');
		$(".list3").slideUp(300);
	});
});

/*---------------------二级菜单----------------------------*/
// $(function(){
// 	$("#second>li[id]").hover(function(){
// 		var str=$(this).attr('id');
// 		var num=str.substring(3,4);
// //		console.log(str);
// 		$("#third-"+num).css("display","block");
// 	},function(){
// 		var str=$(this).attr('id');
// 		var num=str.substring(3,4);/*获得指定区间的字符串，不包含右边界*/
// 		$("#third-"+num).css("display","none");
		
// 	});
// });

/*---------------------二级菜单----------------------------*/
// $(function () {
//     $("#second > li").each(function (i) {
//         $(this).hover(function () {
//             $("#third-"+(i+1)).css('display','block');
//         },function () {
//             $("#third-"+(i+1)).css('display','none');
//         });
//     })
// });

/*--------------------隔行变色-----------------------*/
$(function(){
	$("#second>li:odd").addClass('odd');/*奇数行*/
});
/*--------------------图片轮播-----------------------*/

/*--------------------猜你喜欢（图片滚动）-----------------------*/
$(function(){
	imgScroll.rolling({
		name:'g2',
		width:'180px',
		height:'220px',
		direction:'right',
		speed:10,
		addcss:true
	});	
});
/*--------------------图片透明度变化-----------------------*/
$(function() {  
    $(".lowpiclist img").css("opacity", 1) 
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
    	});
     $(".contentleftup img").css("opacity", 1)  
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
        }); 
    $(".coach img").css("opacity", 1)  
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
        });
    $(".paileft img").css("opacity", 1)  
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
        }); 
    $(".lowbao img").css("opacity", 1)  
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
        }); 
     $(".contentleftdown img").css("opacity", 1)  
        .mouseover(  function(){
            $(this).animate({"opacity":.7},500).animate({"opacity":1},1000);
        });      
});
/*--------------------图片划过闪烁-----------------------*/  
//$(function(){
//	$('.contentleftdown dt').hover(function(){
//		$(this).find(".shine").stop();
//		$(this).find(".shine").css('background-position',"-160px 0"); 
//		$(this).find(".shine").animate({'backgroundPosition':'160px 0'},1000);
//		
//	},function(){
//		
//	});
//});
/*--------------------图片移动-----------------------*/  
$(function(){
	$(".band img").hover(function(){
		$(this).stop().animate({"left":"-6px"},300)
		},function(){
		$(this).stop().animate({"left":"0"},500)
	})
});
//------------用户名显示---------------- 
// $(function() {
//   	if(localStorage.length != 0) {
//   		var num1 = localStorage.getItem("uesrname");
//   		$('.list1 .user_name').html(num1);
//   		$('#vanish').css("display", "none");
//   	} else {
//   		$('#vanish').css("display", "block");
//   	}
// });
//---------------------楼梯----------------------
$(function () {
    var mark = 1;
    $(".loutinav ul li").not(".last").click(function () { //导航的点击事件
        mark = 2;
        var index = $(this).index();//获取点击的索引
        $(" .loutinav ul li").find("span").removeClass("active");
        $(this).find("span").addClass("active");
        var top = $(".loutimain .louti").eq(index).offset().top;//相应的楼层 距离顶部的位置
        $("body,html").animate({//然后滚动下拉
        	"scrollTop": top
        }, 300, function () {
            mark = 1;
        });
    });
    $(window).scroll(function () {
        if (mark == 1) {//导航切换已经完成
            var top = $(window).scrollTop();
            if (top >400) {
                $(".loutinav").fadeIn();
            } else {
                $(".loutinav").fadeOut();
            }
            $(".loutimain .louti").each(function () {
                var index = $(this).index();
                var height = $(this).offset().top + $(this).height() / 2;
                if (height > top) {
                    $(".loutinav ul li").find("span").removeClass("active");
                    $(".loutinav ul li").eq(index).find("span").addClass("active")
                    return false;
                }
            });
        }
    });
    $(".loutinav ul li.last").click(function () {//回到顶部
        $("body ,html").animate(//然后滚动下拉
        {
            "scrollTop": 0
        }, 2000, function () {
            mark = 1;
        });
    });
});

