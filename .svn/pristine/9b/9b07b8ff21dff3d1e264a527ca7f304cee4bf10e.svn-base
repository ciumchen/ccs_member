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
                                <option value="">-- 积分来源 --</option>
                                <option value="1" <?php echo $searchData['type']=='1'? 'selected':''?>><?php echo '消费订单'?></option>
                                <option value="2" <?php echo $searchData['type']=='2'? 'selected':''?>><?php echo '订单产品退款'?></option>
                                <!-- <?php foreach (config_item('type_map') as $k=>$text){?>
                                    <?php $selected = "{$searchData['type_mappe']}" == $k ? "selected" : ''; ?>
                                    <option value="<?php echo $k?>" <?php echo $selected ?>><?php echo $text;?></option>
                                <?php }?> -->
                            </select>
                            
                            <input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">
                            <input class="form-control" name="order_id" value="<?php echo $searchData['order_id'];?>"  placeholder="请输入订单号" type="text">
                            <input class="form-control" name="goods_id" value="<?php echo $searchData['goods_id'];?>"  placeholder="请输入商品ID" type="text">
                            <input class="form-control" name="child_uid" value="<?php echo $searchData['child_uid'];?>"  placeholder="请输入下线UID" type="text">

                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>用户ID</th>
                                    <th>积分</th>
                                    <th>订单号</th>
                                    <th>商品ID</th>
                                    <th>下线UID</th>
                                    <th>积分来源</th>
                                    <th>创建时间</th>
                                    <!-- <th>操作</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($point_list as $point){?>
                                <tr>
                                    <th><?php echo $point['id']?></th>
                                    <th><?php echo $point['uid']?></th>
                                    <th><?php echo $point['point']?></th>
                                    <th><?php echo $point['order_id']?></th>
                                    <th><?php echo $point['goods_id']?></th>
                                    <th><?php echo $point['child_uid']?></th>
                                    <th>
                                        <?php if ($point['type'] == 1){?>
                                            <span class="label label-success"><?php echo '消费订单'?></span>
                                        <?php }?>
                                        <?php if ($point['type'] == 2){?>
                                            <span class="label label-success"><?php echo '订单产品退款'?></span>
                                        <?php }?>
                                    </th>
                                    <th><?php echo $point['create_time'];?></th>
                                    <!-- <th>
                                        <button type="button" data-user-id="<?php echo base64_encode($point['uid'])?>" class="btn btn-info btn_review_back_office btn-xs">查看后台</button>
                                    </th> -->
                                </tr>
                            <?php }?>
                            <?php if ($point_list == array()){?>
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
<!-- <script>
    $(".btn_show").click(function () {
        $(".manual_id").val($(this).attr('data-user-id'));
    })


    $(".btn_review_back_office").click(function () {
        var user_id = $(this).attr('data-user-id');
        $.ajax({
            type: "POST",
            url: "/admin/users_manage/point_list",
            data: {user_id:user_id},
            dataType: "json",
            success: function (data) {
                if (data.code == 100){
                    window.open('/');
                }
            }
        });
    })

</script> -->