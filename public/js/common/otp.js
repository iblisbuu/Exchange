// OTP Info 가져오기
function OtpGet(memberId, successFunc) {
    $.ajax({
        url: '/src/controller/otp/get_otp_secret.php?mb_id=' + memberId,
        dataType: 'json',
        type: 'GET',
        success: function (data) {
            successFunc(data)
        }, error: function (a, b, c) {
            console.warn(c);
        }
    })
}

function OtpCheck(memberId, memberOtp, successFunc) {
    $.ajax({
        url: '/src/controller/otp/check_otp.php?mb_id=' + memberId + '&mb_otp=' + memberOtp,
        dataType: 'json',
        type: 'GET',
        async: false,
        success: function (data) {
            successFunc(data)
        }, error: function (a, b, c) {
            console.warn(c);
        }
    })
}

function OtpDisabled(memberId,memberOtp,successFunc){
    $.ajax({
        url: '/src/controller/otp/disabled_otp.php?mb_id=' + memberId + '&mb_otp=' + memberOtp,
        dataType: 'json',
        type: 'DELETE',
        async: false,
        success: function (data) {
            successFunc(data)
        }, error: function (a, b, c) {
            console.warn(c);
        }
    })
}