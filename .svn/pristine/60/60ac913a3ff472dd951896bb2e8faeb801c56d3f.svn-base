<link href="/html/css/coupons.css" rel="stylesheet" />
<head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">优惠券</h1>
</header>
<div class="mui-content">
    <div class="topBar">
        <span class="hongbao active" href="#hongbao">红包</span>
        <span class="coupons" href="#coupons">优惠券</span>
    </div>
    <div class="mui-card" id="hongbao">
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <span class="tip_icon"><img src="/html/img/提示-1.png"></span>
                <span class="tip_text">红包专区即将上线，敬请期待！</span>
            </div>
        </div>
    </div>
    <div class="mui-card" id="coupons" style="display: none">
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <span class="tip_icon"><img src="/html/img/提示-1.png"></span>
                <span class="tip_text">暂无优惠券信息</span><br>
                <span class="tip_text2">请关注商城活动</span>
            </div>
        </div>
    </div>
</div>
<?php require 'bottom_nav.php'?>
<script>
    $(".topBar").on('click','span',function () {
        var obj = $(this)
        var activeTab = obj.attr('href');
        if (!obj.hasClass('active')){
            obj.addClass('active');
            obj.siblings('span').removeClass('active');
        }
        $("#hongbao").hide();
        $("#coupons").hide();
        $(activeTab).show();
    });
</script>

