$(document).ready(function(){
    $('#review_more').click(function () {
        $('#review_more').text("加载中...");
        var pointBox = $('#pointBox');
        if ($("#review_more").attr('id') == undefined){
            return;
        }

        //初始页数为1,每请求一次，页数+1
        var page = parseInt(pointBox.attr("data-page"));
        var newPage = page + 1;

        pointBox.attr("data-page", newPage);
        $.ajax({
            type: "POST",
            url: "/point/next_page",
            data: {page: newPage},
            dataType: "json",
            success: function (data) {
                var list = data['data'];
                pointBox.append(createHtml(list));
                $('html,body').animate({scrollTop: $('body').height()}, 500);
                if (list.length < 10){
                    $("#review_more").text("没有更多数据了");
                    $("#review_more").css("background",'none');
                    $("#review_more").removeAttr('id');
                }else{
                    $('#review_more').text('点击查看更多');

                }
            }
        });
    })
});


/***
 * 动态生成html
 * @param list 积分列表明细(二维数组)
 * @returns {DocumentFragment}
 */
function createHtml(list) {

    if (list.length == 0){
        return;
    }
    //加个空行，方便查看
    var html = '<tr class="tr_line"></tr>';
    $.each(list, function(index,item){
        html += '<tr>'
            +'<td>'+item['order_no']+'</td>'
            +'<td>'+item['type_text']+'</td>'
            +'<td class="amountMoney">'+item['order_point']+'</td>'
            +'<td>'+item['point_per']+'</td>'
            +'<td class="amountMoney">'+item['point']+'</td>'
            +'</tr>'
    });
    return html;
}