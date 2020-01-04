
<link rel="stylesheet" href="/html/css/login.css?v=1017">
<link rel="stylesheet" href="/html/css/login_icon.css?v=1018">
<body>

<div id="login" avalonctrl="login">
    <?php if(!$pay_pwd){?>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">设置支付密码</h1>
    </header>
    <div class="container no-footer" style="top: 5%;">
        <form id="register-form">
            <div class="login_input">
                <div class="input_group">
                    <label for="code" id="icon1" class=""></label>
                    <input id="code" name="img_code" placeholder="图形验证码"  type="text">
                    <img id="img_code" src="/welcome/create_img_code" onClick="changeCode()"/>
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon1" class="phone_icon"></label>
                    <input id="user_mobile" name="mobile" value="<?php echo $mobile;?>" placeholder="手机号码"  type="hidden">
                    <input disabled value="<?php echo $mobile;?>" type="tel">
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="mobile_code" id="icon1" class="phone_icon"></label>
                    <input id="mobile_code" name="mobile_code" placeholder="手机验证码"  type="tel">
                    <button type="button" id="code_button" class="btn_send_sms" data-type="6">发送验证码</button>
                </div>
                <hr class="input_hr">
                <div class="input_group">
                    <label for="user_mobile" id="icon2" class="phone_icon"></label>
                    <input id="password" name="password" placeholder="请输入新密码" type="password">
                </div>
                <div class="input_group">
                    <label for="confirm_password" id="icon2" class="phone_icon"></label>
                    <input id="confirm_password" name="confirm_password" placeholder="请确认密码" type="password">
                </div>
            </div>
        </form>
        <button type="button" id="submit_button_pay_pwd" class="btn-reset1">提交</button>
    </div>
    </div>
    <?php }else{?>
        <header class="mui-bar mui-bar-nav">
            <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
            <h1 class="mui-title">修改支付密码</h1>
        </header>
        <div class="container no-footer" style="top: 5%;">
            <form id="register-form">
                <div class="login_input">
                    <div class="input_group">
                        <label for="old_password" id="icon2" class="phone_icon"></label>
                        <input id="old_password" name="old_password" value="" placeholder="原支付密码"  type="password">
                    </div>
                    <hr class="input_hr">
                    <div class="input_group">
                        <label for="user_mobile" id="icon2" class="phone_icon"></label>
                        <input id="password" name="password" placeholder="请输入新密码" type="password">
                    </div>
                    <div class="input_group">
                        <label for="confirm_password" id="icon2" class="phone_icon"></label>
                        <input id="confirm_password" name="confirm_password" placeholder="请确认密码" type="password">
                    </div>
                </div>
            </form>
            <button type="button" id="update_pay_pwd" class="btn-reset1 set_pay_pwd">提交</button>
            <div class="input_group">
                <label style="float: right"><a href="/Account_manage/setPayPwd">忘记支付密码？</a></label>
            </div>
        </div>
    <?php }?>
</div>
<?php require 'bottom_nav.php';?>
</body>
<script>
    function changeCode(){
        var img_code = document.getElementById('img_code');
        img_code.setAttribute('src','/register/create_img_code?r='+Math.random());
    }
</script>
<script src="/html/js/login_and_register.js?v=1015"></script>