$(function () {
    $('.banner-section').tw_slide({
        mode: 'basic',
        loop: false,
        auto: false,
        pause: ".pause-div",
        speed: 1000,
        time: 5500,
        navi: true,
        control: true
    });

    // NEWS
    // $.ajax({
    //     url: '/src/controller/board/getNewsList.php',
    //     type: 'GET',
    //     dataType: 'json',
    //     success: function (data) {
    //         let resultCode = data.resultCode;
    //         let resultValue = data.value;
    //         if (resultCode == 10) {
    //             let html = '';
    //             for (let i = 0; i < resultValue.length; i++) {
    //                 if (i == 4) {
    //                     break;
    //                 }
    //                 // NEWS 타입
    //                 let type = resultValue[i]['nw_type'];
    //                 switch (type) {
    //                     case 'notice':
    //                         type = lang('공지', 'notice', 'お知らせ');
    //                         break;
    //                     case 'event':
    //                         type = lang('이벤트', 'event', 'イベント');
    //                         break;
    //                     case 'listing':
    //                         type = lang('상장', 'Listing', '上場');
    //                         break;
    //                 }
    //                 // NEWS 시간
    //                 let time = (((resultValue[i]['nw_datetime']).split(' '))[0]).replace(/-/gi, '.');
    //                 let country = $('body').attr('data-lang');
    //                 let title = resultValue[i]['nw_title_' + country];
    //                 html += '<tr>' +
    //                     '<td><span class="news-type">' + type + '</span><a href="#">' + title + '</a></td>' +
    //                     '<td class="news-date">' + time + '</td>' +
    //                     '</tr>';
    //             }
    //             $(".news-table").append(html);
    //         } else {
    //             console.error('NEWS ERROR', data.resultMessage);
    //         }
    //     }
    // })

});