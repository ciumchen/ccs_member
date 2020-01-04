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
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body " style="width: 40%;">
                    <form id="edit_account_form">
                        <table class="table table_account_info">
                            <tbody>
                                <tr>
                                    <td><span>　用户名</span></td>
                                    <input class="form-control" name="id" value="<?php echo $admin_info['id']?>" readonly type="hidden">
                                    <td><input class="form-control" name="admin_user" value="<?php echo $admin_info['admin_name']?>" readonly type="text"></td>
                                </tr>
                                <tr>
                                    <td><span>真实姓名</span></td>
                                    <td><input class="form-control" name="realname" value="<?php echo $admin_info['realname']?>" readonly  type="text"></td>
                                </tr>
                                <tr>
                                    <td><span>账号角色</span></td>
                                    <td>
                                        <select name="role" class="form-control select" style="width: 150px">
                                            <option value="">-- 账号角色 --</option>
                                            <?php foreach (config_item('admin_role') as $k=>$admin_role){?>
                                                <?php $selected = $admin_info['role'] == $k ? "selected" : ''; ?>
                                                <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $admin_role;?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span>账号状态</span></td>
                                    <td>
                                        <select name="status" class="form-control select" style="width: 150px">
                                            <option value="">-- 账号状态 --</option>
                                            <?php foreach (config_item('admin_status') as $k=>$admin_status){?>
                                                <?php $selected = $admin_info['status'] == $k ? "selected" : ''; ?>
                                                <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $admin_status;?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><button type="button" class="btn btn-danger btn-block btn_edit_account_info">修改信息</button></td>
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
    $(".btn_edit_account_info").click(function () {
        var obj = $(this);
        var oldVal = obj.text();
        obj.text("处理中...");
        obj.attr('disabled',"true");
        $.ajax({
            type: "POST",
            url: "/admin/admin_account_manage/edit_account_info_submit",
            data: $("#edit_account_form").serialize(),
            dataType: "json",
            success: function (data) {
                obj.attr("disabled",false);
                obj.text(oldVal);
                layer.msg(data.msg);
                if (data.code == 100) {
                    window.location.href = '/admin/admin_account_manage/admin_account_list';
                }
            }
        });
    });
</script>