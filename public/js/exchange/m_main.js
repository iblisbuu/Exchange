$(function () {

    $(".coinBox").css('height', $("#main").outerHeight() - $(".searchBox").outerHeight());
    $(".coinList-area").css('height', $('.coinBox').outerHeight() - $(".coin-type").outerHeight());
    $(".coinOrder").css('height', $("#main").outerHeight() - $('.coinInfo').outerHeight() - $('.coinMenu').outerHeight());

    $('.co02Menu li').on('click', function (obj) {
        const type = obj.target.dataset.type;
        $('.co02Menu li').removeClass('active');
        $(obj.target).addClass('active');
        $('.co02Box').addClass('none');
        $('.co02Box[data-type="' + type + '"]').removeClass('none');
        if (type != 'wait') {
            let select_type = (type == 'buying') ? 'btn-red' : 'btn-skyblue';
            let select_text = (type == 'buying') ? lang('매수', 'BUY', '購入', '收购') : lang('매도', 'SELL', '販売', '卖出');
            const coin = $('.co02Box.order').attr('data-coin');
            const coinName = $('.co02Box.order').attr('data-coinName');
            $('.co02Box.order').removeClass('none').removeClass('buying').removeClass('selling').addClass(type).attr('data-type', type).attr('action', 'javascript:sendOrder("' + type + '", "' + coin + '", "' + coinName + '")')
            $('.co02Box.order button.order-submit').removeClass('btn-red').removeClass('btn-skyblue').addClass(select_type).text(select_text);
            $('.ex02 .my-max-value em').text($('.ex02 .my-max-value em').attr('data-' + type));
        }
    });

    $('.hd-coinList').on('click', function () {
        $("#coinList").slideToggle('fast');
    });

    $(".coinMarket>.cmMenu>li").on('click', function (obj) {
        const type = obj.target.dataset.type;
        $(".coinMarket>.cmMenu>li").removeClass('active');
        $(obj.target).addClass('active');
        $('.coinMarket>.cmContent').addClass('none');
        $('.coinMarket>.cmContent.' + type).removeClass('none');
    });

});