$(function () {
    let email = getParameter('auth');
    email = hex2bin(email);
    $('#memberId').val(email)
    emailTimeCounter(email);

    $("#authNum").on('keyup', function (obj) {
        if (obj.target['value'] != '' && obj.target['value'].length == 6) {
            $(".email-certified-box button[type='submit']").addClass('btn-yellow').attr('disabled', false);
        } else {
            $(".email-certified-box button[type='submit']").removeClass('btn-yellow').attr('disabled', true);
        }
    }).focus();
});