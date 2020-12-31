let c2cType = '';
$(function () {
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function (el) {
        return el != ''
    });
    c2cType = (pathArray[2] != null) ? pathArray[2] : 'buy';
    // 탭
    $('.account-tap > a').removeClass('active')
    $('.' + c2cType + '-tab').addClass('active')

    // 리스트 -> 상세
    $('.c2c-lists-div .c2c-lists-box .lists-body .list').click(function () {
        // 종료된 거래가 아닐시에만
        if (!$(this).hasClass('finish')) {
            var thisOrder = $(this).attr('data-order') * 1;
            if (!$(this).hasClass('locked')){
                const deviceType = $("body").attr('data-device');
                if (deviceType == 'pc') {
                    c2cView(thisOrder);
                } else {
                    MobileC2cView(thisOrder);
                }
            } else
                popUpPassword(thisOrder)
            // tr select
        }
    })

    // 코인명/심볼 검색
    $('.c2c-search-box input.search').on("keyup", function () {
        $('.lists-ul .no-list').remove();

        let value = $(this).val().toLowerCase();
        let success = 0;

        $(".c2c-lists-box .list >li:first-child").filter(function () {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
                success++;
                $(this).closest('.list').parent().removeClass('none');
                $(this).closest('.list').parent().addClass('show');
            } else {
                $(this).closest('.list').parent().addClass('none');
                $(this).closest('.list').parent().removeClass('show');
            }
        });

        $(".c2c-lists-box .list >li.coin").filter(function () {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
                success++;
                $(this).closest('.list').parent().removeClass('none');
            } else {
                if (!$(this).closest('.list').parent().hasClass('show')) {
                    $(this).closest('.list').parent().addClass('none');
                }
            }
        });
        if (success === 0) {
            $('.lists-ul').append(
                '<li class="no-list">' + lang('검색 된 거래내역이 없습니다.', 'No transaction details have been searched.', '検索された取引履歴がありません。', '已搜索的交易明细不存在。') + '</li>'
            );
        }
    });


})

function deleteC2C(orderNumber) {
    $.ajax({
        url: '/src/controller/trade/c2c_delete.php',
        type: 'POST',
        data: {'orderNumber': orderNumber},
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                $('.no-content').removeClass('none');
                $('.c2c-details').addClass('none');

                $('.c2c-lists-div .c2c-lists-box .lists-body .list[data-order="' + orderNumber + '"]').removeClass('select').addClass('finish');

                openOkPopup(lang('거래가 종료되었습니다.', 'The deal is closed.', '取引が終了しました。', '交易结束。'), 'closePopup()', lang('확인', 'OK', '確認', '确认'), true)
            } else
                alert(lang('거래종료가 실패하였습니다.\n잠시 후 다시 시도해주세요',
                    'Failed to close transaction.\nPlease try again in a few minutes.',
                    '取引終了に失敗しました。\nしばらくして、もう一度やり直してください。',
                    '交易失败。\n请稍后再试'))
        }, error: function (a, b, c) {
            alert(lang('거래종료가 실패하였습니다.\n잠시 후 다시 시도해주세요',
                'Failed to close transaction.\nPlease try again in a few minutes.',
                '取引終了に失敗しました。\nしばらくして、もう一度やり直してください。',
                '交易失败。\n请稍后再试'))
        }
    })
}

function c2cView(orderNumber, password) {
    password = (password == undefined) ? null : password;

    $.ajax({
        url: '/src/controller/trade/c2c_view.php',
        type: 'POST',
        data: {'orderNumber': orderNumber, 'password': password},
        dataType: 'json',
        success: function (data) {
            closePopup();

            if (data.result == 'success') {
                $('.no-content').addClass('none');

                $('.c2c-lists-div .c2c-lists-box .lists-body .list').removeClass('select')
                $('.c2cBtn').removeClass('deleteBtn')
                $('.c2cBtn').removeClass('sellBtn')
                $('.c2cBtn').removeClass('buyBtn')
                if (data.value.myCheck) {
                    $('.my-type').removeClass('none')
                    $('.c2c-order-box >div').hide()
                    $('.c2cBtn').text(lang('거래 종료하기', 'To close a transaction', '取引終了する', '交易退出'))
                    $('.c2cBtn').addClass('deleteBtn')
                } else if (c2cType == 'sell') {
                    $('.c2c-order-box >div').show()
                    $('.c2cBtn').text(data.value.symbol + lang(' 판매하기', ' Selling', ' 販売する', '销售'))
                    $('.c2cBtn').addClass('sellBtn')
                } else {
                    $('.c2cBtn').text(data.value.symbol + lang(' 구매하기', ' Buying', ' 購買する', '购买'))
                    $('.c2c-order-box >div').show()
                    $('.c2cBtn').addClass('buyBtn')
                }

                // c2c 달성률
                $('.c2c-lists-div .c2c-lists-box .lists-body .list[data-order="' + data.value.orderNumber + '"]').addClass('select');
                $('.c2c-bar .percent-bar').css('width', data.value.percent + '%');
                $('.c2c-details .c2c-type-box .c2c-no').text('NO. ' + data.value.orderNumber);
                $('.c2c-details .c2c-type-box .my-type').text('[' + (data.value.type == 0 ? lang('판매', 'Sell', '販売', '销售') : lang('구매', 'Buy', '購入', '采购')) + ']');
                $('.c2c-details .c2c-type-box .c2c-type > img').attr('src', '/public/img/coin/' + data.value.symbol + '.png');
                $('.c2c-details .c2c-type-box .c2c-type span').text($('.list.select .coin div span').text());
                $('.c2c-details .c2c-type-box .c2c-type p').text(data.value.symbol);
                $('.c2c-details .c2c-detail-box .c2c-price-box span').text(round_down_format('', data.value.price, 5));
                let currency = (data.value.currency == '') ? 'USDT' : data.value.currency;
                $('.c2c-details .c2c-detail-box .c2c-price-box p').text(currency + ' / 1 ' + data.value.symbol);
                $('.c2c-details .c2c-detail-box .c2c-quantity-box span.remain span, .c2c-details .c2c-order-box > div > p').text(data.value.quantity + ' ' + data.value.symbol);
                $('.c2c-details .c2c-detail-box .c2c-quantity-box span.total span').text(data.value.amount + ' ' + data.value.symbol).attr('data-amount', data.value.amount);
                $('.c2c-lists-div .c2c-lists-box .lists-body .list[data-order="' + data.value.orderNumber + '"] li:nth-child(4)').text(data.value.percent + '%');
                $('.c2c-details .c2c-detail-box .c2c-bar-box .c2c-bar-desc-box span b').text(data.value.percent + '%');
                $('.c2c-details .c2c-detail-box .c2c-bar-box .c2c-bar-desc-box p span').text(data.value.finishTime);
                $('.c2c-details .c2c-order-box > div em').text(data.value.symbol);

                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(0).attr('data-amount', data.value.quantity * 0.1);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(1).attr('data-amount', data.value.quantity * 0.25);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(2).attr('data-amount', data.value.quantity * 0.5);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(3).attr('data-amount', data.value.quantity * 1);


                $('.c2c-details').removeClass('none')

                $('.c2cBtn').unbind('click').bind('click', function () {
                    if ($(this).hasClass('deleteBtn')) {
                        openPopup(lang('거래 삭제', 'Deletion of deal', '取引削除', '交易删除'),
                            lang('해당 개인거래를 삭제합니다.<br>삭제할 경우 거래대기로 잡혀 있던 자산은<br>지갑으로 반환됩니다.',
                                'Delete the personal transaction.<br>If deleted, assets held as transaction waiting <br>will be returned to the wallet.',
                                '当該個人取引を削除します。<br>削除する場合、<br>取引待機中の資産はウォレット管理に返されます。',
                                '删除相应个人交易。<br>如需删除,待交易资产将退还为钱包。'),
                            'deleteC2C(' + orderNumber + ')', 'closePopup()', lang('예', 'YES', 'はい', '例子'), lang('아니오', 'NO', 'いいえ', '不'), true)
                    } else if ($('input.order-input').val().replace(/,/gi, '') * 1 <= 0) {
                        alert(lang('주문수량을 입력해주세요.', 'Please enter the order quantity.', '注文数量を入力してください。', '请输入订货数量。'));
                        $('input.order-input').focus()
                    } else
                        popUpConfirmC2C(orderNumber)
                });

                $('.c2c-details .c2c-order-box .order-btn-div > button').unbind('click').bind('click', function () {
                    var thisVal = $(this).attr('data-amount');

                    $('.c2c-details .c2c-order-box > div .order-input').val(thisVal);
                });
            } else if (data.result == 'finish')
                alert(lang('거래가 종료된 주문입니다.', 'This order has been closed.', '取引が終了した注文です。', '是交易结束的订单。'));
            else if (data.result == 'fail_password') {
                alert(lang('비밀번호가 틀렸습니다', 'Wrong password.', 'パスワードが間違えました。', '密码错误'));
                popUpPassword(orderNumber)
            } else if (data.result == 'login') {
                alert(lang('로그인 후 이용해주세요.', 'Please log in and use it.', 'ログインした後、ご利用ください。', '请登录后使用。'))
                location.href = '/member/login?url=%2Fc2c%2Fmain%2F' + c2cType;
            } else
                alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.',
                    'An error has occurred.\nPlease try again in a moment.',
                    'エラーが発生しました。\nしばらくして、もう一度やり直してください。',
                    '发生错误。\n请稍后再试。'))
        }, error: function (a, b, c) {
            alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.',
                'An error has occurred.\nPlease try again in a moment.',
                'エラーが発生しました。\nしばらくして、もう一度やり直してください。',
                '发生错误。\n请稍后再试。'))
            console.log(a, b, c)
        }
    });
}

function MobileC2cView(orderNumber, password) {
    password = (password == undefined) ? null : password;

    $.ajax({
        url: '/src/controller/trade/c2c_view.php',
        type: 'POST',
        data: {'orderNumber': orderNumber, 'password': password},
        dataType: 'json',
        success: function (data) {
            let title = '';
            closePopup();

            if (data.result == 'success') {
                $('.c2c-search-box').addClass('none');
                $('.c2c-lists-div').addClass('none');
                $('.c2c-detail-div').removeClass('none');

                $('.no-content').addClass('none');

                $('.c2c-lists-div .c2c-lists-box .lists-body .list').removeClass('select')
                $('.c2cBtn').removeClass('deleteBtn')
                $('.c2cBtn').removeClass('sellBtn')
                $('.c2cBtn').removeClass('buyBtn')
                if (c2cType == 'my') {
                    $('.my-c2c-type').removeClass('none')
                    $('.c2c-no').css('margin-left','5px');
                    $('.c2c-order-box >div').hide()
                    $('.c2cBtn').text(lang('거래 삭제하기', 'To delete a transaction', '取引を削除する', '刪除交易'))
                    $('.c2cBtn').addClass('deleteBtn')
                    title = lang('나의 거래', 'My C2C', '私の取引', '我的交易')
                } else if (c2cType == 'sell') {
                    $('.c2c-order-box >div').show()
                    $('.c2cBtn').text(data.value.symbol + lang(' 판매하기', ' Selling', ' 販売する', '销售'))
                    $('.c2cBtn').addClass('sellBtn')
                    title = lang('판매', 'Sell', '売り', '销售')
                } else {
                    $('.c2cBtn').text(data.value.symbol + lang(' 구매하기', ' Buying', ' 購買する', '购买'))
                    $('.c2c-order-box >div').show()
                    $('.c2cBtn').addClass('buyBtn')
                    title = lang('구매', 'Buy', '買い', '采购')
                }

                // c2c 달성률
                $('.c2c-lists-div .c2c-lists-box .lists-body .list[data-order="' + data.value.orderNumber + '"]').addClass('select');
                $('.c2c-details .c2c-type-box .my-type').text(title);
                $('.c2c-details .c2c-type .my-c2c-type').text('[' + (data.value.type == 0 ? lang('판매', 'Sell', '売り', '销售') : lang('구매', 'Buy', '買い', '采购')) + ']');
                $('.c2c-details .c2c-type .c2c-no').text('NO. ' + data.value.orderNumber);
                $('.c2c-details .c2c-type span').text($('.list.select .coin div span').text());
                $('.c2c-details .c2c-type p').text(data.value.symbol);
                $('.c2c-details .c2c-detail-box .c2c-price-box span').text(round_down_format('', data.value.price, 5));
                let currency = (data.value.currency == '') ? 'USDT' : data.value.currency;
                $('.c2c-details .c2c-detail-box .c2c-price-box p').text(currency + ' / 1 ' + data.value.symbol);
                $('.c2c-details .c2c-type-box .c2c-type > img').attr('src', '/public/img/coin/' + data.value.symbol + '.png');
                $('.c2c-details .c2c-detail-box .c2c-bar .percent-bar').css('width', data.value.percent + '%');
                $('.c2c-details .c2c-detail-box .c2c-bar-desc-box span span').text(data.value.percent + '%');
                // $('.c2c-lists-div .c2c-lists-box .lists-body .list[data-order="' + data.value.orderNumber + '"] li:nth-child(4) span').text(data.value.percent + '%');
                $('.c2c-details .c2c-detail-box .c2c-bar-box .c2c-bar-desc-box p span').text(data.value.finishTime);
                $('.c2c-details .c2c-detail-box .c2c-quantity-box span.remain span').text(data.value.quantity + ' ' + data.value.symbol);
                $('.c2c-details .c2c-detail-box .c2c-quantity-box span.total span').text(data.value.amount + ' ' + data.value.symbol).attr('data-amount', data.value.amount);
                $('.c2c-details .c2c-order-box .c2c-order p span, .c2c-details .c2c-order-box > div em').text(data.value.symbol);

                $('.c2c-details .c2c-order-box .c2c-quantity .order-input').val(data.value.quantity);

                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(0).attr('data-amount', data.value.quantity * 0.1);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(1).attr('data-amount', data.value.quantity * 0.25);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(2).attr('data-amount', data.value.quantity * 0.5);
                $('.c2c-details .c2c-order-box .order-btn-div > button').eq(3).attr('data-amount', data.value.quantity * 1);


                $('.c2c-details').removeClass('none')

                $('.c2cBtn').unbind('click').bind('click', function () {
                    if ($(this).hasClass('deleteBtn')) {
                        openPopup(lang('거래 삭제', 'Deletion of deal', '取引削除', '交易删除'),
                            lang('해당 개인거래를 삭제합니다.<br>삭제할 경우 거래대기로 잡혀 있던 자산은<br>지갑으로 반환됩니다.',
                                'Delete the personal transaction.<br>If deleted, assets held as transaction waiting <br>will be returned to the wallet.',
                                '当該個人取引を削除します。<br>削除する場合、<br>取引待機中の資産はウォレットに返されます。',
                                '删除相应个人交易。<br>如需删除,待交易资产将退还为钱包。'),
                            'deleteC2C(' + orderNumber + ')', 'closePopup()', lang('예', 'YES', 'はい', '例子'), lang('아니오', 'NO', 'いいえ', '不'), true)
                    } else if ($('input.order-input').val().replace(/,/gi, '') * 1 <= 0) {
                        popUpCheck(lang('주문수량을 입력해주세요.', 'Please enter the order quantity.', '注文数量を入力してください。', '请输入订货数量。'));
                        $('input.order-input').focus()
                    } else
                        popUpConfirmC2C(orderNumber)
                });

                $('.c2c-details .c2c-order-box .order-btn-div > button').unbind('click').bind('click', function () {
                    var thisVal = $(this).attr('data-amount');

                    $('.c2c-details .c2c-order-box > div .order-input').val(thisVal);
                });
            } else if (data.result == 'finish')
                popUpCheck(lang('거래가 종료된 주문입니다.', 'This order has been closed.', '取引が終了した注文です。', '是交易结束的订单。'));
            else if (data.result == 'fail_password') {
                popUpCheck(lang('비밀번호가 일치하지 않습니다.', 'Passwords do not match.', 'パスワードが一致しません。', '密码不一致。'))
            } else if (data.result == 'login') {
                popUpCheck(lang('로그인 후 이용해주세요.', 'Please log in and use it.', 'ログインした後、ご利用ください。', '请登录后使用。'))
                location.href = '/member/login?url=%2Fc2c%2Fmain%2F' + c2cType;
            } else
                popUpCheck(lang('오류가 발생하였습니다.<br>잠시 후 다시 시도해주세요.',
                    'An error has occurred.<br>Please try again in a moment.',
                    'エラーが発生しました。<br>しばらくしてからもう一度試してください。',
                    '发生错误。<br>请稍后再试。'))
        }, error: function (a, b, c) {
            popUpCheck(lang('오류가 발생하였습니다.<br>잠시 후 다시 시도해주세요.',
                'An error has occurred.<br>Please try again in a moment.',
                'エラーが発生しました。<br>しばらくしてからもう一度試してください。',
                '发生错误。<br>请稍后再试。'))
            console.log(a, b, c)
        }
    });
}


function popUpPassword(orderNumber) {
    closePopup();

    let html = '<div class="popup-box">';
    html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
    html += '<div class="popup-head">' + lang('비밀번호를 입력해 주세요', 'Please enter your password.', '暗証番号をご入力ください。', '请输入密码') + '</div>';
    html += '<form action="javascript:checkC2CPassword(' + orderNumber + ')"><div class="popup-content"><input type="password"' +
        ' class="popup-input"></div>';
    html += '<div class="popup-btn">';
    html += '<button type="submit" class="nextPopup">' + lang('확인', 'OK', '確認', '确认') + '</button>';
    html += '</div>';
    html += '</form></div>';
    html += '<div class="popup-bg"></div>';
    openCustomPopup(html)

    $('.popup-input').focus();

}

function checkC2CPassword(orderNumber) {
    c2cView(orderNumber, $('.popup-input').val());
}

function popUpConfirmC2C(orderNumber) {
    const c2cType = (getParameter('type') != null) ? getParameter('type') : 'buy';
    const typeTitle = (c2cType == 'buy') ? lang('구매', 'Buy', '購入', '采购') : lang('판매', 'Sell', '販売', '销售');
    const amount = $('input.order-input').val() * 1;
    const price = $('.c2c-details .c2c-detail-box .c2c-price-box span').text().replace(/,/gi, '') * 1;
    const total = (price * amount).toFixed(4);

    const coin = (c2cType == 'buy') ? 'USDT' : $('.c2c-details .c2c-order-box > div em').text();
    const count = $('.c2c-details .c2c-order-box > div em').text();
    const header = lang(typeTitle + '내역 확인', 'Check your ' + typeTitle + ' history', typeTitle + '履歴を確認', '确认' + typeTitle + '明细');
    const body = '<ul class="popup-order-ul">' +
        '<li><strong>' + lang('거래구분', 'Category', '取引区分', '交易分类') + '</strong><span>' + lang('개인거래 ', 'C2C ', '個人取引 ', '个人交易') + typeTitle + '</span></li>' +
        '<li><strong>' + lang('거래수량', 'Quantity', '取引数量', '交易量') + '</strong><span class="droid">' + amount + ' ' + count + '</span></li>' +
        '<li><strong>' + lang('거래가격', 'Price', '取引価格', '交易价格') + '</strong><span class="droid">' + price + ' USDT</span></li>' +
        '<li><strong>' + lang('거래총액', 'Total', '取引数量', '交易总额') + '</strong><span class="droid">' + total + ' USDT</span></li>' +
        '</ul>' +
        '<div style="padding:25px 0 5px; text-align:center; font-size:14px; font-weight: 400; width:300px;">' +
        lang('위의 내용으로 ' + typeTitle + '를 진행할까요?',
            'Shall we proceed with the ' + typeTitle + ' above?',
            '上記の内容で' + typeTitle + 'を進行しますか？',
            '是否按照以上内容进行' + typeTitle + '?') +
        '</div>';
    const nextEvent = 'C2CTransaction(' + orderNumber + ', ' + amount + ')';
    const closeEvent = 'closePopup()';
    const nextText = lang('예', 'YES', 'はい', '例子');
    const closeText = lang('아니오', 'NO', 'いいえ', '不');
    openPopup(header, body, nextEvent, closeEvent, nextText, closeText, true);
}

function C2CTransaction(orderNumber, amount) {
    $.ajax({
        url: '/src/controller/trade/c2c_trade.php',
        type: 'POST',
        data: {'orderNumber': orderNumber, 'amount': amount},
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                openOkPopup(lang('거래가 완료되었습니다.',
                    'The transaction is complete.',
                    '取引が完了しました。',
                    '交易完成。'), 'location.reload()', lang('확인', 'OK', '確認', '确认'), true)
            } else if (data.result == 'money')
                openOkPopup(lang('보유중인 자산이 부족합니다.',
                    'Insufficient assets in possession.',
                    '保有中の資産が足りません。',
                    '持有的资产不足。'), 'closePopup()', lang('확인', 'OK', '確認', '确认'), true)
            else if (data.result == 'self')
                openOkPopup(lang('본인의 거래는 주문할 수 없습니다.',
                    'Insufficient assets in possession.',
                    '保有中の資産が足りません。',
                    '本人的交易不能订购。'), 'closePopup()', lang('확인', 'OK', '確認', '确认'), true)
            else if (data.result == 'count')
                openOkPopup(lang('거래수량이 부족합니다.<br>새로고침 후 다시 시도해주세요.',
                    'Transaction quantity is insufficient.<br>Please refresh br and try again.',
                    '取引数量が不足しています。<br>リフレッシュしてからもう一度お試しください。',
                    '交易数量不足。<br> 请在新的高温后再次尝试。'), 'closePopup()', lang('확인', 'OK', '確認'), true)
            else
                alert(lang('거래가 실패하였습니다.\n잠시 후 다시 시도해주세요',
                    'The transaction has failed.\nPlease try again in a moment.',
                    '取引に失敗しました。\nしばらくしてからもう一度お試しください。',
                    '交易失败了。\n请稍后再试'))
        }, error: function (a, b, c) {
            alert(lang('거래가 실패하였습니다.\n잠시 후 다시 시도해주세요',
                'The transaction has failed.\nPlease try again in a moment.',
                '取引に失敗しました。\nしばらくしてからもう一度お試しください。',
                '交易失败了。\n请稍后再试'))
            console.log(a, b, c)
        }
    })
}