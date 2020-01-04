
<link href="/html/css/account_manage.css" rel="stylesheet" />
<link rel="stylesheet" href="/html/css/icons-extra.css">
<title>账户管理</title>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">账号设置</h1>
</header>
<div class="mui-content" style="overflow-y:auto; overflow-x:auto; height:800px;">
    <div class="mui-page-content">
        <div class="mui-scroll-wrapper">
            <section class="mui-top-section" onclick="window.location.href = '/account_manage/person_info'">
                <div class="account-setting">
                    <span class="mui-icon mui-icon-gear"><span>账号管理</span></span>
                </div>
                <div class="account-info-detial">
                    <div class="img-info">
                        <img src="<?php echo $user_info['image_url'];?>">
                    </div>
                    <div class="name-detial">
                        <span><?php echo $user_info['true_name'] == '' ? $user_info['username'] : $user_info['true_name'];?></span>
                        <span class="mui-badge mui-badge-purple">V<?php echo $user_info['level'];?></span>
                    </div>
                    <div class="jifen-detial">
                        <span class="mui-badge sg-purple">个人资料</span>
                    </div>
                </div>
                <span class="mui-icon mui-icon-arrowright top-arrowright"></span>
            </section>

            <ul class="mui-table-view personList">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right" onclick="window.location.href = '/account_manage/setLoginPwd'">
                        <span class="mui-icon mui-icon-locked"></span>
                        <span>账号安全</span>
                        <span class="right_text">登录密码、手机号</span>
                    </a>
                </li>

                <?php if ($user_info['wx_info'] == '') {?>
                    <li class="mui-table-view-cell" onclick="window.location.href = '/api/wechat'">
                        <a class="mui-navigate-right">
                            <span class="mui-icon mui-icon-weixin"></span>
                            <span>绑定支付</span>
                            <span class="right_text">微信</span>
                        </a>
                    </li>
                <?php }else{ ?>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" id="confirmBtn">
                        <span class="mui-icon mui-icon-weixin"></span>
                        <span>绑定微信</span>
                        <span class="right_text" style="color: red">解绑</span>
                        </a>
                    </li>
                <?php }?>

                <li class="mui-table-view-cell" onclick="window.location.href = '/account_manage/payPwd'">
                    <a class="mui-navigate-right">
                        <span class="mui-icon mui-icon-gear"></span>
                        <span>支付设置</span>
                        <span class="right_text">设置支付、提现密码</span>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right" href="<?php echo $address_url; ?>">
                        <span class="mui-icon mui-icon-location"></span>
                        <span>收货地址</span>
                        <span class="right_text">收货地址管理</span>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right" onclick="window.location.href = '/faq'">
                        <span class="mui-icon mui-icon-help"></span>
                        <span>帮助中心</span>
                        <span class="right_text">常见问题、疑难解答</span>
                    </a>
                </li>
            </ul>
            <ul class="mui-table-view logout" onclick="window.location.href = '/account_manage/logout'">
                <li class="mui-table-view-cell" style="text-align: center;">
                    <a>退出登录</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php require 'bottom_nav.php';?>
<script>
    mui.init();

    $(".head_ico_file").change(function () {
        var head_ico_file = $(".head_ico_file")[0].files[0];
        var formData = new FormData();
        formData.append("head_ico_file",head_ico_file);
        $.ajax({
            type: "POST",
            url: "/account_manage/upload_head_ico",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (data) {
                mui.toast(data['msg']);
                if (data['code'] === 0){
                    window.location.reload();
                }
            }
        });
    });

    $("#confirmBtn").on('click', function() {
        var btnArray = ['否', '是'];
        mui.confirm('解绑微信，确认？', '解绑微信', btnArray, function(e) {
            if (e.index == 1) {
                $.ajax({
                    type: "POST",
                    url: "/api/wechat_remove",
                    data: [],
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        mui.toast(data['msg']);
                        if (data['code'] === 0){
                            window.location.reload();
                        }
                    }
                });
            } else {

            }
        })
    });
</script>