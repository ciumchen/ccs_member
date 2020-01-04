<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="/html/css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/html/css/icons-extra.css">
    <link rel="stylesheet" href="/html/css/home/bottomTap.css">
    <link rel="stylesheet" href="/html/css/mui-loading.css">
    <link rel="stylesheet" href="/html/css/base.css">
    <script src="/html/js/mui.min.js"></script>
    <script src="/html/js/mui.pullToRefresh.js"></script>
    <script src="/html/js/mui.pullToRefresh.material.js"></script>
    <script src="/html/js/jquery-3.3.1.min.js"></script>
    <script src="/html/js/mui-loading.js"></script>
    <script src="/html/js/base.js"></script>
    <?php if (isset($_SESSION['readOnly']) && $_SESSION['readOnly'] == $_SESSION['user_id']){?>
    <div style="width: 100%;display:block;text-align:center;font-size: 12px;background: #9a9a9a;">
        <span>请勿更改用户信息</span>
        <span><a href="/account_manage/logout">消除登录</a></span>
    </div>
    <script>
        $(document).ready(function(){
            $('.mui-content input').attr("readonly", "readonly");
            $('.mui-content textarea').attr("readonly", "readonly");
            $(".mui-content button[type='button']").attr("disabled","disabled");
        });
    </script>
<?php }?>