
        <link href="/html/css/myInfo/myShare.css" rel="stylesheet" />
        <link rel="stylesheet" href="/html/css/home/bottomTap.css">
        <link rel="stylesheet" href="/html/css/icons-extra.css">
        <script src="/html/js/mui.min.js"></script>
    <title>我的分享</title>
        <style>
            .right{
                font-size: 90%;
                font-weight: 400;
                float: right;
                color: #ccc;
                line-height: 1.5em;
            }
        </style>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">我的分享</h1>
    </header>
    <div class="header-bg"></div>
    <div class="menuStatistics">
        <div class="ueserImg">
            <div class="circle">
                <a href="/welcome/create_share"><div class="imgContent"><img src="/html/img/myInfo/myshare/userImg.png" alt="" srcset=""></div></a>
            </div>
        </div>
       
        <div class="mui-row menu">
            <div class="mui-col-xs-3 menu-item">
                <img src="/html/img/myInfo/myshare/menu-1.png" alt="">
                <div class="subtitle">直接人数</div>
                <div><?php echo $child_count;?></div>
            </div>
            <div class="mui-col-xs-3 menu-item">
                <img src="/html/img/myInfo/myshare/menu-2.png" alt="">
                <div class="subtitle">本期新增</div>
                <div><?php echo $month_child_count;?></div>
            </div>
            <div class="mui-col-xs-3 menu-item">
                <img src="/html/img/myInfo/myshare/menu-3.png" alt="">
                <div class="subtitle">全部团队</div>
                <div><?php echo $all_child_count; ?></div>
            </div>
            <div class="mui-col-xs-3 menu-item">
                <img src="/html/img/myInfo/myshare/menu-4.png" alt="">
                <div class="subtitle">VIP数量</div>
                <div><?php echo $all_level;?></div>
            </div>
        </div>
    </div>

    <div class="searchArea shareList">
<!--        <div class="searchInput">-->
<!--            <input type="text" placeholder="请直接输入手机号/姓名">-->
<!--            <span class="mui-icon mui-icon-search"></span>-->
<!--        </div>-->
        <div class="listTitle">
            <span><img src="/html/img/icon/file.png" alt="" srcset="">
            我的任务</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>手机号</th>
                    <th>姓名</th>
                    <th>级别</th>
                    <th>本期任务</th>
                    <th>已完成</th>
                    <th>完成度</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $user_info['mobile']?></td>
                    <td><?php echo $user_info['true_name']?></td>
                    <td>V<?php echo $user_info['level']?></td>
                    <td><?php echo $level_sale_map[$user_info['level']] ? $level_sale_map[$user_info['level']] : '无' ?></td>
                    <td><?php echo $user_info['month_sales']?></td>
                    <?php if ($user_info['percent'] == '100%'){$class = 'green';}?>
                    <?php if ($user_info['percent'] != '100%'){$class = 'red';}?>
                    <?php if ($user_info['level'] == '0'){$class = 'gray';}?>
                    <td class="<?php echo $class ?>"><?php echo $user_info['level'] == '0' ? '暂无任务' : $user_info['percent'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="shareList">
        <div class="listTitle">
            <span><img src="/html/img/icon/icon-user.png" alt="" srcset="">
            我的直推</span>
            <div class='right'><?php echo date('Y-m')?></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>手机号</th>
                    <th>姓名</th>
                    <th>级别</th>
                    <th>本期任务</th>
                    <th>帮扶计划</th>
                    <th>已完成</th>
                    <th>完成度</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($child_info == array()){?>
                <tr style="color: #c8c7cc;"><td colspan="5" rowspan="4" style="text-align: center">暂无相关记录</td></tr>
            <?php }?>
            <?php foreach ($child_info as $val){?>
                <tr>
                    <td><?php echo $val['mobile'] ?></td>
                    <td><?php echo $val['true_name'] ?></td>
                    <td><?php echo $val['level_text'] ?></td>
                    <td><?php echo $level_sale_map[$val['level']] ? $level_sale_map[$val['level']] : '无' ?></td>
                    <td><?php echo $val['isHelp'] ?></td>
                    <td><?php echo $val['month_sales'] ?></td>
                    <?php if ($val['percent'] == '100%'){$class = 'green';}?>
                    <?php if ($val['percent'] != '100%'){$class = 'red';}?>
                    <?php if ($val['level'] == 'V0'){$class = 'gray';}?>
                    <td class="<?php echo $class ?>"><?php echo $val['level'] == 'V0' ? '暂无任务' : $val['percent'];?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="height: 50px;"></div>

    <?php require 'bottom_nav.php';?>
    
    <script type="text/javascript" charset="utf-8">
        mui.init();
    </script>
