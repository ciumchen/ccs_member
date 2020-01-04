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

                            <select name="type" class="form-control select" style="width: 150px">
                                <option value="">-- 奖金类型 --</option>
                                <?php foreach (config_item('reward_type') as $k=>$reward_text){?>
                                    <?php $selected = $searchData['type'] == $k ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $reward_text;?></option>
                                <?php }?>
                            </select>

                            <select name="status" class="form-control select" style="width: 150px">
                                <option value="">-- 奖金状态 --</option>
                                <?php foreach (config_item('reward_status') as $k=>$status_text){?>
                                    <?php $selected = $searchData['status'] === "$k" ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $status_text;?></option>
                                <?php }?>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">
                            <input class="form-control" name="child_uid" value="<?php echo $searchData['child_uid'];?>"  placeholder="请输入佣金来源ID" type="text">
                            <input class="form-control" name="order_id" value="<?php echo $searchData['order_id'];?>"  placeholder="请输入订单号" type="text">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>佣金来源ID</th>
                                <th>金额</th>
                                <th>百分比</th>
                                <th>奖金类型</th>
                                <th>奖金状态</th>
                                <th>订单号</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($reward_list as $reward){?>
                                <tr class="active">
                                    <th><?php echo $reward['uid']?></th>
                                    <th><?php echo $reward['child_uid']?></th>
                                    <th class="marked"><?php echo $reward['amount']?></th>
                                    <th><?php echo $reward['percent']*100;?>%</th>
                                    <th><?php echo config_item('reward_type')[$reward['type']]?>
                                        <?php if ($reward['remark']){?>
                                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo $reward['remark']?>"></i>
                                        <?php }?>
                                    </th>
                                    <th>
                                        <?php if ($reward['status'] == 0){?>
                                            <span class="label label-default"><?php echo config_item('reward_status')[$reward['status']]?></span>
                                        <?php }?>
                                        <?php if ($reward['status'] == 1){?>
                                            <span class="label label-success"><?php echo config_item('reward_status')[$reward['status']]?></span>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $reward['order_id']?></th>
                                    <th><?php echo $reward['create_time']?></th>
                                    <th>
                                        <?php if($reward['type'] == 1 && isset($reward['is_refund']) && $reward['is_refund'] == 0){ ?>
                                        <button type="button" onclick="refund(<?php echo $reward['id']?>,<?php echo $reward['order_id']?>,<?php echo $reward['goods_id']?>)" class="btn btn-danger btn-xs btn_refute">商品退货</button>
                                        <?php }?>
                                    </th>
                                </tr>
                            <?php }?>
                            <?php if ($reward_list == array()){?>
                                <tr>
                                    <th colspan="8"><p class="text-center minute">没有相关数据</p></th>
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
<!--退款 -->
<div class="modal fade" id="withdraw_refute_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">商品退货操作</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="refund_form">
                    <input class="form-control" id="reward_id" name="reward_id" value="" type="hidden">
                    <span>注意！！！商品退货操作是商品的维度。抽回会员的积分，等级，奖金，消费额。</span><br><br>
                    <span id="order_id"></span><br><br>
                    <span id="goods_id"></span><br><br>
                    <input class="form-control" name="remark" type="text" placeholder="请输入退货备注">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary btn_confirm_refute">确定退货</button>
            </div>
        </div>
    </div>
</div>
<script>

    $(function () {

        $("[data-toggle='tooltip']").tooltip();
    });

    function refund(id,order_id,goods_id) {
        $('#withdraw_refute_modal').modal('show');
        $('#reward_id').val(id);
        $('#order_id').text("订单号："+order_id);
        $('#goods_id').text("商品ID："+goods_id);
    }

    $(".btn_confirm_refute").click(function () {

        $.ajax({
            type: "POST",
            url: "/admin/Reward_manage/refundOrderGoods",
            data: $("#refund_form").serialize(),
            dataType: "json",
            success: function (data) {
                layer.msg(data.msg);
                if (data.code == 1001) {
                    $("[name='remark']").focus();
                }
                if (data.code == 100) {
                    $('#withdraw_refute_modal').modal('hide');
                    window.location.reload();
                }
            }
        });
    })
</script>