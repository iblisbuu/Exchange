let _deviceType;
$(function () {
    _deviceType = $("body").attr('data-device');

    if (_deviceType == 'pc') {
        $("header").addClass("exchange-width");
        $("footer").addClass("exchange-width");
    }

    // 왼쪽 박스 스크롤 top fix
    $(window).scroll(function (e) {
        if(_deviceType != 'mobile') {
            let top = $(document).scrollTop();
            $(".ex01").css("top", top + 10);
        }
    });

    if($.trim($('.coinInfo01 .cTit > h2').text()) == ''){
        $('.coinInfo01 .cTit > h2').text($('.tb-highlight tbody .select a[data-type="name"]').text())
    }

    // 코인명/심볼 검색
    $('.search-box input.search').on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var success = 0;

        $(".coinBox tbody tr").filter(function () {
            $('.coinBox tbody .no-data').remove();
            if ($(this).children('td').children('a').text().toLowerCase().indexOf(value) > -1 || $(this).children('td').children('em').text().toLowerCase().indexOf(value) > -1) {
                if($('.search-type-box > button.active').attr('data-search') != 'bookmark' || ($('.search-type-box > button.active').attr('data-search') == 'bookmark' && $(this).children('td').children('span.bookmark').hasClass('active'))) {
                    if (!$(this).hasClass('none')) {
                        success++;
                    }
                    $(this).show();
                } else
                    $(this).hide();
            } else
                $(this).hide();

            if (success == 0)
                $('.coinBox tbody').append('<tr class="no-data"><td class="text-center" colspan="5">' + lang('검색된 코인이 없습니다.',
                    'No coins found.',
                    '検索されたコインがありません。', '没有搜索到的硬币。') + '</td></tr>');
            else {
                $('.coinBox tbody .no-data').remove();
            }
        });
    });

    // 검색 타입
    $('.search-type-box>button').on('click', function () {
        let search_type = $(this).attr('data-search');

        $('.coinBox tbody .no-data').remove();
        $('.coinBox tbody .hide').removeClass('hide');

        if (search_type == 'bookmark')
            $('.coinBox tbody td .bookmark:not(.active)').parent().parent().addClass('hide');
        else if (search_type == 'have')
            $('.coinBox tbody td .bookmark:not(.have)').parent().parent().addClass('hide');

        if ($('.coinBox tbody tr:not(.no-data)').length == $('.coinBox tbody tr.hide').length)
            $('.coinBox tbody').append('<tr class="no-data"><td class="text-center" colspan="5">' + lang('검색된 코인이 없습니다.',
                'No coins found.',
                '検索されたコインがありません。', '没有搜索到的硬币。') + '</td></tr>');

        $('.search-type-box>button').removeClass('active');
        $(this).addClass('active');
    });

    // 코인 타입
    $(".ex01-type.coin-type>button").on('click', function () {
        let coin_type = $(this).attr('data-type');
        let pageType = $('.coinInfo01 .cTit .cUnit').text().split('/');
        $(".ex01-type.coin-type>button").removeClass('active');
        $(this).addClass('active');

        $('.coinBox tbody .no-data').remove();

        $('.coinBox tr').removeClass('none').removeClass('select');
        $('.coinBox tr td#nowPirce[data-coin="' + $(this).text() + '"]').parent().addClass('none');

        $('.tit > em > span.currency').text($(this).text());
        for (var i = 0; i < $('.coinBox tr').length; i++) {
            if($('.coinBox tr:eq(' + i + ') td#nowPirce').attr('data-coin') == pageType[0] && pageType[1] == $.trim($(this).text()))
                $('.coinBox tr:eq(' + i + ')').addClass('select');

            $('.coinBox tr:eq(' + i + ') td a[data-type="name"]').attr('href', '/exchange/main/' + $('.coinBox tr:eq(' + i + ') td#nowPirce').attr('data-coin') + '?currency=' + $.trim($(this).text()));
            $('.coinBox tr:eq(' + i + ') td a[data-type="name"] + em').attr('onclick', 'location.href=\'/exchange/main/' + $('.coinBox tr:eq(' + i + ') td#nowPirce').attr('data-coin') + '?currency=' + $.trim($(this).text()) + "'");

            $('.coinBox tr:eq(' + i + ') td#nowPirce').text($('.coinBox tr:eq(' + i + ') td#nowPirce').attr('data-' + coin_type));
            $('.coinBox tr:eq(' + i + ') td#nowTotal').text($('.coinBox tr:eq(' + i + ') td#nowTotal').attr('data-' + coin_type));
            $('.coinBox tr:eq(' + i + ') td[data-type="change"]').text($('.coinBox tr:eq(' + i + ') td[data-type="change"]').attr('data-' + coin_type));

            var thisPercent = $('.coinBox tr:eq(' + i + ') td[data-type="change"]').text().replace('%', '').replace(/,/gi, '') * 1;
            var thisClass = thisPercent > 0 ? 'color-red' : (thisPercent < 0 ? 'color-skyblue' : ''),
                removeClass = thisPercent > 0 ? 'color-skyblue' : (thisPercent < 0 ? 'color-red' : '');

            if(thisPercent == 0)
                $('.coinBox tr:eq(' + i + ') *').removeClass('color-red').removeClass('color-skyblue');
            else {
                $('.coinBox tr:eq(' + i + ') *').removeClass(removeClass);
                $('.coinBox tr:eq(' + i + ') td#nowPirce').addClass(thisClass);
                $('.coinBox tr:eq(' + i + ') td[data-type="change"]').addClass(thisClass);
            }
        }
    });

    // 주문내역 타입
    $(".ex01-type.order-list-type>button").on('click', function () {
        let order_list_type = $(this).attr('data-type');
        $(".ex01-type.order-list-type>button").removeClass('active');
        $(this).addClass('active');
        $(".order-list-table").addClass('none');
        $("#" + order_list_type).removeClass('none');
    });

    // 즐겨찾기
    $('.bookmark').on('click', function () {
        let coin_type = $(this).attr('data-type');

        $.ajax({
            url: '/src/controller/member/interest.php',
            type: 'POST',
            data: {'coin': coin_type},
            dataType: 'json',
            success: function (data) {
                if (data.type == 'insert')
                    $('[data-type="' + coin_type + '"].bookmark').addClass('active');
                else if (data.type == 'delete')
                    $('[data-type="' + coin_type + '"].bookmark').removeClass('active');
            }, error: function (a, b, c) {
                console.log(a, b, c);
                alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.',
                    'An error has occurred. Please try again in a moment.',
                    'エラーが発生しました。\n しばらくしてもう一度お試しください。',
                    '发生错误。\n 请稍后再试。'));
            }
        });
    });

    // 주문 > 지정가 매수, 지정가 매도 변경
    $('.order-gnb>li').on('click', function () {
        let pageType = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
        if($('.coinInfo01 .cTit .cUnit').index() == -1){
            pageType[0] = $('.coinOrder .co02 .co02Menu li').eq(0).attr('data-coin');
        }
        let exchange_type = $(this).attr('data-type');
        if(exchange_type != 'wait') {
            let coin_name = $(this).attr('data-coin');
            let origin = $(this).attr('data-origin').replace(/,/gi, '') * 1;
            let coinPrice = $('#nowPirce[data-coin="' + pageType[0] + '"]').text().replace(/,/gi, '') * 1;

            // gnb 변경
            $('.order-gnb>li').removeClass('active');
            $(this).addClass('active');

            // 부모 div type 변경
            $('.order-area').removeClass('buying').removeClass('selling');
            $('.order-area').addClass(exchange_type);

            // form Submit button 변경
            let select_type = (exchange_type == 'buying') ? 'btn-red' : 'btn-skyblue';
            let select_text = (exchange_type == 'buying') ? lang('매수', 'BUY', '購入', '收购') : lang('매도', 'SELL', '販売', '卖出');
            $('.order-submit').removeClass('btn-red').removeClass('btn-skyblue').addClass(select_type).text(lang('지정가 ', '', '指値', '限价') + select_text);
            $('.order-area-content').attr('action', 'javascript:sendOrder("' + exchange_type + '", "' + coin_name + '")');

            $('.ex02 .my-max-value em').text(round_down_format('', origin, 8));
            $('.ex02 .my-max-value em + span').text(exchange_type == 'buying' ? pageType[1] : pageType[0]);

            $('.order-total-area em.droid:not(.totalPrice) + span').text(exchange_type == 'buying' ? pageType[0] : pageType[1]);

            var amountRate = [0.1, 0.25, 0.5, 1];
            for (var i = 0; i < $('.ex02 .order-price-select button').length; i++)
                $('.ex02 .order-price-select button').eq(i).attr('data-amount', round_down_format('', (exchange_type == 'buying' ? (coinPrice > 0 ? origin / coinPrice : 0) : origin) * amountRate[i]));

            totalValueInsert();
        }
    });

    // 주문 % 클릭
    $('.ex02 .order-price-select > button').on('click', function () {
        if ($('.ex02 .order-price-ipt input[name="price"]').attr('readonly') != 'readonly') {
            $('.ex02 .order-price-ipt input[name="amount"]').val($(this).attr('data-amount') * 1);
            totalValueInsert()
        }
    });

    // 주문 수량 또는 금액이 변경될때
    $('.ex02 .order-price-ipt input[name="price"], .ex02 .order-price-ipt input[name="amount"]').on({
        keyup: function () {
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

            totalValueInsert()
        },
        change: function () {
            totalValueInsert()
        }
    });

    // 호가창 클릭
    $('.tb-status tbody.down tr:not(.not), .tb-status tbody.up tr:not(.not)').unbind('click').bind('click', function () {
        var thisPrice = $(this).attr('data-price').replace(/,/gi, '');

        $('.ex02 .order-price-ipt input[name="price"]').val(thisPrice);
        totalValueInsert();
    });

    // 주문창 +, - 버튼
    $('.ex02 .order-price-btn > button, .order-price-area .order-btn > button').click(function () {
        $.ajax({
            url:'/src/controller/common/round_down_format.php',
            type:'GET',
            data:{'price':[$('input[name=price]').val()], 'amount':[$(this).attr('data-amount')]},
            dataType:'json',
            success:function(data){
                $('input[name=price]').val(data.price[0]);
                totalValueInsert()
            }
        });
    })

    // 체결현황 소켓
    socket.on('tradingOrders', function (msg) {
        if($('.coinInfo01 .cTit .cUnit').index() != -1) {
            var currency = $('.coinInfo01 .cTit .cUnit').text().split('/');
            if ($.trim(currency[1]) == msg.currency && msg.amount * 1 > 0) {
                $('.cMainValue').text(round_down_format('', msg.price.replace(/[^0-9.]/g, ''), 8) + ' USDT');
                let nowPrice = $('.coinBox #nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase()).replace(/[^0-9.]/g, '') * 1;
                var html = '<tr class="new-data">';
                html += '<td class="droid">' + msg.datetime + '</td>';
                html += '<td class="droid">' + msg.price + '</td>';
                html += '<td class="droid color-' + (msg.type == 1 ? 'red' : 'skyblue') + '">' + msg.amount + '</td>';
                html += '<td class="droid">' + msg.total + '</td>';
                html += '</tr>';

                if ($('.ex02 .tb-sign tbody tr:nth-child(1)').hasClass('no-data')) {
                    $('.ex02 .tb-sign tbody').html(html);
                } else {
                    if ($('.ex02 .tb-sign tbody tr').length == 20)
                        $('.ex02 .tb-sign tbody tr').last().remove();

                    $('.ex02 .tb-sign tbody tr:nth-child(1)').before(html);
                }
            }

            // 체결완료된 계정과 본인 계정이 일치시
            if(msg.email == $.trim($('.etc-member .member-manage > .hd-sub-gnb span.member-account').text()) && msg.coin == currency[0] && msg.currency == currency[1]) {
                if(msg.type == 0){ // 매수체결시
                    msg.amount = msg.amount.toString().replace(/,/gi, '') * 1;
                    var nowAmount = $('.ex02 .order-gnb > li[data-type="buying"]').attr('data-origin').replace(/,/gi, '') * 1;
                    var newFee = msg.amount * (0.25 / 100);
                    var newAmount = msg.amount - newFee;

                    nowAmount += newAmount;

                    var date = new Date();

                    var html = '';
                    html += '<tr>' +
                        '<td class="droid">' + currency[0] + '/' + currency[1] + '</td>' +
                        '<td class="droid">' + lang('구매', 'Buy', '購買', '采购') + '</td>' +
                        '<td class="droid">' + msg.price + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + round_down_format('', msg.amount) + ' ' + currency[0] + '</td>' +
                        '<td class="droid">' + msg.total + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + round_down_format('', newFee) + ' ' + currency[0] + '</td>' +
                        '<td class="droid">' + round_down_format('', newAmount) + ' ' + currency[0] + '</td>' +
                        '<td class="droid">' + ("0"+(date.getMonth()+1)).slice(-2) + '.' + ("0"+(date.getDate())).slice(-2) + ' ' + ("0"+(date.getHours())).slice(-2) + ':' + ("0"+(date.getMinutes())).slice(-2) + '</td>' +
                    '</tr>';
                } else { // 매도체결
                    msg.total = msg.total.toString().replace(/,/gi, '') * 1;
                    var nowAmount = $('.ex02 .order-gnb > li[data-type="selling"]').attr('data-origin').replace(/,/gi, '') * 1;
                    var newFee = msg.total * (0.25 / 100);
                    var newAmount = msg.total - newFee;

                    nowAmount += newAmount;

                    $('.ex02 .order-gnb > li[data-type="selling"]').attr('data-origin', nowAmount);

                    if($('.ex02 .order-gnb > li[data-type="selling"]').hasClass('active'))
                        $('.ex02 .my-max-value em').text(round_down_format('', nowAmount));

                    var date = new Date();

                    var html = '';
                    html += '<tr>' +
                        '<td class="droid">' + currency[0] + '/' + currency[1] + '</td>' +
                        '<td class="droid">' + lang('판매', 'Sell', '販売', '销售') + '</td>' +
                        '<td class="droid">' + msg.price + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + msg.amount + ' ' + currency[0] + '</td>' +
                        '<td class="droid">' + round_down_format('', msg.total) + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + round_down_format('', newFee) + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + round_down_format('', newAmount) + ' ' + currency[1] + '</td>' +
                        '<td class="droid">' + ("0"+(date.getMonth()+1)).slice(-2) + '.' + ("0"+(date.getDate())).slice(-2) + ' ' + ("0"+(date.getHours())).slice(-2) + ':' + ("0"+(date.getMinutes())).slice(-2) + '</td>' +
                    '</tr>';
                }

                $('.order-list-table#completeOrder tbody tr:nth-child(1)').before(html);
                if($('.order-list-table#completeOrder tbody tr').length > 20)
                    $('.order-list-table#completeOrder tbody tr').last().remove();
            }
        }
    });

    // 주문현황 소켓
    socket.on('tradingList', function (msg) {
        var currency = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
        if($('.coinInfo01 .cTit .cUnit').index() == -1){
            currency[0] = $('.coinOrder .co02 .co02Menu li').eq(0).attr('data-coin');
        }

        if (currency[0] == msg.coin && currency[1] == msg.currency) {
            var price = msg.price.replace(/,/gi, '');
            let nowPrice = $('.coinBox #nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase()).replace(/,/gi, '') * 1;

            var html = '<tr class="new-data" data-price="' + price + '">';
            html += (msg.type == 1 ? '<td></td>' : '<td><p class="amount droid">' + msg.amount + '</p><p class="bar" style="width: 100%;"></p></td>');
            html += '<td class="status-order-price"><em class="color-' + (price > nowPrice ? 'red' : (price < nowPrice ? 'skyblue' : '')) + ' droid">' + msg.price + '</em><span class="droid">' + msg.currencyPrice + '</span></td>';
            html += '<td class="color-' + (msg.percent * 1 > 0 || price > nowPrice ? 'red' : (msg.percent * 1 < 0 ? 'skyblue' : '')) + ' droid">' + (msg.percent) + '%</td>';
            html += (msg.type == 0 ? '<td></td>' : '<td><p class="amount droid">' + msg.amount + '</p><p class="bar" style="width: 100%;"></p></td>');
            html += '</tr>';

            if (msg.type == 1) { // 매수
                if ($('.tb-status tbody.up tr.not').length > 0 && $('.tb-status tbody.up tr[data-price="' + msg.price + '"]').index() == -1 && msg.amount.replace(/,/gi, '') * 1 > 0) {
                    // 목록이 10개 미만에 같은 가격이 없을때
                    for (var i = 0; i < $('.tb-status tbody.up tr:not(.not)').length; i++) {
                        var thisPrice = $('.tb-status tbody.up tr:not(.not)').eq(i).attr('data-price').replace(/,/gi, '') * 1;
                        var checkPrice = price + '';
                        checkPrice = checkPrice.replace(/,/gi, '') * 1;

                        if (thisPrice < price) {
                            $('.tb-status tbody.up tr.not').eq(0).remove();
                            $('.tb-status tbody.up tr:not(.not)').eq(i).before(html);
                            break;
                        }
                    }

                    if (i == $('.tb-status tbody.up tr:not(.not)').length) {
                        if ($('.tb-status tbody.up tr.not').length >= 10) {
                            $('.tb-status tbody.up tr.not').eq(0).remove();
                            $('.tb-status tbody.up tr.not').eq(0).before(html);
                        } else {
                            $('.tb-status tbody.up tr.not').eq(0).remove();
                            $('.tb-status tbody.up tr:not(.not)').last().after(html);
                        }
                    }
                } else if ($('.tb-status tbody.up tr[data-price="' + msg.price + '"]').index() != -1) {
                    // 같은 가격이 있을때
                    var total = $('.tb-status tbody.up tr[data-price="' + msg.price + '"]').attr('data-total') * 1;
                    var amount = $('.tb-status tbody.up tr[data-price="' + msg.price + '"] p.amount').text().replace(/,/gi, '') * 1;
                    amount = amount + msg.amount.replace(/,/gi, '') * 1;

                    if (amount <= 0) {
                        $('.tb-status tbody.up tr[data-price="' + price + '"]').remove();

                        if ($('.tb-status tbody.up tr.not').length != 0) {
                            var html = '<tr class="not new-data">' +
                                '<td style="background: none;"></td>' +
                                '<td style="background: none;"></td>' +
                                '<td style="background: none; border-left: none;"></td>' +
                                '<td style="background: none;"></td>' +
                                '</tr>';
                            $('.tb-status tbody.up tr.not').eq(0).before(html);
                        } else {
                            $.ajax({
                                url: '/src/controller/trade/listlimit.php',
                                type: 'POST',
                                data: {
                                    'currency': currency[1],
                                    'coin': msg.coin,
                                    'type': 1,
                                    'price': $('.tb-status tbody.up tr:not(.not)').last().attr('data-price').replace(/[^0-9.]/g, '') * 1
                                },
                                dataType: 'json',
                                success: function (data) {
                                    if (data.result == 'success') {
                                        var newPrice = data.tr_price.replace(/,/gi, '') * 1;
                                        let nowPrice = $('.coinBox #nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase()).replace(/,/gi, '') * 1;
                                        var className = '';

                                        className = (newPrice > nowPrice ? 'color-red' : (newPrice < nowPrice ? 'color-skyblue' : ''));

                                        var html = '<tr class="new-data" data-price="' + data.tr_price + '" data-total="' + data.tr_total + '">';
                                        html += '<td></td>';
                                        html += '<td class="status-order-price"><em class="' + className + ' droid">' + data.tr_price + '</em><span class="droid">' + data.currencyPrice + '</span></td>';
                                        html += '<td class="' + className + ' droid">' + data.percent + '%</td>';
                                        html += '<td><p class="amount droid">' + data.tr_total + '</p><p class="bar" style="width: ' + data.width + '%;"></p></td>';
                                        html += '</tr>';

                                        $('.tb-status tbody.up tr:not(.not)').last().after(html);
                                    } else {
                                        var html = '<tr class="not new-data">' +
                                            '<td style="background: none;"></td>' +
                                            '<td style="background: none;"></td>' +
                                            '<td style="background: none; border-left: none;"></td>' +
                                            '<td style="background: none;"></td>' +
                                            '</tr>';
                                        $('.tb-status tbody.up tr').last().after(html);
                                    }
                                }, error:function(a, b, c){
                                    console.log(a, b, c)
                                }
                            });
                        }

                    } else {
                        var rating = (amount / total * 100).toFixed(2);
                        amount = round_down_format('', amount, 8);

                        $('.tb-status tbody.up tr[data-price="' + msg.price + '"] p.bar').css('width', rating + '%');
                        $('.tb-status tbody.up tr[data-price="' + msg.price + '"] p.amount').text(amount);
                        $('.tb-status tbody.up tr[data-price="' + msg.price + '"]').removeClass('new-data');
                        setTimeout(function () {
                            $('.tb-status tbody.up tr[data-price="' + msg.price + '"]').addClass('new-data')
                        }, 10)
                    }
                } else if (msg.amount.replace(/,/gi, '') * 1 > 0) {
                    // 목록이 10개 이상에 같은 가격이 없을때
                    for (var i = 0; i < $('.tb-status tbody.up tr:not(.not)').length; i++) {
                        var thisPrice = $('.tb-status tbody.up tr:not(.not)').eq(i).attr('data-price').replace(/,/gi, '') * 1;
                        var checkPrice = price + '';
                        checkPrice = checkPrice.replace(/,/gi, '') * 1;

                        if (thisPrice < price) {
                            $('.tb-status tbody.up tr:not(.not)').last().remove();
                            $('.tb-status tbody.up tr:not(.not)').eq(i).before(html);
                            break;
                        }
                    }
                }
            } else if (msg.type == 0) { //매도
                if ($('.tb-status tbody.down tr.not').length > 0 && $('.tb-status tbody.down tr[data-price="' + price + '"]').index() == -1 && msg.amount.replace(/,/gi, '') * 1 > 0) {
                    // 목록이 10개 미만에 같은 가격이 없을때
                    $('.tb-status tbody.down tr.not').last().remove();

                    if($('.tb-status tbody.down tr:not(.not)').length > 0) {
                        for (var i = $('.tb-status tbody.down tr:not(.not)').length; i >= 0; i--) {
                            var thisPrice = $('.tb-status tbody.down tr:not(.not)').eq(i - 1).attr('data-price').replace(/,/gi, '') * 1;
                            var checkPrice = price + '';
                            checkPrice = checkPrice.replace(/,/gi, '') * 1;

                            if (thisPrice > checkPrice) {
                                $('.tb-status tbody.down tr:not(.not)').eq(i - 1).after(html);
                                break;
                            }
                        }

                        if(i == -1)
                            $('.tb-status tbody.down tr:not(.not)').eq(0).before(html);
                    } else {
                        $('.tb-status tbody.down tr.not').last().after(html);
                    }
                } else if ($('.tb-status tbody.down tr[data-price="' + price + '"]').index() != -1) {
                    // 같은 가격이 있을때
                    var total = $('.tb-status tbody.down tr[data-price="' + price + '"]').attr('data-total') * 1;
                    var amount = $('.tb-status tbody.down tr[data-price="' + price + '"] p.amount').text().replace(/,/gi, '') * 1;
                    amount = amount + msg.amount.replace(/,/gi, '') * 1;

                    if (total < amount)
                        $('.tb-status tbody.down tr[data-price="' + price + '"]').attr('data-total', amount);

                    if (amount <= 0) {
                        $('.tb-status tbody.down tr[data-price="' + price + '"]').remove();

                        if ($('.tb-status tbody.down tr.not').length != 0) {
                            var html = '<tr class="not new-data">' +
                                '<td style="background: none;"></td>' +
                                '<td style="background: none;"></td>' +
                                '<td style="background: none; border-left: none;"></td>' +
                                '<td style="background: none;"></td>' +
                                '</tr>';
                            $('.tb-status tbody.down tr.not').last().after(html);
                        } else {
                            $.ajax({
                                url: '/src/controller/trade/listlimit.php',
                                type: 'POST',
                                data: {
                                    'currency':currency[1],
                                    'coin': msg.coin,
                                    'type': 0,
                                    'price': $('.tb-status tbody.down tr:not(.not)').eq(0).attr('data-price').replace(/,/gi, '') * 1
                                },
                                dataType: 'json',
                                success: function (data) {
                                    console.log(data)
                                    if (data.result == 'success') {
                                        var newPrice = data.tr_price.replace(/,/gi, '') * 1;
                                        let nowPrice = $('.coinBox #nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase()).replace(/,/gi, '') * 1;
                                        var className = '';

                                        className = (newPrice > nowPrice ? 'color-red' : (newPrice < nowPrice ? 'color-skyblue' : ''));

                                        var html = '<tr class="new-data" data-price="' + data.tr_price + '" data-total="' + data.tr_total + '">';
                                        html += '<td><p class="amount droid">' + data.tr_total + '</p><p class="bar" style="width: ' + data.width + '%;"></p></td>';

                                        html += '<td class="status-order-price"><em class="' + className + ' droid">' + (data.tr_price) + '</em><span class="droid">' + data.currencyPrice + '</span></td>';
                                        html += '<td class="' + className + ' droid">' + data.percent + '%</td>';
                                        html += '<td></td>';
                                        html += '</tr>';

                                        $('.tb-status tbody.down tr:not(.not)').eq(0).before(html);
                                    } else {
                                        var html = '<tr class="not new-data">' +
                                            '<td style="background: none;"></td>' +
                                            '<td style="background: none;"></td>' +
                                            '<td style="background: none; border-left: none;"></td>' +
                                            '<td style="background: none;"></td>' +
                                            '</tr>';
                                        $('.tb-status tbody.down tr').eq(0).before(html);
                                    }
                                }, error:function(a, b, c){
                                    console.log(a, b, c)
                                }
                            });
                        }

                    } else {
                        var rating = (amount / total * 100).toFixed(2);
                        amount = round_down_format('', amount, 8);

                        $('.tb-status tbody.down tr[data-price="' + price + '"] p.bar').css('width', rating + '%');
                        $('.tb-status tbody.down tr[data-price="' + price + '"] p.amount').text(amount);
                        $('.tb-status tbody.down tr[data-price="' + price + '"]').removeClass('new-data');
                        setTimeout(function () {
                            $('.tb-status tbody.down tr[data-price="' + price + '"]').addClass('new-data')
                        }, 10)
                    }
                } else if (msg.amount.replace(/,/gi, '') * 1 > 0) {
                    // 목록이 10개 이상에 같은 가격이 없을때
                    var checkPrice = price + '';
                    checkPrice = checkPrice.replace(/,/gi, '') * 1;
                    for (var i = $('.tb-status tbody.down tr:not(.not)').length - 1; i >= 0; i--) {
                        var thisPrice = $('.tb-status tbody.down tr:not(.not)').eq(i).attr('data-price').replace(/,/gi, '') * 1;

                        if (thisPrice > checkPrice) {
                            $('.tb-status tbody.down tr:not(.not)').eq(0).remove();
                            $('.tb-status tbody.down tr:not(.not)').eq(i - 1).after(html);
                            break;
                        }
                    }
                }
            }

            $('.tb-status tbody.down tr:not(.not), .tb-status tbody.up tr:not(.not)').unbind('click').bind('click', function () {
                var thisPrice = $(this).attr('data-price').replace(/[^0-9.]/g, '') * 1;

                $('.ex02 .order-price-ipt input[name="price"]').val(thisPrice);
                totalValueInsert();
            });
        }

        // 해당 주문이 미체결 주문에 있을떄
        if($('.order-list-table#waitOrder tr[data-no="' + msg.no + '"]').index() != -1){
            var newAmount = msg.amount.replace(/,/gi, '') * 1;
            var nowAmount = $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"] td:nth-child(7)').text().replace(/[^0-9.]/g, '') * 1;
            var thisType = $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"] td:nth-child(2)').text().split('/');

            if(nowAmount + newAmount <= 0){
                if($('.order-list-table#waitOrder tbody tr').length == 1){
                    var html = '';
                    html += '<tr class="no-data">' +
                        '<td colspan="8">' + lang('미체결중인 주문이 없습니다.', 'The list is empty', '未締結されている注文がありません。', '没有未结的订单。') + '</td>' +
                        '</tr>';

                    $('.order-list-table#waitOrder tbody').html(html);
                } else
                    $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"]').remove();
            } else if(newAmount < 0) {
                // 미체결 주문
                $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"] td:nth-child(7)').text(round_down_format('', nowAmount + newAmount) + ' ' + thisType[0]);
                // 체결 수량
                var successAmount = $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"] td:nth-child(6)').text().replace(/[^0-9.]/g, '') * 1;
                $('.order-list-table#waitOrder tr[data-no="' + msg.no + '"] td:nth-child(6)').text(round_down_format('', successAmount - newAmount) + ' ' + thisType[0]);
            }
        }
    });
});

function sendOrder(type, coin, coinName) {
    coinName = (coinName === undefined) ? '' : coinName;

    let price = $('.ex02 .order-price-ipt input[name="price"]').val().replace(/,/gi, '');
    let amount = $('.ex02 .order-price-ipt input[name="amount"]').val().replace(/,/gi, '');

    var datas = {
        'price': price,
        'amount': amount,
        'tradeType': type,
        'coinType': coin
    };

    if (datas.amount <= 0) {
        alert(lang('최소 주문 수량은 ' + $('.order-amount-area p > span').text(), 'Minimum Order Quantity is ' + $('.order-amount-area p > span').text(), '最小価格は' + $('.order-amount-area p > span').text(), '最低订货数量为' + $('.order-amount-area p > span').text()) + ' ' + datas.coinType + lang(' 입니다.', '', ' です。', ''));
        $('.ex02 .order-price-ipt input[name="amount"]').focus();
    } else if (datas.price <= 0) {
        alert(lang('주문 금액을 지정해주세요.', 'Please specify the order amount.', '注文金額を指定してください。', '请指定订货金额。'));
        $('.ex02 .order-price-ipt input[name="price"]').focus();
    } else {
        $('.ex02 .order-price-ipt input[name="price"], .ex02 .order-price-ipt input[name="amount"]').attr('readonly', 'readonly');

        let totalCoin = $('.ex02 .order-gnb > li.active').attr('data-type') == 'buying' ? $('.ex02 .order-total-area .order-total em').text() : (round_down_format('',
            ($('.ex02 .order-total-area .order-total em').text().replace(/,/gi, '') * 1 - $('.ex02 .order-total-area span.fee + span > em').text().replace(/,/gi, '') * 1).toFixed(8), 8));

        let orderType = (type == 'buying') ? lang('매수', 'BUY', '購入', '收购') : lang('매도', 'SELL', '販売', '卖出');

        let tradeType = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
        let title = '';
        let html = '';
        if (_deviceType == 'pc') {
            title = (type == 'buying') ? lang('아래의 <span class="color-red">지정가 매수</span> 주문을 등록할까요?',
                'Should the designation below register <span class="color-red">the Buy Limit Order</span>?',
                '下記の<span class="color-red">指値購入</span>注文を登録しましょうか?',
                '下面的<span class="color-red"> 是否登记限价收购</span>订单?')
                : lang('아래의 <span class="color-skyblue">지정가 매도</span> 주문을 등록할까요?',
                    'Should the designation below register <span class="color-skyblue">the Sell Limit Order</span>?',
                    '下記の<span class="color-skyblue">指値販売</span>注文を登録しましょうか?',
                    '下面的<span class="color-red"> 是否登记限价卖出</span>订单?');

            html = '<ul class="popup-order-ul">' +
                '<li><strong>' + lang('구분', 'Category', '区分', '区分') + '</strong><span> ' + lang('지정가', '', '指値', '限价') + orderType + '</span></li>' +
                '<li><strong>' + lang('거래 자산', 'Asset', '取引資産', '交易资产') + '</strong><span>' + $('.coinInfo01 .cTit .cUnit').text() + '</span></li>' +
                '<li><strong>' + lang('개당 가격', 'Price', '注文価格', '单价') + '</strong><span class="droid">'
                + $('input[name="price"]').val() + ' ' + tradeType[1] + '</span></li>' +
                '<li><strong>' + lang('수량', 'Quantity', '数量', '数量') + '</strong><span class="droid">'
                + round_down_format('', amount, 8) + ' ' + coin + '</span></li>' +
                '<li><strong>' + lang('수수료', 'Fee', '手数料', '手续费') + '</strong><span class="droid">' + $('.ex02 .order-total-area span.fee + span > em').text() + ' ' + $('.ex02 .order-total-area span.fee + span > span').text() + '</span></li>' +
                '<li><strong>' + lang('총 거래 금액', 'Total', '注文総額', '总成交金额') + '</strong><span class="droid">' + totalCoin + ' ' + tradeType[1] + '</span></li>' +
                '</ul>';
            html += '<div style="padding:20px 0; text-align:center; font-size:14px; font-weight: 400;">';
            html += lang(orderType + ' 가격(' + tradeType[1] + ')과 수량(', 'Please check the price (' + tradeType[1] + ') and quantity(', orderType + '価格(' + tradeType[1] + ')と数量(', orderType + '价格(' + tradeType[1] + ')和数量(')
                + coin + lang(')을 확인 후<br>진행해 주세요. 거래가 체결되면 취소가 불가능합니다.',
                    ') before proceeding.<br> Cancellation is not possible once the transaction has been concluded.',
                    ')を確認後進んでください。<br>取引が締結されるとキャンセルできません。',
                    ')后进行<br>。 交易签订后不能取消。');
            html += '</div>';
        } else {
            title = '<p style="text-align: center;margin-bottom: 25px;color:#0a0a0a;">'
                + '<strong style="font-size: 17px;font-weight: 500;">' + coinName + '</strong> '
                + '(' + coin.toUpperCase() + '/' + tradeType[1] + ')'
                + '</p>';

            title += '<ul class="popup-order-ul">' +
                '<li><strong>' + lang('거래 구분', 'Category', '区分', '交易分类') + '</strong><span> ' + lang('지정가', '', '指値', '限价') + orderType + '</span></li>' +
                '<li><strong>' + lang('거래 자산', 'Asset', '取引資産', '交易资产') + '</strong><span>' + coin + '/' + tradeType[1] + '</span></li>' +
                '<li><strong>' + lang('가격', 'Price', '注文価格', '价格') + '</strong><span class="droid">'
                + round_down_format('', price, 8) + ' ' + tradeType[1] + '</span></li>' +
                '<li><strong>' + lang('수량', 'Quantity', '数量', '数量') + '</strong><span class="droid">'
                + round_down_format('', amount, 8) + ' ' + coin + '</span></li>' +
                '<li><strong>' + lang('수수료', 'Fee', '手数料', '手续费') + '(0.25%)</strong><span class="droid">'
                + $('.ex02 .order-total-area span.fee + span > em').text() + ' ' + tradeType[1] + '</span></li>' +
                '<li><strong>' + lang('총 ' + orderType + ' 금액 (약)', 'Total', '注文総額', '总金额 (约)') + '</strong><span class="droid">' + totalCoin + ' ' + tradeType[1] + '</span></li>' +
                '</ul>';
            html = '<strong style="font-weight:500; font-size:15px;">';
            html += (type == 'buying') ?
                lang('<span class="color-red">지정가 매수</span> 주문을 등록할까요?',
                    'Should the designation below register <span class="color-red">the Buy Limit Order</span>?',
                    '<span class="color-red">指値購入</span>注文を登録しましょうか?',
                    '<span class="color-red"> 是否要登记限价收购</span>订单?')
                : lang('<span class="color-skyblue">지정가 매도</span> 주문을 등록할까요?',
                    'Should the designation below register <span class="color-skyblue">the Sell Limit Order</span>?',
                    '<span class="color-skyblue">指値販売</span>注文を登録しましょうか?',
                    '<span class="color-skyblue"> 是否要登记限价出售</span>订单?');
            html += '</strong>';
        }
        openPopup(title, html, "trading('" + type + "', '" + coin + "')",
            "openOkPopup('" + lang('주문이 취소되었습니다.', 'Order cancelled.', '注文が取り消されました。', '订单已取消。') + "', 'resetTrading()')");
        $(".popup-box").css('width', '420px');
    }
}

// 주문 리셋
function resetTrading() {
    $(".popup-box").remove();
    $('.ex02 .order-price-ipt input[name="price"], .ex02 .order-price-ipt input[name="amount"]').removeAttr('readonly');
}

// 주문 입력
function trading(tradeType, coinType) {
    let pageType = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
    var datas = {
        'price': $('.ex02 .order-price-ipt input[name="price"]').val(),
        'amount': $('.ex02 .order-price-ipt input[name="amount"]').val(),
        'tradeType': tradeType,
        'coinType': coinType,
        'currency': pageType[1]
    };

    $.ajax({
        url: '/src/controller/trade/trading.php',
        type: 'POST',
        data: datas,
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {

                var newOrigin = $('.ex02 .order-gnb > li[data-type="' + datas.tradeType + '"]').attr('data-origin').replace(/,/gi, '') * 1;

                newOrigin -= datas.tradeType == 'selling' ? datas.amount.replace(/,/gi, '') * 1 : datas.amount.replace(/,/gi, '') * datas.price.replace(/,/gi, '');

                $('.ex02 .order-gnb > li[data-type="' + datas.tradeType + '"]').attr('data-origin', newOrigin);

                $('.ex02 .my-max-value em').text(round_down_format('', newOrigin));


                if (data.od_no > 0) {
                    var html = '';
                    html += '<tr data-no="' + data.od_no + '">' +
                        '<td class="droid">' + data.datetime + '</td>' +
                        '<td class="droid">' + datas.coinType + '/' + datas.currency + '</td>' +
                        '<td class="droid">' + (datas.tradeType == 'buying' ? lang('구매', 'BUY', '購入', '采购') : lang('판매', 'SELL', '販売', '销售')) + '</td>' +
                        '<td class="droid">' + datas.price + ' ' + datas.currency + '</td>' +
                        '<td class="droid">' + round_down_format('', datas.amount, 8) + ' ' + datas.coinType + '</td>' +
                        '<td class="droid">' + round_down_format('', data.nowAmount, 8) + ' ' + datas.coinType + '</td>' +
                        '<td class="droid">' + round_down_format('', data.saveAmount, 8) + ' ' + datas.coinType + '</td>' +
                        '<td class="droid"><button onclick="tradeCancel(' + data.od_no + ', \'' + datas.coinType + '\')">' +
                        lang('취소', 'Cancel', 'キャンセル') +
                        '</button></td>' +
                        '</tr>';

                    if(data.saveAmount.replace(/[^0-9.]/g, '') * 1  > 0) {

                        if ($('#waitOrder .order-list-table-body tbody tr:nth-child(1)').hasClass('no-data')) {
                            $('#waitOrder .order-list-table-body tbody').html(html);
                        } else {
                            if ($('#waitOrder .order-list-table-body tbody tr').length == 20)
                                $('#waitOrder .order-list-table-body tbody tr').last().remove();

                            $('#waitOrder .order-list-table-body tbody tr:nth-child(1)').before(html);
                            $('#waitOrder .order-list-table-body table').css({'transform': 'translateY(-46px)'});
                            setTimeout(function () {
                                $('#waitOrder .order-list-table-body table').css({
                                    'transform': 'translateY(0px)',
                                    'transition': 'transform 800ms'
                                });
                            }, 10)
                        }
                    }
                }

                openOkPopup('<p class="text-center">' + lang('주문이 등록되었습니다.', 'Your order has been registered.', '注文が登録されました。', '订单已登记。') + '</p>', 'resetTrading()');


                for (var i = 0; i < data.sockets.length; i++)
                    socket.emit(data.sockets[i].name, data.sockets[i].datas);
            } else if (data.result == 'login') {
                openOkPopup('<p class="text-center">' + lang('로그인 후 이용해주세요.', 'Please log in and use it.', 'ログインしてからご利用ください。', '请登录后使用。') + '</p>', 'location.href="/member/login?url=%2Fexchange%2Fmain"');
            } else if (data.result == 'money') {
                openOkPopup('<p class="text-center">' + lang('자산이 부족합니다.', 'Insufficient assets.', '資産が足りません。', '资产不足。') + '</p>', 'resetTrading()');
            } else if (data.result == 'minimum') {
                openOkPopup('<p class="text-center">' + lang('최소 주문금액은 ' + data.price + ' ' + data.currency + ' 이상 가능합니다.', 'The minimum order amount is ' + data.price + ' ' + data.currency + ' or higher.', '注文金額は最小' + data.price + ' ' + data.currency + '以上可能です。', '最低订货金额可达到' + data.price + ' ' + data.currency + '以上。') + '</p>', 'resetTrading()');
            } else if (data.result == 'minimum_unit') {
                openOkPopup('<p class="text-center">' + lang('해당 코인의 호가 단위는 ' + data.unit + ' 입니다.', 'The unit of arc for that coin is ' + data.unit, '当該コインの呼び値の単位は' + data.unit + 'です。', '相应硬币的报价单位是' + data.unit + '。') + '</p>', 'resetTrading()');
            } else {
                openOkPopup('<p class="text-center">' + lang('주문등록에 실패하였습니다.<br>잠시 후 다시 시도해주세요.',
                    'Failed to register order. <br>Please try again in a moment.',
                    '注文登録に失敗しました。<br>しばらくしてからもう一度お試しください。',
                    '订单登记失败。<br> 请稍后再试。') + '</p>', 'resetTrading()');
            }
        }, error: function (a, b, c) {
            console.log(a, b, c)
            openOkPopup('<p class="text-center">' + lang('오류가 발생하였습니다.<br>잠시 후 다시 시도해주세요.',
                'An error has occurred. <br>Please try again in a moment.',
                'エラーが発生しました。<br>しばらくしてからもう一度お試しください。',
                '发生错误。<br> 请稍后再试。') + '</p>', 'resetTrading()');
        }
    });
}

// 미체결 취소
function tradeCancel(index, coin) {
    let title = '';
    let html = '';
    let title_style = '';
    let desc_style = 'width:280px;';
    if (_deviceType != 'pc') {
        title_style += 'font-weight: 500;font-size: 17px;margin-bottom: 12px;';
        desc_style += 'font-size: 15px;font-weight: normal;';
    }
    title = '<div class="text-center" style="' + title_style + '">' + lang('미체결 주문 취소', 'Cancellation of Unresolved Order', '未締結注文取り消し', '取消未签署订单') + '</div>';
    html = '<p class="text-center" style="' + desc_style + '">' + lang('미체결된 주문을 취소합니다.', 'Cancels any outstanding orders.', '未締結注文を取り消します。', '取消未通过的订单。') + '</p>';

    openPopup(title, html, "tradingCancel(" + index + ", '" + coin + "')");
}

function tradingCancel(index, coin) {
    $.ajax({
        url: '/src/controller/trade/cancel.php',
        type: 'POST',
        data: {'tr_no': index, 'coin': coin},
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                var $pageType = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
                if($('.coinInfo01 .cTit .cUnit').index() == -1){
                    $pageType[0] = $('.coinOrder .co02 .co02Menu li').eq(0).attr('data-coin');
                }

                for (var i = 0; i < data.sockets.length; i++)
                    socket.emit(data.sockets[i].name, data.sockets[i].datas);

                if(data.currency == $pageType[1]) {
                    var tradeType = data.tradeType * 1 == 0 ? 'selling' : 'buying';
                    var nowAmount = $('.ex02 .order-gnb > li[data-type="' + tradeType + '"]').attr('data-origin') * 1;

                    if (data.tradeType * 1 == 0)
                        var newAmount = nowAmount + $('.order-list-table#waitOrder tr[data-no="' + index + '"] td:nth-child(7)').text().replace(/[^0-9.]/g, '') * 1;
                    else
                        var newAmount = nowAmount + ($('.order-list-table#waitOrder tr[data-no="' + index + '"] td:nth-child(7)').text().replace(/[^0-9.]/g, '') * $('.order-list-table#waitOrder tr[data-no="' + index + '"] td:nth-child(4)').text().replace(/[^0-9.]/g, ''));

                    $('.ex02 .order-gnb > li[data-type="' + tradeType + '"]').attr('data-origin', newAmount);

                    if ($('.ex02 .order-gnb > li[data-type="' + tradeType + '"]').hasClass('active'))
                        $('.ex02 .my-max-value em').text(round_down_format('', newAmount));
                }

                $('.order-list-table#waitOrder tr[data-no="' + index + '"] ').remove();

                if($('.order-list-table#waitOrder tbody tr').length == 0){
                    var html = '';
                    html += '<tr class="no-data">' +
                        '<td colspan="8">' + lang('미체결중인 주문이 없습니다.', 'The list is empty', '未締結されている注文がありません。', '没有未结的订单。') + '</td>' +
                        '</tr>';

                    $('.order-list-table#waitOrder tbody').html(html);
                }

                $('.btn-cancel[onclick="tradeCancel(' + index + ', \'' + coin + '\')"]').parent().remove();

                openOkPopup(lang('주문이 취소되었습니다.', 'Order cancelled.', '注文が取り消されました。', '订单已取消。'));
            } else
                openOkPopup(lang('주문취소를 실패하였습니다.<br>잠시 후 다시 시도해주세요.',
                    'Failed to cancel order.<br>Please try again after a while,',
                    '注文キャンセルに失敗しました。<br>しばらくしてもう一度お試しください。',
                    '取消订单失败。<br> 请稍后再试。'));
        }, error: function (a, b, c) {
            console.log(a, b, c);
            openOkPopup(lang('주문취소를 실패하였습니다.<br>잠시 후 다시 시도해주세요.',
                'Failed to cancel order.<br>Please try again after a while,',
                '注文キャンセルに失敗しました。<br>しばらくしてもう一度お試しください。',
                '取消订单失败。<br> 请稍后再试。'));
        }
    });
}

function totalValueInsert() {
    let totalCoin = $('input[name=price]').val() * $('input[name=amount]').val();
    let nowFee = ($('.order-gnb > li.active').attr('data-type') == 'buying' ? ($('input[name=amount]').val() * ($('.order-total-area span.fee').attr('data-fee') * 1 / 100)) : (totalCoin * ($('.ex02 .order-total-area span.fee').attr('data-fee') * 1 / 100)));
    $.ajax({
        url:'/src/controller/common/round_down_format.php',
        type:'GET',
        data:{'price':[totalCoin, nowFee], 'amount':[0, 0]},
        dataType:'json',
        success:function(data){
            $('.order-total-area .order-total em').text(data.price[0]);
            $('.order-total-area span.fee + span > em').text(data.price[1]);
        }
    });
}