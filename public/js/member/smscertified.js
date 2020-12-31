$(function () {
    $("#phoneNum").keyup(function (obj) {
        (obj.target['value'] != '') ? $("#btnSendSms").attr('disabled', false) : $("#btnSendSms").attr('disabled', true)
        // 인증코드 발송 후 휴대번호 변경
        if ($("#btnCertified").attr('disabled') != 'disabled') {
            $("#btnCertified").attr('disabled', true);
        }
    });

    $(".sms-option > li").on('click', function (obj) {
        const country = obj.target.closest('li')['dataset']['country'];
        const num = obj.target.closest('li')['dataset']['num'];
        $("#smsCountry>img").attr('src', '/public/img/common/lang-' + country + '.png');
        $(".sms-country-num").val(num);
    });

    $("#otpNum").keyup(function (obj) {
        (obj.target['value'] != '' &&obj.target['value'].length == 6) ? $("#btnCheckOTP").attr('disabled', false) : $("#btnCheckOTP").attr('disabled', true)
    });

})

// SMS 전송
function sendSMS() {
    const country = $(".sms-country-num").val();
    const phoneNum = $("#phoneNum").val();
    $('#phoneNum').attr('readonly',true);
    function successFunc(data) {
        if (data.resultCode == 10) {
            alert(lang('인증문자가 발송되었습니다.', 'Certified text has been sent.', '認証コードが送られた。','发送了认证短信。'));
            $("#btnCertified").attr('disabled', false);
            $("#certifiedSMS>.sms-alert-box>.common-alert").html('');
            let startTime = 179;
            setTime = startTime;
            let time;
            timeCounter = setInterval(function () {
                time = authTimeCounter(startTime)
                if (time) {
                    $(".auth-time>span").text(time);
                } else {
                    $(".auth-time>span").text('03:00');
                    $("#certifiedSMS>.sms-alert-box>.common-alert").html(lang('* 인증시간이 만료되었습니다. 인증코드를 재발송해주세요.',
                        '* The authentication time has expired. Please resend the authentication code.',
                        '* 認証時間が経過しました。認証コードを再送信してください。',
                        '* 认证时间已满。 请重新发送验证码。'));
                    $("#btnCertified").attr('disabled', true);
                }
            }, 1800);
        } else {
            alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。','请再试一次。'));
        }
    }

    sendSms(country, phoneNum, lang('인증문자를 전송중입니다.', 'We\'re sending you a certification text.', '認証コードを送信中です。','正在发送认证短信。'), successFunc);
}

// 인증 확인
function certifiedSMS(type) {
    type = (type == undefined) ? '' : type;
    const phoneNum = $("#phoneNum").val();
    const authNum = $("#authNum").val();

    $.ajax({
        url: '/src/controller/member/certified.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({'cf_type': 'phone', 'cf_id': phoneNum, 'cf_auth': authNum}),
        success: function (data) {
            const resultCode = data.resultCode;
            $("#certifiedSMS>.common-alert").html('');
            if (resultCode == 10) {
                updateMember(type)
            } else if (resultCode == 96) {
                $("#certifiedSMS>.sms-alert-box>.common-alert").html(lang('* 인증시간이 만료되었습니다. 인증코드를 재발송해주세요.', '* The authentication time has expired. Please resend the authentication code.', '* 認証時間が経過しました。認証コードを再送信してください。','* 认证时间已满。 请重新发送验证码。'));
            } else if (resultCode == 99) {
                $("#certifiedSMS>.sms-alert-box>.common-alert").html(lang('* 인증번호가 일치하지 않습니다.',
                    '* The authentication number does not match.', '* 認証番号が一致していません。','* 认证编号不一致。'));
            } else {
                alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。','请再试一次。'))
            }
        }, error: function (a, b, c) {
            console.error('SMS 인증 => ', c);
        }
    });
}

// 확인 후 계정 업데이트
function updateMember(type) {
    const mb_id = $("#mb_id").val();
    const mb_hp = $("#phoneNum").val();
    const mb_level = type == 'changeSms' ? 3 : 2;
    const mb_country = $(".sms-country-num").val();
    $.ajax({
        url: '/src/controller/member/change_member_info.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({'mb_id': mb_id, 'mb_hp': mb_hp, 'mb_level': mb_level, 'mb_country': mb_country}),
        success: function (data) {
            const resultCode = data.resultCode;
            if (resultCode == 10) {
                alert(lang('SMS 인증이 완료되었습니다.', 'SMS authentication is complete.', 'SMS認証が完了しました。','SMS认证已完成。'));
                location.href = '/member/account/certification';
            } else {
                alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。','请再试一次。'))
            }
        }, error: function (a, b, c) {
            console.error('회원정보 업데이트 => ', c);
        }
    });
}

function changeSmsOtp() {
    let memberId = $("input[name=memberId]").val();
    let memberOTP = $("#otpNum").val();
    OtpCheck(memberId, memberOTP, function (data) {
        if (data.resultCode == 10) {
            alert(lang('인증되었습니다.', 'authenticated.', '認証されました。','已认证。'));
            location.href = '/member/smscertified';
        } else {
            $("#changeSms .common-alert").html(lang('* 인증번호가 일치하지 않습니다.', '* The authentication' +
                ' number does not match.', '* 認証番号が一致していません。','* 认证编号不一致。'));
        }
    })
}