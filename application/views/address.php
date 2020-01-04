<link rel="stylesheet" href="/html/css/address.css" />
<link href="/html/css/mui.picker.css" rel="stylesheet" />
<link href="/html/css/mui.poppicker.css" rel="stylesheet" />
</head>

<body>
<header class="mui-bar mui-bar-nav home-title">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">收货地址</h1>
</header>

<div class="mui-content">
    <section class="addressList">
        <?php foreach ($address_list as $key=>$value){ ?>
            <?php $class_name = $value['default'] == 1 ? 'check-box-div' : 'no-check-box-div'?>
            <?php $checked = $value['default'] == 1 ? 'checked' : ''?>
            <div class="mui-card" data-address-id="<?echo $value['id']?>">
                <div class="mui-card-content">
                    <div>
                        <span class="consignee"><?php echo $value['accept_name']?></span>
                        <span class="mobile"><?php echo $value['mobile']?></span>
                    </div>
                    <div>
                        <span class="address_details"><?php echo $value['details']?></span>
                    </div>
                </div>
                <div class="mui-card-footer">
                    <div class="mui-radio mui-left mui-card-link <?php echo $class_name ?>">
                        <input name="radio" type="radio" <?php echo $checked?> >
                        <span class="address address-biaoshi">已设为默认</span>
                        <span class="address no-address">设为默认</span>
                    </div>
                    <div class="mui-card-link address_op_span">
                        <span class="edit-address">编辑</span>
                        <span class="remove-address">删除</span>
                    </div>
                </div>
            </div>
        <?php }?>
    </section>
    <div class="botton-btn-sumint">
        <div class="shouAdd">
            <span class="mui-icon mui-icon-plusempty"></span>
            <span class="font15">添加新地址</span>
        </div>
    </div>
</div>

<!-- 新增地址窗口 -->
<div class="adressWin">
    <div class="close-adressWin" onclick="closeAddressWin()">
        <span class="close-title">添加新收货地址</span>
        <span  class="mui-icon mui-icon-closeempty"></span>
    </div>
    <form class="address_form">
        <div class="adressWin-content">
            <input type="hidden" name="province_id" value="">
            <input type="hidden" name="city_id" value="">
            <input type="hidden" name="area_id" value="">
            <input type="hidden" name="address_id" value="">
            <div class="detial1">
                <input type="text" name="consignee"  placeholder="收件人姓名"/>
                <input type="tel" name="mobile"  placeholder="收件人手机号码"/>
            </div>
            <div class="detial2" id="showCityPicker3">
                <span class="detial2-1" id="cityResult3">选择地址</span>
                <span class="detial2-2 mui-icon mui-icon-arrowright"></span>
            </div>
            <div class="detial3">
                <textarea rows="3" name="details" placeholder="详细地址（可填写街道、小区、大厦）"></textarea>
            </div>
        </div>
    </form>
    <div class="adressWin-bottom">
        <button type="button" class="">保存</button>
    </div>
</div>
<div class="mui-adressWin-backdrop"></div>
</body>

<script src="/html/js/mui.picker.min.js"></script>
<script src="/html/js/mui.poppicker.js"></script>
<script src="/html/js/regions.js" type="text/javascript" charset="utf-8"></script>
<script src = "/html/js/address.js"></script>
