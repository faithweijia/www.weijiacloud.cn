// JavaScript Document

/*顶部top--导航*/

$(function(){
	$("#tMenu .webnav").hover(function(){
		$(this).addClass("hover").find("div.top_hidebox").show();
	},function(){
		$(this).removeClass("hover").find("div.top_hidebox").hide();
	});

});
$(function(){
  $("#tMenu .webnavdh").hover(function(){
    $(this).addClass("hover").find(".top_hidebox").show();
  },function(){
    $(this).removeClass("hover").find(".top_hidebox").hide();
  });

});