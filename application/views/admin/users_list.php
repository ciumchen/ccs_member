<!--  添加账户  -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加会员</h4>
            </div>
            <div class="modal-body">
                <form id="add_users_account_form">
                    <table class="table table_account_info">
                        <tbody>
                            <tr>
                                <td><span>真实姓名：</span></td>
                                <td><input class="form-control" name="true_name" placeholder="请输入真实姓名"  type="text"></td>
                            </tr>
                            <tr>
                                <td><span>手&nbsp;机&nbsp;号：</span></td>
                                <td><input class="form-control" name="mobile" placeholder="请输入手机号"  type="text"></td>
                            </tr>
                            <tr>
                                <td><span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</span></td>
                                <td><input class="form-control" name="password" placeholder="请输入登录密码"  type="text"></td>
                            </tr>
                            <tr>
                                <td><span>推荐人手机号：</span></td>
                                <td><input class="form-control" name="parent_mobile" placeholder="请输入推荐人手机号"  type="text"></td>
                            </tr>
                            <tr>
                                <td><span>奖励类型：</span></td>
                                <td>
                                <select name="type" class="form-control" style="width: 150px">
                                    <option value="">-- 金币来源 --</option>
                                    <?php foreach (config_item('gold_type') as $k=>$text){?>
                                        <?php $selected = "{$searchData['gold_type']}" == $k ? "selected" : ''; ?>
                                        <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                    <?php }?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span>注册奖励：</span></td>
                                <td><input class="form-control" name="gold" placeholder="请输入金币等价券数量"  type="text"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                    <div class="modal-footer" style="text-align:center">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="width:80px;">关闭</button>
                        <button type="button" class="btn btn-primary btn_add_users_account">确定添加</button>
                    </div>
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
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <form class="form-inline" method="GET">
                            <select name="level" class="form-control select">
                                <option value="">-- 请选择客户等级 --</option>
                                <option value="1" <?php echo $searchData['level']=='1'? 'selected':''?>><?php echo 'VIP1'?></option>
                                <option value="2" <?php echo $searchData['level']=='2'? 'selected':''?>><?php echo 'VIP2'?></option>
                                <option value="3" <?php echo $searchData['level']=='3'? 'selected':''?>><?php echo 'VIP3'?></option>
                                <option value="4" <?php echo $searchData['level']=='4'? 'selected':''?>><?php echo 'VIP4'?></option>
                                <option value="5" <?php echo $searchData['level']=='5'? 'selected':''?>><?php echo 'VIP5'?></option>
                                <option value="6" <?php echo $searchData['level']=='6'? 'selected':''?>><?php echo 'VIP6'?></option>
                            </select>

                            <select name="status" class="form-control select">
                                <option value="">-- 账户状态 --</option>
                                <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo config_item('user_status')[1]?></option>
                                <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo config_item('user_status')[2]?></option>
                            </select>
                            
                            <input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">
                            <input class="form-control" name="mobile" value="<?php echo $searchData['mobile'];?>"  placeholder="请输入手机号" type="text">
                            <input class="form-control" name="true_name" value="<?php echo $searchData['true_name'];?>"  placeholder="请输入真实姓名" type="text">
                            <input class="form-control" name="parent_id" value="<?php echo $searchData['parent_id'];?>"  placeholder="请输入推荐人ID" type="text">
                            <br>
                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                            <?php if (in_array($adminInfo['role'],array(1))){?>
                            <button type="button" style="margin: 6px 150px;" class="btn btn-info" data-toggle="modal"  data-target="#addAccountModal"><i class="fa fa-plus"></i> 添加会员</button>
                            <?php }?>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>头像</th>
                                <th>会员ID</th>
                                <th>手机号</th>
                                <th>真实姓名</th>
                                <th>等级</th>
                                <th>累计积分</th>
                                <th>累计消费额</th>
                                <th>累计提现金额</th>
                                <th>推荐人ID</th>
                                <th>账户状态</th>
                                <th>加入时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users_list as $user){?>
                                <div class="modal fade" id="user_details_modal_<?php echo $user['uid']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">用户详细信息</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p class="p_block">
                                                    <span><img style="width:40px;height: 40px" src="<?php echo $user['image_url']?>"></span>
                                                    &nbsp;&nbsp;<span>手机号:<?php echo $user['mobile']?></span><br />
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>姓名:<?php echo $user['true_name']?></span>
                                                </p>
                                                <p class="p_block">
                                                    <span>个人消费奖：</span><span class="marked">￥<?php echo $user['reward']['shopping_reward']?></span>
                                                </p>
                                                <p class="p_block">
                                                    <span>分享奖：</span><span class="marked">￥<?php echo $user['reward']['m_rec_reward']?></span>
                                                </p>
                                                <p class="p_block">
                                                    <span>供应商推荐奖：</span><span class="marked">￥<?php echo $user['reward']['s_rec_reward']?></span>
                                                </p>
                                                <p class="p_block">
                                                    <span>开拓和管理奖：</span><span class="marked">￥<?php echo $user['reward']['manage_reward']?></span>
                                                </p>
                                                <p class="p_block">
                                                    <span>经营奖：</span><span class="marked">￥<?php echo $user['reward']['plat_reward']?></span>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                <button type="button" class="btn btn-primary btn_edit_users">确定</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr class="active">
                                    <th><img style="width:40px;height: 40px" src="<?php echo $user['image_url']?>"></th>
                                    <th><?php echo $user['uid']?></th>
                                    <th><?php echo $user['mobile']?></th>
                                    <th><?php echo $user['true_name']?></th>
                                    <th><?php echo "VIP".$user['level']?></th>
                                    <th><?php echo $user['point']?></th>
                                    <th><?php echo $user['shopping_amount']?></th>
                                    <th><?php echo $user['withdraw_amount']?></th>
                                    <th><?php echo $user['parent_id']?></th>
                                    <th>
                                        <?php if ($user['status'] == 1){?>
                                            <span class="label label-success"><?php echo config_item('user_status')[$user['status']]?></span>
                                        <?php }?>
                                        <?php if ($user['status'] == 2){?>
                                            <span class="label label-default"><?php echo config_item('user_status')[$user['status']]?></span>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $user['create_time']?></th>
                                    <th>
                                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user_details_modal_<?php echo $user['uid']?>">查看详细</button>
                                        <button type="button" data-user-id="<?php echo base64_encode($user['uid'])?>" class="btn btn-info btn_review_back_office btn-xs">查看后台</button>
                                        <?php if (in_array($adminInfo['role'],array(1))){?>
                                        <a href="<?php echo "/admin/users_manage/edit_users_info?uid={$user['uid']}"?>" type="button" class="btn btn-danger btn-xs btn_refute" style="width:65px;">编辑</a>
                                        <?php }?>
                                    </th>
                                </tr>
                            <?php }?>
                            <?php if ($users_list == array()){?>
                                <tr>
                                    <th colspan="12"><p class="text-center minute">没有相关数据</p></th>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <nav style="text-align: center">
        <ul class="pagination" align="center">
            <?php echo $pager; ?>
            <li><span style="color: #e84c3d"><?php echo '共有'.$total_rows.'条记录'?></span></li>
        </ul>
        </nav>
    </section>
</section>
<script>

    $(".btn_add_users_account").click(function () {
        var obj = $(this);
        var oldVal = obj.text();
        obj.text("处理中...");
        obj.attr('disabled',"true");
        $.ajax({
            type: "POST",
            url: "/admin/Users_manage/add_users_submit",
            data: $("#add_users_account_form").serialize(),
            dataType: "json",
            success: function (data) {
                obj.attr("disabled",false);
                obj.text(oldVal);
                layer.msg(data.msg);
                if (data.code == 100) {
                    window.location.href = '/admin/Users_manage/index';
                }
                if (data.code == 1011){
                    $("[name='true_name']").focus();
                }
                if (data.code == 1012){
                    $("[name='mobile']").focus();
                }
                if (data.code == 1013){
                    $("[name='password']").focus();
                }
                if (data.code == 1014){
                    $("[name='parent_mobile']").focus();
                }
                if (data.code == 1015){
                    $("[name='type']").focus();
                }
                if (data.code == 1016){
                    $("[name='gold']").focus();
                }
            }
        });
    });

    $(".btn_show").click(function () {
        $(".manual_id").val($(this).attr('data-user-id'));
    })

    $(".btn_manual_re").click(function () {
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/manual_re",
            data: $("#manual_re_form").serialize(),
            dataType: "json",
            success: function (data) {
                layer.msg(data.msg);
                if (data.code == 100) {
                    window.location.reload();
                }
                if (data.code == 1001){
                    $("[name='amount']").focus();
                }
                if (data.code == 1002){
                    $("[name='remark']").focus();
                }
                if (data.code == 1010){
                    $("[name='card_no']").focus();
                }
            }
        });
    })

    $(".btn_review_back_office").click(function () {
        var user_id = $(this).attr('data-user-id');
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/review_back_office",
            data: {user_id:user_id},
            dataType: "json",
            success: function (data) {
                if (data.code == 100){
                    window.open('/');
                }
            }
        });
    })
</script>