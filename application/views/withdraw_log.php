<link href="/html/css/mui.picker.min.css" rel="stylesheet"/>
<style>
    [data-type="date"] .mui-dtpicker-title h5, [data-type="date"] .mui-picker {
        width: 50%;
    }

    [data-id="picker-d"] {
        display: none;
    }

    .mui-dtpicker-title [data-id="title-d"] {
        display: none;
    }

    .title {
        cursor: pointer;
        margin: 20px 15px 10px;
    }

    .mui-table-view-cell {
        font-size: 14px;
    }

    .right {
        position: absolute;
        right: 20px;
    }

    .time {
        color: #9a9a9a;
        font-size: 12px;
    }
</style>
    <script src="/html/js/mui.picker.min.js"></script>
    <title>提现记录</title>
</head>
<body>
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">提现记录</h1>
    </header>

    <div class="mui-content">
        <div class="title" onclick="chooseDate();">
            <span class="date"><?php echo $date == '' ? '请选择查询时间' : $date ?></span>
            <span class="mui-icon mui-icon-arrowdown" style="margin: 5px;"></span>
        </div>
        <?php if ($list == array()){?>
            <div class="show_no">没有相关提现记录</div>
        <?php }?>
        <ul class="mui-table-view">
            <?php foreach ($list as $key => $value) { ?>
                <li class="mui-table-view-cell mui-media">
                    <a href="javascript:;">
                        <img class="mui-media-object mui-pull-left" src="/html/img/myInfo/coupon.png">
                        <div class="mui-media-body">
                            <span>提现到 - <?php echo config_item('withdraw_to')[$value['type']];?></span>
                            <span class="amountMoney right">￥<?php echo $value['amount'] ?></span>
                            <br>
                            <span class="time"><?php echo $value['create_time'] ?></span>
                            <?php if ($value['status'] == 1) {
                                $class_name = 'label-default';
                            } ?>
                            <?php if ($value['status'] == 2) {
                                $class_name = 'label-success';
                            } ?>
                            <?php if ($value['status'] == 3) {
                                $class_name = 'label-danger';
                            } ?>
                            <span
                                class="<?php echo $class_name; ?> right"><?php echo config_item('withdraw_status')[$value['status']] ?></span>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>


    <?php require 'bottom_nav.php';?>

</body>
    </html>
    <script>
        function chooseDate() {
            var picker = new mui.DtPicker({"type": "date"});
            picker.show(function (rs) {
                var arr = rs.text.split('-');
                rs.text = arr[0] + '-' + arr[1];
                $('.date').text(rs.text);
                picker.hide();
                window.location.href = '/withdraw/withdraw_log?date='+rs.text;
            });
        }
        mui.init();
    </script>