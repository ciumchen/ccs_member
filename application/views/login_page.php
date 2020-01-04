
    <link rel="stylesheet" href="/html/css/login.css">
    <link rel="stylesheet" href="/html/css/login_icon.css">
<body>

<div id="login" avalonctrl="login">
    <div class="container no-footer" style="top: 0%;">
        <form id="login-form">
            <div class="login_input">
                <div class="input_group">
                    <label for="user_mobile" id="icon1" class="phone_icon"></label>
                    <input id="user_mobile" name="mobile" placeholder="手机号码"  type="tel">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon2" class="phone_icon"></label>
                    <input id="input_code" name="password" placeholder="密码" type="password">
                </div>
            </div>
        </form>
        <button type="button" id="submit_button" class="btn-login">登录</button>
        <button class="submit_button_login" onclick="window.location.href = '/register/register_page'">注册新账号</button>
        <p style="line-height: 40px;margin-left: 3%;color: #a1a1a1;"><a href="/login/reset_pwd">忘记了密码?</a></p>
    </div>
</div>
</body>
<script src="/html/js/login_and_register.js"></script>