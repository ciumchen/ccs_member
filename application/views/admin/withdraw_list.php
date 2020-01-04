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
                        <form class="form-inline" method="GET" id="withdraw_form">
                            <select name="status" class="form-control select" style="width: 150px">
                                <option value="">-- 状态 --</option>
                                <?php foreach (config_item('withdraw_status') as $k=>$text){?>
                                    <?php $selected = "{$searchData['status']}" === "{$k}" ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                <?php }?>
                            </select>
                            <select name="type" class="form-control select" style="width: 150px">
                                <option value="">-- 提现方式 --</option>
                                <?php foreach (config_item('withdraw_to') as $k=>$text){?>
                                    <?php $selected = "{$searchData['withdraw_to']}" == $k ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                <?php }?>
                            </select>

                            <input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">
                            <input class="form-control" name="mobile" value="<?php echo $searchData['mobile'];?>"  placeholder="请输入手机号码" type="text">
                            <input class="form-control" name="true_name" value="<?php echo $searchData['true_name'];?>"  placeholder="请输入姓名" type="text">

                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">

                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>

                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>手机号</th>
                                <th>提现方式</th>
                                <th>支付宝账号</th>
                                <th>支付宝姓名</th>
                                <th>提现金额</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($withdraw_list as $withdraw){?>

                                <!--驳回 -->
                                <div class="modal fade" id="withdraw_refute_modal_<?php echo $withdraw['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">驳回提现请求</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" id="refute_form">
                                                    <input class="form-control withdraw_id" name="withdraw_id" value="<?php echo $withdraw['id']?>" type="hidden">
                                                    <span>支付宝账号：<?php echo $withdraw['account']?></span><br><br>
                                                    <span>支付宝姓名：<?php echo $withdraw['account_name']?></span><br><br>
                                                    <span>提现金额：<?php echo $withdraw['amount']?></span><br><br>
                                                    <input class="form-control" name="remark" type="text" placeholder="请输入驳回原因" />
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                <button type="button" class="btn btn-primary btn_confirm_refute">确定驳回</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <tr>
                                    <th><?php echo $withdraw['uid']?></th>
                                    <th><?php echo $withdraw['mobile']?></th>
                                    <th><label class="label label-primary"><?php echo config_item('withdraw_to')[$withdraw['type']];?></label></th>
                                    <th><?php echo $withdraw['account'];?></th>
                                    <th><?php echo $withdraw['account_name'];?></th>
                                    <th class="marked">￥<?php echo $withdraw['amount']?></th>
                                    <th>
                                        <?php if ($withdraw['status'] == 1){?>
                                            <label class="label label-default"><?php echo config_item('withdraw_status')[$withdraw['status']];?></label>
                                        <?php }?>
                                        <?php if ($withdraw['status'] == 2){?>
                                            <label class="label label-success"><?php echo config_item('withdraw_status')[$withdraw['status']];?></label>
                                        <?php }?>
                                        <?php if ($withdraw['status'] == 3){?>
                                            <label class="label label-danger"><?php echo config_item('withdraw_status')[$withdraw['status']];?></label>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo $withdraw['remark']?>"></i>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $withdraw['create_time'];?></th>
                                    <th>
                                        <?php if ($withdraw['status'] == 1){?>
                                            <button type="button" onclick="order_withdraw_alipay($(this))" data-id="<?php echo $withdraw['id'];?>"  class="btn btn-info btn-xs">已打款</button>
                                            <button type="button" data-toggle="modal" data-target="#withdraw_refute_modal_<?php echo $withdraw['id']?>" class="btn btn-danger btn-xs btn_refute">驳回请求</button>
                                        <?php }?>
                                    </th>
                                </tr>
                            <?php }?>
                            <?php if ($withdraw_list == array()){?>
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
            <?php echo $pager;?>
            <li><span style="color: #e84c3d"><?php echo '共有'.$total_rows.'条记录'?></span></li>
        </ul>
        </nav>
    </section>
</section>
<script>
    $(function () { $("[data-toggle='tooltip']").tooltip(); });

    /* 提现到支付宝 */
    function order_withdraw_alipay(obj){
        if (confirm("确定已打款?")){
            var id = obj.attr('data-id');
            $.ajax({
                type: "POST",
                url: "/admin/users_manage/withdraw_to_alipay",
                data: {id:id},
                dataType: "json",
                success: function (data) {
                    layer.msg(data.msg);
                    if (data.code == 100){
                        window.location.reload();
                    }
                }
            });
        }
    }

    $(".btn_confirm_refute").click(function () {
        var form = $(this).parents('.modal-content').find('#refute_form');
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/withdraw_refute",
            data: form.serialize(),
            dataType: "json",
            success: function (data) {
                layer.msg(data.msg);
                if (data.code == 1001) {
                    $("[name='remark']").focus();
                }
                if (data.code == 100) {
                    window.location.reload();
                }
            }
        });
    })
</script>