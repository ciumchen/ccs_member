<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="applicable-device" content="mobile" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>每日签到</title>
<link href="/html/css/sign.css" rel="stylesheet" type="text/css" />
<script src="/html/js/jquery-1.8.3.min.js"></script>
<script>
$(window).load(function() {
	$("#status").fadeOut();
	$("#preloader").delay(350).fadeOut("slow");
})
</script>
<script type="text/javascript">
$(document).ready(function(){
	
});
</script>
</head>
 
<body>
<div class="mobile"> 
  <!--页面加载 开始-->
  <div id="preloader">
    <div id="status">
      <p class="center-text"><span>拼命加载中···</span></p>
    </div>
  </div>
  <!--页面加载 结束--> 
  <!--header 开始-->
  <header>
    <div class="header"> <a class="new-a-back" href="javascript:history.back();"> <span><img src="/Public/Jmall/images/iconfont-fanhui.png"></span> </a>
      <h2>每日签到</h2>
      <div class="header_right shaixuan"><a href="{:U('Jmall/index')}"><img src="/Public/Jmall/images/iconfont-shouye.png"></a></div>
    </div>
  </header>
  <!--header 结束-->
  
  <div >
    <div class="qiandap-box" style="padding-bottom:30px;">
      <div class="qiandao-con clear">
        <div class="qiandao-left">
          <div class="qiandao-left-top clear">
            <div class="current-date">2016年1月6日</div>
            <div class="qiandao-history qiandao-tran qiandao-radius" id="js-qiandao-history">签到统计</div>
          </div>
          <div class="qiandao-main" id="js-qiandao-main">
            <ul class="qiandao-list" id="js-qiandao-list">
            </ul>
          </div>
        </div>
        <div class="qiandao-right">
          <div class="qiandao-top">
            <div class="just-qiandao qiandao-sprits" id="js-just-qiandao"> </div>
          </div>
          <div class="qiandao-bottom">
            <div class="qiandao-rule-list">
              <h4>签到规则</h4>
              <p>首次签到获得0.05元现金奖励</p>
              <p>连续签到每天增加0.01元现金奖励</p>
              <p>连续签到16天及以上每天获得0.2元现金奖励</p>
            </div>
            <div class="qiandao-rule-list">
              <h4>其他说明</h4>
              <p>如果中间有一天间断未签到的，重先开始计算连续签到时间。</p>
              <p>获得的奖励不能直接提现，只能投资后转让变现。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 我的签到 layer start -->
  <div class="qiandao-layer qiandao-history-layer">
    <div class="qiandao-layer-con qiandao-radius"> <a href="javascript:;" class="close-qiandao-layer qiandao-sprits"></a>
      <ul class="qiandao-history-inf clear">
        <li>
          <p>连续签到</p>
          <h4>5</h4>
        </li>
        <li>
          <p>本月签到</p>
          <h4>17</h4>
        </li>
        <li>
          <p>总签到</p>
          <h4>28</h4>
        </li>
        <li>
          <p>累计奖励</p>
          <h4>30</h4>
        </li>
      </ul>
      <div class="qiandao-history-table">
        
      </div>
    </div>
    <div class="qiandao-layer-bg"></div>
  </div>
  <!-- 我的签到 layer end --> 
  
</div>
<script>
$(function() {
	var widthUl = $('#js-qiandao-main').width();
	// li的宽度和高度
	var widthLi = (widthUl - 7)/7; 
	
	if(widthLi < 45){
		$('#js-qiandao-list').css('marginTop','40px');	
	}else if(widthLi > 45 && widthLi < 50){
		$('#js-qiandao-list').css('marginTop','50px');	
	}
    var signFun = function() {
 
        var dateArray = [5, 9, 11, 14] // 已经签到的天
        
        var $dateBox = $("#js-qiandao-list"),
            $currentDate = $(".current-date"),
            $qiandaoBnt = $("#js-just-qiandao"),
            _html = '',
            _handle = true,
            myDate = new Date();
        $currentDate.text(myDate.getFullYear() + '年' + parseInt(myDate.getMonth() + 1) + '月' + myDate.getDate() + '日');
 
        var monthFirst = new Date(myDate.getFullYear(), parseInt(myDate.getMonth()), 1).getDay();
 
        var d = new Date(myDate.getFullYear(), parseInt(myDate.getMonth() + 1), 0);
        var totalDay = d.getDate(); //获取当前月的天数
 
        for (var i = 0; i < 42; i++) {
            _html += ' <li style="width:'+widthLi+'px;height:'+widthLi+'px;line-height:'+widthLi+'px"><div class="qiandao-icon"></div></li>'
        }
        $dateBox.html(_html) //生成日历网格
 
        var $dateLi = $dateBox.find("li");
		
        for (var i = 0; i < totalDay; i++) {
            $dateLi.eq(i + monthFirst).addClass("date" + parseInt(i + 1)).attr('data-num',parseInt(i + 1));
			for (var j = 0; j < dateArray.length; j++) {
                if (i == dateArray[j]) {
                    $dateLi.eq(i + 1).addClass("qiandao");
				}
            }
        } //生成当月的日历且含已签到
		
		for(var i = 1; i < 32; i++){
			var liNum = $dateLi.eq(i).attr('data-num');
			if(liNum != 'undefined' && liNum != '' && liNum != null){
				$('.date'+liNum).append(liNum);	
			}	
		} //	嵌入天
		
		// 没有天的li背景变色
	for(var i = 0; i < $dateLi.length; i++){
		if(i < monthFirst || i>(totalDay+monthFirst-1)){
			$dateLi.eq(i).css('background','#eee');
		}	
	}
 
        $(".date" + myDate.getDate()).addClass('able-qiandao');
 
        $dateBox.on("click", "li", function() {
                if ($(this).hasClass('able-qiandao') && _handle) {
                    $(this).addClass('qiandao');
                    qiandaoFun();
                }
            }) //签到
 
        $qiandaoBnt.on("click", function() {
            if (_handle) {
                qiandaoFun();
            }
        }); //签到
 
        function qiandaoFun() {
            $qiandaoBnt.addClass('actived');
            openLayer("qiandao-active", qianDao);F
            _handle = false;
        }
 
        function qianDao() {
            $(".date" + myDate.getDate()).addClass('qiandao');
        }
    }();
 
    function openLayer(a, Fun) {
        $('.' + a).fadeIn(Fun)
    } //打开弹窗
 
    var closeLayer = function() {
            $("body").on("click", ".close-qiandao-layer", function() {
                $(this).parents(".qiandao-layer").fadeOut()
            })
        }() //关闭弹窗
 
    $("#js-qiandao-history").on("click", function() {
        openLayer("qiandao-history-layer", myFun);
 
        function myFun() {
            console.log(1)
        } //打开弹窗返回函数
    })
	$('.qiandao-icon').css('width',widthLi+'px').css('height',widthLi+'px');
	
})
 
 
</script>
</body>
</html>
