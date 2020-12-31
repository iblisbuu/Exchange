// 비밀번호 유효성 체크:: 1 일 경우 모든 유효성 일치
let PASS_VALID;

$(function () {
    $("#memberId").keyup(SendCodeBtnActive);
    $(".change-password-form input").keyup(changePasswordBtnActive);
});

// 인증 코드 발송 BTN 활성화
function SendCodeBtnActive() {
    const memberId = $("#memberId").val().trim();
    const memberOrgId = $("#memberOrgId").val().trim();
    if (memberId !== "") {
        $.ajax({
            url: '/src/controller/member/exist_member.php?id=' + memberId,
            dataType: 'json',
            type: 'GET',
            success: function (data) {
                if (data) {
                    const returnCode = data.resultCode * 1;
                    switch (returnCode) {
                        case 10 :
                            $('.common-alert').remove();
                            if ((memberId == memberOrgId && memberOrgId != '') || (memberOrgId == '' && memberId)) {
                                $('#sendCodeBtn').attr('disabled', false);
                            } else {
                                const html = '<p class="common-alert">' + lang('* 가입하신 이메일을 입력해주세요.', '* Please enter the email you signed up for.', '* 登録されたメールアドレスを入力してください。', '* 请输入您的邮箱。') + '</p>';
                                $("#memberId").after(html);
                            }
                            break;
                        case 14:
                            if ($('.common-alert').length == 0) {
                                const html = '<p class="common-alert">' + lang('* 존재하지 않는 계정입니다.', '* This account does not exist.', '* 存在しないアカウントです。', '* 不存在的账户。') + '</p>';
                                $("#memberId").after(html);
                            }
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
    } else {
        $('#sendCodeBtn').attr('disabled', true);
        $('.common-alert').remove();
    }

}


// 비밀번호 변경 BTN 활성화
function changePasswordBtnActive() {
    if ($("#authNum").val() != '' && PASS_VALID) {
        $("#changePwBtn").attr('disabled', false);
    } else {
        $("#changePwBtn").attr('disabled', true);
    }
}


// 비밀번호 유효성 체크
function passwordValidate(obj) {
    const inputType = $(obj).attr('id');
    const inputValue = $(obj).val();

    $(".common-alert-return").removeClass('none');
    // 모바일
    $('.common-alert-return>li').children('.xi-check').removeClass('valid');

    if (inputType == 'memberPw') {
        const smValid = /(?=.*[a-z])/g;
        const lgValid = /(?=.*[A-Z])/g;
        const spValid = /(?=.*[#?!@#$%^&*-])/g;
        const numValid = /(?=.*[0-9])/g;
        const lengthValid = /^(?=.{8,32}$).*/;
        (smValid.test(inputValue)) ? $('.validate__sm').addClass('valid') : $('.validate__sm').removeClass('valid');
        (lgValid.test(inputValue)) ? $('.validate__lg').addClass('valid') : $('.validate__lg').removeClass('valid');
        (spValid.test(inputValue)) ? $('.validate__sp').addClass('valid') : $('.validate__sp').removeClass('valid');
        (numValid.test(inputValue)) ? $('.validate__num').addClass('valid') : $('.validate__num').removeClass('valid');
        (lengthValid.test(inputValue)) ? $('.validate__length').addClass('valid') : $('.validate__length').removeClass('valid');
    }
    // 모바일
    $('.common-alert-return>li.valid').children('.xi-check').addClass('valid');

    const memberPw = $('#memberPw').val();
    const memberPwChk = $('#memberPwChk').val();
    (memberPw == memberPwChk && memberPw !== "" && memberPwChk !== "") ? $(".validate__confirm").addClass('valid') : $(".validate__confirm").removeClass('valid');

    if ($('.validate__sm').hasClass('valid') && $('.validate__lg').hasClass('valid') && $('.validate__sp').hasClass('valid') && $('.validate__num').hasClass('valid') && $('.validate__length').hasClass('valid') && $('.validate__confirm').hasClass('valid')) {
        PASS_VALID = 1;
    } else {
        PASS_VALID = 0;
    }

    
}

// 비밀번호 변경
function changePassword() {
    // 인증코드 맞는지 확인 후 변경
    checkEmailCertified(changePasswordSub)
}