<!--  添加账户  -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加管理员账号</h4>
            </div>
            <div class="modal-body">
                <form id="add_admin_account_form">
                    <table class="table table_account_info">
                        <tbody>
                        <tr>
                            <td><span>登录账号</span></td>
                            <td><input class="form-control" name="admin_name" placeholder="请输入登录账号"  type="text"></td>
                        </tr>
                        <tr>
                            <td><span>密码</span></td>
                            <td><input class="form-control" name="admin_password" placeholder="请输入登录密码"  type="text"></td>
                        </tr>
                        <tr>
                            <td><span>真实姓名</span></td>
                            <td><input class="form-control" name="realname" placeholder="请输入账号使用者真实姓名"  type="text"></td>
                        </tr>
                        <tr>
                            <td><span>账号角色</span></td>
                            <td>
                                <select name="role" class="form-control select" style="width: 150px">
                                    <option value="">-- 账号角色 --</option>
                                    <?php foreach (config_item('admin_role') as $k=>$admin_role){?>
                                        <option value="<?php echo $k?>" ><?php echo $admin_role;?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary btn_add_admin_account">确定添加</button>
            </div>
        </div>
    </div>
</div>

<section class="main-content-wrapper">
    <section id="main-content">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo $domain_admin?>">管理后台</a></li>
                    <li><?php echo $title;?></li>
                    <?php if (isset($item_title)){?>
                        <li><?php echo $item_title;?></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <button type="button" class="btn btn-info" data-toggle="modal"  data-target="#addAccountModal"><i class="fa fa-plus"></i> 添加管理员账号</button>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>用户名</th>
                                    <th>真实姓名</th>
                                    <th>角色</th>
                                    <th>账号状态</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($admin_user_list as $admin){?>
                                <tr class="active">
                                    <th class="marked"><?php echo $admin['id']?></th>
                                    <th><?php echo $admin['admin_name']?></th>
                                    <th><?php echo $admin['realname']?></th>
                                    <th>
                                        <?php if ($admin['role'] == 1){?>
                                            <span class="label label-danger"><?php echo config_item('admin_role')[$admin['role']] ?></span><br>
                                        <?php }?>
                                        <?php if ($admin['role'] == 2){?>
                                            <span class="label label-info"><?php echo config_item('admin_role')[$admin['role']] ?></span><br>
                                        <?php }?>
                                    </th>
                                    <th>
                                        <?php if ($admin['status'] == 1){?>
                                            <span class="label label-success"><?php echo config_item('admin_status')[$admin['status']]?></span><br>
                                        <?php }?>
                                        <?php if ($admin['status'] == 2){?>
                                            <span class="label label-default"><?php echo config_item('admin_status')[$admin['status']]?></span><br>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $admin['create_time']?></th>
                                    <th>
                                        <?php if($_SESSION['admin_name'] !== $admin['admin_name']){?>
                                        <a href="<?php echo "/admin/admin_account_manage/edit_account_info?admin_name={$admin['admin_name']}"?>" type="button" class="btn btn-info">编辑</a>
                                        <?php }?>
                                    </th>
                                </tr>
                            <?php }?>
                            <?php if ($admin_user_list == array()){?>
                                <tr>
                                    <th colspan="11"><p class="text-center minute">没有相关数据</p></th>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <nav style="text-align: center">
        <ul class="pagination">
            <?php echo $pager; ?>
            <li><span style="color: #e84c3d"><?php echo '共有'.$total_rows.'条记录'?></span></li>
        </ul>
        </nav>
    </section>
</section>
<script>
    $(".btn_add_admin_account").click(function () {
        var obj = $(this);
        var oldVal = obj.text();
        obj.text("处理中...");
        obj.attr('disabled',"true");
        $.ajax({
            type: "POST",
            url: "/admin/admin_account_manage/add_admin_account_submit",
            data: $("#add_admin_account_form").serialize(),
            dataType: "json",
            success: function (data) {
                obj.attr("disabled",false);
                obj.text(oldVal);
                layer.msg(data.msg);
                if (data.code == 100) {
                    window.location.href = '/admin/admin_account_manage/admin_account_list';
                }
                if (data.code == 1011){
                    $("[name='admin_name']").focus();
                }
                if (data.code == 1012){
                    $("[name='admin_password']").focus();
                }
                if (data.code == 1013){
                    $("[name='realname']").focus();
                }
            }
        });
    });
</script>