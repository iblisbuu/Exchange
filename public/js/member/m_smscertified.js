$(function () {
    // 국가코드 선택
    $('#smsCountry').on('click', function(){
        if($('.sms-option').css('display')=='block') {
            $('.sms-option').css('display','none')
        } else {
            $('.sms-option').css('display','block')
        }
    })

    $('.sms-country-num').on('click', function(){
        if($('.sms-option').css('display')=='block') {
            $('.sms-option').css('display','none')
        } else {
            $('.sms-option').css('display','block')
        }
    })
})