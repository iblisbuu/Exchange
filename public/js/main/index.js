$(function () {
    headerType();
    $(window).scroll(function () {
        headerType();
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
});

function headerType() {
    let top = $(window).scrollTop();
    if (top < 200) {
        $("header").removeClass('default');
        $("header").addClass('start');
    } else {
        $("header").removeClass('start');
        $("header").addClass('default');
    }
}

// sort
function sort(obj, type) {
    // sort 오름차순, 내림차순
    // addClass "active" 오름차순
    // removeClass "active" 내림차순
    $(obj).toggleClass('active');

    let sortType = false;
    if ($(obj).hasClass('active')) {
        sortType = true;
    }

    const introBody = $(obj).closest('.intro-table').find('.intro-body')[0].children;
    const introBodyArr = Array.from(introBody);

    introBodyArr.sort(function (a, b) {
        let aText = $(a).find('[data-sort="' + type + '"]')[0].innerText;
        let bText = $(b).find('[data-sort="' + type + '"]')[0].innerText;
        if (type == 'price' || type == 'percent') {
            aText = aText.replace(/[^0-9]/g, '') * 1;
            bText = bText.replace(/[^0-9]/g, '') * 1;
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
    let introHtml = '';
    for (let i = 0; i < introBodyArr.length; i++) {
        introHtml += $(introBodyArr[i])[0].outerHTML;
    }
    $(obj).closest('.intro-table').find('.intro-body').html(introHtml)
}