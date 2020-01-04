
<link rel="stylesheet" href="/html/css/login.css?v=1015">
<link rel="stylesheet" href="/html/css/login_icon.css?v=1015">
<body>

<div id="login" avalonctrl="login">
    <div class="container no-footer" style="top: 0%;">
        <form id="register-form">
            <div class="login_input">
                <div class="input_group">
                    <label for="true_name" id="icon3" class="username_icon"></label>
                    <input id="true_name" name="true_name" placeholder="请输入姓名"  type="text">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="code" id="icon1" class=""></label>
                    <input id="code" name="img_code" placeholder="图形验证码"  type="text">
                    <img id="img_code" src="/register/create_img_code" onClick="changeCode()"/>
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon1" class="phone_icon"></label>
                    <input id="user_mobile" autocomplete="off" name="mobile" placeholder="手机号码"  type="tel">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="mobile_code" id="icon1" class="phone_icon"></label>
                    <input id="mobile_code" name="mobile_code" placeholder="手机验证码"  type="tel">
                    <button type="button" autocomplete="off" id="code_button" class="btn_send_sms" data-type="1">发送验证码</button>
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon2" class="phone_icon"></label>
                    <input id="input_code" autocomplete="off" name="password" placeholder="请输入密码" type="password">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="confirm_password" id="icon2" class="phone_icon"></label>
                    <input id="confirm_password" autocomplete="off" name="confirm_password" placeholder="请确认密码" type="password">
                </div>

                <?php if ($parent_mobile === 0){?>
                    <hr class="input_hr">
                    <div class="input_group">
                        <label for="user_mobile" id="icon1" class="phone_icon"></label>
                        <input id="user_mobile" autocomplete="off" name="parent_mobile" placeholder="推荐人手机号"  type="tel">
                    </div>
                <?php }else{?>
                    <input name="parent_mobile" value="<?php echo $parent_mobile;?>"  type="hidden">
                <?php }?>
            </div>
            <p>
                <input id="agreement" checked="checked" name="agree" type="checkbox">
                <label for="agreement"></label>
                <strong>我已经阅读并同意</strong>
                <a><span class="user-agreement">供聚会联用户服务协议</span></a>
            </p>
        </form>
        <button type="button" id="submit_button" class="btn-register">注册新账号</button>
        <button class="submit_button_login" onclick="window.location.href = '/login/login_page'">登录</button>
    </div>
</div>
</body>
<script>
    function changeCode(){
        var img_code = document.getElementById('img_code');
        img_code.setAttribute('src','/register/create_img_code?r='+Math.random());
    }
</script>
<script src="/html/js/login_and_register.js?v=1018"></script>