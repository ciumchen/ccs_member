<!--  金币总数  -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">查看金币数量</h4>
            </div>
            <div class="modal-body">
                <p class="p_block">
                     <?php foreach ($totGold as $key=>$val){?>
                       <?php echo $val['type'];?>：<?php echo $val['gold']; ?></br>
                    <?php }?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
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
                            <!--<select name="status" class="form-control select" style="width: 150px">
                                <option value="">-- 状态 --</option>
                                <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo '未入账'?></option>
                                <option value="1" <?php echo $searchData['status']=='1'? 'selected':''?>><?php echo '已入账'?></option>
                                <?php foreach (config_item('status_map') as $k=>$text){?>
                                    <?php $selected = "{$searchData['status_map']}" == $k ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                <?php }?>
                            </select>-->

                            <select name="type" class="form-control select" style="width: 150px">
                                <option value="">-- 金币来源 --</option>
                                <?php foreach (config_item('gold_type') as $k=>$text){?>
                                    <?php $selected = "{$searchData['type']}" == $k ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                <?php }?>
                            </select>

                            <input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">
                            
                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                            <button type="button" style="margin: 6px 150px;" class="btn btn-info" data-toggle="modal"  data-target="#addAccountModal"><i class="fa fa-eye"></i>查看金币数量</button>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户ID</th>
                                <th>金币数量</th>
                                <th>金币来源</th>
                                <th>关联会员</th>
                                <th>订单号</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <!-- <th>操作</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($gold_list as $gold){?>
                                <tr>
                                    <th><?php echo $gold['id']?></th>
                                    <th><?php echo $gold['uid']?></th>
                                    <th><?php echo $gold['gold']?></th>
                                    <th><label class="label label-primary"><?php echo config_item('gold_type')[$gold['type']];?></label></th>
                                    <th><?php echo $gold['child_uid']?></th>
                                    <th><?php echo $gold['order_id']?></th>
                                    <th>
                                        <?php if ($gold['status'] == 0){?>
                                            <label class="label label-default"><?php echo config_item('gold_status')[$gold['status']];?></label>
                                        <?php }?>
                                        <?php if ($gold['status'] == 1){?>
                                            <label class="label label-success"><?php echo config_item('gold_status')[$gold['status']];?></label>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $gold['create_time'];?></th>
                                    <!-- <th>
                                        <button type="button" data-user-id="<?php echo base64_encode($gold['uid'])?>" class="btn btn-info btn_review_back_office btn-xs">查看后台</button>
                                    </th> -->
                                </tr>
                            <?php }?>
                            <?php if ($gold_list == array()){?>
                                <tr>
                                    <th colspan="11"><p class="text-center minute">没有相关数据</p></th>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <!-- <span style="color: #e84c3d"><?php echo $searchData['type']=='1'?><?php echo $goldDetail; ?></span> -->
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
    $(".btn_show").click(function () {
        $(".manual_id").val($(this).attr('data-user-id'));
    })


    $(".btn_review_back_office").click(function () {
        var user_id = $(this).attr('data-user-id');
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/gold_list",
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
