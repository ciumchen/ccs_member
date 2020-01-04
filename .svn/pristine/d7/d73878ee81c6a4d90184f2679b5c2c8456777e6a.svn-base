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

            <div class="panel panel-default">
                <div class="panel-heading">系统参数</div>
                <div class="panel-body">
                    <div class="tab-wrapper tab-primary">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#home1" data-toggle="tab">今日牌价</a>
                            </li>
                            <li><a href="#profile1" data-toggle="tab">经营奖的分配总数</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home1">
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    最近5天牌价。</div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>日期</th>
                                        <th>牌价</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($rows as $row){?>
                                        <tr>
                                            <th><?php echo $row['day']?></th>
                                            <th><?php echo $row['gold_price']?></th>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                                <form id="calc_gold_form">
                                    <table class="table table_account_info">
                                        <tbody>
                                        <tr>
                                            <td><span>最近日期</span></td>
                                            <td>
                                                <select name="day" class="form-control select" style="width: 150px">
                                                    <option value="">-- 日期选择 --</option>
                                                    <?php foreach ($days as $v){?>
                                                        <option value="<?php echo $v?>"><?php echo $v;?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>注册会员增长率：权重0.2</span></td>
                                            <td><input class="form-control" id="X" value="" readonly type="text"></td>
                                        </tr>
                                        <tr>
                                            <td><span>VIP增长率：权重0.4</span></td>
                                            <td><input class="form-control" id="Y" value="<?php ?>" readonly  type="text"></td>
                                        </tr>
                                        <tr>
                                            <td><span>商城营业额增长率：权重0.1</span></td>
                                            <td><input class="form-control" id="Z" value="<?php ?>" readonly  type="text"></td>
                                        </tr>
                                        <tr>
                                            <td><span>商城毛利增长率：权重0.2</span></td>
                                            <td><input class="form-control" id="P" value="<?php ?>"  readonly type="text"></td>
                                        </tr>
                                        <tr>
                                            <td><span>大数据经营产生纯利润：权重0.1</span></td>
                                            <td><input class="form-control" id="Q" placeholder="请手动输入，未产生纯利润填0" name="dataProfit" value="<?php ?>"   type="text"></td>
                                        </tr>
                                        <tr>
                                            <td><span>今日牌价</span></td>
                                            <td><input id="gold" style="display: none" class="form-control" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><button type="button" class="btn btn-danger btn_edit_account_info">预算</button>
                                                <button type="button" class="btn btn-danger btn_edit_account_info2">提交</button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="tab-pane" id="profile1">
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>注意！</strong>每月初填写一次，经营奖的分配总数是上个月产生，提交后将根据团队利润权重和分配总额计算会员应得的金币等价券（待发状态）。</div>
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="month" class="col-sm-2 control-label">月份：</label>
                                        <div class="col-sm-10">
                                            <?php echo $year_month;?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reward" class="col-sm-2 control-label">分配总数：</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="reward" placeholder="支持2位小数" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-primary reward-btn">提交</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

    <script>
        $(".btn_edit_account_info").click(function () {
            var obj = $(this);
            var oldVal = obj.text();
            if($('[name="day"] :selected').val() ==  ''){
                layer.msg("请选择日期");
                return;
            }
            if($('[name="dataProfit"]').val() == ''){
                layer.msg("请输入大数据纯利润，没有填写0");
                return;
            }
            obj.text("处理中...");
            obj.attr('disabled',"true");
            $.ajax({
                type: "POST",
                url: "/admin/system_manage/param_setting",
                data: {day:$('[name="day"] :selected').val(),dataProfit:$('[name="dataProfit"]').val()},
                dataType: "json",
                success: function (data) {
                    obj.attr("disabled",false);
                    obj.text(oldVal);

                    if (data.code == 0) {
                        $('#X').val(data.data.X);
                        $('#Y').val(data.data.Y);
                        $('#Z').val(data.data.Z);
                        $('#P').val(data.data.P);
                        $('#gold').val(data.data.gold);
                        $('#gold').show();
                    }else{
                        layer.msg(data.msg);
                    }
                }
            });
        });

        $(".btn_edit_account_info2").click(function () {
            var obj = $(this);
            var oldVal = obj.text();
            if($('[name="day"] :selected').val() ==  ''){
                layer.msg("请选择日期");
                return;
            }
            if($('[name="dataProfit"]').val() == ''){
                layer.msg("请输入大数据纯利润，没有填写0");
                return;
            }
            if($('#gold').val() == ''){
                layer.msg("请先预算后，再提交。");
                return;
            }
            obj.text("处理中...");
            obj.attr('disabled',"true");
            $.ajax({
                type: "POST",
                url: "/admin/system_manage/param_setting",
                data: {day:$('[name="day"] :selected').val(),gold:$('#gold').val(),isSubmit:1},
                dataType: "json",
                success: function (data) {
                    obj.attr("disabled",false);
                    obj.text(oldVal);

                    if (data.code == 0) {
                        layer.msg("提交牌价成功！");
                        setTimeout(function () {
                            location.reload();
                        },1000);
                    }else{
                        layer.msg(data.msg);
                    }
                }
            });
        });

        $(".reward-btn").click(function () {
            var obj = $(this);
            var oldVal = obj.text();

            if($('#reward').val() == ''){
                layer.msg("请输入分配总额");
                return;
            }
            obj.text("处理中...");
            obj.attr('disabled',"true");
            $.ajax({
                type: "POST",
                url: "/admin/reward_manage/addBusinessGoldPrice",
                data: {reward:$('#reward').val()},
                dataType: "json",
                success: function (data) {
                    obj.attr("disabled",false);
                    obj.text(oldVal);

                    if (data.code == 0) {
                        layer.msg(data.msg);
                        $('#reward').val();
                    }else{
                        layer.msg(data.msg);
                    }
                }
            });
        });
    </script>





