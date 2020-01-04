<section class="main-content-wrapper">
    <section id="main-content">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo $domain_admin?>">管理后台</a></li>
                    <li><?php echo $title;?></li>
                </ul>
            </div>
        </div>
        <div class="row" align="center">
            <div class="col-md-12">
                <div class="panel-body " style="width: 40%;">
                    <form id="edit_users_form">
                        <table class="table table_account_info">
                            <tbody>
                                <tr>
                                    <td><span>会&nbsp;员&nbsp;ID&nbsp;：</span></td>
                                    <td><input class="form-control" name="uid" value="<?php echo $users_info['uid'] ?>" readonly type="text"></td>
                                </tr>
                                <tr>
                                    <td><span>真实姓名：</span></td>
                                    <td><input class="form-control" name="true_name" value="<?php echo $users_info['true_name']?>" readonly type="text"></td>
                                </tr>
                                <tr>
                                    <td><span>手&nbsp;机&nbsp;号&nbsp;：</span></td>
                                    <td><input class="form-control" name="mobile" value="<?php echo $users_info['mobile'] ?>" type="text"></td>
                                </tr>
                                <tr>
                                    <td><span>账号状态：</span></td>
                                    <td>
                                        <select name="status" class="form-control select" style="width: 150px">
                                            <option value="">-- 账号状态 --</option>
                                            <?php foreach (config_item('user_status') as $k=>$user_status){?>
                                                <?php $selected = $users_info['status'] == $k ? "selected" : ''; ?>
                                                <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $user_status;?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr align="center">
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn_edit_users" style="width:80px;">修改信息</button>
                                        <button type="button" onclick="javascript:history.go(-1)" class='btn btn-default' style="width:80px;">取消</button>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>
<script>
    $(".btn_edit_users").click(function () {
        var obj = $(this);
        var oldVal = obj.text();
        obj.text("处理中...");
        obj.attr('disabled',"true");
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/doEdit_users",
            data: $("#edit_users_form").serialize(),
            dataType: "json",
            success: function (data) {
                obj.attr("disabled",false);
                obj.text(oldVal);
                layer.msg(data.msg);
                if (data.code == 100) {
                    window.location.href = '/admin/users_manage/index';
                }
            }
        });
    });
</script>