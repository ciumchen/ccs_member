    <link href="/html/css/mui.picker.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/html/css/home/bottomTap.css">
    <link rel="stylesheet" href="/html/css/icons-extra.css">
    <link href="/html/css/myInfo/myReward.css" rel="stylesheet"/>
    <script src="/html/js/mui.picker.min.js"></script>
    <style>
        [data-type="date"] .mui-dtpicker-title h5, [data-type="date"] .mui-picker {
            width: 50%;
        }
        [data-id="picker-d"]{
            display: none;
        }
        .mui-dtpicker-title [data-id="title-d"]{
            display: none;
        }
        .mui-card{
            margin: 0;
            margin-bottom: 10px;
            box-shadow: none;
        }
        .rewardList img{
            width: 20px;
            height: 20px;
        }
        .mui-card-header{
            font-size: 14px;
        }
        .mui-card-content-inner{
            padding: 8px;
        }


    </style>
    <title>我的奖金</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">我的奖金</h1>
    </header>
    <div class="mui-content">
        <div class="totalReward section">
            <div class="statistics">
                <div class="small_title">可提现金额(元)<button type="button" onclick="javascript:location.href='/withdraw/withdraw_log'" style="position: absolute;right: 20px;" class="mui-btn mui-btn-danger">提现记录</button></div>
                <div class="statistics_number"> <?php echo $allow_withdraw_amount;?></div>
                <button class="statistics_btn" onclick="window.location.href = '/withdraw'">提现</button>
                <button class="statistics_btn" type="button" id="crawle">兑换金币</button>
            </div>
            <div class="mui-row">
                <div class="mui-col-xs-3">
                    <div class="amountMoney"><?php echo $total_reward?></div>
                    <div class="small_title">累积奖金</div>
                </div>
                <div class="mui-col-xs-3">
                    <div class="amountMoney"><?php echo $month_reward?></div>
                    <div class="small_title">本期奖金</div>
                </div>
                <!-- <div class="mui-col-xs-3">
                    <div class="amountMoney"><?php echo $push_reward?></div>
                    <div class="small_title">本期已入账</div>
                </div> -->
                <div class="mui-col-xs-3">
                    <div class="amountMoney"><?php echo $wait_reward?></div>
                    <div class="small_title">本期待入账</div>
                </div>
                <div class="mui-col-xs-3">
                    <div class="amountMoney"><?php echo $allow_withdraw_amount?></div>
                    <div class="small_title">可提现</div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="section_title">
                <span><img src="/html/img/icon/filejj.png" alt="" srcset="">本期奖金明细</span>
                <div class='right'><?php echo date('Y-m')?></div>
            </div>
            <div class="mui-row rewardList">
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/userjj.png" alt="" srcset="">
                    <div>个人消费奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['shopping_reward']?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/sharejj.png" alt="" srcset="">
                    <div>分享奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['m_rec_reward']?></div>
                </div>
<!--                <div class="mui-col-xs-3 rewardItem">-->
<!--                    <img src="/html/img/icon/icon-car.png" alt="" srcset="">-->
<!--                    <div>供应商推荐奖</div>-->
<!--                    <div class="amountMoney">--><?php //echo $month_reward_details['s_rec_reward']?><!--</div>-->
<!--                </div>-->
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/exploitjj.png" alt="" srcset="">
                    <div>开拓和管理奖</div>
                    <div class="amountMoney"><?php echo $month_reward_details['manage_reward']?></div>
                </div>
<!--                <div class="mui-col-xs-3 rewardItem">-->
<!--                    <img src="/html/img/icon/icon-house.png" alt="" srcset="">-->
<!--                    <div>经营奖</div>-->
<!--                    <div class="amountMoney">--><?php //echo $month_reward_details['plat_reward']?><!--</div>-->
<!--                </div>-->
            </div>
        <div style="font-size: 12px;color: #c8c7cc;border-top: 1px solid #c8c7cc;margin: 0;">
        <div>注：个人消费奖、分享奖、供应商推荐奖为本月实时奖金,开拓和管理奖、经营奖为上个月奖金</div>
        </div>
        <div class="section">
            <div class="section_title">
                <span><img src="/html/img/icon/amassjj.png" alt="" srcset=""> 累计奖金明细</span>
            </div>
            <div class="mui-row rewardList">
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/userjj.png" alt="" srcset="">
                    <div>个人消费奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['shopping_reward']; ?></div>
                </div>
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/sharejj.png" alt="" srcset="">
                    <div>分享奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['m_rec_reward']; ?></div>
                </div>
<!--                <div class="mui-col-xs-3 rewardItem">-->
<!--                    <img src="/html/img/icon/icon-car.png" alt="" srcset="">-->
<!--                    <div>供应商推荐奖</div>-->
<!--                    <div class="amountMoney">--><?php //echo $total_reward_details['s_rec_reward']; ?><!--</div>-->
<!--                </div>-->
                <div class="mui-col-xs-3 rewardItem">
                    <img src="/html/img/icon/exploitjj.png" alt="" srcset="">
                    <div>开拓和管理奖</div>
                    <div class="amountMoney"><?php echo $total_reward_details['manage_reward']; ?></div>
                </div>
<!--                <div class="mui-col-xs-3 rewardItem">-->
<!--                    <img src="/html/img/icon/suppliersjj.png" alt="" srcset="">-->
<!--                    <div>经营奖</div>-->
<!--                    <div class="amountMoney">--><?php //echo $total_reward_details['plat_reward']; ?><!--</div>-->
<!--                </div>-->
            </div>
        </div>

        <div>
            <div class="mui-card">
                <div class="mui-card-content">
                    <div class="mui-card-content-inner">
                        <span style="font-size: 14px;font-weight: 600">
                            <img src="/html/img/icon/history.png" alt="" srcset="" style="height: 20px;width: 20px;margin-bottom: -5px">
                            历史账单
                        </span>
                    </div>
                </div>
            </div>

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
<!--                            <div class="mui-col-xs-3 rewardItem">-->
<!--                                <img src="/html/img/icon/suppliersjj.png" alt="" srcset="">-->
<!--                                <div>供应商推荐奖</div>-->
<!--                                <div class="amountMoney">--><?php //echo $value['s_rec_reward']?><!--</div>-->
<!--                            </div>-->
                            <div class="mui-col-xs-3 rewardItem">
                                <img src="/html/img/icon/icon-manage.png" alt="" srcset="">
                                <div>开拓和管理奖</div>
                                <div class="amountMoney"><?php echo $value['manage_reward']?></div>
                            </div>
<!--                            <div class="mui-col-xs-3 rewardItem">-->
<!--                                <img src="/html/img/icon/icon-house.png" alt="" srcset="">-->
<!--                                <div>经营奖</div>-->
<!--                                <div class="amountMoney">--><?php //echo $value['plat_reward']?><!--</div>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
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
                ele = document.getElementById('endDate')
                var input = $('.end_time');
            }else{
                ele = document.getElementById('startDate')
                var input = $('.start_time');
            }
            var picker = new mui.DtPicker({"type":"date"});
            picker.show(function(rs) {
                var arr = rs.text.split('-');
                rs.text = arr[0]+'-'+arr[1];
                ele.innerHTML = rs.text;
                input.val(rs.text);
                picker.dispose();
            });
        }
        mui.init();

    </script>
</body>
</html>