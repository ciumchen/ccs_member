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
            <form id="group_goods_from" class="form-inline">
                <textarea class="form-control" name="goods_no_list" style="width: 500px;height: 200px" placeholder="请输入商品ID，多个ID请换行"></textarea>
            </form>
            <br>
            <button type="button" class="btn btn-primary btn_group_goods" style="width: 200px;margin-left: 150px;">提交</button>
        </section>
    </section>
</section>
<script>
    $(".btn_group_goods").click(function () {
        $.ajax({
            type: "POST",
            url: "/admin/goods_manage/edit_group_goods",
            data: $("#group_goods_from").serialize(),
            dataType: "json",
            success: function (data) {
                layer.msg(data.msg);
                if (data.code == 0) {
                    window.location.reload();
                }
            }
        });
    })
</script>





