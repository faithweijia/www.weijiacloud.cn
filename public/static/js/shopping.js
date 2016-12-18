function showProduct(product){
	var carlist = document.getElementById("cart-list");
	var html="<tr class=\'pl\'><td class=\'c1\'><input type=\'checkbox\' checked/></td>";
    html+="<td class=\'c2\'>"+product.img+"<a href=\'../html/particulars.html\'>"+product.name+"</a></td><td class=\"c3\" >中国大陆</td>";
    html+="<td class=\'c4\'><b>¥</b><b class=\'total_Z\'>"+product.total+"</b></td>";
    html+="<td class=\'c5\'><div><input class=\'but\' type=\'button\' value=\'－\' id=\'minus\'/><p id=\'count\'>"+product.count+"</p><input class=\'but\' type=\'button\' value=\'＋\' id=\'add\' /></div></td>";
    html+="<td class=\'c6\'><b>¥<b class=\'total_Z\'>"+product.total+"</b>元</b><p>返利199库币</p></td>"
    html+="<td class=\'c7\'><a href=\'#\' id='remove' onclick = \'deleteProduct(this)\'>删除</a></td> <tr>";
    carlist.innerHTML += html;
}

function deleteProduct(dom){
	var productName = dom.parentNode.parentNode.children[1].children[1].innerHTML;
	dom.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild(dom.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode);
	var strCookie = getCookie("cartcookie");
	if(strCookie.length>0){
		var arrCookie = JSON.parse(strCookie);//字符串转对象；
		for(var i=0;i<arrCookie.length;i++){
			if(arrCookie[i].name == productName){
				arrCookie.splice(i,1);
			}
		}
	}
	var strCookieNew = JSON.stringify(arrCookie);
	setCookie("cartcookie",strCookieNew,30);
}
$(function(){
	var strCookie = getCookie("cartcookie");
	if(strCookie.length>0){
		var arrCookie = JSON.parse(strCookie);//字符串转对象；
		for(var k=0;k<arrCookie.length;k++){
			showProduct(arrCookie[k]);
			
		}
	}
//	购物车数量加减和总金额计算
	var count = $('#count');
	var total = $('.total_Z');
	$('#number').html(count.html());
	total.html( (parseInt(count.html())*2970));
    $("#add").click(function () {
    	if(count.html()<10){
    		count.html(parseInt(count.html()) + 1);
    		$('#number').html(count.html());
    		total.html( (parseInt(count.html())*2970));
    	}
    })
    $("#minus").click(function () {
        if(count.html()>1){
    		count.html(parseInt(count.html()) - 1);
    		$('#number').html(count.html());
    		total.html( (parseInt(count.html())*2970));
    	}
    })
    
	$('#remove').click(function(){
		$('.conceal_S').remove();
		$('.conceal_H').css({display:'block'});
	})
	console.log(getCookie("cartcookie"));
})
