
<link href="/html/css/edit_person_info.css" rel="stylesheet" />
<title>编辑资料</title>
</head>
<body>
<div class="mui-content">
    <div class="mui-page-content">
        <div class="mui-scroll-wrapper">
            <header class="mui-bar mui-bar-nav">
                <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
                <h1 class="mui-title">编辑资料</h1>
            </header>
            <form class="mui-input-group" id="formData">
                <div class="mui-input-row">
                    <label>姓名</label>
                    <input value="<?php echo $user_info['true_name'];?>" placeholder="请输入姓名" name="true_name" type="text">
                </div>
            </form>
            <button type="button" class="mui-btn mui-btn-danger mui-btn-block btnSubmit">提交修改</button>
        </div>
    </div>
</div>
<?php require 'bottom_nav.php';?>
<script>
    $(".btnSubmit").click(function () {
        $.ajax({
            type: "POST",
            url: "/account_manage/edit_person_info_submit",
            data: $("#formData").serialize(),
            dataType: "json",
            success: function (data) {
                mui.toast(data['msg']);
                if (data['code'] === 0){
                    window.location.href = '/account_manage/person_info';
                }
            }
        });
    })
</script>