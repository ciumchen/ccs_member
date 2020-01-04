
    <link href="/html/css/myInfo/putForward.css" rel="stylesheet" />
</head>

<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">提现</h1>
</header>
<div class="mui-content" style="overflow-y:auto; overflow-x:auto; width:400px; height:800px;">
    <ul class="mui-table-view" onclick="window.location.href = '/withdraw/withdraw_log'">
        <li class="mui-table-view-cell"><a class="mui-navigate-right">查看提现记录</a></li>
    </ul>
    <br>
    <section class="mui-slider-title-tap">
<!--        <div>-->
<!--            <span>银行卡</span>-->
<!--        </div>-->
        <div class="active">
            <span>支付宝</span>
        </div>
    </section>
    <section class="mui-slider-content">
        <!-- <div class="hide-class">
            银行卡支付
        </div> -->
        <div>
            <form id="withdraw_form">
                <div class="mui-card" style="margin: 0;">
                <div class="mui-card-content">
                    <div>
                        <div class="label-div">可提现额度：</div>
                        <div class="blue-label content-div"><?php echo $allow_withdraw_amount?></div>
                    </div>
                        <div>
                            <div class="label-div">提现金额：</div>
                            <div class="content-div blank-color ruo-bottom">
                                <input type="number" name="amount" placeholder="请输入提现金额(最低100)" />
                            </div>
                        </div>
                        <div>
                            <div class="label-div">支付宝账号：</div>
                            <div class="content-div blank-color ruo-bottom">
                                <input type="text" autocomplete="on" value="<?php echo $account?>" name="account" placeholder="请输入支付宝账号" />
                            </div>
                        </div>
                        <div class="last-div">
                            <div class="label-div">真实姓名：</div>
                            <div class="content-div blank-color ruo-bottom">
                                <input type="text" value="<?php echo $account_name?>" name="real_name" placeholder="请输入真实姓名" />
                            </div>
                        </div>
                    <div class="last-div">
                        <div class="label-div">支付密码：</div>
                        <div class="content-div blank-color ruo-bottom">
                            <input type="password" id="payPwd" name="payPwd" placeholder="请输入支付密码" />
                        </div>
                    </div>
                        <!--<div class="last-div">
                            <div class="label-div">手机号码：</div>
                            <div class="content-div blank-color ruo-bottom">
                                <input type="tel" name="mobile" placeholder="请输入手机号码" />
                            </div>
                        </div>
                        <div class="input_group">
                            <label for="mobile_code" id="icon1" class="phone_icon"></label>
                            <input id="mobile_code" name="mobile_code" placeholder="手机验证码"  type="tel">
                            <button type="button" id="code_button" class="btn_send_sms" data-type="1">发送验证码</button>
                        </div>-->
                        <ul class="mui-table-view">
                            <li class="mui-table-view-cell mui-checkbox mui-left">
                                <input name="agree" type="checkbox">
                                <span class="user-xieyi-line">我已阅读并同意《<span class="user-xieyi">供聚会联用户服务协议</span>》</span>
                            </li>
                        </ul>
                    <section class="submit-applacation btn_withdraw">提交申请</section>
                </div>
            </div>
            </form>
        </div>
    </section>
</div>
<?php require 'bottom_nav.php';?>
<script src="/html/js/user_withdraw.js"></script>