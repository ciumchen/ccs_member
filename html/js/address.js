
//jq "$" 符号与mui 冲突，自定义别名
var jq = jQuery.noConflict();

/* 关闭弹层 */
function closeAddressWin () {
    mui('.adressWin')[0].style.display = 'none';
    mui('.mui-adressWin-backdrop')[0].style.display = 'none';
}

(function($, doc) {
    $.init();
    $.ready(function() {
        var _getParam = function(obj, param) {
            return obj[param] || '';
        };
        var cityPicker3 = new $.PopPicker({
            layer: 3
        });
        cityPicker3.setData(cityData3);
        var showCityPickerButton = doc.getElementById('showCityPicker3');
        var cityResult3 = doc.getElementById('cityResult3');
        showCityPickerButton.addEventListener('tap', function(event) {
            cityPicker3.show(function(items) {
                cityResult3.innerText = _getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text');
                //获取地区value
                jq('input[name="province_id"]').val(_getParam(items[0], 'value'));
                jq('input[name="city_id"]').val(_getParam(items[1], 'value'));
                jq('input[name="area_id"]').val(_getParam(items[2], 'value'));
                //return false;
            });
        }, false);

        /* 设为默认地址 */
        jq(".addressList").on('click','.mui-radio',function () {
            var address_card = jq(this).parents('.mui-card');
            var address_id = address_card.attr('data-address-id');
            jq.ajax({
                type: "POST",
                url: "/address/set_default",
                data: {address_id:address_id},
                dataType: "json",
                success: function (data) {
                    if (data['code'] == 0) {
                        window.location.reload();
                    }else {
                        mui.toast(data.msg);
                    }
                }
            });
        })


        /* 删除地址 */
        jq('.addressList').on('click', '.remove-address', function(e) {
            var address_card = jq(this).parents('.mui-card');
            var address_id = address_card.attr('data-address-id');
            mui.confirm('要删除该地址吗','提示',['否', '是'], function(e){
                if (e.index === 1) {
                    jq.ajax({
                        type: "POST",
                        url: "/address/delete",
                        data: {address_id:address_id},
                        dataType: "json",
                        success: function (data) {
                            mui.toast(data.msg);
                            if (data['code'] == 0) {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });

        /* 修改地址 */
        jq(".addressList").on('click','.edit-address',function () {
            var address_card = jq(this).parents('.mui-card');
            var address_id = address_card.attr('data-address-id');
            jq('.adressWin-bottom').find('button').attr('class','mui-btn btn_edit_address');
            jq.ajax({
                type: "POST",
                url: "/address/edit",
                data: {address_id:address_id},
                dataType: "json",
                success: function (data) {
                    var address_info = data[0]['address_info'];
                    if (data['code'] == 0) {
                        jq('input[name="address_id"]').val(address_id);
                        jq('input[name="province_id"]').val(address_info['province']);
                        jq('input[name="city_id"]').val(address_info['city']);
                        jq('input[name="area_id"]').val(address_info['area']);
                        jq('input[name="consignee"]').val(address_info['accept_name']);
                        jq('#cityResult3').text(address_info['region_name']);
                        jq('input[name="mobile"]').val(address_info['mobile']);
                        jq('textarea[name="details"]').val(address_info['address']);

                        jq('.close-title').text("修改收货地址");
                        jq('.adressWin').css('display','block');
                        jq('.mui-adressWin-backdrop').css('display','block');
                    }else {
                        mui.toast(data.msg);
                    }
                }
            });
        })
    });
})(mui, document);

/* 添加地址 */
jq('.shouAdd').on('click',function (){
    jq(".adressWin-content").find('input').val('');   //清空收件人、收件人手机号、地址value
    jq('.adressWin-content textarea').val('');        //清空详细地址
    jq('#cityResult3').text('选择地址');                //清空选择的地址
    jq('.close-title').text("添加收货地址");            //title 文字
    jq('.adressWin').css('display','block');
    jq('.mui-adressWin-backdrop').css('display','block');
    jq('.adressWin-bottom').find('button').attr('class','mui-btn btn_add_address');
});
jq(".adressWin-bottom").on('click','.btn_add_address',function () {
    jq.ajax({
        type: "POST",
        url: "/address/add",
        data: jq(".address_form").serialize(),
        dataType: "json",
        success: function (data) {
            if (data['code'] == 0) {
                window.location.reload();
            }else {
                mui.toast(data.msg);
            }
        }
    });
});


jq(".adressWin-bottom").on('click','.btn_edit_address',function () {
    var address_card = jq(this).parents('.mui-card');
    jq.ajax({
        type: "POST",
        url: "/address/edit_submit",
        data: jq(".address_form").serialize(),
        dataType: "json",
        success: function (data) {
            if (data['code'] == 0) {
                window.location.reload();
            }else {
                mui.toast(data.msg);
            }
        }
    });
});


