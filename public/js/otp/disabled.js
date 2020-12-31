$(function () {
    $("#memberOtp").on('keyup', function () {
        if ($(this).val() != '' &&$(this).val().length == 6) {
            $(".btnCertified").attr('disabled', false)
        } else {
            $(".btnCertified").attr('disabled', 'disabled')
        }
    })
});

function otpCheck() {
    let memberId = $("#memberId").val();
    let memberOtp = $("#memberOtp").val();
    OtpCheck(memberId, memberOtp, function (data) {
        if (data.resultCode == 10) {
            openDisabledPopup();
        } else {
            $(".alert-message").text('* ' + lang('인증번호가 일치하지 않습니다.', 'The authentication numbers do not match.', '認証番号が一致していません。', '验证码不一致。'));
        }
    })
}

function openDisabledPopup() {
    let header = lang('OTP 비활성화', 'Disable OTP', 'OTP非活性', 'OTP非活性化');
    let body = lang('OTP 인증을 비활성화 하시겠습니까?', 'Disable OTP authentication?', 'OTP認証を無効にしますか？', '确定不激活OTP认证吗?');
    let nextEvent = 'disabledOtp()';
    let closeEvent = "location.href='/member/account/certification'";
    let nextText = lang('예', 'OK', '確認', '例子');
    let closeText = lang('아니오', 'Cancel', 'キャンセル', '不');
    openPopup(header, body, nextEvent, closeEvent, nextText, closeText, true);
}

function disabledOtp() {
    // successDisabledPopup();
    let memberId = $("#memberId").val();
    let memberOtp = $("#memberOtp").val();
    OtpDisabled(memberId, memberOtp, function (data) {
        if (data.resultCode == 10) {
            successDisabledPopup();
        } else {
            alert(lang('다시 한번 시도해주세요.', 'Please try again.', 'もう一度試してください。', '再一次尝试一下。'));
        }
    })
}

function successDisabledPopup() {
    const html = '<div class="popup-box" style="padding: 40px 45px !important;">' +
        '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>' +
        '<div class="popup-head" style="border-bottom: 0; margin-bottom: 15px; padding: 15px 0 0;">' + lang('OTP 인증이 비활성화 되었습니다.', 'OTP authentication is disabled.', 'OTP認証が無効になりました。', 'OTP认证变得不活跃。') + '</div>' +
        '<div class="popup-content" style="word-break: keep-all;">'
        + lang('안전한 거래를 위해 OTP 인증 사용을 권장합니다.',
            'We recommend using OTP authentication for secure transactions.',
            '安全な取引のためにOTP認証の使用をお勧めします。',
            '为确保交易安全,建议使用OTP认证。') + '</div>' +
        '<div class="popup-btn">' +
        '<button type="button" class="closePopup" onclick="location.href=\'/member/account/certification\'">'
        + lang('닫기', 'Close', '閉じる', '关闭') + '</button>' +
        '</div>' +
        '</div>' +
        '<div class="popup-bg"></div>';
    openCustomPopup(html);
}