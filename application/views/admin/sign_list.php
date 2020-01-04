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
                            
                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户ID</th>
                                <th>签到日期</th>
                                <th>签到时间</th>
                                <!-- <th>操作</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($sign_list as $sign){?>
                                <tr>
                                    <th><?php echo $sign['id']?></th>
                                    <th><?php echo $sign['uid']?></th>
                                    <th><?php echo $sign['sign_day'];?></th>
                                    <th><?php echo $sign['sign_time'];?></th>
                                    <!-- <th>
                                        <button type="button" data-user-id="<?php echo base64_encode($level['uid'])?>" class="btn btn-info btn_review_back_office btn-xs">查看后台</button>
                                    </th> -->
                                </tr>
                            <?php }?>
                            <?php if ($sign_list == array()){?>
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
    
</script>