<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/m_disabled.css?ver=' . time() . '">');
?>

<section class="login">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?>
    </div>
    <form class="login-box member-box" autocomplete="off" action="javascript:otpCheck()">
        <div class="account-common-send-info" style="margin-top:30px;">
            <label class="common-title-desc"><?= lang('OTP 인증 사용을 중단합니다.<br>OTP를 비활성화할 경우 입출금 시 휴대폰 인증 번호를 사용하게 됩니다. 안전한 거래를 위해 OTP인증 사용을 권장합니다.',
                    'Aborts using OTP authentication. <br>If you disable OTP, you will use your mobile phone authentication number for deposit and withdrawal. We recommend using OTP authentication for secure transactions.',
                    'OTP認証の使用を停止します。<br> OTPを無効にする場合、入出金の時に電話の認証番号を使用します。 安全な取引のためにOTP認証の使用をお勧めします。',
                    '停止使用OTP认证。<br>不激活OTP时存取款时使用手机认证号码。为确保交易安全,建议使用OTP认证。') ?></label>
            <p class="disabled-desc"><?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.', 'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.', 'OTP認証アプリに表示された認証番号6桁を入力してください。', '请输入OTP认证应用显示的6位数认证码。') ?></p>
        </div>
        <div class="member-input-group">
            <input type="hidden" id="memberId" value="<?= $member['mb_id'] ?>">
            <input type="text" id="memberOtp" class="common-input" maxlength="6" style="text-indent:15px;"
                   placeholder="<?= lang('인증번호 입력', 'Enter the authentication number', '認証番号入力', '输入验证码') ?>">
            <p class="alert-message common-alert"></p>
        </div>
        <div class="member-btn">
            <button type="submit" class="btn btn-full btnCertified" disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?></button>
        </div>
    </form>
</section>