$(function () {
    let MobileC2cType = '';
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function (el) {
        return el != ''
    });
    MobileC2cType = (pathArray[2] != null) ? pathArray[2] : 'buy';
    //나의거래 구분 표시
    if(MobileC2cType=='my'){
        $('.sortation').removeClass('none')
        $('.lists-head>ul>li').eq(0).css('width','15%');
        $('.lists-head>ul>li').eq(1).css('width','10%');
        $('.lists-head>ul>li').eq(2).css('width','30%');
        $('.lists-head>ul>li').eq(3).css('width','25%');
        $('.list li:nth-child(1)').css('width','15%');
        $('.list li:nth-child(2)').css('width','10%');
        $('.list li:nth-child(3)').css('width','30%');
        $('.list li:nth-child(14)').css('width','25%');
    }

    // 구매 or 판매 페이지에서 뒤로가기
    $('.hd-back').click(function(){
        if($('.c2c-detail-div').hasClass('none') == false){
            $('.c2c-search-box').removeClass('none');
            $('.c2c-lists-div').removeClass('none');
            $('.c2c-detail-div').addClass('none');
            $(".hd-back").attr('href','');
        } else {
            $(".hd-back").attr('href','javascript:history.back()');
        }
    })


})

function popUpCheck(text){
    closePopup();

    let html = '<div class="popup-box c2c-password-no-coincide">';
    html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
    html += '<div class="password-no-content">' + text + '</div>';
    html += '<button onclick="closePopup()">' + lang('확인', 'OK', '確認', '确认') + '</button></div>'
    html += '<div class="popup-bg"></div>';
    openCustomPopup(html)
}