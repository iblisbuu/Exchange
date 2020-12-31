$(function () {
    $('#nwContent').summernote({
        tabsize: 2,
        disableResizeEditor: true,
        height: 345,
        width: '100%',
        toolbar: [
            ['fontsize',['fontsize']],
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ]
    });
});

function saveNotice() {
    const topfix = $("[name='topfix']:checked").val(); // 상단고정
    const category = $("[name='category']:checked").val(); // 카테고리
    const title = $("#nwTitle").val(); // 제목
    const content = $("#nwContent").val().replace(/'/gi, "\\'").replace(/"/gi, "\""); // 내용
    const language = $("[name='language']:checked").val(); // 언어

    // 빈 값 체크
    if (title == '' || content == '') {
        alert(lang('제목과 내용을 필수로 작성해주세요.', 'Please fill out the title and contents as required.', 'タイトルと内容を必須で作成してください。','请一定要写好题目和内容。'));
        return;
    }

    let data = {};
    data['nw_topfix'] = topfix;
    data['nw_type'] = category;
    data['nw_title_' + language] = title;
    data['nw_content_' + language] = content;
    data['mb_id'] = $("#mb_id").val();

    $.ajax({
        url: '/src/controller/board/insertNews.php',
        type: "POST",
        data: JSON.stringify(data),
        dataType: 'json',
        success: function (data) {
            if (data.resultCode == 10) {
                if (confirm(lang('공지사항이 등록되었습니다. 목록으로 돌아가시겠습니까?', 'Notice has been registered. Would you like to' +
                    ' return to the list?', 'お知らせ事項が登録されました。 リストにお戻りになりますか。','公告事项已登记。 能回到目录吗?'))) {
                    location.href = '/notice/customer/support';
                } else {
                    location.reload();
                }
            } else {
                alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。','请再试一次。'));
            }
        }, error: function (a, b, c) {
            console.log(a, b, c);
        }, beforeSend: function () {
            $('body').append(createLoader(lang('공지사항을 등록중입니다.', 'Registering notice.', 'お知らせ事項を登録中です。','公告事项正在登记中。')));
        }, complete: function () {
            $('.loader').remove();
        }
    })
}