<link rel="stylesheet" href="/html/css/myInfo/myInfo.css?v=1">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <script src="../../html/js/mui.min.js"></script>
    <link href="../../html/css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../../html/css/icons-extra.css">
    <link rel="stylesheet" href="../../html/css/home/bottomTap.css">
    <link rel="stylesheet" href="../../html/css/myInfo/myInfo.css">
    <link rel="stylesheet" href="../../html/css/marketing/dialog-register-success.css">
</head>
<link rel="stylesheet" href="/html/css/myInfo/myInfo.css?v=1">
<body>
<div class="mui-content">
	<section class="mui-top-section">
		<div class="account-setting">
			<span class="mui-icon mui-icon-gear" onclick="window.location.href='/account_manage/index'"><span>账号管理</span></span>
		</div>
		<div class="account-info-detial">
			<div class="img-info" onclick="window.location.href='/account_manage/index'">
				<img src="<?php echo $user_info['image_url']?>"/>
			</div>
            <div class="detail-wrap">
                <div class="name-detial">
                    <span><?php echo $user_info['true_name'] == '' ? $user_info['username'] : $user_info['true_name'];?></span>
                    <span class="mui-badge mui-badge-purple">V<?php echo $user_info['level']?></span>
                </div>
                <div class="jifen-detial">
                    <span class="mui-badge sg-purple" onclick="window.location.href = '/point'">积分：<?php echo $user_info['point']?></span></br>
                </div>
            </div>
            <div class="today-price">
                <div class="today-price-box">
					今日牌价
					<!--<br><span class="number"><?php echo $goldPrice; ?></span>-->
					<br><span class="number">2.07</span>
                </div>
            </div>
        </div>
        <div class="click-check">
            <a onclick="window.location.href = '/gold'"><img src="/html/img/myInfo/click-check.png"></a>
        </div>
	</section>
	<section class="mes-info" onclick="window.location.href = '/message?type=<?php echo $user_info['message_type']?>'">
		<img src="/html/img/myInfo/messageToast.png" class="mes-toast"/>
		<span class="mess-title">消息中心</span>
		<span class="mui-icon mui-icon-arrowright"></span>
		<?php if ($user_info['message_type'] != 0){?>
			<span class="mess-content">你有一条新的<?php echo config_item('message_type')[$user_info['message_type']]?>信息<span><img src="/html/img/myInfo/point.png" class="point"/></span></span>
		<?php }else{?>
			<span class="mess-content">暂无未读信息<span></span></span>
		<?php }?>
	</section>
	<section class="my-bonus common-section">
		<div class="bonus-title" onclick="window.location.href = '/reward'">
			<img src="/html/img/myInfo/amountjj.png"/>
			<span class="bonus-title-label">我的奖金</span>
			<span class="mui-icon mui-icon-arrowright"></span>
            <span class="more-info">更多</span>
		</div>
		<ul class="mui-table-view mui-grid-view mui-grid-9">
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/amassjj.png"/></span>
					<div class="mui-media-body title">累积</div>
					<div class="mui-media-body"><?php echo $total_reward?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/currentjj.png"/></span>
					<div class="mui-media-body title">本期</div>
					<div class="mui-media-body"><?php echo $month_reward;?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/recordedjj.png"/></span>
					<div class="mui-media-body title">待入账</div>
					<div class="mui-media-body"><?php echo $wait_reward;?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" onclick="goPutForword()">
				<a href="/withdraw">
					<span class="mui-icon"><img src="/html/img/myInfo/withderjj.png"/></span>
					<div class="mui-media-body title">可提现</div>
					<div class="mui-media-body"><?php echo $allow_withdraw_amount;?></div>
				</a>
			</li>
		</ul>
	</section>
    <section class="my-bonus common-section">
        <div class="bonus-title" onclick="window.location.href = '/gold'">
            <img src="/html/img/myInfo/ccsdatajb.png"/>
            <span class="bonus-title-label">数据金币</span>
            <span class="mui-icon mui-icon-arrowright"></span>
            <span class="more-info">更多</span>
        </div>
        <ul class="mui-table-view mui-grid-view mui-grid-9">
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/acbounsjb.png"/></span>
                    <div class="mui-media-body title">累积</div>
                    <div class="mui-media-body"><?php echo $total_gold?></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/operatejb.png"/></span>
                    <div class="mui-media-body title">大数据</div>
                    <div class="mui-media-body"><?php echo isset($showGold[4]) ? $showGold[4] : 0 ;?></div>
                </a>
            </li>
            <!-- <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/signjb.png"/></span>
                    <div class="mui-media-body title">签到</div>
                    <div class="mui-media-body"><?php echo isset($showGold[1]) ? $showGold[1] : 0 ;?></div>
                </a>
            </li> -->
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/pointjb.png"/></span>
                    <div class="mui-media-body title">积分</div>
                    <div class="mui-media-body"><?php echo isset($showGold[2]) ? $showGold[2] : 0 ;?></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/sharejb.png"/></span>
                    <div class="mui-media-body title">分享</div>
                    <div class="mui-media-body"><?php echo isset($showGold[3]) ? $showGold[3] : 0 ;?></div>
                </a>
            </li>
             <br/>
            <!-- <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/operatejb.png"/></span>
                    <div class="mui-media-body title">大数据</div>
                    <div class="mui-media-body"><?php echo isset($showGold[4]) ? $showGold[4] : 0 ;?></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/buyjb.png"/></span>
                    <div class="mui-media-body title">兑换</div>
                    <div class="mui-media-body"><?php echo isset($showGold[5]) ? $showGold[5] : 0 ;?></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a>
                    <span class="mui-icon"><img src="/html/img/myInfo/bestowaljb.png"/></span>
                    <div class="mui-media-body title">受赠</div>
                    <div class="mui-media-body"><?php echo isset($showGold[6]) ? $showGold[6] : 0 ;?></div>
                </a>
            </li> -->
        </ul>
    </section>
	<section class="my-share common-section">
		<div class="bonus-title" onclick="window.location.href = '/share'">
			<img src="/html/img/myInfo/my-share.png"/>
			<span class="bonus-title-label">我的分享</span>
			<span class="mui-icon mui-icon-arrowright"></span>
            <a href="/share"><span class="more-info">更多</span></a>
		</div>
		<ul class="mui-table-view mui-grid-view mui-grid-9">
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/dirsharefx.png"/></span>
					<div class="mui-media-body title">直接分享</div>
					<div class="mui-media-body"><?php echo $child_count;?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/salesfx.png"/></span>
					<div class="mui-media-body title">直推销售额</div>
					<div class="mui-media-body"><?php echo $child_amount;?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/teamfx.png"/></span>
					<div class="mui-media-body title">全部团队</div>
					<div class="mui-media-body"><?php echo $all_child_count;?></div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/salesfx.png"/></span>
					<div class="mui-media-body title">团队销售额</div>
					<div class="mui-media-body"><?php echo $all_child_amount;?></div>
				</a>
			</li>
		</ul>
	</section>
	<section class="other-section common-section">
		 <div class="bonus-title">
			<img src="/html/img/myInfo/tools.png"/>
			<span class="bonus-title-label">必备工具</span>
		</div>
		<ul class="mui-table-view mui-grid-view mui-grid-9">
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="<?php echo $order_url ?>">
					<span class="mui-icon"><img src="/html/img/myInfo/order.png"/></span>
					<div class="mui-media-body other-title">我的订单</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="<?php echo $coupons_url ?>">
					<span class="mui-icon"><img src="/html/img/myInfo/coupon.png"/></span>
					<div class="mui-media-body other-title">我的卡包</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" onclick="window.location.href = '/welcome/create_share'">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/sharehb.png"/></span>
					<div class="mui-media-body other-title">分享海报</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" >
				<a href="<?php echo $auction_url ?>">
					<span class="mui-icon"><img src="/html/img/myInfo/auction.png"/></span>
					<div class="mui-media-body other-title">我的拍卖</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="<?php echo $money_url ?>">
					<span class="mui-icon"><img src="/html/img/myInfo/margin.png"/></span>
					<div class="mui-media-body other-title">我的保证金</div>
				</a>
			</li>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="<?php echo $history_url?>">
					<span class="mui-icon"><img src="/html/img/myInfo/footprint.png"/></span>
					<div class="mui-media-body other-title">我的足迹</div>
				</a>
			</li>
			<?php if($user_info['level'] <= 2 || $count > 0){ ?>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" onclick="window.location.href = '/plan'">
				<a>
					<span class="mui-icon"><img src="/html/img/myInfo/collect.png"/></span>
					<div class="mui-media-body other-title">帮扶计划</div>
				</a>
			</li>
			<?php }else{ ?>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="<?php echo $collection_url?>">
					<span class="mui-icon"><img src="/html/img/myInfo/collect.png"/></span>
					<div class="mui-media-body other-title">我的收藏</div>
				</a>
			</li>
			<?php } ?>
			<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
				<a href="tel:0755-83573441">
					<span class="mui-icon"><img src="/html/img/myInfo/call.png"/></span>
					<div class="mui-media-body other-title">客服中心</div>
				</a>
			</li>
		</ul>
	</section>

	<?php require 'bottom_nav.php';?>

	<!-- 注册成功弹窗 -->
	<div id="dialog-register-success" class="mui-popover x-dialog-wrap">
		<div class="inner-wrap">
			<div class="btn-success-wrap">
				<a href="javascript:;" class="btn-success">恭喜你注册成功！</a>
			</div>
			<!-- <div class="discounts-item">
				<div class="discounts-wrap discounts-a">
					<div class="left-text">
						20<small>元</small>
						<span class="extra">指定商品满29元使用</span>
					</div>
					<div class="right-text right-text-a">优惠券</div>
				</div>
				<div class="discounts-use discounts-use-a">
					<h2>已入账</h2>
					<p>立即使用</p>
				</div>
			</div> -->
			<div class="discounts-item">
				<div class="discounts-wrap discounts-b">
					<div class="left-text">
						20
					</div>
					<div class="right-text right-text-b">金币等价券</div>			
				</div>
				<div class="discounts-use discounts-use-b">
					<h2>已入账</h2>
					<a onclick="window.location.href = '/gold'"><p>查看</p>
				</div>			
			</div>	
			<div class="tip">进入CCS大数据采集平台即可使用<img src="../../html/img/market/arrow.png"></>	
		</div>
	</div>
</div>
</body>

<script>

    <?php if($isAlert){ ?>
	// 成功注册后弹出
    mui('#dialog-register-success').popover('show', document.body)
    <?php }?>

	function tips() {
		mui.toast('持续开放中...')
	}
</script>
