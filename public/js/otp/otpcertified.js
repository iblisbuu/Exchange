$(function () {
    getOtpInfo();

    $("#memberOTP").on('keyup', function () {
        if ($("#memberOTP").val() !== '' && $("#memberOTP").val().length == 6 ) {
            $("button[type='submit']").attr('disabled', false)
        } else {
            $("button[type='submit']").attr('disabled', 'disabled')
        }
    });
});

// OTP Secret 가져오기
function getOtpInfo() {
    OtpGet($("#memberId").val(), function (data) {
        let resultCode = data.resultCode;
        if (resultCode == 10) {
            let otpType = data.value['type'];
            if (otpType == 'already') {
                alert(lang('이미 인증되었습니다.', 'Already authenticated.', '既に認証されました。', '已经认证了。'));
                location.href = '/member/account/certification';
            } else if (otpType == 'new') {
                let otpInfo = data.value;

                $(".otp-loading").append('<img id="otpQrCode" src="' + otpInfo['qrcode'] + '"/>')
                $(".otp-loading #otpQrCode").on('load', function () {
                    $("#otpLoading").remove();
                })
                $("#otpSecret").val(otpInfo['secret']);
            }
        } else {
            alert(lang('다시 시도해주세요.', 'Please try again.', 'やり直してください。', '请再试一次。'));
        }
    })
}

// OTP 신규 등록
function checkOtp() {
    let memberId = $("#memberId").val();
    let memberOTP = $("#memberOTP").val();

    OtpCheck(memberId, memberOTP, function (data) {
        if (data.resultCode == 10) {
            alert(lang('인증되었습니다.', 'authenticated.', '認証されました。', '已认证。'));
            location.href = '/member/account/certification';
        } else {
            if($('.alert-message').text()){
                errorText('.alert-message')
            }
            $(".alert-message").text('* ' + lang('인증번호가 일치하지 않습니다.', 'The authentication numbers do not match.', '認証番号が一致していません。', '验证码不一致。'));
        }
    })
}