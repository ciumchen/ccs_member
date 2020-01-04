
<link href="/html/css/person_info.css" rel="stylesheet" />
<title>个人资料</title>
</head>
<body>
<div class="mui-content">
    <div class="mui-page-content">
        <div class="mui-scroll-wrapper">
            <header class="mui-bar mui-bar-nav">
                <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
                <h1 class="mui-title">个人资料</h1>
            </header>

            <input type="file" class='head_ico_file' name="head_ico_file" hidden />
            <ul class="mui-table-view headImgBox" onclick="$('.head_ico_file').click()">
                <li class="mui-table-view-cell mui-media">
                    <a class="mui-navigate-right">
                        <span><img class="mui-media-object mui-pull-right" src="<?php echo $user_info['image_url']?>"></span>
                        <div class="mui-media-body">会员头像</div>
                    </a>
                </li>
            </ul>

            <ul class="mui-table-view personList">
                <li class="mui-table-view-cell" onclick="window.location.href = '/account_manage/edit_person_info?type=true_name'">
                    <a class="mui-navigate-right">
                        <span>姓名</span>
                        <span class="textLeft"><?php echo $user_info['true_name'] == '' ? '未填写' : $user_info['true_name']?></span>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <span>手机号</span>
                        <span class="textLeft"><?php echo $user_info['mobile'] == '' ? '未填写' : $user_info['mobile']?></span>
                    </a>
                </li>
            </ul>
            <br>
            <ul class="mui-table-view personList">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <span>推荐人手机号</span>
                        <span class="textLeft"><?php echo $user_info['parent_info']['mobile'] == '' ? '未填写' : $user_info['parent_info']['mobile']?></span>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <span>推荐人姓名</span>
                        <span class="textLeft"><?php echo $user_info['parent_info']['true_name'] == '' ? '未填写' : $user_info['parent_info']['true_name']?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php require 'bottom_nav.php';?>
<script>
    $(".head_ico_file").change(function () {
        var head_ico_file = $(".head_ico_file")[0].files[0];
        var formData = new FormData();
        formData.append("head_ico_file",head_ico_file);
        $.ajax({
            type: "POST",
            url: "/account_manage/upload_head_ico",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (data) {
                mui.toast(data['msg']);
                if (data['code'] === 0){
                    window.location.reload();
                }
            }
        });
    })
</script>