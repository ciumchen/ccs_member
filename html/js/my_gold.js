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
            url: "/gold/next_page",
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
    var html = '';
    if (list.length == 0){
        return false;
    }
    $.each(list, function(index,item){
        var cls = '';
        var calc= '';
        if(item['gold']>0)
        {
        cls = 'add';
        calc = '+'
        }

        html += '<div class="detail-list-item">'
        +'<div class="detail-list-item__time">'
            +'<span>'+item['create_time']+' </span>'
        +'<span class="detail-list-item--right">金币总额</span>'
        +'</div>'
        +'<div class="detail-list-item_content">'
        +'<span>'+item['type_text']+'</span>'
        +'<span class="detail-list-item--right '+cls+'">'+calc+item['gold']+'</span>'
        +'</div>'
        +'</div>';
    });
    return html;

}

// function createHtml(list) {
//
//     if (list.length == 0){
//         return;
//     }
//     //加个空行，方便查看
//     var html = '<tr class="tr_line"></tr>';
//     $.each(list, function(index,item){
//         html += '<tr>'
//             +'<td>'+item['type_text']+'</td>'
//             +'<td class="amountMoney">'+item['amount']+'</td>'
//             +'<td >'+item['child_uid']+'</td>'
//             +'<td>'+item['order_id']+'</td>'
//             +'<td>'+item['create_time']+'</td>'
//             +'</tr>'
//     });
//     return html;
// }