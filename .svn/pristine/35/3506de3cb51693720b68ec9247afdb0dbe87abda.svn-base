    <link href="/html/css/mui.picker.min.css" rel="stylesheet"/>
    <link href="/html/css/myInfo/myReward.css?v=1" rel="stylesheet"/>
    <script src="/html/js/mui.picker.min.js"></script>
    <title>我的奖金</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">我的奖金</h1>
    </header>
    <div class="mui-content">
        <div class="totalReward section">
            <div class="section-content">
                <div class="statistics">
                    <div class="small_title">可提现奖金(元)
                        <span class="mui-icon-extra mui-icon-extra-card" style="position: absolute;right: 20px;"  onclick="window.location.href = '/withdraw/withdraw_log'"></span>
</div>
                    <div class="statistics_number"> <?php echo $allow_withdraw_amount;?></div>
                    <div class="statistics_btn">
                        <span class="left-span" onclick="window.location.href = '/withdraw'">提现</span>
                        <span class="right-span exchange-goldencoin">兑换金币</span>
                    </div>
                </div>
                <div class="mui-row">
                    <div class="mui-col-xs-3">
                        <div class="amountMoney"><?php echo $total_reward?></div>
                        <div class="small_title">累计已入账</div>
                    </div>
                    <div class="mui-col-xs-3">
                        <div class="amountMoney"><?php echo $total_wait_reward?></div>
                        <div class="small_title">累计待入账</div>
                    </div>
                    <div class="mui-col-xs-3">
                        <div class="amountMoney"><?php echo $month_reward?></div>
                        <div class="small_title">本期已入账</div>
                    </div>
                    <div class="mui-col-xs-3">
                            <div class="amountMoney"><?php echo $wait_reward?></div>
                        <div class="small_title">本期待入账</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/icon/file.png" alt="" srcset="">
                    本期奖金明细
                </span>
                <div class="section-title__right">
                    <?php echo date('Y-m')?>
                </div>
            </div>
            <div class="mui-row section-content">
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-user1.png" alt="" srcset="">
                    <div>个人消费奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['shopping_reward']?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-share.png" alt="" srcset="">
                    <div>分享奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['m_rec_reward']?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/suppliersjj.png" alt="" srcset="">
                    <div>供应商推荐奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['s_rec_reward']?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-manage.png" alt="" srcset="">
                    <div>开拓和管理奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['manage_reward']?></div>
                </div>
            </div>
        </div>
        <!-- 累计奖金明细 -->
        <div class="section">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/icon/icon-morefile.png" alt="" srcset="">
                    累计奖金明细
                </span>
            </div>
            <div class="mui-row section-content">
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-user1.png" alt="" srcset="">
                    <div>个人消费奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['shopping_reward']; ?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-share.png" alt="" srcset="">
                    <div>分享奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['m_rec_reward']; ?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/suppliersjj.png" alt="" srcset="">
                    <div>供应商推荐奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['s_rec_reward']?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/icon-manage.png" alt="" srcset="">
                    <div>开拓和管理奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['manage_reward']; ?></div>
                </div>
            </div>
        </div>
        <!-- 历史账单 -->
        <div class="section">
            <div class="section-title">
                <span class="section-title__left">
                    <img src="/html/img/icon/icon-fileli.png" alt="" srcset="">
                    历史账单
                </span>
            </div>
<!--            <div class="datePicker section-content">-->
<!--                <div type="text" class="input" id='startDate' onclick="chooseDate('start')">-->
<!--                    <span class="mui-icon-extra mui-icon-extra-regist"></span>-->
<!--                    <p>2018-05</p>-->
<!--                </div>-->
<!--                <span>-</span>-->
<!--                <div type="text" class="input" id='endDate' onclick="chooseDate('end')" >-->
<!--                    <span class="mui-icon-extra mui-icon-extra-regist"></span>-->
<!--                    <p>2018-06</p>-->
<!--                </div>-->
<!--                <span class="mui-icon mui-icon-search" onclick="search.init()"></span>-->
<!--            </div>-->
        </div>
        <!-- 历史账单明细 -->
        <div class="section">
            <?php if ($list == array()){?>
                <div class="mui-card">
                    <div class="mui-card-content">
                        <div class="mui-card-content-inner" style="color: #8c8c8c;text-align: center;">暂无历史账单记录</div>
                    </div>
                </div>
            <?php }?>
            <?php foreach ($list as $value){?>
                <div class="mui-card">
                    <div class="mui-card-content">
                        <div class="mui-card-header">
                            <label><?php echo $value['at_date']?></label>
                            <label class="amountMoney">总计：<?php echo $value['total'];?></label>
                        </div>
                        <div class="mui-card-content-inner">
                            <div class="mui-row rewardList reward_history">
                                <div class="mui-col-xs-3 rewardItem">
                                    <img src="/html/img/icon/icon-user1.png" alt="" srcset="">
                                    <div>个人消费奖</div>
                                    <div class="amountMoney"><?php echo $value['shopping_reward']?></div>
                                </div>
                                <div class="mui-col-xs-3 rewardItem">
                                    <img src="/html/img/icon/icon-share.png" alt="" srcset="">
                                    <div>分享奖</div>
                                    <div class="amountMoney"><?php echo $value['m_rec_reward']?></div>
                                </div>
                                <div class="mui-col-xs-3 rewardItem">
                                    <img src="/html/img/icon/suppliersjj.png" alt="" srcset="">
                                    <div>供应商推荐奖</div>
                                    <div class="amountMoney"><?php echo $value['s_rec_reward']?></div>
                                </div>
                                <div class="mui-col-xs-3 rewardItem">
                                    <img src="/html/img/icon/icon-manage.png" alt="" srcset="">
                                    <div>开拓和管理奖</div>
                                    <div class="amountMoney"><?php echo $value['manage_reward']?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>

    <!-- 兑换金币弹窗 -->
    <div id="dialog-exchange" class="mui-popover x-dialog-wrap">
        <div class="x-dialog">
            <div class="x-dialog-header">
                兑换金币
                <div class="x-dialog-header__close">
                    <span class="mui-icon mui-icon-closeempty"></span>
                </div>
            </div>
            <div class="x-dialog-content m-form">
                <h2>兑换金币将按照今日牌价<em><?php echo $goldPrice?></em> 等比例兑换</h2>
                <p>1金币 = (1x今日牌价)奖金</p>
                <div class="form-item coin-number">
                    <div class="form-item-label">金币数量:</div>
                    <div class="form-item-content f-cb">
                        <input type="text" name="number" maxlength="5" value="">
                        <div class="number-info f-fr">(本次兑换讲消耗奖金<em id="f_amount">0</em>)</div>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-label">支付密码:</div>
                    <div class="form-item-content f-cb">
                        <input type="password" maxlength="35" name="password">
                        <!--<div class="icon-password"></div>-->
                    </div>
                </div>
                <div class="form-item">
                    <button type="button" class="mui-btn btn-submit confirm_btn">确定</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 兑换金币成功弹窗 -->
    <div id="dialog-exchange-success" class="mui-popover x-dialog-wrap">
        <div class="x-dialog">
            <div class="x-dialog-header">
                兑换金币
                <div class="x-dialog-header__close">
                    <span class="mui-icon mui-icon-closeempty"></span>
                </div>
            </div>
            <div class="x-dialog-content m-form">
                <div class="success-message">
                    您成功兑换<em id="t_gold"></em>金币
                </div>
                <div class="form-item">
                    <button type="button" class="mui-btn btn-submit close_btn">确定</button>
                </div>
            </div>
        </div>
    </div>

    <?php require 'bottom_nav.php';?>

    <script>
        var search = {
            init:function(){
                mui('.dateArr')[0].style.display = "block";
                mui('.dateArr .date')[0].innerHTML = mui('#startDate')[0].innerHTML +"--"+mui('#endDate')[0].innerHTML;
            },
            clear:function(){
                mui('#startDate')[0].innerHTML = '';
                mui('#endDate')[0].innerHTML = '';
                mui('.dateArr .date')[0].innerHTML = '';
                mui('.dateArr')[0].style.display="none";
            }
        }

        function chooseDate(str){
            var ele =null;
            if(str=='end'){
                ele = document.getElementById('endDate');
            }else{
                ele = document.getElementById('startDate');
            }
            var picker = new mui.DtPicker({"type":"month"});
            picker.show(function(rs) {
                var arr = rs.text.split('-');
                rs.text = arr[0]+'-'+arr[1];
                ele.innerHTML = '<span class="mui-icon-extra mui-icon-extra-regist"></span>'+rs.text;
                picker.dispose();
            });

        }

        mui.init();
        mui('body').on('tap','.exchange-goldencoin', function() {
            mui('#dialog-exchange').popover('show', document.body)
        });
        mui('body').on('tap','.x-dialog-header__close', function() {
            mui('#dialog-exchange').popover('hide')
            mui('#dialog-exchange-success').popover('hide')
        });
        mui('body').on('click', '.close_btn', function () {
            mui('#dialog-exchange-success').popover('hide');
            mui('#dialog-exchange').popover('hide');
        });

        $('.confirm_btn').click(function () {

            var gold = $('[name="number"]').val();
            var password = $('[name="password"]').val();

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
            data.payPwd = password;

            $(this).prop("disabled",true);
            showLoading();
            $.ajax({
                url:"/Reward/amountToGold",
                data:data,
                type:"post",
                success:function(data){
                    hideLoading();
                    if(data.code == 0){
                        $('.statistics_number').html(data.data.amount);
                        $('#t_gold').html(gold);
                        mui('#dialog-exchange').popover('hide');
                        mui('#dialog-exchange-success').popover('show', document.body)
                    }else{
                        $('.confirm_btn').removeAttr("disabled");
                        mui.toast(data.msg);
                    }

                }
            });
        });

        var gold_price = '<?php echo $goldPrice?>';

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

            if(gold_price == '计算中...'){
                this.value = '';
                mui.toast('牌价正在计算中');return;
            }
            var f_amount = (this.value*gold_price).toFixed(2);
            $('#f_amount').text(f_amount);
        });
    </script>