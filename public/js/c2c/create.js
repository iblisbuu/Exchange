let createParam = {};

$(function () {
    $("input[name='c2c_type']").on('change', function () {
        let type = $(this).attr('id');
        location.href = '/c2c/create/' + type;
    });

    $("input[name='c2c_range']").on('change', function () {
        let type = $(this).attr('id');
        if (type == 'setPassword') {
            $(".cre-password-box").removeClass('none');
        } else {
            $(".cre-password-box").addClass('none');
        }
    });

    // 출금 금액 INPUT
    $("#c2cPrice,#c2cAmount").on({
        keyup: function (obj) {
            if($(this).val().substr(0, 1) * 1 == 0 && $(this).val().substr(1, 1) * 1 >= 0 && $(this).val().length > 1)
                var thisVal = $(this).val().replace(/(^0+)/, '');
            else
                var thisVal = $(this).val()

            if(thisVal * 1 > 0 || thisVal != $(this).val()){
                if(thisVal.toString().indexOf('.') != -1){
                    thisVal = thisVal.split('.');
                    $(this).val(thisVal[0] + '.' + thisVal[1].substr(0, 8))
                } else
                    $(this).val(thisVal)
            }

            const type = $(this).attr('data-type');
            numberFormat(type);
        }
    });

    // 가격 Button
    $(".cre-price-box>button").on('click', function () {
        const type = $(this).attr('data-type');
        let price = ($("#c2cPrice").val() < 0) ? 0 : $("#c2cPrice").val().replace(/,/gi, '');
        (type == 'plus') ? price++ : price--;
        price = (price < 0) ? 0 : double_number_format(price);
        $("#c2cPrice").val(price);
        numberFormat('price');
    });

    function numberFormat(type) {
        let orgValue = ($.trim($('#c2cPrice').val()) == '' ? 0 : $('#c2cPrice').val()) + '';
        let orgAmount = ($.trim($('#c2cAmount').val()) == '' ? 0 : $('#c2cAmount').val()) + '';

        // 1-1. 영어, 한글, ',' 제거
        orgValue = orgValue.toString()
            .replace(/,/gi, '');

        orgAmount = orgAmount.toString()
            .replace(/,/gi, '');

        let totalValue = (orgValue == '') ? 0 : orgValue;
        let totalAmount = (orgAmount == '') ? 0 : orgAmount;
        let totalPrice = (totalAmount * totalValue).toFixed(5);
        totalPrice = double_number_format(totalPrice);
        // $("#waitingPrice").text(totalPrice);
        $('.cre-total-box .cre-total-price').text(totalPrice);
    }

    // 구매할 코인 Select box
    $(".cre-coin>ul>li").not('.not-select').on('click', function () {
        let type = $(this).closest('div.cre-coin').find('input');
        type.val($(this).text());
        if ($(this).closest('div.cre-coin').attr('data-type') == 'currency') {
            $('#nowPrice').text($('#nowPrice').attr('data-' + ((type.val().split(' / '))[1]).toLowerCase()));
            $('#waitingPrice').text($('#waitingPrice').attr('data-' + ((type.val().split(' / '))[1]).toLowerCase()));
            $("[name='mainSymbol']").text((type.val().split(' / '))[1]);
        }

        if($(this).parent().parent().attr('data-type') != 'currency') {
            for (var i = 0; i < $('.cre-coin[data-type="currency"] > ul > li').length; i++)
                $('.cre-coin[data-type="currency"] > ul > li').eq(i).css('display', ($('.cre-coin[data-type="currency"] > ul > li').eq(i).text() == $(this).text() ? 'none' : 'list-item'));
        }

        $(this).parent().addClass('hide')
    });

    $('input#c2cCoin, input#c2cCurrency').on('hover', function () {
        $(".cre-coin>ul").removeClass('hide')
    })

    $('input#c2cPassword').keyup(function () {
        var thisVal = $(this).val().replace(/[^0-9]/g, '');

        if(thisVal.length > 6)
            thisVal = thisVal.substr(0, 6);

        $(this).val(thisVal)
    })

    $("#c2cFinalDate").datepicker({
        showOn: 'both',
        buttonImage: '/public/img/c2c/calendar.png',
        buttonImageOnly: true,
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        minDate: '1',
        maxDate: '30',
        beforeShow: function (input, inst) {
            setTimeout(function () {
                inst.dpDiv.css(
                    {
                        top: $(".ui-datepicker-trigger").offset().top + ($(".ui-datepicker-trigger").height() / 2),
                        left: $(".ui-datepicker-trigger").offset().left + $(".ui-datepicker-trigger").width() + 2,
                    });
            }, 0);
        }
    })
});

// 거래하기
function createC2CSub(type) { // type:: 거래타입

    let finalData = $("#c2cFinalDate").val(); // 거래 종료일
    let coin = $("#c2cCoin").val(); // 거래할 코인 종류
    coin = (coin.split(' / '))[1]
    let price = $("#c2cPrice").val().replace(/,/gi, ''); // 거래할 코인의 가격
    let amount = $("#c2cAmount").val().replace(/,/gi, '') // 거래할 코인의 수량

    if (coin == 'USDT') {
        openOkPopup(lang('구매/판매할 코인과 기축통화가 동일한 개인거래는 생성할 수 없습니다.',
            'You cannot create a private transaction that has the same coin and the same key currency to purchase/sell.',
            '購入/販売するコインと基軸資産が同じな個人の取引は生成することはできません。',
            '购买/销售的硬币和基础货币相同的个人交易不能生成。'), 'closePopup()', lang('확인', 'OK', '確認', '确认'), true)
        return;
    }

    // 비밀번호 설정 여부
    let passwordChk = false;
    let password = '';
    let range = lang('전체공개', 'All', '完全な情報開示', '全体公开');
    if ($("[name='c2c_range']:checked").attr('id') == 'setPassword') {
        passwordChk = true;
        password = $("#c2cPassword").val();
        range = lang('비밀번호', 'Password', 'パスワード', '密码');
    }
    let param = {
        'finalData': finalData,
        'coin': coin,
        'price': price,
        'amount': amount,
        'type': type
    }
    if (passwordChk) {
        param['password'] = password;
    }

    if (finalData != '' && coin != undefined && price != '' && amount != '' && (passwordChk ? password.length == 6 : !passwordChk))
        openC2CPopup(type, range, param);
    else if(password.length != 6 && passwordChk)
        alert(lang('비밀번호는 6자리 숫자만 입력이 가능합니다.', 'Only 6 digits can be entered for the password.', 'パスワードは、6桁のみ入力可能です。', '密码仅能输入6位数字。'))
    else
        alert(lang('빈 칸을 모두 입력해주세요.', 'Please enter all the blanks.', '空欄を全て入力してください。', '请输入全部空格。'));
}

function openC2CPopup(type, range, param) {
    createParam = param;
    const typeTitle = (type == 'buy') ? lang('구매', 'Buy', '購入', '采购') : lang('판매', 'Sell', '販売', '销售');
    const amount = param.amount;
    const price = param.price;
    const total = param.amount * param.price;
    const coin = param.coin;
    const currency = 'USDT';
    const header = lang('개인거래 생성 정보 확인', 'Check C2C generation information', '個人取引の作成について確認', '确认个人交易生成信息');
    const body = '<ul class="popup-order-ul">' +
        '<li><strong>' + lang('거래구분', 'Category', '取引区分', '交易分类') + '</strong><span>' + lang('개인거래 ', 'C2C ', '個人取引 ', '个人交易') + typeTitle + '</span></li>' +
        '<li><strong>' + lang('공개 범위', 'Public scope', '公開範囲', '公开范围') + '</strong><span>' + range + '</span></li>' +
        '<li><strong>' + lang('거래수량', 'Quantity', '取引数量', '交易量') + '</strong><span class="droid">' + amount + ' ' + coin + '</span></li>' +
        '<li><strong>' + lang('거래가격', 'Price', '取引価格', '交易价格') + '</strong><span class="droid">' + price + ' ' + currency + '</span></li>' +
        '<li><strong>' + lang('거래총액', 'Total', '出来高', '交易总额') + '</strong><span class="droid">' + total + ' ' + currency + '</span></li>' +
        '</ul>' +
        '<div style="padding:20px 0; text-align:center; font-size:14px; font-weight: 400;">' +
        lang('위의 내용으로 개인거래 ' + typeTitle + '를 생성할까요',
            'Shall we create C2C with the ' + typeTitle + ' above',
            '上記の内容で個人取引の' + typeTitle + '個人取引の購入を生成しますか',
            '以上述内容生成个人交易' + typeTitle + '吗?') +
        '?</div>';
    const nextEvent = 'createC2C()';
    const closeEvent = 'closePopup()';
    const nextText = lang('예', 'YES', 'はい', '例子');
    const closeText = lang('아니오', 'NO', 'いいえ', '不');
    openPopup(header, body, nextEvent, closeEvent, nextText, closeText, true);
}

// TODO 거래하는 코드
function createC2C() {
    console.log(createParam)
    $.ajax({
        url: '/src/controller/trade/c2c_create.php',
        type: 'POST',
        data: createParam,
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success')
                location.href = '/c2c/main?type=my';
            else if(data.result == 'money')
                alert(lang('보유금액이 부족합니다.', 'Insufficient reserves.', '保有金額が足りません。',
                    '储备金额不足。'))
            else
                alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.', 'An error has occurred.\n' +
                    'Please try again in a moment.', 'エラーが発生しました。\n' +
                    'しばらくして、もう一度やり直してください。',
                    '发生错误。\n请稍后再试。'))
        }, error: function (a, b, c) {
            alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.', 'An error has occurred.\n' +
                'Please try again in a moment.', 'エラーが発生しました。\n' +
                'しばらくして、もう一度やり直してください。',
                '发生错误。\n请稍后再试。'))
            console.log(a, b, c)
        }
    });
}