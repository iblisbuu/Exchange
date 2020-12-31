$(function () {

    const deviceType = $("body").attr('data-device');
    if (deviceType == 'mobile' && $("#titleHeader").length) {
        $("header").html($("#titleHeader").html());
    }
    if (getCookie('notice')) {
        $('#top-notice').remove();
        if(deviceType == 'mobile')
            $('.banner-section').css('padding-top', 0)
    } else if($('#top-notice').index() != -1) {
        topNoticeLayout()
    }

    // 헤더 x scroll
    $(window).scroll(function (e) {
        let left = $(document).scrollLeft();
        $("header").css("left", -left);
    });

    $('.hd-sub-gnb.lang-ul > li > a').click(function () {
            clickLangChange($(this).attr('data-lang'))
        }
    );

    $('.gnb .lang-item').click(function () {
            clickLangChange($(this).attr('data-lang'))
        }
    );

    // 탑 공지사항 배너 닫기
    $('#top-notice button').click(function () {
        setCookie('notice', 'n', 1);
        topNoticeLayout();
    });

    // 모바일
    openLnb();

    $('.mb-menu').click(function () {
        if ($('.mb-menu').hasClass('close')) {
            closeGnb();
        } else {
            openGnb();
        }
        $('.mb-menu').toggleClass('close');
    })

    //Gnb 배경 클릭 시 Gnb 닫기
    $(".gnb-bg").click(function () {
        closeGnb();
    });

});

// 탑배너 존재시 body, header css 조절
function topNoticeLayout() {
    const deviceType = $("body").attr('data-device');
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function (el) {
        return el != ''
    });
    if (deviceType == 'pc') {
        // cookie(notice)값이 있으면 비노출
        const padding = (pathArray.length == 0) ? 0 : 70;
        const top = 56;
        const total = padding + top;
        if (getCookie('notice')) {
            $('body').animate({'padding-top': padding}, 500);
            $('header').animate({'top': 0}, 500);
            $('#top-notice').animate({'top': -top}, 500);
            setTimeout(function () {
                $('#top-notice').remove();
                $('body').css('padding-top', padding + 'px');
                $('header').css('top', '0');
            }, 500)
        } else {
            $('body').css('padding-top', total + 'px');
            $('header').css('top', top + 'px');
        }
    } else if (deviceType == 'mobile') {
        // cookie(notice)값이 있으면 비노출
        if (getCookie('notice')) {
            $('body').animate({'padding-top': 65}, 500);
            $('#top-notice').animate({'top': 0}, 500);
            $('#main').animate({'padding-top':0},500)
            setTimeout(function () {
                $('#top-notice').remove();
                $('body').css('padding-top', '65px');
            }, 500)
        } else {
            $('body').css('padding-top', '116px');
            $('#top-notice').css('top', '65px');
            $('#main').css('padding-top','65px');
        }
    }
}


function clickLangChange(this_val) {
    $.ajax({
        url: '/src/controller/common/lang_change.php',
        type: 'POST',
        data: {'lang': this_val},
        success: function (data) {
            location.reload();
        }, error: function (a, b, c) {
            console.log(c);
        }
    });

    return false;
}

/*모바일*/
function openGnb() {
    $(".gnb-div").removeClass('gnb-none');
}

function closeGnb() {
    $(".gnb-div").addClass('gnb-none');
}

function openLnb() {
    $(".gnb-list>ul>li>a").click(function () {
        $(this).toggleClass('active')
        $(this).closest('li').children('.mb-lnb').slideToggle();
    });
}