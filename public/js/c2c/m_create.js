$(function () {
    if($('#buy').is(":checked")==true){
        $('.xi-check-buy').removeClass('none');
        $('.xi-check-sell').addClass('none');
    } else {
        $('.xi-check-buy').addClass('none');
        $('.xi-check-sell').removeClass('none');
    }

    if($('#setPublic').is(":checked")==true){
        $('.xi-check-public').removeClass('none');
        $('.xi-check-password').addClass('none');
    } else {
        $('.xi-check-public').addClass('none');
        $('.xi-check-password').removeClass('none');
    }

    $('#c2cCoin').on('click', function () {
        $(".coin>ul").removeClass('hide')
        $(".coin>ul").removeClass('none')
    })

    $('#c2cCurrency').on('click', function () {
        $(".currency>ul").removeClass('hide')
        $(".currency>ul").removeClass('none')
    })

    $("input[name='c2c_range']").on('change', function () {
        let type = $(this).attr('id');
        if (type == 'setPassword') {
            $('.xi-check-public').addClass('none');
            $('.xi-check-password').removeClass('none');
        } else {
            $('.xi-check-public').removeClass('none');
            $('.xi-check-password').addClass('none');
        }
    });
});