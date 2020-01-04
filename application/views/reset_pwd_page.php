
<link rel="stylesheet" href="/html/css/login.css?v=1015">
<link rel="stylesheet" href="/html/css/login_icon.css?v=1015">
<body>

<div id="login" avalonctrl="login">
    <div class="container no-footer" style="top: 0%;">
        <form id="register-form">
            <div class="login_input">
                <div class="input_group">
                    <label for="code" id="icon1" class=""></label>
                    <input id="code" name="img_code" placeholder="图形验证码"  type="text">
                    <img id="img_code" src="/register/create_img_code" onClick="changeCode()"/>
                </div>
                <div class="input_group">
                    <label for="user_mobile" id="icon1" class="phone_icon"></label>
                    <input id="user_mobile" name="mobile" placeholder="手机号码"  type="tel">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="mobile_code" id="icon1" class="phone_icon"></label>
                    <input id="mobile_code" name="mobile_code" placeholder="手机验证码"  type="tel">
                    <button type="button" id="code_button" class="btn_send_sms" data-type="5">发送验证码</button>
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon2" class="phone_icon"></label>
                    <input id="input_code" name="password" placeholder="请输入新密码" type="password">
                </div>
            </div>
        </form>
        <button type="button" id="submit_button" class="btn-reset">重置密码</button>
    </div>
</div>
</body>
<script>
    function changeCode(){
        var img_code = document.getElementById('img_code');
        img_code.setAttribute('src','/register/create_img_code?r='+Math.random());
    }
</script>
<script src="/html/js/login_and_register.js?v=1015"></script>