$(function () {
    // 마케팅 수신동의 체크박스
    if($('input[name=marketing]').is(":checked")==true) {
        $('.xi-check-marketing').removeClass('none')
    } else {
        $('.xi-check-marketing').addClass('none')
    }

    $('.xi-check-marketing').click(function () {
        $('input[name=marketing]').prop('checked',false)
        $('.xi-check-marketing').addClass('none')
    })

    // 인증현황
    $('.level-items .level-item.complete .level').css('color','#f0b600')
    $('.level-items .level-item.complete .level-dot > div').css('background-color','#f0b600')
    $('.level-items .level-item.complete .level-item-check').attr('src','/public/img/account/level-ck-on.png')

    // 인증현황 sms인증
    if($('.level-content.sms-content p').text() == '') {
        $('.level-content.sms-content').css('justify-content','flex-end')
    }
})