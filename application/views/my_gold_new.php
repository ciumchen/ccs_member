    <link href="/html/css/myInfo/goldenCoin.css" rel="stylesheet" />
    <script src="/html/js/mui.picker.min.js"></script>
</head>
<body class="golden-coin">
	<!-- 顶部标题栏 -->
	
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <!--<a class="mui-pull-right">
            <img src="/html/img/icon/icon-more.png" alt="">
        </a>-->
        <h1 class="mui-title">数据金币</h1>
	</header>
	
	<!-- 内容区域 -->
    <div class="mui-content">
		<!-- 总览 -->
        <div class="section section-overview">
            <div class="section-content">
                <div class="statistics">
                    <div class="small_title">金币总量</div>
                    <div class="statistics_number"><?php echo $user_info['gold'];?> <em>枚</em></div>
                    <div class="statistics_btn">
						<a href="<?php echo $addon_url ?>"><span class="left-span">兑换商品</span></a>
                        <span class="right-span exchange-goldencoin">转赠</span>
                    </div>
                    <div class="today-price">
                        <!--今日牌价：<?php echo $goldPrice ? $goldPrice :'计算中...' ?>-->
                        今日牌价：2.07
                    </div>
                </div>
                <div class="statistics-detail">
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[1]) ? $showGold[1] : 0 ;?></div>
                        <div class="small_title">签到</div>
                    </div>
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[2]) ? $showGold[2] : 0 ;?></div>
                        <div class="small_title">积分</div>
                    </div>
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[3]) ? $showGold[3] : 0 ;?></div>
                        <div class="small_title">分享</div>
                    </div>
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[4]) ? $showGold[4] : 0 ;?></div>
                        <div class="small_title">大数据</div>
                    </div>
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[5]) ? $showGold[5] : 0 ;?></div>
                        <div class="small_title">兑换</div>
                    </div>
                    <div class="statistics-detail-item">
                        <div><?php echo isset($showGold[6]) ? $showGold[6] : 0 ;?></div>
                        <div class="small_title">受赠</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 累计签到 -->
        <div class="section section-checkin">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/myInfo/goldencoin/checkin/icon-title.png"> 累计签到
                </span>
                <div class="section-title__right" onclick="window.location.href = '/sign/sign_rule'">
                    查看规则
                    <span class="mui-icon mui-icon-arrowright"></span>
                </div>
            </div>
            <div class="mui-row section-content">
                <div>
                    <div class="circle-small em-font <?php if($isSign){ ?>checked<?php }?>" id="amount">
                        +<?php echo $before_amount ?>
                    </div>
                    <button class="circle-big to-check <?php if($isSign){ ?>checked<?php }?>" <?php if($isSign){ ?>disabled="disabled"<?php }?> id="sign-btn"></button>
                   <!-- <div class="circle-small em-font">
                        +0.3
                    </div>-->
                </div>
                <div class="section-checkin-description">
                    (已连续签到<em id="total_sign"><?php echo $user_info['total_day'] ?></em>天)
                </div>
            </div>
        </div>
        <!-- 积分奖励-->
        <div class="section section-credit-reward">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-file-1@3x.png" alt="" srcset=""> 积分奖励
                </span>
            </div>
            <div class="mui-row section-content">
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V1@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">1-2</div>
                </div>
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V2@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">2-3</div>
                </div>
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V3@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">3-4</div>
                </div>
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V4@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">4-5</div>
                </div>
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V5@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">5-6</div>
                </div>
                <div class="mui-col-xs-2 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/credit-reward/JF-V6@3x.png" alt="" srcset="">
                    <div>每天奖励</div>
                    <div class="amountMoney">6-x</div>
                </div>
            </div>
            <div class="section-footer">
                注：积分所获得的奖励均为数据金币等价券。
            </div>
        </div>
        <!-- 分享奖励 -->
        <div class="section section-share">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/myInfo/goldencoin/share/icon-title.png" alt="" srcset=""> 分享奖励
                </span>
            </div>
            <div class="mui-row section-content" style="width:200px;margin: 0 auto;">
                <div class="mui-col-xs-6 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/share/first.png" alt="" srcset="">
                    <div>首次消费</div>
                    <div class="amountMoney">5</div>
                </div>
                <div class="mui-col-xs-6 rewardItem">
                    <img src="/html/img/myInfo/goldencoin/share/second.png" alt="" srcset="">
                    <div>完成升级</div>
                    <div class="amountMoney">20</div>
                </div>
            </div>
            <div class="section-footer">
                注：会员在平台首次消费，推荐人获得5/人；完成升级，推荐人获得20/级。
            </div>
        </div>
        <!-- 经营奖励 -->
        <div class="section seciton-operate">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/myInfo/goldencoin/operate/icon-title.png" alt="" srcset=""> 大数据奖励
                </span>
            </div>
            <div class="section-footer">
                <div class="f-text-middle">
                    <p>平台每个月将大数据创造的利润奖励给团队领导人；</p>
                    <p>团队领导人必须是V1及以上会员，团队规模4人以上</p>
                </div>
            </div>
        </div>
        <!-- 金币明细 -->
        <div class="section section-detail-list">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/myInfo/goldencoin/detail-list/icon-title.png" alt="" srcset=""> 金币明细
                </span>
            </div>
            <div class="section-content">
<!--                <div class="datePicker">-->
<!--                    <div type="text" class="input" id='startDate' onclick="chooseDate('start')">-->
<!--                        <span class="mui-icon-extra mui-icon-extra-regist"></span>-->
<!--                        2018-05-01-->
<!--                    </div>-->
<!--                    <span>-</span>-->
<!--                    <div type="text" class="input" id='endDate' onclick="chooseDate('end')">-->
<!--                        <span class="mui-icon-extra mui-icon-extra-regist"></span>-->
<!--                        2018-06-01</div><span class="mui-icon mui-icon-search" onclick="search.init()">-->
<!--                    </span>-->
<!--                </div>-->
                <div class="detail-list" id="pointBox" data-page="1">

                    <?php foreach ($list as $value){?>
                    <div class="detail-list-item">
                        <div class="detail-list-item__time">
                            <span><?php echo $value['create_time'] ?></span>
                            <span class="detail-list-item--right">金币总额</span>
                        </div>
                        <div class="detail-list-item_content">
                            <span><?php echo $value['type_text'] ?></span>
                            <span class="detail-list-item--right <?php if($value['gold']>0){?>add<?php }?>"><?php if($value['gold']>0){?>+<?php }?><?php echo $value['gold'] ?></span>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php if (count($list) == 0){?>
                    <div class="show_no">暂无相关信息</div>
                <?php }?>
                <?php if (count($list) >= 10){?>
                    <div id="review_more" class="review_more">点击查看更多</div>
                <?php }else{?>
                    <div id="review_more" style="background: none;" class="review_more">没有更多数据了</div>
                <?php }?>
            </div>
        </div>
    </div>
	
	<!-- 底部导航栏  -->
    <?php require 'bottom_nav.php';?>
	
    <!-- 转赠金币弹窗 -->
    <div id="dialog-exchange" class="mui-popover x-dialog-wrap">
        <div class="x-dialog">
            <div class="x-dialog-header">
                转赠金币
                <div class="x-dialog-header__close">
                    <span class="mui-icon mui-icon-closeempty"></span>
                </div>
            </div>
            <div class="x-dialog-content m-form">
                <div class="form-item">
                    <div class="form-item-label">受赠人手机号:</div>
                    <div class="form-item-content f-cb">
                        <input type="text" class="f-fl phone" maxlength="20" name="phone" value="">
                        <span class="f-fl name"><em id="receive_name"></em>
                        <input type="hidden" value="0" id="isConfirm">
                        <button class="f-fl mui-btn phone-submit">确定</button>
                    </div>
                </div>
                <div class="form-item coin-number">
                    <div class="form-item-label">金币数量:</div>
                    <div class="form-item-content f-cb">
                        <input type="text" maxlength="5" name="number" value="">
                        <div class="info">
                            本次转赠将消耗<em id="f_gold">0</em>枚金币<br/>
                            包含15%的手续费
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-label">支付密码:</div>
                    <div class="form-item-content f-cb">
                        <input type="password" name="password" maxlength="35">
<!--                        <div class="icon-password"></div>-->
                    </div>
                </div>
                <div class="form-item">
                    <button type="button" class="mui-btn btn-submit confirm_btn">确定</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 转赠金币成功弹窗 -->
    <div id="dialog-exchange-success" class="mui-popover x-dialog-wrap">
        <div class="x-dialog">
            <div class="x-dialog-header">
                转赠金币
                <div class="x-dialog-header__close">
                    <span class="mui-icon mui-icon-closeempty"></span>
                </div>
            </div>
            <div class="x-dialog-content m-form">
                <div class="success-message">
                    转赠成功
                </div>
                <div class="form-item">
                    <button type="button" class="mui-btn btn-submit close_btn">确定</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var search = {
            init: function () {
                mui('.dateArr')[0].style.display = "block";
                mui('.dateArr .date')[0].innerHTML = mui('#startDate')[0].innerHTML + "---" + mui('#endDate')[0].innerHTML;
            },
            clear: function () {
                mui('#startDate')[0].innerHTML = '';
                mui('#endDate')[0].innerHTML = '';
                mui('.dateArr .date')[0].innerHTML = '';
                mui('.dateArr')[0].style.display = "none";
            }

        }
        function chooseDate(str) {
            var ele = null;
            if (str == 'end') {
                ele = document.getElementById('endDate')
            } else {
                ele = document.getElementById('startDate')
            }
            var picker = new mui.DtPicker({ "type": "date" });
            picker.show(function (rs) {
                console.log(rs);
                // ele = rs.text
                ele.innerHTML = rs.text;
                picker.dispose();
            });
        }
        mui.init();

        // mui('body').on('click', '.exchange-goldencoin', function () {
        //     //mui('#dialog-exchange-success').popover('show', document.body)
        //     mui('#dialog-exchange').popover('show', document.body);
        // });

        $('.exchange-goldencoin').click(function () {
            mui('#dialog-exchange').popover('show', document.body);
        });

        mui('body').on('click', '.x-dialog-header__close', function () {
            mui('#dialog-exchange-success').popover('hide');
            mui('#dialog-exchange').popover('hide');
        });
        mui('body').on('click', '.close_btn', function () {
            mui('#dialog-exchange-success').popover('hide');
            mui('#dialog-exchange').popover('hide');
        });

        $('.gold_get_goods').click(function () {
            mui.toast('持续开放中');
        });
        $('.phone-submit').on('click',function () {

            if($('[name="phone"]').val() == ''){
                mui.toast('请输入手机号。');
                return;
            }
            $(this).prop("disabled",true);
            $.ajax({
                url:"/Gold/getReceive",
                data:{mobile:$('[name="phone"]').val()},
                type:"post",
                success:function(data){
                    if(data.code == 0){
                        $('#receive_name').text('('+data.data.name+')');
                        $('#isConfirm').val(1);
                        $('.phone-submit').hide();
                    }else{
                        $('.phone-submit').removeAttr("disabled");
                        mui.toast(data.msg);
                    }

                }
            });
        });

        $('.confirm_btn').click(function () {

            var mobile = $('[name="phone"]').val();
            var gold = $('[name="number"]').val();
            var password = $('[name="password"]').val();

            if(mobile == ''){
                mui.toast('请输入手机号');
                return;
            }
            if($('#isConfirm').val() == 0){
                mui.toast('请确认手机号');
                return;
            }
            if(gold == ''){
                mui.toast('请输入金币数量');
                return;
            }
            if(password == ''){
                mui.toast('请输入支付密码');
                return;
            }

            var data = {};
            data.gold = gold;
            data.mobile = mobile;
            data.payPwd = password;

            $(this).prop("disabled",true);
            $.ajax({
                url:"/Gold/TransferMemberGold",
                data:data,
                type:"post",
                success:function(data){
                    if(data.code == 0){
                        $('.statistics_number').html(data.data.gold+'<em>枚</em>');
                        mui('#dialog-exchange').popover('hide');
                        mui('#dialog-exchange-success').popover('show', document.body);
                    }else{
                        $('.confirm_btn').removeAttr("disabled");
                        mui.toast(data.msg);
                    }

                }
            });
        });

        $("[name='number']").keyup(function () {
            //先把非数字的都替换掉，除了数字和.
            this.value = this.value.replace(/[^\d.]/g,"");
            //必须保证第一个为数字而不是.
            this.value = this.value.replace(/^\./g,"");
            //小數點保留2位
            this.value = this.value.replace(/^(\d+\.\d{2}).+/g,"$1");
            //保证只有出现一个.而没有多个.
            this.value = this.value.replace(/\.{2,}/g,".");
            //保证.只出现一次，而不能出现两次以上
            this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
            var f_gold = (this.value*1.15).toFixed(2);
            $('#f_gold').text(f_gold);
        });

        $('#sign-btn').on('click',function () {
			var that = this;
            $.ajax({
                url:"/Sign/setSign",
                type:"post",
                success:function(data){

                    if(data.code == 0){
                        $('.circle-small').addClass('checked');
                        $(that).attr("disabled","disabled");
                        $(that).addClass("checked");
                        $('#total_sign').html(data.data.total_day);
                    }
                    mui.toast(data['msg']);
                }
            })
        });
    </script>
    <script src="/html/js/my_gold.js?v=1"></script>

