<!--  帮扶计划  -->
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
							<input class="form-control" name="uid" value="<?php echo $searchData['uid'];?>"  placeholder="请输入用户ID" type="text">

							<input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM'})" placeholder="开始月份" type="text"> -
							<input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM'})" placeholder="结束月份" type="text">
							<button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
						</form>
						<table class="table">
							<thead>
							<tr>
								<th>编号</th>
								<th>用户ID</th>
								<th>期数</th>
								<th>启动月份</th>
								<th>当月计划</th>
								<th>奖励状态</th>
								<th>当月利润</th>
								<th>订单号</th>
								<!-- <th>操作</th> -->
							</tr>
							</thead>
							<tbody>
							<?php foreach ($helpPlan_list as $helpPlan){?>
							<tr>
								<th><?php echo $helpPlan['id']?></th>
								<th><?php echo $helpPlan['uid']?></th>
								<th><?php echo $helpPlan['count']?></th>
								<th><?php echo $helpPlan['year_month']?></th>
								<th>
									<?php if ($helpPlan['is_buy'] == 0){?>
									<label class="label label-default"><?php echo config_item('is_buy')[$helpPlan['is_buy']];?></label>
									<?php }?>
									<?php if ($helpPlan['is_buy'] == 1){?>
									<label class="label label-success"><?php echo config_item('is_buy')[$helpPlan['is_buy']];?></label>
									<?php }?>
								</th>
								<th>
									<?php if ($helpPlan['status'] == 0){?>
									<label class="label label-default"><?php echo config_item('help_status')[$helpPlan['status']];?></label>
									<?php }?>
									<?php if ($helpPlan['status'] == 1){?>
									<label class="label label-success"><?php echo config_item('help_status')[$helpPlan['status']];?></label>
									<?php }?>
								</th>
								<th><?php echo $helpPlan['profit'];?></th>
								<th><?php echo $helpPlan['order_id'];?></th>
								<!-- <th>
                                    <button type="button" data-user-id="<?php echo base64_encode($gold['uid'])?>" class="btn btn-info btn_review_back_office btn-xs">查看后台</button>
                                </th> -->
							</tr>
							<?php }?>
							<?php if ($helpPlan_list == array()){?>
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
