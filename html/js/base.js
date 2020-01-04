
function showLoading() {
    mui.showLoading("加载中...","div");
}

function hideLoading() {
    mui.hideLoading(function () {});
}



var index;
$( document ).ajaxStart(function () {
    showLoading();
});
$( document ).ajaxComplete(function () {
    hideLoading();
});