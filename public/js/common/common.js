function setCookie(name, value, day) {
    let date = new Date();
    date.setTime(date.getTime() + day * 60 * 60 * 24 * 1000);
    document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
}

function deleteCookie(name) {
    let date = new Date();
    document.cookie = name + "= " + "; expires=" + date.toUTCString() + "; path=/";
}

function getCookie(name) {
    var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return value ? value[2] : null;
}

// 숫자에 , 를 출력
function number_format(data) {

    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;

    data = data + '';

    var sign = data.match(/^[\+\-]/);
    if (sign) {
        data = data.replace(/^[\+\-]/, "");
    }

    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i = 0; i < data.length; i++) {
        number = number + data.charAt(i);

        if (i < data.length - 1) {
            k++;
            if ((k % cutlen) == 0) {
                number = number + comma;
                k = 0;
            }
        }
    }

    if (sign != null)
        number = sign + number;

    return number;
}

function round_down_format(select, value, round, format) {
    round = (round === undefined) ? 8 : round;
    value = value + '';

    if(value.toString().indexOf(',') != -1)
        value = value.replace(/[^0-9.]/g, '') * 1;

    if(value.toString().indexOf('e') != -1)
        value = '0';

    if(value.toString().indexOf('.') != -1){
        var explode = value.split('.'),
            double = '';

        explode[1] = explode[1].substr(0, round);

        for(var i = (round >= explode[1].length ? round : explode[1].length); i >= 0; i--){
            if(explode[1].substr(i, 1) + '' == '0' && double == '')
                ;//continue;

            double = explode[1].substr(i, 1) + '' + double;
        }

        // double = '0.' + double;
        // double *= 1;
        // double = Math.round(double * 1e12) / 1e12;
        // double = double + '';
        // double = double.replace('0.', '');

        var returnVal = (format != -1 ? number_format(explode[0]) : explode[0]) + '' + ($.trim(double) != '' ? '.' + double : '');

        return returnVal.replace('e', '0').replace('E', '0');
    } else
        return (format != -1 ? number_format(value) : value);
}

function double_number_format(x, limit) {
    limit = (limit === undefined) ? 5 : limit;

    if (x == '00') {
        return 0;
    }

    // 1. 소수점 . 기준으로 나누기
    x += '';
    let xArr = x.split('.');
    x = xArr[0];
    // 2. 소수점 5자리까지
    let xDouble = (xArr[1] == undefined) ? 0 : xArr[1].substr(0, limit);
    x = number_format(x);
    if (xArr[1] != undefined)
        x += '.' + xDouble;
    return x;
}

function bin2hex(s) {
    // http://jsphp.co/jsphp/fn/view/bin2hex
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Linuxworld
    // *     example 1: bin2hex('Kev');
    // *     returns 1: '4b6576'
    // *     example 2: bin2hex(String.fromCharCode(0x00));
    // *     returns 2: '00'
    let i, f = 0,
        a = [];
    s += '';
    f = s.length;
    for (i = 0; i < f; i++) {
        a[i] = s.charCodeAt(i).toString(16).replace(/^([\da-f])$/, "0$1");
    }
    return a.join('');
}

function hex2bin(hex) {
    let bytes = [], str;

    for (let i = 0; i < hex.length - 1; i += 2)
        bytes.push(parseInt(hex.substr(i, 2), 16));

    return String.fromCharCode.apply(String, bytes);
}


function getParameter(param) {
    var url = location.href;
    var parameters = (url.slice(url.indexOf('?') + 1, url.length)).split('&');
    for (var i = 0; i < parameters.length; i++) {
        var varName = parameters[i].split('=')[0];
        if (varName.toUpperCase() == param.toUpperCase()) {
            var varValue = parameters[i].split('=')[1];
            return decodeURIComponent(varValue);
        }
    }
}


function lang(ko, en, ja, ch) {
    var this_lang = $('body').attr('data-lang');

    if (this_lang == 'ko')
        var msg = ko;
    else if (this_lang == 'en')
        var msg = en != undefined ? en : ko;
    else if (this_lang == 'ja')
        var msg = ja != undefined ? ja : en != undefined ? en : ko;
    else if (this_lang == 'ch')
        var msg = ch != undefined ? ch : en != undefined ? ch : ko;

    return msg;
}

socket.on('coinCounting', function (msg) {
    if (msg.ci_price.toString().replace(/,/gi, '') * 1 >= 0) {
        $('#nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase(), msg.ci_price);
        $('.tb-highlight tbody td[data-type="change"][data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase(), msg.percent + '%')
        $('.tb-highlight tbody td#nowTotal[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase(), msg.ci_total_price + lang('백만', 'M'))
    }

    if($('.coinBox .ex01-type > button[data-type="' + msg.currency.toLowerCase() + '"]').hasClass('active') && msg.ci_price.toString().replace(/,/gi, '') * 1 >= 0){
        $('.tb-highlight tbody td[data-type="change"][data-coin="' + msg.coin + '"]').removeClass('color-red').removeClass('color-skyblue');
        $('.tb-highlight tbody td#nowPirce[data-coin="' + msg.coin + '"]').removeClass('color-red').removeClass('color-skyblue');

        $('.tb-highlight tbody td[data-type="change"][data-coin="' + msg.coin + '"]').addClass(msg.percent * 1 > 0 ? 'color-red' : (msg.percent * 1 < 0 ? 'color-skyblue' : ''))
        $('.tb-highlight tbody td#nowPirce[data-coin="' + msg.coin + '"]').addClass(msg.percent * 1 > 0 ? 'color-red' : (msg.percent * 1 < 0 ? 'color-skyblue' : ''))

        $('.coinInfo01 #cdayUpdown').text(msg.percent * 1 > 0 ? '▲' : (msg.percent * 1 < 0 ? '▼' : ''));
        $('.coinInfo > div.coinInfo01').removeClass('increase').removeClass('decrease').addClass(msg.percent * 1 >= 0 ? 'increase' : 'decrease')

        var buyOrigin = $('.ex02 .order-gnb > li[data-type="buying"]').attr('data-origin') * 1;
        var coinPrice = msg.ci_price.toString().replace(/,/gi, '') * 1;
        if($('.ex02 .order-gnb > li.active').attr('data-type') == 'buying')
            $('.ex02 .my-max-value em').text(round_down_format('', buyOrigin / coinPrice, 8));

        $('#nowPirce[data-coin="' + msg.coin + '"]').text(msg.ci_price);
        $('.tb-highlight tbody td[data-type="change"][data-coin="' + msg.coin + '"]').text(msg.percent + '%');
        $('.tb-highlight tbody td#nowTotal[data-coin="' + msg.coin + '"]').text(msg.ci_total_price + lang('백만', 'M'));
        $('.coinInfo01 #cMainPercent').text(msg.percent + '%');
        $('.coinInfo02 > div > div.volume > span > em.droid').text(msg.ci_total_coin);
        $('.coinInfo02 > div > div.amount em.droid').text(msg.ci_total_price);
    }

    if(msg.currency == 'USDT')
        $('.intro-section .intro-bottom > .intro-table > li > ul > li[data-sort="percent"]').text(msg.percent + '%');

    if($('.coinInfo01 .cTit .cUnit').index() != -1 || $('.coinInfo .cValue .cMainValue').index() != -1) {
        var $pageInfo = $('.coinInfo01 .cTit .cUnit').index() != -1 ? $('.coinInfo01 .cTit .cUnit').text().split('/') : $('.coinInfo .cValue .cMainValue').text().split(' ');
        if($('.coinInfo01 .cTit .cUnit').index() == -1){
            $pageInfo[0] = $('.coinOrder .co02 .co02Menu li').eq(0).attr('data-coin');
        }

        console.log($pageInfo)

        var checkPrice = $('.coinBox #nowPirce[data-coin="' + msg.coin + '"]').attr('data-' + msg.currency.toLowerCase()).replace(/,/gi, '') * 1;

        if ($pageInfo[0] == msg.coin && $pageInfo[1] == msg.currency) {
            $('.tb-status tbody.total tr td #coin_total_sell').text(msg.ci_total_sell);
            $('.tb-status tbody.total tr td #coin_total_buy').text(msg.ci_total_buy);

            if (msg.ci_price >= 0) {
                $('.coinInfo01 .cValue .cMainValue').text(msg.ci_price + ' ' + msg.currency);
                $('.coinInfo01 #cdayValue').text(msg.yesterdayPrice);
            }

            $('.coinInfo02 > div > div.high em').text(msg.ci_high_price);
            $('.coinInfo02 > div > div.low em').text(msg.ci_low_price);
            $('.coinInfo02 > div > div.volume em').text(msg.ci_total_coin);

            // 매도 주문현황 색상 처리
            for (var i = 0; i < $('.tb-status tbody.down tr:not(.not)').length; i++) {
                var thisPrice = $('.tb-status tbody.down tr:not(.not)').eq(i).attr('data-price').replace(/[^0-9.]/g, '') * 1;

                if (thisPrice == checkPrice) {
                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') *').removeClass('color-red').removeClass('color-skyblue');
                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') .status-order-price + td').text('0.00%');
                } else {
                    var removeColor = (thisPrice > checkPrice ? 'color-skyblue' : (thisPrice < checkPrice ? 'color-red' : '')),
                        addColor = (thisPrice > checkPrice ? 'color-red' : (thisPrice < checkPrice ? 'color-skyblue' : ''))
                        newPercent = round_down_format('', (thisPrice / checkPrice * 100) - 100, 2);

                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') *').removeClass(removeColor);
                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') .status-order-price > em').addClass(addColor);
                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') > td.droid').addClass(addColor);

                    $('.tb-status tbody.down tr:not(.not):eq(' + i + ') .status-order-price + td').text((checkPrice != 0 ? newPercent : '0.00') + '%');
                }
            }
            // 매수 주문현황 색상 처리
            for (var i = 0; i < $('.tb-status tbody.up tr:not(.not)').length; i++) {
                var thisPrice = $('.tb-status tbody.up tr:not(.not)').eq(i).attr('data-price').replace(/[^0-9.]/g, '') * 1;

                if (thisPrice == checkPrice) {
                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') *').removeClass('color-red').removeClass('color-skyblue');
                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') .status-order-price + td').text('0.00%');
                } else {
                    var removeColor = (thisPrice > checkPrice ? 'color-skyblue' : (thisPrice < checkPrice ? 'color-red' : '')),
                        addColor = (thisPrice > checkPrice ? 'color-red' : (thisPrice < checkPrice ? 'color-skyblue' : '')),
                        newPercent = round_down_format('', (thisPrice / checkPrice * 100) - 100, 2);

                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') *').removeClass(removeColor);
                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') .status-order-price > em').addClass(addColor);
                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') > td.droid').addClass(addColor);

                    $('.tb-status tbody.up tr:not(.not):eq(' + i + ') .status-order-price + td').text((checkPrice != 0 ? newPercent : '0.00') + '%');
                }
            }
        }
    }
});

$(document).ready(function () {
    $('.switch input[type="checkbox"]').on('change', function () {
        if ($(this).is(':checked')) {
            setCookie('theme', 'dark', 31);
            $('head').append('<link rel="stylesheet" href="/public/css/common/common_dark.css" id="darkmode">')
        } else {
            setCookie('theme', 'white', 31);
            $('head #darkmode').remove();
        }
    });
})

/**
 * 리캡챠
 * @param successFunc  ***리캡챠 성공후 실행할 함수를 파라미터로 전달
 */
function recaptcha(successFunc) {
    grecaptcha.ready(function () {
        grecaptcha.execute('6LdRMMEZAAAAAMzERqsu-vLOSHirAyhd0jaqZFKn', {action: 'homepage'}).then(function (token) {
            $("#recaptchaResponse").val(token);
            const recaptchaResponse = $("#recaptchaResponse").val();
            const recaptchaPram = {
                "recaptcha_response": recaptchaResponse
            }
            $.ajax({
                url: '/src/controller/common/recaptcha.php',
                dataType: 'json',
                type: 'POST',
                async: false,
                data: JSON.stringify(recaptchaPram),
                success: function (data) {
                    if (data) {
                        const returnCode = data.resultCode * 1;
                        const returnMessage = data.resultMessage;
                        switch (returnCode) {
                            case 10 :
                                successFunc()
                                break;

                            default:
                                alert(lang("잠시후 다시 시도해 주세요.", 'Please try again in a momentarily.', 'しばらくして、もう一度やり直してください。', '请稍后再试。'));
                                break;
                        }

                    } else {
                        console.error("ERROR");
                    }
                }, error: function (a, b, c) {
                    console.error(c)
                }
            });
        });
    });
}

function createLoader(text) {
    let loader = '<div class="loader"><div><div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div><p>' + text + '</p></div></div>';
    return loader
}

let setTime;
let timeCounter;

function sendSms(country, phone, loadingMessage, successFunc) {
    $.ajax({
        url: '/src/controller/member/send_sms_certified.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({'mb_country': country, 'mb_hp': phone}),
        success: function (data) {
            successFunc(data)
        }, beforeSend: function () {
            $('body').append(createLoader(loadingMessage));
        }, complete: function () {
            $('.loader').remove();
        }
    });
}

function authTimeCounter(setAuthTime) {
    let minute = (Math.floor(setTime / 60) < 10) ? '0' + Math.floor(setTime / 60) : Math.floor(setTime / 60);
    let second = (Math.floor(setTime % 60) < 10) ? '0' + Math.floor(setTime % 60) : Math.floor(setTime % 60);
    let time = minute + ":" + second;
    setTime--;
    if (setTime < 0) {
        clearInterval(timeCounter)
        setTime = setAuthTime;
    }
    if (minute == 0 && second == 0) {
        return false;
    } else {
        return time;
    }
}

function sendEmail(memberId, loadingMessage, successFunc) {
    loadingMessage = (loadingMessage === undefined) ? '' : loadingMessage;

    $.ajax({
        url: '/src/controller/member/send_email_certified.php?id=' + memberId,
        dataType: 'json',
        type: 'GET',
        success: function (data) {
            successFunc(data)
        }, error: function (a, b, c) {
            console.error(c)
        }, beforeSend: function () {
            if (loadingMessage) {
                $('body').append(createLoader(loadingMessage));
            }
        }, complete: function () {
            $('.loader').remove();
        }
    });
}

function tableSort(obj, type, number, step) {
    // sort 오름차순, 내림차순
    // addClass "active" 오름차순
    // removeClass "active" 내림차순
    number = (number == undefined) ? false : number;
    step = (step == undefined) ? 0 : step;
    $(obj).toggleClass('active');

    let sortType = false;
    if ($(obj).hasClass('active')) {
        sortType = true;
    }

    let tbody = $(obj).closest('table').find('tbody')[0];
    let tbodyLength = tbody.rows.length;
    let tbodyArr = new Array();

    for (let i = 0; i < tbodyLength; i++) {
        tbodyArr[i] = tbody.rows[i];
    }

    tbodyArr.sort(function (a, b) {
        let aText, bText = 0;
        if ($(obj).hasClass('double-td')) {
            aText = $(a).find('[data-type="' + type + '"]')[0].children[step].innerText;
            bText = $(b).find('[data-type="' + type + '"]')[0].children[step].innerText;
        } else {
            aText = $(a).find('[data-type="' + type + '"]')[0].innerText;
            bText = $(b).find('[data-type="' + type + '"]')[0].innerText;
        }
        if (number) {
            aText = aText.replace(/[^-0-9.]/g, '') * 1;
            bText = bText.replace(/[^-0-9.]/g, '') * 1;
        }

        if (sortType) {
            if (aText < bText) return -1;
            if (aText > bText) return 1;
            if (aText == bText) return 0;
        } else {
            if (aText < bText) return 1;
            if (aText > bText) return -1;
            if (aText == bText) return 0;
        }
    })

    let tbodyHtml = '';
    for (let i = 0; i < tbodyArr.length; i++) {
        tbodyHtml += $(tbodyArr[i])[0].outerHTML;
    }
    $(obj).closest('table').find('tbody').html(tbodyHtml)
}

function errorText(obj, margin, movePosition, time) {
    margin = (margin == undefined) ? $(obj).css('margin') : margin;
    movePosition = (movePosition == undefined) ? '1.5' : movePosition;
    time = (time == undefined) ? '1000' : time;
    let setPositionAnimate = setInterval(function () {
        time -= 200;
        if (time < 0) {
            clearInterval(setPositionAnimate);
        }
        movePosition *= -1;
        let position = (movePosition > 0) ? 'right' : 'left';
        $(obj)
            .css('font-weight', '500')
            .css('margin', margin)
            .css('margin-' + position, movePosition + 'px');
    }, 50)
}

function getParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function () {
    // 소수점 스크롤 제한
    $('input[type=number]').on('mousewheel.disableScroll', function (e) {
        e.preventDefault();
    });
    $('input[type=number]').on('keyup keydown', function (e) {
        if(e.keyCode == 40 || e.keyCode == 38)
            return false;
    });
})