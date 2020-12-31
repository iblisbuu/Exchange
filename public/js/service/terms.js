let Type = '';
$(function () {
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function (el) {
        return el != ''
    });
    Type = (pathArray[2] != null) ? pathArray[2] : 'guide';
    // 탭
    $('.account-tap > a').removeClass('active')
    $('.' + Type + '-tab').addClass('active')
})