let checkAdress = '';

$(function () {

    if($("body").attr('data-device')=='pc') {
        $('.wb-btn-box > button').click(function () {
            let thisCoin = $(this).attr('data-coin');
            let thisType = $(this).attr('data-type');
            if ($(this).hasClass('active')) {
                $(".wallet-table tr." + thisType + "." + thisCoin).addClass('hide');
                $(this).removeClass('active');
                return;
            }
            initiationActive(); // BTN 초기화
            $('.wb-btn-box > button').removeClass('active');
            $('tr.deposit').addClass('hide');
            $('tr.withdraw').addClass('hide');

            $('tr.' + thisCoin + ".withdraw input").not('input.phoneNum').val('');

            if ($(this).attr('data-type') == 'deposit') {
                if(thisCoin == 'fvc') {
                    alert(lang('Coming Soon'));
                } else {
                    $('.wallet-bank .wallet-table .wb-btn-box button[data-type="withdraw"][data-coin="' + thisCoin + '"]').removeClass('active')
                    $('tr.withdraw.' + thisCoin).addClass('hide');

                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active')
                        $(this).parent().parent().parent().children('tr.deposit.' + thisCoin).removeClass('hide')
                    } else {
                        $(this).removeClass('active')
                        $(this).parent().parent().parent().children('tr.deposit.' + thisCoin).addClass('hide')
                    }
                }
            } else if ($(this).attr('data-type') == 'withdraw') {
                if(thisCoin == 'fvc') {
                    alert(lang('Coming Soon'));
                } else if(thisCoin == 'usdt'){
                    alert(lang('현재 출금은 일시적으로 중단되었습니다.', 'The current withdrawal has been temporarily suspended.', '現在、出金は一時的に中断されています。', '现在暂时停止付款。'))
                } else {
                    $('.wallet-bank .wallet-table .wb-btn-box button[data-type="deposit"][data-coin="' + thisCoin + '"]').removeClass('active')
                    $('tr.deposit.' + thisCoin).addClass('hide');

                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active')
                        $(this).parent().parent().parent().children('tr.withdraw.' + thisCoin).removeClass('hide')
                    } else {
                        $(this).removeClass('active')
                        $(this).parent().parent().parent().children('tr.withdraw.' + thisCoin).addClass('hide')
                    }
                }
            }
        })

        $('.wb-btn-box > div').click(function () {
            alert(lang('보안레벨 Level.2부터 사용가능한 메뉴입니다.', 'This menu is available from Security Level 2.', 'セキュリティレベルLEVEL.2から使用可能なメニューです。', '安全等级为Level.2的菜单。'))
        });

        $("input#myCoin").on('change', function () {
            searchCheck()
        });
        $("input#interestCoin").on('change', function () {
            searchCheck()
        });

        // 코인명/심볼 검색
        $('.search-box input.search').on("keyup", function () {
            $(".wb-btn-box button").removeClass('active');
            $(".wallet-table tr.withdraw,.wallet-table tr.deposit").addClass('hide');

            let value = $(this).val().toLowerCase();
            let success = 0;
            let noResult = 0;
            $(".wallet-bank .wallet-table tbody tr .td-coin-symbol").filter(function () {

                if ($(this).text().toLowerCase().indexOf(value) > -1 && !$(this).closest('tr').hasClass('none')) {
                    success++;
                    $(this).closest('tr').show();
                } else {
                    $(this).closest('tr').hide();
                    if (($('#myCoin').is(':checked') || $('#interestCoin').is(':checked'))) {
                        if (value !== "") {
                            noResult++;
                        }
                    }
                }

                if (noResult > 0 && success == 0 || success == 0) {
                    $('.wallet-bank .wallet-table tbody .no-data').remove();
                    $('.wallet-bank .wallet-table tbody').append('<tr class="no-data"><td class="text-center" colspan="5">' + lang('검색된 코인이' +
                        ' 없습니다.', 'No coins found.', '検索されたコインがありません。', '没有搜索到的硬币。') + '</td></tr>');
                } else {
                    $('.wallet-bank .wallet-table tbody .no-data').remove();
                }
            });
        });

        // 출금 금액 INPUT
        $("tr.withdraw input[name='price']").on({
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

                const thisObj = obj;
                priceChange(thisObj)
            }
        });

        // 체크박스
        $("input.wd-agree").on('change', function () {
            if ($(this).is(':checked')) {
                activeButton('check', true);
            } else {
                activeButton('check', false);
            }
        });
    } else {
        $('.bank-table-btns > button').click(function () {
            let thisCoin = $(this).attr('data-coin');
            let thisType = $(this).attr('data-type');
            if ($(this).hasClass('active')) {
                $(".bank-table ." + thisType + "." + thisCoin).addClass('hide');
                $(this).removeClass('active');
                return;
            }
            initiationActive(); // BTN 초기화
            $('.bank-table-btns > button').removeClass('active');
            $('.deposit').addClass('hide');
            $('.withdraw').addClass('hide');

            $('.' + thisCoin + ".withdraw input").not('input.phoneNum').val('');

            if ($(this).attr('data-type') == 'deposit') {
                if(thisCoin == 'fvc') {
                    alert(lang('Coming Soon'));
                } else {
                    $('.wallet-bank .wallet-table .wb-btn-box button[data-type="withdraw"][data-coin="' + thisCoin + '"]').removeClass('active')
                    $('.withdraw.' + thisCoin).addClass('hide');

                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active')
                        $(this).parent().parent().parent().parent().children('.deposit.' + thisCoin).removeClass('hide')
                    } else {
                        $(this).removeClass('active')
                        $(this).parent().parent().parent().parent().children('.deposit.' + thisCoin).addClass('hide')
                    }
                }
            } else if ($(this).attr('data-type') == 'withdraw') {
                if(thisCoin == 'fvc') {
                    alert(lang('Coming Soon'));
                } else if(thisCoin == 'usdt'){
                    alert(lang('현재 출금은 일시적으로 중단되었습니다.', 'The current withdrawal has been temporarily suspended.', '現在、出金は一時的に中断されています。', '现在暂时停止付款。'))
                } else {
                    $('.wallet-bank .wallet-table .wb-btn-box button[data-type="deposit"][data-coin="' + thisCoin + '"]').removeClass('active')
                    $('.deposit.' + thisCoin).addClass('hide');

                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active')
                        $(this).parent().parent().parent().parent().children('.withdraw.' + thisCoin).removeClass('hide')
                    } else {
                        $(this).removeClass('active')
                        $(this).parent().parent().parent().parent().children('.withdraw.' + thisCoin).addClass('hide')
                    }
                }
            }
        })

        $('.bank-table-btns .btn-disabled').click(function () {
            openOkPopup(lang('보안레벨 Level.2부터 사용가능한 메뉴입니다.', 'This menu is available from Security Level 2.', 'セキュリティレベルLevel.2から使用可能なメニューです。', '安全等级为Level.2的菜单。'), 'closePopup()', lang('확인', 'OK', '確認', '确认'), true)
        });

        $('.radio').on('click', function () {
            searchMobileCheck()
            if($("#myCoin").is(":checked") == true) {
                $("#myCoin").attr("checked", false)
                $('.xi-check-coin').addClass('none')
            } else {
                $("#myCoin").attr("checked", true)
                $('.xi-check-coin').removeClass('none')
            }
        })

        // 코인명/심볼 검색
        $('.search-box .search input').on("keyup", function () {
            $(".bank-table-btns button").removeClass('active');
            $(".bank-table .withdraw,.bank-table .deposit").addClass('hide');

            let value = $(this).val().toLowerCase();
            let success = 0;
            let noResult = 0;
            $(".bank-table .table-content .td-coin-symbol").filter(function () {

                if ($(this).text().toLowerCase().indexOf(value) > -1 && !$(this).closest('tr').hasClass('none')) {
                    success++;
                    $(this).closest('.table-content').show();
                } else {
                    $(this).closest('.table-content').hide();
                    if (($('#myCoin').is(':checked') || $('#interestCoin').is(':checked'))) {
                        if (value !== "") {
                            noResult++;
                        }
                    }
                }

                if (noResult > 0 && success == 0 || success == 0) {
                    $('.bank-table .no-data').remove();
                    $('.bank-table').append('<div class="table-content no-data">' + lang('검색된 코인이' +
                        ' 없습니다.', 'No coins found.', '検索されたコインがありません。', '没有搜索到的硬币。') + '</div>');
                } else {
                    $('.bank-table .no-data').remove();
                }
            });
        });

        // 출금 금액 INPUT
        $(".withdraw input[name='price']").on({
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

                const thisObj = obj;
                priceChange(thisObj)
            }
        });

        // 체크박스
        $('.wallet-withdraw-agree').on('click', function () {
            if($(this).children('.wd-agree').is(":checked") == true) {
                $(this).children('.wd-agree').attr("checked", false)
                $(this).children().children('.xi-check-agree').addClass('none')
                activeButton('check', false);
            } else {
                $(this).children('.wd-agree').attr("checked", true)
                $(this).children().children('.xi-check-agree').removeClass('none')
                activeButton('check', true);
            }
        })

        $('.wallet-input-box input[name="address"]').keydown(function () {
            checkAdress = $.trim($(this).val());
        })
        $('.wallet-input-box input[name="address"]').keyup(function () {
            if (checkAdress != $.trim($(this).val()))
                $(this).removeClass('vaildSuccess');
        })
    }

    // 받을 지갑 주소 INPUT
    $("input[name='address']").on('keyup', function (obj) {
        const inputValue = $(obj.target).val();
        if (inputValue != '') {
            activeButton('address', true);
        } else {
            activeButton('address', false);
        }
    });

    $("input#memberOtp").on('keyup', function () {
        if ($(this).val() == '') {
            activeButton('otp', false);
        } else {
            activeButton('otp', true);
        }
    });

    $('button#maxPrice').click(function () {
        let amount = $(this).attr('data-amount') * 1 - $(this).attr('data-fee') * 1;
        const obj = $(this).parent().children('input[name="price"]');
        obj.val($(this).parent().children('input[name="price"]').attr('data-maximum').replace(/,/gi, ''));

        var obj2 = {
            'target': '.withdraw.' + obj.attr('data-symbol').toLowerCase() + ' input[name="price"]'
        }

        priceChange(obj2)
    });
});

function priceChange(obj) {
    activeButton('price', false);

    const coinSymbol = $(obj.target).attr('data-symbol'); // 출금 심볼
    let smCoinSymbol = coinSymbol.toLowerCase();

    const inputValue = $(obj.target).val() * 1; // 출금 금액
    $(obj.target).parent().parent().find('.alert-message').html('');

    if (inputValue > 0 || inputValue != '' || inputValue != 0) {
        const maxValue = $(obj.target).attr('data-maximum') * 1; // 최대 값
        const minValue = $(obj.target).attr('data-minimum') * 1; // 최소 값

        console.log(inputValue, maxValue)

        if (inputValue > maxValue) {
            const moreMessage = lang('현재 출금 가능액보다 많습니다.', 'It is more than the current withdrawal amount.', '現在の出金可能額より多いです。', '比现在可以汇款的金额还多。')
            $(obj.target).parent().parent().find('.alert-message').html('<span>' + moreMessage + ' (' + double_number_format(maxValue, 5) + ' ' + coinSymbol + ')</span>');

        } else if (inputValue < minValue) {
            const lessMessage = lang('최소 출금 가능액보다 적습니다.', 'Less than minimum withdrawal allowance.', '最小出金可能額より少ないです。', '比最小出纳金额少。')
            $(obj.target).parent().parent().find('.alert-message').html('<span> ' + lessMessage + ' (' + minValue + ' ' + coinSymbol + ')</span>');

        } else {
            activeButton('price', true);
            // 출금 수수료
            const mainFee = $("tr.withdraw." + smCoinSymbol + " ." + smCoinSymbol + '-wallet-fee').attr('data-fee') * 1
            //$("tr.withdraw." + coinSymbol.toLowerCase() + " ." + coinSymbol.toLowerCase() + '-wallet-fee [name="fee"]').text(double_number_format(fee) + ' ' + coinSymbol);
            // 출금 수령 수량
            $.ajax({
                url:'/src/controller/common/round_down_format.php',
                type:'GET',
                data:{'price':[inputValue], 'amount':[mainFee, 0]},
                dataType:'json',
                success:function(data){
                    $("tr.withdraw." + smCoinSymbol + " ." + smCoinSymbol + '-wallet-total [name="total"]').text(data.price[0] + ' ' + coinSymbol);
                }
            });
        }
    } else {
        $("tr.withdraw." + smCoinSymbol + " ." + smCoinSymbol + '-wallet-total [name="total"]').text(double_number_format(0) + ' ' + coinSymbol);
    }
}

function searchCheck() {
    $(".wb-btn-box button").removeClass('active');
    $(".wallet-table tr.withdraw,.wallet-table tr.deposit").addClass('hide');

    $('.wallet-bank .wallet-table tbody .no-data').remove();
    const have = $('#myCoin').is(':checked');
    const interest = $('#interestCoin').is(':checked');
    $('.wallet-bank .wallet-table tbody tr .td-coin-symbol').closest('tr').removeClass('none');

    if (have && !interest) {
        $('.wallet-bank .wallet-table tbody tr .td-coin-symbol:not(.have)').closest('tr').addClass('none')
    } else if (!have && interest) {
        $('.wallet-bank .wallet-table tbody tr .td-coin-symbol:not(.interest)').closest('tr').addClass('none')
    } else if (have && interest) {
        $('.wallet-bank .wallet-table tbody tr .td-coin-symbol:not(.have)').closest('tr').addClass('none')
        $('.wallet-bank .wallet-table tbody tr .td-coin-symbol:not(.interest)').closest('tr').addClass('none')
    } else {
        $('.wallet-bank .wallet-table tbody tr .td-coin-symbol').closest('tr').removeClass('none');
    }

    if ($('.wallet-bank .wallet-table tbody tr:not(.none,.hide)').length == 0) {
        $('.wallet-bank .wallet-table tbody').append('<tr class="no-data"><td class="text-center" colspan="5">' + lang('검색된 코인이' +
            ' 없습니다.', 'No coins found.', '検索されたコインがありません。', '没有搜索到的硬币。') + '</td></tr>');
    }
}

function searchMobileCheck() {
    $(".bank-table-btns button").removeClass('active');
    $(".bank-table .withdraw,.bank-table .deposit").addClass('hide');

    $('.bank-table .no-data').remove();
    const have = $('#myCoin').is(':checked');
    // const interest = $('#interestCoin').is(':checked');
    $('.bank-table .table-content .td-coin-symbol').closest('.table-content').removeClass('none');

    if (!have) {
        $('.bank-table .table-content .td-coin-symbol:not(.have)').closest('.table-content').addClass('none')
    // } else if (!have && interest) {
    //     $('.bank-table .table-content .td-coin-symbol:not(.interest)').closest('.table-content').addClass('none')
    // } else if (have && interest) {
    //     $('.bank-table .table-content .td-coin-symbol:not(.have)').closest('.table-content').addClass('none')
    //     $('.bank-table .table-content .td-coin-symbol:not(.interest)').closest('.table-content').addClass('none')
    } else {
        $('.bank-table .table-content .td-coin-symbol').closest('.table-content').removeClass('none');
    }

    if ($('.bank-table .table-content:not(.none,.hide)').length == 0) {
        $('.bank-table').append('<div class="table-content no-data">' + lang('검색된 코인이' +
            ' 없습니다.', 'No coins found.', '検索されたコインがありません。', '没有搜索到的硬币。') + '</div>');
    }
}

// BTN 활성화
let activeMenu = [false, false, false, false];

function activeButton(type, boolean) {
    switch (type) {
        case'address':
            activeMenu[0] = boolean;
            break;
        case 'price':
            activeMenu[1] = boolean;
            break;
        case 'phone':
        case 'otp':
            activeMenu[2] = boolean;
            break
        case'check':
            activeMenu[3] = boolean;
            break;
    }
    if (activeMenu.indexOf(false) == -1) {
        $('.applyWithdraw').attr('disabled', false);
    } else {
        $('.applyWithdraw').attr('disabled', 'disabled');
    }
}

// BTN 초기화
function initiationActive() {
    activeMenu = [false, false, false, false];
    $('.applyWithdraw').attr('disabled', 'disabled');
}

// SMS 전송
function sendSMS(coin) {
    const country = $("." + coin + " .phoneNum").attr('data-country');
    const phoneNum = $("." + coin + " .phoneNum").val();

    function successFunc(data) {
        if (data.resultCode == 10) {
            alert(lang('인증문자가 발송되었습니다.', 'Certificate text has been sent.', '認証コードが送られた。', '发送了认证短信。'));
            $("#btnCertified").attr('disabled', false);
            $("#certifiedSMS>.common-alert").html('');
            setTime = 179;
            let time;
            timeCounter = setInterval(function () {
                time = authTimeCounter(179)
                if (time) {
                    $(".auth-time").html('<span>남은 시간</span><span>(' + time + ')</span>');
                } else {
                    $(".auth-time").html(lang('* 인증시간이 만료되었습니다. 인증코드를 재발송해주세요.', '* The authentication time has' +
                        ' expired. Please resend the authentication code.', '* 認証時間が経過しました。認証コードを再送信してください。', '* 认证时间已满。 请重新发送验证码。'));
                }
            }, 1800);
        } else {
            alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。'));
        }
    }

    sendSms(country, phoneNum, lang('인증문자를 전송중입니다.', 'Sending authentication text.', '認証文字を送信中です。', '正在发送认证短信。'), successFunc);
}

// 인증 확인
function certifiedSMS(coin) {
    activeButton('phone', false);
    const phoneNum = $("." + coin + " .phoneNum").val();
    const authNum = $("." + coin + " .authNum").val();

    $.ajax({
        url: '/src/controller/member/certified.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({'cf_type': 'phone', 'cf_id': phoneNum, 'cf_auth': authNum}),
        success: function (data) {
            const resultCode = data.resultCode;
            $("#certifiedSMS>.common-alert").html('');
            if (resultCode == 10) {
                alert(lang('인증되었습니다.', 'authenticated.', '認証されました。', '已认证。'));
                $(".auth-time").html('');
                activeButton('phone', true);
                clearInterval(timeCounter)
            } else if (resultCode == 96) {
                $(".auth-time").html('<span>' + lang('* 인증시간이 만료되었습니다. 인증코드를 재발송해주세요.', '* The authentication time' +
                    ' has expired. Please resend the authentication code.', '* 認証時間が経過しました。認証コードを再送信してください。', '* 认证时间已满。 请重新发送验证码。') + '</span>');
            } else if (resultCode == 99) {
                $(".auth-time").html('<span>' + lang('* 인증번호가 일치하지 않습니다.', '* The authentication numbers do not' +
                    ' match.', '* 認証番号が一致していません。', '* 认证编号不一致。') + '</span>');
            } else {
                alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。', '请再试一次。'))
                console.log(data);
            }
        }, error: function (a, b, c) {
            console.error('SMS 인증 => ', c);
        }
    });
}

// 출금
function withdraw(mb_level, coin) {
    let auth = true;
    // OTP 인증
    if (mb_level == 3) {
        let memberId = $("#memberId").val();
        let memberOtp = $("." + coin + " #memberOtp").val();
        auth = checkOtp(memberId, memberOtp, coin);
    }

    // 여기서부터 출금 시작
    if (auth) {
        if ($('.withdraw.' + coin + ' input[name="address"]').hasClass('vaildSuccess')) {
            $('.withdraw.' + coin + ' input').attr('readonly', 'readonly');

            let price = $('.withdraw.' + coin + ' input[name="price"]').val().replace(/,/gi, '') * 1;
            let mainFee = $("tr.withdraw." + coin + ' .' + coin + '-wallet-fee').attr('data-fee') * 1;
            let fee = price * mainFee;
            let totalAmount = price - fee;

            var datas = {
                'address': $('.withdraw.' + coin + ' input[name="address"]').val(),
                'amount': price,
                'coin': coin
            };

            $.ajax({
                url: '/src/controller/wallet/withdraw.php',
                type: 'POST',
                data: datas,
                dataType: 'json',
                success: function (data) {
                    if (data.result == 'success') {
                        location.reload(); // 나중에 출금내역으로 이동
                    } else if (data.result == 'amount' && datas.coin == 'btc') {
                        alert(lang('최소 ' + data.amount + ' BTC 이상 출금신청이 가능합니다.', 'You can request withdrawal at least ' + data.amount + ' BTC.', '最小' + data.amount + ' BTC以上の出金申請が可能です。', '至少可以申请' + data.amount + ' BTC以上出纳。'))
                    } else if (data.result == 'amount' && datas.coin == 'eth') {
                        alert(lang('최소 ' + data.amount + ' ETH 이상 출금신청이 가능합니다.', 'At least ' + data.amount + ' ETH or more can be applied for' +
                            ' withdrawal.', '最低' + data.amount + 'ETH以上出金申請が可能です。', '至少' + data.amount + ' ETH 以上可申请禁止出境。'))
                    } else if (data.result == 'amount' && datas.coin == 'fvc') {
                        alert(lang('최소 ' + data.amount + ' FVC 이상 출금신청이 가능합니다.', 'At least ' + data.amount + ' FVC can be applied for' +
                            ' withdrawal.', '最小' + data.amount + 'FVC以上の出金申請が可能です。', '至少' + data.amount + ' FVC以上可申请禁止出境。'))
                    } else if(data.result == 'user_maximum') {
                        alert(lang('한도 금액에서 ' + data.amount + data.symbol + ' 만큼 초과하였습니다.', 'You have exceeded the limit by ' + data.amount + data.symbol, '限度額で' + data.amount + data.symbol + '分を超過しています。', '超出限额' + data.amount + data.symbol + '程度。'));
                    } else
                        alert(lang('출금 신청에 실패하였습니다.\n잠시 후 다시 시도해주세요', 'Failed to apply for withdrawal.\nPlease try' +
                            ' again in a moment.', '出金申請に失敗しました。\n後ほどもう一度お試しください。', '申请禁止出境令失败。\n' +
                            '请稍后再试'))

                    $('.withdraw.' + coin + ' input').removeAttr('readonly');
                }, error: function (a, b, c) {
                    console.log(a, b, c)
                    alert(lang('출금 신청에 실패하였습니다.\n잠시 후 다시 시도해주세요', 'Failed to apply for withdrawal.\nPlease try again' +
                        ' in a moment.', '出金申請に失敗しました。\n後ほどもう一度お試しください。', '申请禁止出境令失败。\n' +
                        '请稍后再试'))
                    $('.withdraw.' + coin + ' input').removeAttr('readonly');
                }
            });
        } else {
            alert(lang('지갑주소를 검증해주세요.', 'Please verify the wallet address.', 'ウォレット管理アドレスを検証してください。', '请验证钱包地址。'))
            $('.withdraw.' + coin + ' input[name="address"]').focus()
        }
    }
}

function checkOtp(memberId, memberOtp, coin) {
    let result = true;
    OtpCheck(memberId, memberOtp, function (data) {
        $('.withdraw.' + coin + ' .alert-otp-auth').text('');
        if (data.resultCode != 10) {
            $('.withdraw.' + coin + ' .alert-otp-auth').text(lang('* 인증번호가 일치하지 않습니다.', '* The authentication' +
                ' numbers do not match.', '* 認証番号が一致していません。', '* 认证编号不一致。'));
            result = false;
        }
    })
    return result;
}

/*
*  지갑주소 검증
* */
function addressCheck(coin) {
    var address = $('.withdraw[data-symbol="' + coin + '"] input[name="address"]');

    if ($.trim(address.val()) == '') {
        alert(lang('지갑주소를 입력해주세요.', 'Please enter your wallet address.', 'ウォレット管理アドレスを入力してください。', '请输入钱包地址。'))
        address.focus();
    } else {
        $.ajax({
            url: '/src/controller/wallet/address_check.php',
            type: 'POST',
            data: {'address': address.val(), 'coin': coin},
            dataType: 'json',
            success: function (data) {
                if (data.result == 'success') {
                    alert(lang('올바른 지갑주소입니다.', 'This is the correct wallet address.', '正しいウォレット管理アドレスです。', '正确的钱包地址。'));
                    $('.withdraw[data-symbol="' + coin + '"] input[name="address"]').addClass('vaildSuccess')
                } else
                    alert(lang('올바르지 않은 지갑 주소입니다.', 'Invalid wallet address.', '正しくない財布のアドレスです。', '是不正确的钱包地址。'));
            }, error: function (a, b, c) {
                alert(lang('주소검증에 실패하였습니다.\n잠시 후 다시 시도해주세요', 'Address verification failed.\n' +
                    'Please try again in a few minutes.', 'アドレス検証に失敗しました。\n' +
                    'しばらくして、もう一度やり直してください。', '地址验证失败。\n' +
                    '请稍后再试'));
                console.log(a, b, c);
            }
        })
    }
}