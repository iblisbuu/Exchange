$(function () {

    // select초기값
    let contractNum, coinNum, dateNum = 0;
    for(var i=0;i<$('.contract-selected').next().children().length;i++) {
        if($('.contract-selected').next().children().eq(i).attr('data-option') == $('.contract-selected').val()) {
            contractNum = i
        } 
    }
    for(var i=0;i<$('.coin-selected').next().children().length;i++) {
        if($('.coin-selected').next().children().eq(i).attr('data-option') == $('.coin-selected').val()) {
            coinNum = i
        } 
    }
    for(var i=0;i<$('.date-selected').next().children().length;i++) {
        if($('.date-selected').next().children().eq(i).attr('data-option') == $('.date-selected').val()) {
            dateNum = i
        } 
    }
    $('.contract-selected').next().children().eq(contractNum).attr('selected',true)
    $('.coin-selected').next().children().eq(coinNum).attr('selected',true)
    $('.date-selected').next().children().eq(dateNum).attr('selected',true)

    $('.balance-select-boxes select').change(function () {
        $(this).prev().val($(this).children('option:selected').attr('data-option'))
        $(this).prev().attr('data-select', $(this).children('option:selected').attr('data-option'))

        var urlPlus = '';
        for (var i = 0; i < $('.balance-select-boxes > div').length; i++) {
            var thisVal = $('.balance-select-boxes > div').eq(i).children('div').children('input').val();
            if (thisVal != 'all') {
                let connect = (urlPlus == '') ? '?' : '&'
                urlPlus += connect + $('.balance-select-boxes > div').eq(i).children('div').children('input').attr('data-name') + '=' + thisVal;
            }
        }
        urlPlus = $(location).attr('pathname') + urlPlus;

        location.href = urlPlus;
    })
    
    // 투자내역 입출금내역 table height 동일하게
    $('.history-table > thead > tr > th.deposit-fixed').height($('.history-table > thead > tr > th:nth-child(2)').height());
});