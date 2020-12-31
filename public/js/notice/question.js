let files = [];
$(function () {
    const deviceType = $("body").attr('data-device');
    if (deviceType == 'pc') {
        $('#queContent').summernote({
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
                ['insert', ['link', 'picture']]
            ]
        });
    }
    
    $("#queFile").on('change', function () {
        const fileInput = document.getElementById("queFile");
        const fileList = fileInput.files;
        if (fileList.length > 5) {
            alert(lang('첨부파일은 5개까지 첨부 가능합니다.', 'You can attach up to 5 attached files.', '添付ファイルは5個まで添付可能です。', '附件可以加到5个。'));
            return;
        }
        files = Array.from(fileList)
        changeFiles();
    });
});

function removeFile(obj) {
    let fileName = $(obj).closest('li').attr('data-name');
    files = files.filter(function (item, i) {
        return fileName != item.name
    })
    changeFiles();
}

function changeFiles() {
    let html = '';
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > (3 * 1024 * 1024)) {
            alert(lang('파일 사이즈가 3mb를 넘습니다.', 'The file size is over 3mb.', 'ファイルサイズが3mbを超えます。', '文件尺寸超过3mb。'));
            files = files.filter(function (item, index) {
                return index != i
            })
            continue;
        }
        var reg = /(.*?)\/(jpg|jpeg|png|pdf)$/;
        const fileType = files[i].type;
        if (fileType.match(reg) == null) {
            alert(lang('이미지 파일과 pdf 파일만 첨부가능합니다.', 'Only image files and pdf files can be attached.', '画像ファイルとpdf ファイルのみ添付可能です。', '仅能附上图片文件和pdf文件。'));
            files = files.filter(function (item, index) {
                return index != i
            })
            continue;
        }
        const name = files[i].name;
        html += '<li class="file-name" ' +
            'data-id="' + i + '"' +
            'data-name="' + name + '">' + name + '' +
            '<button type="button" onclick="removeFile(this)"></button>' +
            '</li>';
    }
    $(".file-name-box").html(html);
}

function sendQuestion() {
    const email = $("#queEmail").val();
    const title = $("#queTitle").val();
    const content = $("#queContent").val();
    const text = content.replace(/<p>/gi, '');
    const plainText = $("<div />").html(text).text();
    const emailReg = /^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\-]+\.[A-Za-z0-9\-]+/;

    // 빈 값 체크
    if (email == '' || title == '' || plainText.trim() == '') {
        alert(lang('이메일 주소와 제목, 내용을 필수로 작성해주세요.', 'Please fill out the email address, title, and contents as' +
            ' required.', 'メールアドレスと件名、内容を必須にお書きください。', '请填写电子邮件地址,题目和内容。'));
        return;
    }
    if (emailReg.test(email) == false) {
        alert(lang('이메일 형식이 올바르지 않습니다.', 'Email format is invalid.', 'メールの形式が正しくありません。', '电子邮件形式不正确。'));
        $("#queEmail").focus();
        return;
    }

    let data = new FormData();
    data.append('email', email);
    data.append('title', title);
    data.append('content', content);

    // 파일 체크
    if (files.length > 0) {
        $.each(files, function (key, value) {
            data.append(key, value)
        })
    }

    $.ajax({
        url: '/src/controller/question/insertQuestion.php',
        contentType: false,
        processData: false,
        enctype: "multipart/form-data",
        type: "POST",
        data: data,
        success: function (data) {
            if (data.resultCode == 10) {
                let html = '<div class="popup-box popup-ok" style="width: 375px;">' +
                    '<span class="closeBtn" onclick="closePopup()">' +
                    '<i class="xi-close-thin"></i>' +
                    '</span>' +
                    '<div style="margin-bottom: 24px;">' +
                    '<p style="text-align: center; font-size: 18px; font-weight: 500; color: #0a0a0a; margin-bottom:' +
                    ' 15px;">' + lang('1:1 문의가 접수되었습니다', 'A 1:1 inquiry has been received.', '1：1お問い合わせは受信されました。', '1:1咨询已收到。') + '</p>' +
                    '<p style="font-weight: normal; font-size: 14px; color: #0a0a0a; text-align: center;">' + lang('담당자가 확인 후<br>접수하신 이메일로 답변을 드릴 예정입니다.', 'The person in charge will check<br>and respond to the email you received.', '担当者が確認した後<br>受付されたメールでお答えいたします。', '负责人确认后,将通过<br>接收的电子邮件进行回复。') + '</p>' +
                    '</div>' +
                    '<div class="popup-btn">' +
                    '<button type="button" class="closePopup" onclick="location.href=\'/notice/customer/question\'" style="width: 100%;">' +
                    lang('닫기', 'Close', '閉じる', '关闭') +
                    '</button>' +
                    '</div>' +
                    '</div><div class="popup-bg"></div>';
                openCustomPopup(html);
            } else {
                alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。', '请再试一次。'));
            }
        }, error: function (a, b, c) {
            console.log(a, b, c);
        }, beforeSend: function () {
            $('body').append(createLoader(lang('담당자에게 접수중입니다.', 'I\'m receiving it from the person in charge.', '担当者に受付中です。', '负责人正在受理中。')));
        }, complete: function () {
            $('.loader').remove();
        }
    })
}