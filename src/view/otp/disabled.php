<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/otp.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/otp/disabled.js"></script>');
if (empty($member)) {
    add_event('alert_hooks', 'not_login');
    return false;
}
if ($member['mb_level'] != 3) {
    alert(lang('OTP 인증을 진행해주세요.', 'Please proceed with OTP authentication.', 'OTP認証を実行してください。', '请进行OTP认证。'));
    move('/member/account/certification');
}
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/otp/m_disabled.php";
} else {
    // PC 일 경우
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/common.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/disabled.css?ver=' . time() . '">');
?>

<section class="login common-bg">
    <form class="login-box common-yellow-box" autocomplete="off" action="javascript:otpCheck()">
        <h1 class="common-title"><?= lang('OTP 비활성', 'OTP Inactive', 'OTP非活性', 'OTP非活性') ?></h1>
        <p class="common-title-desc"><?= lang('OTP 인증 사용을 중단합니다.<br>OTP를 비활성화할 경우 입출금 시 휴대폰 인증 번호를 사용하게 됩니다. 안전한 거래를 위해 OTP인증 사용을 권장합니다.',
                'Aborts using OTP authentication. <br>If you disable OTP, you will use your mobile phone authentication number for deposit and withdrawal. We recommend using OTP authentication for secure transactions.',
                'OTP認証の使用を停止します。<br> OTPを無効にする場合、入出金の時に電話の認証番号を使用します。 安全な取引のためにOTP認証の使用をお勧めします。',
                '停止使用OTP认证。<br>不激活OTP时存取款时使用手机认证号码。为确保交易安全,建议使用OTP认证。') ?></p>
        <p class="disabled-desc"><?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.', 'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.', 'OTP認証アプリに表示された認証番号6桁を入力してください。', '请输入OTP认证应用显示的6位数认证码。') ?></p>
        <div class="common-input-group">
            <input type="hidden" id="memberId" value="<?= $member['mb_id'] ?>">
            <label for="memberOtp"><?= lang('OTP 인증번호', 'OTP Authentication Number', 'OTP認証番号', 'OTP认证编号') ?></label>
            <input type="text" id="memberOtp" class="common-input" maxlength="6"
                   placeholder="<?= lang('인증번호 입력', 'Enter the authentication number', '認証番号入力', '输入验证码') ?>">
            <p class="alert-message"></p>
        </div>
        <button type="submit" class="btn btn-full btnCertified"
                disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?></button>
    </form>
</section>
<?php
}
?>