<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>分享会员 赢取金币</title>
    <!-- css -->
    <link href="../../html/css/mui.min.css" rel="stylesheet" />
    <link href="../../html/css/mui.picker.min.css" rel="stylesheet" />
    <link href="../../html/css/home/bottomTap.css" rel="stylesheet" >
    <link href="../../html/css/icons-extra.css" rel="stylesheet" >
    <!-- <link href="../../css/myInfo/goldenCoin.css" rel="stylesheet" />    -->
    <link href="../../html/css/marketing/sharePoster.css?v=1019" rel="stylesheet" />
    <!-- js -->
    <script src="../../html/js/mui.min.js"></script>
    <script src="../../html/js/mui.picker.min.js"></script>
    <script src="../../html/js/qrcode.min.js"></script>
</head>

<header class="mui-bar mui-bar-nav white-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">分享会员 赢取金币</h1>
</header>
<body class="share-poster">
<!-- 内容区域 -->
<div class="mui-content">
    <!-- 总览 -->
    <div class="section-poster"></div>
    <section class="section-button-share">
        <a href="javascript:;" class="btn-share btn-share-instant">
            <span style="border-left: solid 3px #f21910;padding: 5px;font-size: 15px">立即分享</span>
        </a>
        <a href="javascript:;" class="btn-share btn-share-qtcode">扫码分享</a>
    </section>
</div>

<!-- 立即分享弹窗 -->
<div id="dialog-share-instant" class="mui-popover x-dialog-wrap">
    <div id="qrcode" style="display: none;"></div>
    <img id="result">
</div>
<!-- 扫码分享弹窗 -->
    <div id="dialog-share-qtcode" class="mui-popover x-dialog-wrap">
        <div>
            <h2>扫码领取20数据金币等价券</h2>
            <img  style="height: 180px;width: 180px;" src="/welcome/qr_code">
        </div>
    </div>

    <script>
        mui.init();
        // 立即分享
        mui('body').on('click', '.btn-share-instant', function () {
            mui('#dialog-share-instant').popover('show', document.body)
            // mui('#dialog-exchange').popover('show', document.body)
        })
        // 扫码分享
        mui('body').on('click', '.btn-share-qtcode', function () {
            mui('#dialog-share-qtcode').popover('show')
            // mui('#dialog-exchange').popover('hide')
        })        
    </script>

    <script>
        var text = '<?php echo $recommend_link;?>';
        var postUrl = '../../html/img/market/haibao.png';
        var imgId = '#result';
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: text,
            width : 180,
            height : 180
        });
        var eleImgCover = document.querySelector('#qrcode img')
        function createPost(text, postUrl,imgId) {
            var getImg = function(url, callback) {
                var canvas = document.createElement('canvas');
                var width = 720;
                var height = 1120;
                canvas.width = width;
                canvas.height = height;
                var context = canvas.getContext('2d');
                var imgUpload = new Image();
                imgUpload.onload = function () {
                    context.drawImage(imgUpload, 0, 0, width, height);
                    context.drawImage(eleImgCover, (width - 180)/2, height - 240);
                    callback(canvas.toDataURL('/welcome/qr_code'));
                };
                imgUpload.src = url;    
            }
            getImg(postUrl, function(data) {
                document.querySelector(imgId).src = data
            })
        }       
        mui.init();
        // 立即分享
        mui('body').on('click', '.btn-share-instant', function () {
            createPost(text, postUrl,imgId)
            mui('#dialog-share-instant').popover('show', document.body)
            // mui('#dialog-exchange').popover('show', document.body)
        })
        // 扫码分享
        mui('body').on('click', '.btn-share-qtcode', function () {
            mui('#dialog-share-qtcode').popover('show')
            // mui('#dialog-exchange').popover('hide')
        })        
    </script>
</body>
<?php require 'bottom_nav.php'?>
<script src="/html/js/clipboard.min.js"></script>
<script>
    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        mui.toast('复制成功');
    });
    clipboard.on('error', function(e) {
        mui.toast('浏览器不支持该功能,请手动复制');
    });
</script>

