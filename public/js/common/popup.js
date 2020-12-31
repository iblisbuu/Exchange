function openPopup(header, body, nextEvent, closeEvent, nextText, closeText, bg) {
    closeEvent = (closeEvent === undefined) ? 'closePopup()' : closeEvent;
    nextText = (nextText === undefined) ? lang('확인', 'OK', '確認','确认') : nextText;
    closeText = (closeText === undefined) ? lang('취소', 'Cancel', 'キャンセル','取消') : closeText;
    bg = (bg === undefined) ? false : bg;

    $(".popup-box,.popup-bg").remove();
    let html = '<div class="popup-box">';
    html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
    if (header) {
        html += '<div class="popup-head">' + header + '</div>';
    }
    html += '<div class="popup-content">' + body + '</div>';
    html += '<div class="popup-btn">';
    html += '<button type="button" class="closePopup" onclick="' + closeEvent + '">' + closeText + '</button>';
    html += '<button type="button" class="nextPopup" onclick="' + nextEvent + '">' + nextText + '</button>';
    html += '</div>';
    html += '</div>';
    if (bg) {
        html += '<div class="popup-bg"></div>';
    }
    $('body').append(html);
    //$('.popup-box').css("top", Math.max(0, (($(window).height() - $('.popup-box').outerHeight()) / 2) + $(window).scrollTop()) + "px");
    //$('.popup-box').css("left", Math.max(0, (($(window).width() - $('.popup-box').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

function openMobilePopup(header, body, nextEvent, closeEvent, nextText, closeText, bg) {
    closeEvent = (closeEvent === undefined) ? 'closePopup()' : closeEvent;
    nextText = (nextText === undefined) ? lang('확인', 'OK', '確認','确认') : nextText;
    closeText = (closeText === undefined) ? lang('취소', 'Cancel', 'キャンセル','取消') : closeText;
    bg = (bg === undefined) ? false : bg;

    $(".popup-box,.popup-bg").remove();
    let html = '<div class="popup-box c2c-mobile">';
    html += '<span class="closeBtn" onclick="closePopup()"><img src="/public/img/c2c/close.svg"></span>';
    if (header) {
        html += '<div class="popup-head">' + header + '</div>';
    }
    html += '<div class="popup-content">' + body + '</div>';
    html += '<div class="popup-btn">';
    html += '<button type="button" class="closePopup" onclick="' + closeEvent + '">' + closeText + '</button>';
    html += '<button type="button" class="nextPopup" onclick="' + nextEvent + '">' + nextText + '</button>';
    html += '</div>';
    html += '</div>';
    if (bg) {
        html += '<div class="popup-bg"></div>';
    }
    $('body').append(html);
    //$('.popup-box').css("top", Math.max(0, (($(window).height() - $('.popup-box').outerHeight()) / 2) + $(window).scrollTop()) + "px");
    //$('.popup-box').css("left", Math.max(0, (($(window).width() - $('.popup-box').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

function openOkPopup(body, nextMethod, nextText, bg) {
    nextMethod = (nextMethod === undefined) ? "closePopup()" : nextMethod;
    nextText = (nextText === undefined) ? lang('확인', 'OK', '確認','确认') : nextText;
    bg = (bg === undefined) ? false : bg;

    $(".popup-box,.popup-bg").remove();
    let html = '<div class="popup-box popup-ok">';
    html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
    html += '<div class="popup-head">' + body + '</div>';
    html += '<div class="popup-btn">';
    html += '<button type="button" class="closePopup" onclick=' + nextMethod + '>' + nextText + '</button>';
    html += '</div>';
    html += '</div>';
    if (bg) {
        html += '<div class="popup-bg"></div>';
    }
    $('body').append(html);
    //$('.popup-box').css("top", Math.max(0, (($(window).height() - $('.popup-box').outerHeight()) / 2) + $(window).scrollTop()) + "px");
    //$('.popup-box').css("left", Math.max(0, (($(window).width() - $('.popup-box').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

function openInfoPopup(obj) {
    let img = $(obj).closest('.info').find('[data-type="symbol_url"]').text();
    let name = $(obj).closest('.info').find('[data-type="coin_name"]').text();
    let symbol = $(obj).closest('.info').find('[data-type="symbol"]').text();
    let homePage = $(obj).closest('.info').find('[data-type="url"]').text();
    let book = $(obj).closest('.info').find('[data-type="book"]').text();
    let start = $(obj).closest('.info').find('[data-type="start"]').text();
    let count = $(obj).closest('.info').find('[data-type="count"]').text();
    let info = $(obj).closest('.info').find('[data-type="info"]').text();
    $(".popup-box,.popup-bg").remove();
    // 코인 이미지, 홈페이지, 백서, 최초발행, 발행량, 코인소개
    let html = '<div class="popup-box popup-info">' +
        '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>' +
        '<div class="popup-head">' +
        '<div class="ph-title">' +
        '<img src="' + img + '">' +
        '<p>' + name + '(' + symbol + ')</p>' +
        '</div>' +
        '<div class="ph-link">' +
        '<a href="' + homePage + '" target="_blank">' + lang('홈페이지', 'Homepage', 'ホームページ','网页') + '</a>' +
        '<a href="' + book + '" target="_blank">' + lang('백서', 'White Paper', '白書','白皮书') + '</a>' +
        '</div>' +
        '</div>' +
        '<div class="popup-content">' +
        '<p class="pc-title">' + lang('코인 정보', 'Coin Information', 'コイン情報','硬币信息') + '</p>' +
        '<p><em>' + lang('최초발행', 'Initial issue', '初発','首次发行') + '</em><span>' + start + '</span></p>' +
        '<p><em>' + lang('발행량', 'Issued volume', '発行量','发行量') + '</em><span>' + count + '</span></p>' +
        '<p class="pc-title" style="margin-top:10px;">' + lang('코인 소개', 'About Coin', 'コイン紹介','硬币介绍') + '</p>' +
        '<div class="pc-info">' + info + '</div>' +
        '</div>' +
        '</div>';
    $('.info').append(html);
    //$('.popup-box').css("top", Math.max(0, (($(window).height() - $('.popup-box').outerHeight()) / 2) + $(window).scrollTop()) + "px");
    //$('.popup-box').css("left", Math.max(0, (($(window).width() - $('.popup-box').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

function closePopup() {
    $(".popup-box,.popup-bg").remove();
}

function openCustomPopup(html, location) {
    location = (location === undefined) ? 'body' : location;

    $(".popup-box,.popup-bg").remove();
    $(location).append(html);
}
