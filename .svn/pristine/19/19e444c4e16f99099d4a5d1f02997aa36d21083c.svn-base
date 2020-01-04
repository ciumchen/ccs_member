<style>
    html{
        font-size: calc(100vw/7.5);
    }
    .mui-table-view{
        padding: 0.25rem;
        background: transparent;
    }
    .mui-table-view .mui-table-view-cell.finished{
        background: #999999;
        margin-bottom: 0.2rem;
        border-radius: 0.1rem;
    }
    .mui-table-view .mui-table-view-cell.finished>a:active{
        background: #999999;
    }
    .mui-table-view .mui-table-view-cell.nofinished{
        background: url(/html/img/icon/li-bg.png) no-repeat center center;
        background-size:100% 100%;
        margin-bottom: 0.2rem;
        border-radius: 0.1rem;
    }
    .mui-table-view .mui-table-view-cell.nofinished>a:active{
        background: url(/html/img/icon/li-bg.png) no-repeat center center;
        background-size:100% 100%;
    }
    .mui-table-view .mui-media-body{
        font-size: 0.25rem;
        color: #fff;
        padding: 0.1rem 0 0;
    }
    .mui-table-view .mui-media-object{
        width: 0.56rem;
        height: 0.56rem;
        max-width: 0.56rem;
        line-height: 0.56rem;
    }
    .mui-table-view-cell{
        padding: 0.25rem 0.2rem;
    }
    .mui-table-view-cell>a:not(.mui-btn){
        margin: -0.25rem -0.2rem;
    }
    .mui-table-view .mui-table-view-cell .mui-pull-right{
        position: absolute;
        right: 0.25rem;
        top: 0.15rem;
        text-align: right;
        /*min-width: 2rem;*/
    }
    .mui-table-view .mui-table-view-cell.nofinished .mui-pull-right{
        /*top: 50%;
        margin-top: -0.24rem;*/
    }
    .mui-table-view .mui-table-view-cell .mui-pull-right button{
        color: #fff;
        width: 1.08rem;
        height: 0.48rem;
        text-align: center;
        background: url(/html/img/icon/btn-bg.png) no-repeat center center;
        background-size: 100% 100%;
        padding: 0;
        border: #4CD964;
    }
    .mui-table-view .mui-table-view-cell .mui-pull-right span{
        display: block;
        font-size: 0.22rem;
        color: #fff;
    }
    .mui-table-view-cell > a:not(.mui-btn){
        padding: 20px;
    }
    .mui-table-view:before{
        background: none;
    }
    .mui-table-view:after{
        background: none;
    }
    .popover {
        position: fixed;
        bottom: 45px;
        /*padding: 0.2rem;*/
        width: 100%;
    }
    .popover .jiangpin{
        background: #fff;
        border-radius: 0.1rem;
        padding-bottom: 0.1rem;
    }
    .popover .jiangpin>span{
        /*font-size: 0.25rem;*/
        color: #666;
        padding: 0.1rem 0.4rem;
        display: block;
    }
    .popover .jiangpin .jpDiv{
        text-align: center;
    }
    .popover .jiangpin .list{
        overflow: hidden;
        /*display: inline-block;*/
    }
    .popover .jiangpin .list>div{
        float: left;
        text-align: center;
        width: 50%;
    }
    .popover .jiangpin .list div span{
        display: block;
        /*font-size: 0.26rem;*/
        color: #999;
        margin-top: 0.1rem;
        white-space:pre-wrap;
        max-width: 3.6rem;
    }
    .popover .jiangpin .list div .imgDiv{
        height: 1.54rem;
        line-height: 1.54rem;
    }
    .popover .jiangpin .list div.jpDiv1 img{
        width: 1.42rem;
        vertical-align: middle;
    }
    .popover .jiangpin .list div.jpDiv2 img{
        width: 2.45rem;
        vertical-align: middle;
    }
    .popover .jiangpin .list>img{
        float: left;
        width: 0.76rem;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -0.68rem;
        margin-top: -0.18rem;
    }

    .popover a.mui-icon {
        color: #666;
        margin-right: 0.2rem;
        margin-top: 0.2rem;
        margin-left: 0.2rem;
    }

</style>
<head>
<body>
<div class="mui-content">
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">CCS大数据团队精英帮扶计划</h1>
    </header>
    <ul class="mui-table-view mui-table-view-chevron" style="margin-top: 45px;margin-bottom:45px ">
        <?php if($rows){ ?>
            <?php foreach ($rows as $row){?>
                <li class="mui-table-view-cell mui-media <?php if($row['is_buy'] == 1){?>finished <?php }else{?>nofinished<?php }?>">
                    <a class="" href="<?php echo $goods_url?>">
                        <img class="mui-media-object mui-pull-left" src="/html/img/icon/xin-icon.png">
                        <div class="mui-media-body">团队精英帮扶计划<?php echo $row['count']?></div>
                        <div class="mui-media-btn mui-pull-right">
                            <button type="button" class="mui-btn mui-btn-danger"><?php if($row['is_buy'] == 1){ ?>已完成<?php }else{?>待完成<?php }?></button>
                            <span>任务月份：<?php echo $row['year_month']?></span>
                        </div>
                        <?php if ($row['count'] <= 2){ ?>
                        <div class="mui-media-body" style="float: left;">完成当月900低消,500消费奖、500分享奖转成入账。</div>
                        <?php }?>
                        <?php if ($row['count'] == 3){ ?>
                        <div class="mui-media-body" style="float: left;">完成当月900低消,500消费奖、700分享奖转成入账。</div>
                        <?php }?>
                    </a>
                </li>
            <?php }?>
        <?php }else{?>
            <li class="mui-table-view-cell mui-media nofinished">
                <a class="" href="<?php echo $goods_url?>">
                    <img class="mui-media-object mui-pull-left" src="/html/img/icon/xin-icon.png">
                    <div class="mui-media-body">您还未参加帮扶计划，点击加入我们！！！</div>
                </a>
            </li>
        <?php }?>
    </ul>
</div>
<!--弹出层-->
<div class="popover">
    <a class="mui-icon mui-icon-close mui-pull-right"></a>
    <div class="jiangpin">
        <span>十项帮扶计划全部完成将获得以下产品包和奖励包：</span>
        <div class="jpDiv">
            <div class="list">
                <div class="jpDiv1">
                    <div class="imgDiv">
                        <img src="/html/img/icon/lihe.png"/>
                    </div>
                    <span>价值9488元的产品包</span>
                </div>
                <img src="/html/img/icon/jia.png"/>
                <div class="jpDiv2">
                    <div class="imgDiv">
                        <img src="/html/img/icon/cd.png"/>
                    </div>
                    <span>价值9488元CCS大数据品质睡眠床垫</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".mui-icon-close").click(function(){
        $(this).parent(".popover").hide();
    })
</script>
<?php require 'bottom_nav.php'?>

