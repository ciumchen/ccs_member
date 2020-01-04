<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/html/css/mui.min.css" rel="stylesheet" />
    <link href="/html/css/myInfo/myOrder.css" rel="stylesheet" />
    <link rel="stylesheet" href="/html/css/home/bottomTap.css">
    <link rel="stylesheet" href="/html/css/icons-extra.css">
    <script src="/html/js/mui.min.js"></script>
    <title>我的订单</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <!-- <a class="mui-btn mui-btn-link mui-pull-right">关闭</a> -->
        <h1 class="mui-title">我的订单</h1>
    </header>
    <div class="mui-content">
        <div class="app-tabs">
            <a class="tab-control-item tab-active" data-href="0">全部</a>
<!--            <a class="tab-control-item" data-href="1">待付款</a>-->
<!--            <a class="tab-control-item" data-href="2">待发货</a>-->
<!--            <a class="tab-control-item" data-href="3">待收货</a>-->
<!--            <a class="tab-control-item" data-href="4">退换/售后</a>-->
        </div>
        <div>
            <div id='item1' class="tab-control-body tab-active">
                <?php foreach ($order_list as $val){?>
                <div class="cardItem">
                    <div class="card-header">
                        <span>订单号：<?php echo $val['order_no']; ?></span>
<!--                        <span class="deleBtn button"><span class="mui-icon mui-icon-trash"></span>删除</span>-->
                    </div>
                    <div class="card-content">
                        <?php foreach ($val['goods_list'] as $v){?>
                        <div class="mui-row goodsInfo" onclick='window.location.href ="/welcome/go_to_mall?goods_id=<?php echo $v['goods_id']?>" '>
                            <div class="mui-col-xs-4">
                                <div class="imgContent">
                                    <img src="<?php echo $v['img']?>" alt="" srcset="">
                                </div>
                            </div>
                            <div class="mui-col-xs-8">
                                <div><?php echo $v['goods_name']?></div>
                                <small>产品数量：<?php echo $v['goods_nums']?></small>
                                <small>产品积分：<?php echo $v['goods_point']?></small>
                                <div>
                                    <!--                                    <span class="button">待收货</span>
                                                                        <span class="button floatRight">查物流</span>-->
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <div class="mui-row fontSmall">
                            <div class="mui-col-xs-4">订单积分：<?php echo $val['order_point']?></div>
                            <div class="mui-col-xs-8 textRight">
                                <span class="">共<?php echo $val['goods_count']?>件商品</span>
                                <span>实付款：￥<?php echo $val['real_amount']?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        状态:<span class="button"><?php echo $val['status']?></span>
<!--                        <span class='goShare button'>去分享</span>-->
<!--                        <span class='goEvaluate button disabled'>去评价</span>-->
<!--                        <span class='goBey button'>再次购买</span>-->
                    </div>
                </div>
                <?php }?>

                <?php if ($order_list == array()){?>
                    <div class="show_no">暂无相关信息</div>
                <?php }?>
            </div>
<!--            <div  class="tab-control-body">-->
<!--                <div class='noOrder'>暂无相关订单</div>-->
<!--            </div>-->
<!--            <div class="tab-control-body">-->
<!--                <div class='noOrder'>暂无相关订单</div>-->
<!--            </div>-->
<!--            <div class="tab-control-body">-->
<!--                <div class="cardItem">-->
<!--                    <div class="card-header">-->
<!--                        <span>订单号：325461457</span>-->
<!--                        <span class="deleBtn button"><span class="mui-icon mui-icon-trash"></span>删除</span>-->
<!--                    </div>-->
<!--                    <div class="card-content">-->
<!--                        <div class="mui-row goodsInfo">-->
<!--                            <div class="mui-col-xs-4">-->
<!--                                <div class="imgContent">-->
<!--                                    <img src="/html/img/myInfo/show.png" alt="" srcset="">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="mui-col-xs-8">-->
<!--                                <div>夏季新款范冰冰同款吊带连衣裙</div>-->
<!--                                <small>颜色分类：黄色</small>-->
<!--                                <small>尺码：XS</small>-->
<!--                                <div>状态: -->
<!--                                    <span class="button">待收货</span>-->
<!--                                    <span class="button floatRight">查物流</span>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="mui-row fontSmall">-->
<!--                            <div class="mui-col-xs-4">产品积分：36.5</div>-->
<!--                            <div class="mui-col-xs-8 textRight">-->
<!--                                <span class="">共2件商品</span>-->
<!--                                <span>实付款：￥365.00</span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="card-footer">-->
<!--                        <span class='goShare button'>去分享</span>-->
<!--                        <span class='goEvaluate button disabled'>去评价</span>-->
<!--                        <span class='goBey button'>再次购买</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="tab-control-body">-->
<!--                <div class='noOrder'>暂无相关订单</div>-->
<!--            </div>-->
        </div>
    </div>

    <?php require 'bottom_nav.php';?>

    <script src="/html/js/jquery-3.3.1.min.js"></script>
    <script>
        $('.app-tabs .tab-control-item').on('click',function(){
            $(this).addClass('tab-active').siblings().removeClass('tab-active');
            var index = $(this).attr('data-href');
            $('.tab-control-body').eq(index).addClass('tab-active').siblings().removeClass('tab-active')
        })
    </script>
</body>
</html>