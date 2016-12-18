var param = {
index: 1,
size: 10
}
function userList(param) {
//获取用户列表
    $.get('../data/information.json', param, function(e) {
        //分页显示
        var count = e.length;
        //console.log(count);
        var pageCount = Math.ceil(count / param.size);
        $('#Pagination').children().remove();
        for (var i = pageCount; i > 0; i--) {
            $('#Pagination').prepend("<li><a href='javascript:;;'>" + i + "</a></li>");
        }
        var ulStr = "";
        $.each(e, function(i,vl) {
//      	console.info(vl);
            var ulDom = "<li index="+vl.id +">";
            var imgli = "<img src=" + vl.img + "/>";
            var infoli = "<a class='info' href='../html/particulars.html'>" + vl.info + "</a>";
            var priceli = "<p><b>¥</b>" + vl.price + "</p>";
            var optionTd = "</li>";
            ulDom += imgli + infoli + priceli + optionTd;
            ulStr += ulDom;
        })
        $('#list_ul').html(ulStr);
    })
}
$(function() {
    $('#setPage').change(function() {
            param.size = this.value;
            userList(param);
    })  
    $('.pagination').on('click', 'a', function() {
            param.index = this.innerHTML;
            userList(param);
    })
    userList(param);
})