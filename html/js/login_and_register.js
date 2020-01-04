/*--获取重定向参数--*/
function request(paras)
{
    var url = location.href;
    var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
    var paraObj = {}
    for (i=0; j=paraString[i]; i++){
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if(typeof(returnValue)=="undefined"){
        return "";
    }else{
        return returnValue;
    }
}

/*  倒计时 */
function time(obj,wait,oldVal) {
    if (wait == 0) {
        obj.attr("disabled",false);
        obj.text(oldVal);
        obj.css('color','');
        obj.css('border','1px solid #e02e24');
        wait = 60;
    } else {
        obj.attr("disabled", true);
        obj.css('color','#BBBBBB');
        obj.css('border','1px solid #6663');
        obj.text('等待'+wait+'s');
        wait--;
        setTimeout(function () {
            time(obj,wait,oldVal)
        }, 1000)
    }
}

/* login */
$('.btn-login').on('click',function () {
    var obj = $(this);
    var redirect = request('redirect');
    var url = redirect ? redirect :'/';
    $.ajax({
        type: "POST",
        url: "/login/login_for_pwd",
        data: $('#login-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                window.location.href = url;
            }
        }
    });
});


/* 手机发送验证码 */
$('.btn_send_sms').on('click',function () {
    var obj = $(this);
    var smsType = obj.attr('data-type');
    if (smsType == undefined){
        $("#register-form").append("<input name='sms_type' value='sms' type='hidden'/>")
    }else {
        $("#register-form").append("<input name='sms_type' value='"+smsType+"' type='hidden'/>")
    }
    var oldVal = obj.text();
    $.ajax({
        type: "POST",
        url: "/login/send_sms",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data.code == 100) {
                time(obj,60,oldVal);
            }
        }
    });
});

$('.btn-register').on('click',function () {
    var redirect = request('redirect');
    var url = redirect ? redirect :'/';
    $.ajax({
        type: "POST",
        url: "/register/check_register",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                window.location.href = url+'?isAlert=1';
            }
        }
    });
});

$('.btn-reset').on('click',function () {
    $.ajax({
        type: "POST",
        url: "/login/forget_pwd_submit",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                setTimeout(function () {
                    window.location.href = '/login/login_page';
                },1500);
            }
        }
    });
});

$('#submit_button_pay_pwd').on('click',function () {
    $.ajax({
        type: "POST",
        url: "/account_manage/set_pay_pwd",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                setTimeout(function () {
                    window.location.href = '/account_manage/index';
                },1500);
            }
        }
    });
});

$('#update_pay_pwd').on('click',function () {
    $.ajax({
        type: "POST",
        url: "/account_manage/modify_pay_pwd",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                setTimeout(function () {
                    window.location.href = '/account_manage/index';
                },1500);
            }
        }
    });
});

$('#update_login_pwd').on('click',function () {
    $.ajax({
        type: "POST",
        url: "/account_manage/modify_login_pwd",
        data: $('#register-form').serialize(),
        dataType: "json",
        success: function (data) {
            mui.toast(data['msg']);
            if (data['code'] == 0) {
                setTimeout(function () {
                    window.location.href = '/account_manage/index';
                },1500);
            }
        }
    });
});
