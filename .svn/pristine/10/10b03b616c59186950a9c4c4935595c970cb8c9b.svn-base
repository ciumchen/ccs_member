<link href="/html/css/mui.picker.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/html/css/home/bottomTap.css">
    <link rel="stylesheet" href="/html/css/icons-extra.css">
    <link href="/html/css/myInfo/myReward.css" rel="stylesheet"/>
    <link href="/html/css/myInfo/myShare.css" rel="stylesheet"/>
    <link href="/html/css/myInfo/myInfo.css" rel="stylesheet"/>
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
    <title>数据金币</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">数据金币</h1>
    </header>
    <div class="mui-content">
        <div class="totalReward section">
            <div class="statistics">
                <div class="small_title"><span>金币总量</span><span style="position: absolute;right: 20px;color: #e7512e">今日牌价：<?php echo $goldPrice ? $goldPrice :'计算中...' ?></span></div>
                <div class="statistics_number"> <?php echo $user_info['gold'];?></div>
                <div class="statistics_btn">转赠</div>
            </div>
            <div class="mui-row">
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[1]) ? $showGold[1] : 0 ;?></div>
                    <div class="small_title">签到</div>
                </div>
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[2]) ? $showGold[2] : 0 ;?></div>
                    <div class="small_title">积分</div>
                </div>
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[3]) ? $showGold[3] : 0 ;?></div>
                    <div class="small_title">分享</div>
                </div>
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[4]) ? $showGold[4] : 0 ;?></div>
                    <div class="small_title">经营</div>
                </div>
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[5]) ? $showGold[5] : 0 ;?></div>
                    <div class="small_title">购买</div>
                </div>
                <div class="mui-col-xs-2">
                    <div class="amountMoney"><?php echo isset($showGold[6]) ? $showGold[6] : 0 ;?></div>
                    <div class="small_title">受赠</div>
                </div>
            </div>
        </div>

        <section class="my-bonus common-section">
            <div class="bonus-title" onclick="window.location.href = '/sign/sign_rule'">
                <img src="/html/img/myInfo/countsign.png"/>
                <span class="bonus-title-label">累积签到</span>
                <span class="mui-icon mui-icon-arrowright"></span>
                <span class="more-info">查看规则</span>
            </div>
            <ul class="mui-table-view mui-grid-view mui-grid-9">
                <style>
                    .signInfo{
                        text-align: center;
                    }
                    .signInfo p {
                        position: relative;
                        left: 50%;
                        top: 50%;
                        background-color: #e7512e;
                        color: #ffffff;
                        height: 60px;
                        width: 60px;
                        border-radius: 50%;
                    }
                </style>
                <div class="signInfo">
                    <button id="sign-btn" style="cursor:pointer;">签到</button>
                </div>
            </ul>
        </section>

        <section class="my-bonus common-section">

            <div class="bonus-title">
                <img src="/html/img/myInfo/golddatail.png"/>
                <span class="bonus-title-label">金币明细</span>
            </div>
            <div class="shareList">
            <table>
                <thead>
                <tr>
                    <th>类型</th>
                    <th>金币</th>
                    <th>下线ID</th>
                    <th>订单ID</th>
                    <th>时间</th>
                </tr>
                </thead>
                <tbody id="pointBox" data-page="1">
                <?php foreach ($list as $value){?>
                    <tr>
                        <td><?php echo $value['type_text'] ?></td>
                        <td class="amountMoney"><?php echo $value['gold'] ?></td>
                        <td><?php echo $value['child_uid'] ?></td>
                        <td ><?php echo $value['order_id'] ?></td>
                        <td><?php echo $value['create_time'] ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
            </div>
            <?php if (count($list) == 0){?>
                <div class="show_no">暂无相关信息</div>
            <?php }?>
            <?php if (count($list) >= 10){?>
                <div id="review_more" class="review_more">点击查看更多</div>
            <?php }?>
        </div>
        </section>

    </div>

    <?php require 'bottom_nav.php';?>

    <script>
        mui.init();

        $('#sign-btn').on('click',function () {
            $.ajax({
                url:"/Sign/setSign",
                type:"post",
                success:function(data){
                    mui.toast(data['msg']);
                }
            })
        });

        $("button").click(function(){
            $(this).attr("disabled", "disabled");    
        });
    </script>

    <script src="/html/js/my_gold.js"></script>
