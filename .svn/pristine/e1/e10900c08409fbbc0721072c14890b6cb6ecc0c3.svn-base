$(".btn_admin_login").click(function () {
    $.ajax({
        type: "POST",
        url: "/admin/Index/admin_login_submit",
        data: $('#admin_form_login').serialize(),
        dataType: "json",
        success: function (data)
        {
            if (data.code == 1001 || data.code == 1011) {
                layer.msg(data.msg);
                $("[name='admin_login_name']").focus();
            }
            else if (data.code == 1002 || data.code == 1012)
            {
                layer.msg(data.msg);
                $("[name='admin_login_password']").focus();
            }
            else if (data.code == 1013){
                layer.msg(data.msg);
            }
            else if (data.code == 100)
            {
                window.location.href = '/admin/users_manage/index';
            }
        }
    });
});

//登录回车键
$("body").keydown(function(e) {
    var a = e||window.event
    if (a.keyCode == '13') {//keyCode=13是回车键
        $(".btn_admin_login").click();
    }
});
