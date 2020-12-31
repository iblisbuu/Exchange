function login() {
    recaptcha(loginSub);
}

function loginSub() {
    let result = 0;
    const memberId = $("#memberId").val().trim();
    const memberPw = $("#memberPw").val().trim();
    if (memberId != "" && memberPw != "") {
        const param = {
            "mb_id": memberId,
            "mb_password": memberPw
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
                    const returnMessage = data.resultMessage;
                    switch (returnCode) {
                        case 10 :
                            let url;
                            // 이메일 인증
                            if (data.value.level == 3) {
                                location.href = "/member/loginotp?&auth=" + bin2hex(memberId) + "&url=" + data.value.url
                            } else {

                                $('body').append(createLoader(lang('인증 메일을 전송중입니다.', 'Sending authentication mail.', '認証メールを送信中です。', '正在发送认证邮件。')));
                                setTimeout(function () {
                                    sendEmail(memberId, '', function (mailResult) {
                                        $('.loader').remove();
                                        if (mailResult.resultCode) {
                                            alert(lang("인증코드가 전송 되었습니다.", 'Authentication code has been sent.', '認証に失敗しました。', '验证码已发送。'));
                                            location.href = "/member/emailcertified?auth=" + bin2hex(memberId) + "&url=" + data.value.url + "&lv=" + data.value.level;
                                        } else {
                                            alert(lang("인증코드 전송에 실패했습니다.", 'Authentication code transfer failed.', '認証コードの送信に失敗しました。', '验证码发送失败。'));
                                        }
                                    });
                                }, 500)
                            }
                            result = 10 // 성공
                            break;

                        case 97 :
                            location.href = "/member/emailcertified?auth=" + bin2hex(memberId) + "&lv=0";
                            result = 10 // 성공
                            break;
                        case 14 :
                            result = 22 // 실패 -  아이디,비번 에러
                            alert(lang("아이디나 비밀번호를 확인해주세요.", 'Please check your ID or password.', 'IDやパスワードを確認してください。', '请确认账号或密码。'))
                            break;
                        default:
                            result = 22 // 실패 -  아이디,비번 에러
                            alert(lang("아이디나 비밀번호를 확인해주세요.", 'Please check your ID or password.', 'IDやパスワードを確認してください。', '아이디나 비밀번호를 확인해주세요.'))
                            // console.error(returnMessage);
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
                if (result !== 0) {
                    accessRecord(memberId, result)
                }
            }
        });
    } else {
        alert(lang("빈값을 입력 해 주세요.", 'Please enter an empty value.', '空き値を入力してください。', '请输入空额。'));
        return;
    }
}

/**
 *   이메일 인증코드 확인 ** 이메일 확인 성공후 실행할 함수를 파라미터로 전달
 */
function checkEmailCertified(successFunc, mailType) {
    let result = 0;
    mailType = (mailType == undefined) ? '' : mailType;
    let otpLevel = getParameter('lv') == "3" ? true : false

    const memberId = $("#memberId").val().trim();
    const authNum = $("#authNum").val().trim();
    if (memberId != "" && authNum != "") {
        const param = {
            "cf_type": "mail",
            "cf_id": memberId,
            "cf_auth": authNum,
        }
        if (mailType) {
            param['mail_type'] = mailType;
        }
        if (otpLevel) {
            param['otp_level'] = otpLevel;
        }

        $.ajax({
            url: '/src/controller/member/certified.php',
            dataType: 'json',
            type: 'POST',
            data: JSON.stringify(param),
            success: function (data) {
                if (data) {
                    const returnCode = data.resultCode * 1;
                    const returnMessage = data.resultMessage;
                    switch (returnCode) {
                        case 10 :
                            result = 12 // 성공 - 이메일 인증
                            if (successFunc == changePasswordSub) {
                                result = 13 // 성공 - 비밀번호 변경
                            }
                            successFunc()
                            break;
                        case 96 :
                            result = 24 // 실패 - 이메일 인증 코드 만료
                            if (successFunc == changePasswordSub) {
                                result = 25 // 실패 - 비밀번호 변경
                            }
                            if (mailType == 'join') {
                                if ($(".common-alert").text()) {
                                    errorText('.common-alert')
                                }
                                $(".common-alert").html(lang('* 인증코드가 만료되었습니다.', '* Your authentication code has expired.', '* 認証コードが満了しました。', '* 认证代码到期。'));
                            } else {
                                alert(lang("인증코드가 만료되었습니다.", 'Your authentication code has expired.', '認証コードが満了しました。', '认证代码已到期。'))
                            }
                            break;
                        default:
                            result = 23 // 실패 - 이메일 인증
                            if (successFunc == changePasswordSub) {
                                result = 25 // 실패 - 비밀번호 변경
                            }
                            if (mailType == 'join') {
                                if ($(".common-alert").text()) {
                                    errorText('.common-alert')
                                }
                                $(".common-alert").html(lang("* 인증에 실패했습니다.", '* Authentication failed.', '* 認証に失敗しました。Q', '* 认证失败了。'));
                            } else {
                                alert(lang("인증에 실패했습니다.", 'Authentication failed.', '認証に失敗しました。', '认证失败了。'))
                            }
                            break;
                    }

                } else {
                    console.error("ERROR");
                }
            }, error: function (a, b, c) {
                console.error(c)
            }, complete: function () {
                // 아이디가 존재하는 경우만 로그인 접근 기록
                if (result !== 0) {
                    accessRecord(memberId, result)
                }
            }
        });
    } else {
        if (mailType != 'join') {
            alert(lang("인증 번호를 입력 해 주세요.", 'Please enter the authentication number.', '認証番号を入力してください。', '请输入验证码。'));
        }
        return;
    }
}

/**
 * 이메일로 인증코드 다시 보내기
 */
function sendEmailCertified(type) {
    type = (type == undefined) ? undefined : type;
    const memberId = $("#memberId").val().trim();
    if (memberId != "") {
        function successFunc(data) {
            if (data) {
                const returnCode = data.resultCode * 1;
                switch (returnCode) {
                    case 10 :
                        alert(lang("인증코드가 전송 되었습니다.", 'Authentication code has been sent.', '認証に失敗しました。', '验证码已发送。'));
                        if (type == 'resend') {
                            location.reload();
                        } else {
                            emailTimeCounter(memberId);
                        }
                        break;
                    default:
                        alert(lang("인증코드 전송에 실패했습니다.", 'Authentication code transfer failed.', '認証コードの送信に失敗しました。', '验证码发送失败。'))
                        break;
                }
            } else {
                console.error("ERROR");
            }
        }

        sendEmail(memberId, lang('인증 메일을 전송중입니다.', 'Sending authentication mail.', '認証メールを送信中です。', '正在发送认证邮件。'), successFunc);
    } else {
        alert(lang("다시 시도 해 주세요.", 'Please try again.', 'やり直してください。', '请再试一次。'));
        return;
    }
}

function emailTimeCounter(cf_id) {
    $.ajax({
        url: '/src/controller/member/validCertified.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify(
            {'cf_type': 'mail', 'cf_id': cf_id}
        ),
        success: function (data) {
            if (data.resultCode == 10) {
                let startTime = 1800 - ((Math.floor(+new Date() / 1000) * 1) - data.value.cf_datetime);
                if (startTime > 0) {
                    setTime = startTime;
                    let time;
                    timeCounter = setInterval(function () {
                        time = authTimeCounter(startTime);
                        if (time) {
                            $(".auth-time>span").text(time);
                        } else {
                            $(".auth-time>span").text('30:00');
                            $(".common-alert").text(lang('* 인증시간이 만료되었습니다. 인증코드를 재발송해주세요.', '* The authentication time has expired. Please resend the authentication code.', '* 認証時間が経過しました。認証コードを再送信してください。'));
                            $("#btnCertified").attr('disabled', true);
                        }
                    }, 1000);
                } else {
                    $(".auth-time>span").text('30:00');
                    $("#btnCertified").attr('disabled', true);
                }
            } else {
                console.log(data.resultMessage);
            }
        }, error: function (a, b, c) {
            console.log(c);
        }
    })
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
                        // console.error(returnMessage);
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

// OTP 로그인
function loginOtp() {
    let memberId = hex2bin(getParameter('auth'));
    let memberOTP = $("#otpNum").val();
    let result = 0;
    OtpCheck(memberId, memberOTP, function (data) {
        if (data.resultCode == 10) {
            //alert(lang('인증되었습니다.', 'authenticated.', '認証されました。', '已认证。'));
            $.ajax({
                url: '/src/controller/member/session_login.php',
                dataType: 'json',
                type: 'POST',
                async: false,
                data: JSON.stringify({'mb_id': memberId}),

                success: function (data) {
                    if (data) {
                        const returnCode = data.resultCode * 1;
                        const returnMessage = data.resultMessage;
                        switch (returnCode) {
                            case 10 :
                                result = 11;
                                location.href = getParameter('url');
                                break;
                            default:
                                result = 22;
                                alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.', 'An error has occurred.\n' +
                                    'Please try again in a moment.', 'エラーが発生しました。\n' +
                                    'しばらくして、もう一度やり直してください。',
                                    '发生错误。\n' +
                                    '请稍后再试。'))
                                // console.error(returnMessage);
                                break;
                        }
                    } else {
                        result = 22;
                        console.error("ERROR");
                        alert(lang('오류가 발생하였습니다.\n잠시 후 다시 시도해주세요.', 'An error has occurred.\n' +
                            'Please try again in a moment.', 'エラーが発生しました。\n' +
                            'しばらくして、もう一度やり直してください。',
                            '发生错误。\n' +
                            '请稍后再试。'))
                    }

                }, error: function (a, b, c) {
                    console.warn(c);
                }, complete: function () {
                    // 아이디가 존재하는 경우만 로그인 접근 기록
                    if (result !== 0) {
                        accessRecord(memberId, result)
                    }
                }
            })
        } else {
            if ($("#otpLoginForm .common-alert").text()) {
                errorText("#otpLoginForm .common-alert");
            }
            $("#otpLoginForm .common-alert").html(lang('* 인증번호가 일치하지 않습니다.', '* The authentication' +
                ' number does not match.', '* 認証番号が一致していません。', '* 认证编号不一致。'));
        }
    })
}

function changePasswordSub() {
    const memberId = $('#memberId').val().trim();
    const memberPw = $('#memberPw').val().trim();
    const memberPwChk = $('#memberPwChk').val().trim();
    const param = {
        "mb_id": memberId,
        "mb_password": memberPw,
        "mb_password_chk": memberPwChk
    }
    $.ajax({
        url: '/src/controller/member/change_password.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify(param),
        success: function (data) {
            if (data) {
                const returnCode = data.resultCode * 1;
                const returnMessage = data.resultMessage;
                switch (returnCode) {
                    case 10 :
                        alert(lang("비밀번호가 변경 되었습니다.", 'Your password has been changed.', 'パスワードが変更されました。', '密码已变更。'))
                        location.href = data.value['move']
                        break;
                    default:
                        alert(lang("다시 시도 해주세요.", 'Please try again.', 'やり直してください。', '请再试一次。'))
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