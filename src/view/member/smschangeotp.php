<?php
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/otp.js"></script>');

if ($member['mb_level'] < 3) {
    alert(lang('휴대폰 번호 변경을 위해 OTP 인증 먼저 진행 해 주세요.', 'Please proceed with OTP authentication first to change the mobile phone number.', '電話番号の変更のためにOTP認証を先に進めてください。','为了变更手机号码请先进行OTP认证。'), "/member/account/certification");
    return false;
}
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_smschangeotp.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/smsCertified.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/smscertified.js"></script>');
?>
<style>
    #main{ height: 100%; }
</style>
<section class="sms-certified-box change-sms-box common-bg">
    <div class="common-yellow-box">
        <h1 class="common-title"> <?= lang('SMS 인증', 'SMS Authentication', 'SMS認証','SMS认证') ?></h1>
        <p class="sms-desc">
            <?= lang('거래소 이용 및 가상자산 입출금을 위해 SMS 인증을 진행합니다.', 'SMS authentication is carried out for exchange use and virtual asset deposit and withdrawal.', '取引所利用と暗号資産入出金のためにSMS認証を行います。','为交易所使用及虚拟资产存取款进行SMS认证。') ?>
        </p>
        <span class="otp-desc"><?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.', 'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.', 'OTP認証アプリに表示された認証番号6桁を入力してください。','请输入OTP认证应用显示的6位数认证码。') ?></span>
        <form id="changeSms" class="common-input-group" autocomplete="off" action="javascript:changeSmsOtp()">
            <input type="hidden" name="memberId" value="<?= $_SESSION['mb_id'] ?>">
            <label for="otpNum"><?= lang('OTP 인증번호', 'Otp Authentication Number', 'OTP認証番号','OTP认证编号') ?></label>
            <div class="common-input">
                <input type="text" id="otpNum" maxlength="6"
                       placeholder="<?= lang('인증번호 입력', 'Enter the authentication number', '認証番号入力','输入验证码') ?>">
            </div>
            <p class="common-alert"></p>
            <button type="submit" class="btn" id="btnCheckOTP"
                    disabled><?= lang('OTP 인증', 'OTP Authentication', 'OTP認証','OTP认证')
                ?></button>
        </form>
    </div>
</section>

<?php
}
?>