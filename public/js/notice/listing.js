function sendListing() {
    const ls_email = $("input[name=email]").val().trim();
    const ls_project_name = $("input[name=projectName]").val().trim();
    const ls_project_desc = $("input[name=projectDesc]").val().trim();
    const ls_corp = $("input[name=corp]").val().trim();
    const ls_token_name = $("input[name=tokenName]").val().trim();
    const ls_token_theme = $("input[name=tokenTheme]").val().trim();
    const ls_token_type = $("input[name=tokenType]").val().trim();
    const ls_website = $("input[name=website]").val().trim();
    const ls_whitepaper = $("input[name=whitePaper]").val().trim();
    const ls_contract = $("input[name=contract]").val().trim();
    const ls_sns = $("input[name=sns]").val().trim();
    const emailReg = /^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\-]+\.[A-Za-z0-9\-]+/;

    if (!ls_email || !ls_project_name || !ls_project_desc || !ls_corp || !ls_token_name || !ls_token_theme
        || !ls_token_type || !ls_website || !ls_whitepaper || !ls_contract || !ls_sns) {
        alert(lang('양식을 빠짐 없이 입력 해 주세요.', 'Please fill in all forms.', 'フォームをもれなく入力してください。', '请输入所有样式。'))
        return;
    }

    if (emailReg.test(ls_email) == false) {
        alert(lang('이메일 형식이 올바르지 않습니다.', 'Email format is invalid.', 'メールの形式が正しくありません。', '电子邮件形式不正确。'));
        $("input[name=email]").focus();
        return;
    }

    const param = {
        "ls_email": ls_email,
        "ls_project_name": ls_project_name,
        "ls_project_desc": ls_project_desc,
        "ls_corp": ls_corp,
        "ls_token_name": ls_token_name,
        "ls_token_theme": ls_token_theme,
        "ls_token_type": ls_token_type,
        "ls_website": ls_website,
        "ls_whitepaper": ls_whitepaper,
        "ls_contract": ls_contract,
        "ls_sns": ls_sns,
    }
    $.ajax({
        url: '/src/controller/board/insertListing.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify(param),
        success: function (data) {
            if (data.resultCode == 10) {
                let html = '<div class="popup-box">';
                html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
                html += '<div class="popup-content">';
                html += '<span style="font-size:18px; font-weight: 500; margin-bottom: 15px; display:' +
                    ' block;">' + lang('제출이 완료되었습니다.', 'Submission complete.', '提出が完了しました。', '提交完毕。') + '</span>';
                html += '<p>' + lang('담당자가 확인 후 개별 연락을 드리겠습니다.', 'The person in charge will check and contact' +
                    ' you individually.', '担当者が確認した後、個々の連絡をいたします。', '负责人确认后会个别联系您。') + '</p>';
                html += '</div>';
                html += '<div class="popup-btn">';
                html += '<button class="closePopup" onclick="javascript:location.href=\'/\'" style="width:' +
                    ' 122px; height: 46px; padding: 0">' + lang('닫기', 'Close', '閉じる', '关闭') + '</button>';
                html += '</div>';
                html += '</div>';
                html += '<div class="popup-bg"></div>';
                openCustomPopup(html)
            } else {
                // console.log(data.resultMessage);
            }
        }, error: function (a, b, c) {
            console.log(c);
        }, beforeSend: function () {
            $('body').append(createLoader(lang('제출중입니다.', 'I\'m submitting it.', '提出中です。', '正在提交中')));
        }, complete: function () {
            $('.loader').remove();
        }
    })
}
