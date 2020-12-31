$(function () {
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function(el){
        return el != ''
    });
    let noticeType = (pathArray[2] != null) ? pathArray[2] : 'support';
    // 탭
    $('.account-tap > a').removeClass('active');
    $('.' + noticeType + '-tab').addClass('active');
});