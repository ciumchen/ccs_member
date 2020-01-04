
    <link rel="stylesheet" href="/html/css/history.css">
    <title>我的收藏</title>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">我的收藏</h1>
</header>
<div class="mui-content">
    <?php foreach ($list as $key=>$goods) {?>
        <ul class="mui-table-view list">
            <li class="mui-table-view-cell mui-media">
                <a href="javascript:;">
                    <a href="/welcome/go_to_mall?goods_id=<?php echo $goods['goods_id'] ?>">
                        <img class="mui-media-object mui-pull-left history_img" src="<?php echo $goods['goods_img'] ?>">
                    </a>
                    <div class="mui-media-body">
                        <p class='mui-ellipsis h_goods_name'><?php echo $goods['goods_name'] ?></p>
                        <span style="font-size: 12px;color: #c8c7cc">商品积分：<?php echo $goods['point'] ?></span>
                        <br>
                        <p style="line-height: 30px">
                            <span class="amountMoney">￥<?php echo $goods['sell_price'] ?></span>
                            <a href="/welcome/go_to_mall?goods_id=<?php echo $goods['goods_id'] ?>">
                                <button type="button" class="mui-btn mui-btn-danger" style="padding: 3px;font-size: 12px;margin-left: 50%">去购买</button>
                            </a>
                        </p>
                    </div>
                </a>
            </li>
        </ul>
    <?php }?>

    <?php if ($list == array()){?>
        <div class="show_no">暂无相关信息</div>
    <?php }?>
</div>
</body>
</html>