
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

                            <select name="module_name" class="form-control select module_name" style="width: 150px">
                                <option value="">-- 操作模块 --</option>
                                <?php foreach ($module_name_map as $module_name){?>
                                    <?php $selected = $searchData['module_name'] == $module_name ? "selected" : ''; ?>
                                    <option value="<?php echo $module_name?>" <?php echo $selected ?>><?php echo $module_name;?></option>
                                <?php }?>
                            </select>

                            <select name="function_name" class="form-control select function_name" style="width: 150px">
                                <option value="">-- 操作位置 --</option>
                                <?php foreach ($function_name_map as $item){?>
                                    <?php $selected = $searchData['function_name'] == $item['function_name'] ? "selected" : ''; ?>
                                    <option value="<?php echo $item['function_name']?>" <?php echo $selected ?>><?php echo $item['function_name']?></option>
                                <?php }?>
                            </select>

                            <select name="admin_name" class="form-control select" style="width: 150px">
                                <option value="">-- 操作者 --</option>
                                <?php foreach ($admin_name_map as $admin){?>
                                    <?php $selected = $searchData['admin_name'] == $admin['admin_name'] ? "selected" : ''; ?>
                                    <option value="<?php echo $admin['admin_name']?>" <?php echo $selected ?>><?php echo $admin['admin_name'];?></option>
                                <?php }?>
                            </select>

                            <input class="form-control" name="opera_obj_id" value="<?php echo $searchData['opera_obj_id'];?>"  placeholder="关键词查找" type="text">
                            <input class="form-control Wdate" name="start" value="<?php echo $searchData['start'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" type="text"> -
                            <input class="form-control Wdate" name="end" value="<?php echo $searchData['end'];?>" onclick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" type="text">
                            <button type="submit" style="margin-top: 6px;" class="btn btn-info"><i class="fa fa-search"></i> 搜 索</button>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>操作模块</th>
                                <th>操作位置</th>
                                <th>操作对象</th>
                                <th>动作描述</th>
                                <th>操作者</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($admin_action_log as $admin_action){?>
                                <tr>
                                    <th><?php echo $admin_action['module_name']?></th>
                                    <th><?php echo $admin_action['function_name']?></th>
                                    <th><?php echo $admin_action['opera_obj_id']?></th>
                                    <th style="width: 30%;"><?php echo $admin_action['action_text']?></th>
                                    <th><?php echo $admin_action['admin_name']?></th>
                                    <th><?php echo $admin_action['create_time']?></th>
                                </tr>
                            <?php }?>
                            <?php if ($admin_action_log == array()){?>
                                <tr>
                                    <th colspan="4"><p class="text-center minute">没有相关数据</p></th>
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
    $(".module_name").change(function(){
        var module_name = $(this).find("option:selected").val();

        //每次触发先清空option
        $(".function_name").empty();
        $(".function_name").append("<option value=''>-- 操作位置 --</option>");
        $.ajax({
            type: "POST",
            url: "/admin/Admin_action_log/get_function_name",
            data: {module_name:module_name},
            dataType: "json",
            success: function (data)
            {
                $.each(data.function_name_map, function(key,item){
                    $(".function_name").append("<option value='"+item.function_name+"'>"+item.function_name+"</option>");
                });
            }
        });
    });
</script>