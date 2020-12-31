/**
 * 회원가입
 */
// 비밀번호 유효성 체크:: 1 일 경우 모든 유효성 일치
let PASS_VALID;

$(function () {
    $(".sign-up-box input").keyup(joinValid);
    $(".sign-up-box input[type='checkbox']").change(joinValid, checkChange);
});

// 가입하기 BTN
function joinValid() {
    if ($("input#memberId").val() != '' && PASS_VALID && $("input#privacy").is(":checked") && $("input#terms").is(":checked")) {
        $(".sign-up-box button[type='submit']").addClass('btn-yellow').attr('disabled', false);
    } else {
        $(".sign-up-box button[type='submit']").removeClass('btn-yellow').attr('disabled', true);
    }
}

function checkChange() {
    const checkType = ($("input[name='checkbox']:checked").length == 3) ? true : false;
    $("#signAll").prop('checked', checkType);
    joinValid();
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

// 전체 동의하기
function agreeClickAll() {
    if ($("#signAll").is(":checked")) {
        $(".sign-agree-box input[type='checkbox']").prop('checked', true);
        // 모바일
        $('.sign-agree-box i').removeClass('none')
    } else {
        $(".sign-agree-box input[type='checkbox']").prop('checked', false);
        // 모바일
        $('.sign-agree-box i').addClass('none')
    }

    joinValid();
}

// 회원가입
function signUp() {
    const memberId = $("#memberId").val().trim();
    const memberPw = $("#memberPw").val().trim();
    const memberPwChk = $("#memberPwChk").val().trim();
    const memberMarketing = $("#marketing").is(":checked");
    const param = {
        "mb_id": memberId,
        "mb_password": memberPw,
        "mb_password_chk": memberPwChk,
        "mb_marketing": memberMarketing
    }
    $.ajax({
        url: '/src/controller/member/sign_up.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify(param),
        success: function (data) {
            if (data) {
                const returnCode = data.resultCode * 1;
                const returnMessage = data.resultMessage;
                switch (returnCode) {
                    case 10 :
                        location.href = "/member/emailcertified?auth=" + bin2hex(memberId);
                        break;
                    case 19:
                        if ($('.common-alert').length == 0) {
                            const html = '<p class="common-alert">' + lang('이미 가입된 이메일 입니다.', 'This email is already registered.', 'すでに登録されたメールです。', '已加入的邮件。') + '</p>';
                            $("#memberId").focus().after(html).addClass('alert');
                        } else {
                            $("#memberId").focus()
                        }
                        break;
                    default:
                        alert(lang("회원가입에 실패했습니다.", 'Failed to sign up for this member.', '会員登録に失敗しました。', '注册会员失败了。'))
                        break;
                }
            }
        }, error: function (a, b, c) {
            console.error(c)
        }, beforeSend: function () {
            $('body').append(createLoader(lang('회원가입 인증 메일을 전송중입니다.', 'I\'m sending you a membership confirmation mail.', '会員登録認証メールを送信中です。', '正在发送注册会员认证邮件。')));
        }, complete: function () {
            $('.loader').remove();
        }
    });
}

function openDesc(type, obj) {
    const country = $('body').attr('data-lang');
    const top = $(obj).offset().top;
    const left = $(obj).offset().left;
    $.ajax({
        url: '/src/controller/terms/getTerms.php?type=' + type,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.resultCode == 10) {
                const value = data.value[0]['te_content_' + country];
                let body = '<h1>';
                if (type == 'use') body += lang('이용약관 동의', 'Agreement on Terms of Use', '利用規約に同意', '使用条款同意');
                if (type == 'privacy') body += lang('개인정보 처리방침 동의', 'Agree on privacy policy', '個人情報の処理方針に同意', '同意个人信息处理方针');
                body += '<span>(' + lang('필수', 'Essential', '必須', '必須') + ')</span></h1>';
                body += ' <div class="mCustomScrollbar popup-terms-content" data-mcs-theme="dark">' + value + '</div>';
                openOkPopup(body, undefined, lang('닫기', 'Close', '閉じる', '关闭'), true);
                $(".mCustomScrollbar").mCustomScrollbar();
                $(".popup-ok").attr('style', 'position:fixed !important;');
                $(".popup-ok").css({'top':'50%', 'left':'50%', 'transform':'translate(-50%, -50%)', 'z-index':'600'});
                //$(".popup-ok").css('left', left).css('transform','translate(14%,-97%)');
            } else {
                console.log('[ERROR] ', data);
            }
        }, error: function (a, b, c) {
            console.log(c);
        }
    });
}