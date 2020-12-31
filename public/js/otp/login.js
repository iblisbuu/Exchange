$(function () {
    $('#memberPw').keyup(buttonActive).focus()
});

// 버튼 활성화
function buttonActive() {
    let type = $(this).attr('id');
    let value = $('#' + type).val();

    if (value == '' || $("#memberId").val() == '') {
        $(".alert-message").text('');
        $('button[type="submit"]').attr('disabled', 'disabled');
    } else {
        $('button[type="submit"]').attr('disabled', false);
    }
}

// 로그인
function login() {
    recaptcha(loginSub);
}

function loginSub() {
    let result;
    const myId = $("#myId").val().trim();
    const memberId = $("#memberId").val().trim();
    if (myId == memberId) {
        const memberPw = $("#memberPw").val().trim();
        if (memberId != "" && memberPw != "") {
            const param = {
                "mb_id": memberId,
                "mb_password": memberPw,
            }
            $.ajax({
                url: '/src/controller/member/login.php' + $(location).attr('search'),
                dataType: 'json',
                type: 'POST',
                async: false,
                data: JSON.stringify(param),
                success: function (data) {
                    if (data) {
                        const returnCode = data.resultCode * 1;
                        switch (returnCode) {
                            case 10 :
                                result = 11 // 성공 - OTP 인증
                                $('body').append(createLoader(lang('인증 메일을 전송중입니다.', 'Sending authentication mail.', '認証メールを送信中です。', '正在发送认证邮件。')));
                                setTimeout(function () {
                                    sendEmail(memberId, '', function (data) {
                                        $('.loader').remove();
                                        if (data.resultCode) {
                                            alert(lang("인증코드가 전송 되었습니다.", 'Authentication code has been sent.', '認証に失敗しました。', '验证码已发送。'));
                                            location.href = "/otp/emailcertified?auth=" + bin2hex(memberId);
                                        } else {
                                            alert(lang("인증코드 전송에 실패했습니다.", 'Authentication code transfer failed.', '認証コードの送信に失敗しました。', '验证码发送失败。'));
                                        }
                                    });
                                }, 500)
                                break;
                            default:
                                result = 22// 실패 -  아이디,비번 에러
                                if ($(".alert-message").text() != '') {
                                    errorText(".alert-message")
                                }
                                $(".alert-message").text('* ' + lang('계정정보가 일치하지 않습니다. 다시 확인해주세요.', 'Account information does not match. Please check again.',
                                    'アカウント情報が一致していません。もう一度確認してください。', '帐号信息不一致。 请重新确认一下。'))
                                break;
                        }
                    } else {
                        result = 20 // 실패
                        console.error("ERROR");
                    }
                }, error: function (a, b, c) {
                    result = 20 // 실패
                    console.error(c)
                }, complete: function () {
                    // 아이디가 존재하는 경우만 로그인 접근 기록
                    if (result !== "") {
                        accessRecord(memberId, result)
                    }
                }
            });
        } else {
            alert(lang("빈값을 입력 해 주세요.", 'Please enter an empty value.', '空き値を入力してください。', '请输入空额。'));
            return;
        }
    } else {
        $(".alert-message").text('* ' + lang('로그인한 계정정보와 일치하지 않습니다. 다시 확인해주세요.', 'It does not match the account information you logged in. Please check again.',
            'ログインしたアカウント情報と一致しません。 もう一度確認をお願いします。', '与登录账号信息不一致。 请重新确认一下。'))
    }
}


/**
 * 이메일로 인증코드 보내기
 */
function sendEmailCertified(memberId) {
    sendEmail(memberId, '', function (data) {
        if (data.resultCode) {
            alert(lang('전송', 'Success', '成功', '传输'))
        } else {
            alert(lang('실패', 'Failure', '失敗', '失败'))
        }
    });
    return true;
}

// 접근기록 저장
function accessRecord(mb_id, ac_result) {
    const resultParam = {
        "mb_id": mb_id,
        "ac_result": ac_result
    }
    $.ajax({
        url: '/src/controller/member/insert_access.php',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: JSON.stringify(resultParam),
        success: function (data) {
            if (data) {
                const returnCode = data.resultCode * 1;
                const returnMessage = data.resultMessage;

                switch (returnCode) {
                    case 10 :
                        return true;
                        break;
                    default:
                        break;
                }
            } else {
                console.error("ERROR");
            }
        }, error: function (a, b, c) {
            console.error(c)

        }
    });
}