<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理后台 - <?php echo $title?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="/html/css/bootstrap.min.css?v=2">
    <link rel="stylesheet" href="/html/css/font-awesome.min.css?v=2">
    <link rel="stylesheet" href="/html/css/animate.css?v=2">
    <link rel="stylesheet" href="/html/css/main.min.css?v=2">
    <link rel="stylesheet" href="/html/css/admin.css?v=2">
    <link rel="stylesheet" href="/html/css/site.css?v=2">
    <link rel="stylesheet" href="/html/plugins/My97DatePicker/skin/WdatePicker.css">

    <script src="/html/plugins/My97DatePicker/WdatePicker.js"></script>
    <script src="/html/js/jquery-3.3.1.min.js"></script>
    <script src="/html/plugins/layer/layer.js"></script>
    <script src="/html/js/bootstrap.min.js"></script>
    <script src="/html/js/application.js"></script>
    <script>
        $(function () { $("[data-toggle='tooltip']").tooltip(); });
    </script>
</head>

<body>
<section id="container">
    <header id="header">
        <div class="brand"><a href="<?php echo $domain_admin; ?>" class="logo"><span>后台管理中心</span></a></div>
        <div class="toggle-navigation toggle-left">
            <button type="button" class="btn btn-default" id="toggle-left" data-toggle="tooltip" data-placement="right" title="Toggle Navigation">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <div class="user-nav">
            <ul>
                <li style="margin-right: 20px;">
                    <a target="_blank" href="/"><i class="fa fa-home fa-2x"></i></a>
                </li>
                <li class="profile-photo">
                    <img src="/html/img/admin.jpg" alt="" class="img-circle">
                </li>
                <li class="dropdown settings">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo $_SESSION['admin_name']?><i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu animated fadeInDown">
                        <li><a href="/admin/index/admin_logout"><i class="fa fa-power-off"></i> 退出登录</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <aside class="sidebar">
        <div id="leftside-navigation" class="nano">
            <?php if ($dirName == 'admin/'){?>
                <ul class="nano-content">
                    <li class="sub-menu">
                        <a href="javascript:void(0);"><span>用户管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                        <ul style="<?php echo $controllerName == 'users_manage' ? 'display:block' : 'display:none' ?>">
                            <li class="<?php echo $methods == 'index' ? 'active' : '' ?>"><a href="/admin/users_manage">用户列表</a></li>
                            <li class="<?php echo $methods == 'level_list' ? 'active' : '' ?>"><a href="/admin/users_manage/level_list">等级变动列表</a></li>
                            <li class="<?php echo $methods == 'gold_list' ? 'active' : '' ?>"><a href="/admin/users_manage/gold_list">金币列表</a></li>
                            <li class="<?php echo $methods == 'point_list' ? 'active' : '' ?>"><a href="/admin/users_manage/point_list">积分列表</a></li>
                            <li class="<?php echo $methods == 'sign_list' ? 'active' : '' ?>"><a href="/admin/users_manage/sign_list">签到列表</a></li>
                            <li class="<?php echo $methods == 'withdraw_list' ? 'active' : '' ?>"><a href="/admin/users_manage/withdraw_list">用户提现列表</a></li>
							<li class="<?php echo $methods == 'saleMonth_list' ? 'active' : '' ?>"><a href="/admin/users_manage/saleMonth_list">每月消费列表</a></li>
							<li class="<?php echo $methods == 'helpPlan_list' ? 'active' : '' ?>"><a href="/admin/users_manage/helpPlan_list">帮扶计划列表</a></li>
						</ul>
                    </li>
                    <li class="<?php echo $controllerName == 'reward_manage' ? 'active' : '' ?>">
                        <a href="/admin/reward_manage"><span>奖金管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                    </li>
                    <!-- <li class="sub-menu">
                        <a href="javascript:void(0);"><span>商品管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                        <ul style="<?php echo $controllerName == 'goods_manage' ? 'display:block' : 'display:none' ?>">
                            <li class="<?php echo $methods == 'edit_group_goods_page' ? 'active' : '' ?>"><a href="/admin/goods_manage/edit_group_goods_page">上架大礼包</a></li>
                        </ul>
                    </li> -->
                    <li class="<?php echo $controllerName == 'admin_goldPrice_list' ? 'active' : '' ?>">
                        <a href="/admin/admin_goldPrice_list"><span>每日牌价</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                    </li>

                    <li class="<?php echo $controllerName == 'admin_action_log' ? 'active' : '' ?>">
                        <a href="/admin/admin_action_log"><span>操作日志管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                    </li>

                    <?php if (in_array($adminInfo['role'],array(1))){?>
                        <li class="<?php echo $controllerName == 'admin_account_manage' ? 'active' : '' ?>">
                            <a href="/admin/admin_account_manage/admin_account_list"><span>管理员账户管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                        </li>
                    <?php }?>

                    <?php if (in_array($adminInfo['role'],array(1))){?>
                        <li class="sub-menu">
                            <a href="javascript:void(0);"><span>系统设置管理</span><i class="arrow fa fa-angle-right pull-right"></i></a>
                            <ul style="<?php echo $controllerName == 'system_manage' ? 'display:block' : 'display:none' ?>">
                                <li class="<?php echo $methods == 'switch_setting' ? 'active' : '' ?>"><a href="/admin/system_manage/switch_setting">系统开关设置</a></li>
                                <li class="<?php echo $methods == 'param_setting' ? 'active' : '' ?>"><a href="/admin/system_manage/param_setting">系统参数</a></li>
                            </ul>
                        </li>
                    <?php }?>
                </ul>
            <?php }?>
        </div>
    </aside>
