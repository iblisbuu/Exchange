<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/otp.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/smscertified.js"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_loginotp.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/smsCertified.css?ver=' . time() . '">');
?>
<style>
    #main {
        height: 100%;
    }
</style>
<section class="sms-certified-box change-sms-box common-bg">
    <div class="common-yellow-box">
        <h1 class="common-title"> <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?></h1>
        <p class="sms-desc">
            <?= lang('보안 강화를 위해 설정된 OTP 인증 후 로그인 됩니다.',
                'Log in after OTP authentication set for enhanced security.',
                'セキュリティのためにOTP認証を行います。',
                '为强化安全设置的OTP认证后登录。') ?>
        </p>
        <span class="otp-desc"><?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.',
                'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.',
                'OTP認証アプリの認証番号を入力してください。',
                '请输入OTP认证应用显示的6位数认证码。') ?></span>
        <form id="otpLoginForm" class="common-input-group" autocomplete="off" action="javascript:loginOtp()">
            <input type="hidden" name="memberId" value="<?= $_SESSION['mb_id'] ?? '' ?>">
            <label for="otpNum"><?= lang('OTP 인증번호', 'Otp Authentication Number', 'OTP認証番号', 'OTP认证编号') ?></label>
            <div class="common-input">
                <input type="text" id="otpNum" maxlength="6"
                       placeholder="<?= lang('인증번호 입력', 'Enter the authentication number', '認証番号入力', '输入验证码') ?>">
            </div>
            <p class="common-alert"></p>
            <button type="submit" class="btn" id="btnCheckOTP"
                    disabled><?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证')
                ?></button>
        </form>
    </div>
</section>
<script>
    $(function () {
        $("#otpNum").focus();
    })
</script>
<?php
}
?>