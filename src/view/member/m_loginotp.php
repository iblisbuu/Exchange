<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_emailCertified.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_loginotp.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
?>
<section class="sms-certified-box">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?>
    </div>
    <form autocomplete="off" action="javascript:loginOtp()" class="member-box" id="otpLoginForm">
        <input type="hidden" name="memberId" value="<?= $_SESSION['mb_id'] ?? '' ?>">
        <div class="member-title">
            <h1><?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?></h1>
        </div>
        <div class="account-common-title">
            <p class="sms-desc">
                <?= lang('보안 강화를 위해 설정된 OTP 인증 후 로그인 됩니다.',
                'Log in after OTP authentication set for enhanced security.',
                'セキュリティのためにOTP認証を行います。',
                '为强化安全设置的OTP认证后登录。') ?>
            </p>
        </div>
        <div class="account-common-send-info">
            <p class="otp-desc">
                <?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.',
                'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.',
                'OTP認証アプリの認証番号を入力してください。',
                '请输入OTP认证应用显示的6位数认证码。') ?>
            </p>
        </div>
        <div class="member-input-group time-limit">
            <input type="text" maxlength="6" id="otpNum" placeholder="<?= lang('인증번호 입력', 'Enter the authentication number', '認証番号入力', '输入验证码') ?>">
        </div>
        <p class="common-alert alert-message"></p>
        <div class="member-btn">
            <button type="submit" class="btn" id="btnCheckOTP" disabled>
                <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证')?>
            </button>
        </div>
    </form>
</section>