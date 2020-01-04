
        <link rel="stylesheet" href="/html/css/bootstrap.min.css">
        <link href="/html/css/myInfo/myShare.css?v=1" rel="stylesheet" />
        <link href="/html/css/point.css?v=1" rel="stylesheet" />
    <title>积分中心</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">我的积分</h1>
    </header>
    <div class="menuStatistics">
        <div class="pointBox">
            <p class="pointTop"><?php echo $user_info['point'];?></p>
            <p class="pointCenter">已超过<?php echo $more_per?>的会员</p>
            <p class="pointBottom"><?php echo $upgrade_tips?></p>
        </div>
        <div class="iconBox">
            <div>
                <div class="spanIcon" onclick="window.location.href = '/welcome/go_to_mall'">
                    <span class="mui-icon mui-icon-extra mui-icon-extra-cart"></span>
                    <span class="text">去购物</span>
                </div>
            </div>
            <div>
                <div class="spanIcon" onclick="window.location.href = '/welcome/create_share'">
                    <span class="mui-icon mui-icon-extra mui-icon-extra-share"></span>
                    <span class="text">去分享</span>
                </div>
            </div>
            <div>
                <div class="spanIcon" onclick="window.location.href = '/welcome/go_to_mall'">
                    <span class="mui-icon mui-icon-extra mui-icon-extra-trend"></span>
                    <span class="text">去升级</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mui-content">
        <div class="mui-scroll mui-card">
            <div class="mui-card-content">
                <div class="mui-card-content-inner">
                    <span style="font-size: 14px;font-weight: 600"><img src="/html/img/icon/icon-fileli.png" alt="" srcset="" style="height: 20px;width: 20px;">积分来源明细</span>
                </div>
                <div class="shareList">
                    <table>
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>类型</th>
                            <th>订单积分</th>
                            <th>系数</th>
                            <th>获得积分</th>
                        </tr>
                        </thead>
                        <tbody id="pointBox" data-page="1">
                            <?php foreach ($list as $value){?>
                                <tr>
                                    <td><?php echo $value['order_no'] ?></td>
                                    <td><?php echo $value['type_text'] ?></td>
                                    <td class="amountMoney"><?php echo $value['order_point'] ?></td>
                                    <td><?php echo $value['point_per'] ?></td>
                                    <td class="amountMoney"><?php echo $value['point'] ?></td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>

                    <?php if (count($list) == 0){?>
                        <div class="show_no">暂无相关信息</div>
                    <?php }?>
                    <?php if (count($list) >= 10){?>
                        <div id="review_more" class="review_more">点击查看更多</div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 20px;"></div>



    <?php require 'bottom_nav.php';?>
    <script src="/html/js/my_point.js"></script>

