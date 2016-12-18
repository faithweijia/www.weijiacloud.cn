//封装cookie
function getCookie(key){
	var cookieName = "";
	var str = document.cookie;
	var arr = str.split("; ");
	for(var i=0;i<arr.length;i++){
		var arritem = arr[i].split("=");
		if(arritem[0] == key){
			cookieName=arritem[1];
		}
	}
	return decodeURIComponent(cookieName);
}
function setCookie(name,value,expiredays){
	var date = new Date();
	date.setDate(date.getDate()+expiredays);
	document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + date;
}






















