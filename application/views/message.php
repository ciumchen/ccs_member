<link href="/html/css/message.css" rel="stylesheet" />
<head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">消息中心</h1>
</header>
<div class="mui-content">
    <div class="topBar">
        <span class="onlineService <?php  if ($message_type == 100){echo 'active';}?>"  href="#onlineService" data-type="100">在线客服</span>
        <span class="systemMsg <?php  if ($message_type >= 10 && $message_type < 100){echo 'active';}?>" data-type="10" href="#systemMsg">系统通知</span>
        <span class="accountMsg <?php  if ($message_type < 10){echo 'active';}?>" data-type="0" href="#accountMsg">账户通知</span>
    </div>
    <div class="mui-card" id="onlineService">
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <span class="tip_icon"><img src="/html/img/提示-1.png"></span>
                <span class="tip_text">暂无客服消息</span><br>
            </div>
        </div>
    </div>
    <div class="mui-card" id="systemMsg">
        <?php if ($list == array()){?>
            <div class="mui-card-content">
                <div class="mui-card-content-inner">
                    <span class="tip_icon"><img src="/html/img/提示-1.png"></span>
                    <span class="tip_text">暂无系统通知</span><br>
                </div>
            </div>
        <?php }else{?>
            <?php foreach ($list as $item){?>
                <div class="mui-card-content">
                    <div class="mui-card-content-inner">
                        <?php  $href = $item['param2'] != '' ? $item['param2'] : 'javaScript:' ?>

                        <!-- 图片 -->
                        <?php if ($item['param3'] != ''){?>

                        <?php }?>

                        <!-- 标题 -->
                        <?php if ($item['param1'] != ''){?>
                            <span class="messageTitle"><a href="<?php echo $href;?>"><?php echo $item['param1']?></a></span>
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        <?php }?>
    </div>

    <div class="mui-card" id="accountMsg">
        <?php if ($list == array()) { ?>
            <div class="mui-card-content">
                <div class="mui-card-content-inner">
                    <span class="tip_icon"><img src="/html/img/提示-1.png"></span>
                    <span class="tip_text">暂无账户通知</span><br>
                </div>
            </div>
        <?php } else { ?>
            <?php foreach ($list as $item) { ?>
                <div class="mui-card-content msgBox">
                    <?php if ($item['type'] == 1){?>
                        <div class="mui-card-content-inner">
                            <span class="box_title">我的奖金</span>
                            <span class="box_context"><?php echo $item['content'];?></span>
                            <span class="box_param">入账金额：<?php echo $item['param1'];?></span>
                            <span class="box_time">入账时间：<?php echo $item['create_time']?></span>
                            <span class="box_details" onclick="window.location.href = '/reward'">点此查看入账详情</span>
                            <a class="details" href="/reward">详情</a>
                        </div>
                    <?php }?>
                    <?php if ($item['type'] == 2){?>
                        <div class="mui-card-content-inner">
                            <span class="box_title">我的直接分享</span>
                            <span class="box_context"><?php echo $item['content'];?></span>
                            <span class="box_param">新增会员：<?php echo $item['param1'];?></span>
                            <span class="box_time">完成率：<?php echo $percent ?>%</span>
                            <span class="box_time">新增时间：<?php echo $item['create_time']?></span>
                            <span style="margin-top: 10px"></span>
                            <a class="details" href="/share">详情</a>
                        </div>
                    <?php }?>

                    <?php if ($item['type'] == 3){?>
                        <div class="mui-card-content-inner">
                            <span class="box_title">我的订单</span>
                            <span class="box_context"><?php echo $item['content'];?></span>
                            <span class="box_time"><?php echo $item['param2']?></span>
                            <span style="margin-top: 10px"></span>
                            <a class="details" href="/order">详情</a>
                        </div>
                    <?php }?>

                    <?php if ($item['type'] == 4){?>
                        <div class="mui-card-content-inner">
                            <span class="box_title">我的积分</span>
                            <span class="box_context"><?php echo $item['content'];?></span>
                            <span class="box_param">入账积分：<?php echo $item['param1'];?></span>
<!--                            <span class="box_time">总积分：--><?php //echo $user_info['point'] ?><!--</span>-->
                            <span class="box_time">新增时间：<?php echo $item['create_time']?></span>
                            <span style="margin-top: 10px"></span>
                            <a class="details" href="/point">详情</a>
                        </div>
                    <?php }?>
                </div>
                <div class="line"></div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="bottomMargin"></div>
</div>
<?php require 'bottom_nav.php'?>
<script>
    var href = $(".topBar").find(".active").attr('href');
    $(".mui-content").find('.mui-card').hide();
    $(href).show();

    $(".topBar").on('click','span',function () {
        var obj = $(this);
        var messageType = obj.attr('data-type');
        var activeTab = obj.attr('href');
        if (!obj.hasClass('active')){
            obj.addClass('active');
            obj.siblings('span').removeClass('active');
        }
        window.location.href = '/message?type='+messageType;
    });
</script>

