$(".btn_withdraw").on('click',function () {
    showLoading();
    $.ajax({
        type: "POST",
        url: "/withdraw/submit",
        data: $("#withdraw_form").serialize(),
        dataType: "json",
        success: function (data) {
            hideLoading();
            mui.toast(data['msg']);
            if (data['code'] === 0){
                window.location.href = '/withdraw/withdraw_log';
            }
        }
    });
})