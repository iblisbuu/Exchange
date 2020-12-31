<?php
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/otp.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/otp/otpcertified.js"></script>');
if (empty($member)) {
    add_event('alert_hooks', 'not_login');
    return false;
}
if ($member['mb_level'] != 2) {
    alert(lang('SMS 인증 후 가능합니다.', 'It is possible after SMS authentication.', 'SMS認証後可能です。', 'SMS认证后可以。'));
    move('/member/smscertified');
}
if ($member['mb_level'] > 2) {
    alert(lang('이미 인증되었습니다.', 'Already authenticated.', '既に認証されています。', '已经认证了。'));
    move('/member/account/certification');
}
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/otp/m_otpcertified.php";
} else {
    // PC 일 경우
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/common.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/otpcertified.css?ver=' . time() . '">');
?>
<style>#main { height: auto; }</style>
<section class="login common-bg">
    <form class="login-box common-yellow-box" autocomplete="off" action="javascript:checkOtp()">
        <h1 class="common-title"><?= lang('OTP 인증', 'OTP authentication', 'OTP認証', 'OTP认证') ?></h1>
        <p class="common-title-desc">
            <?= lang('OTP 인증을 통하여 고객님의 지갑을 안전하게 보호하세요.', 'Protect your wallet safely with OTP authentication.', 'OTP認証を通じて、お客様のウォレットを保護します。', '请通过OTP认证保护顾客的钱包。') ?>
        </p>

        <p class="step-desc step-01">
            <?= lang('OTP앱을 스마트폰에 다운로드 하세요.', 'Download the OTP app to your smartphone.', 'OTPアプリをスマートフォンにダウンロードしてください。', '请把OTP应用程序下载到智能手机上。') ?>
        </p>
        <div class="step-box otp-store-box">
            <a href="http://click.gl/cCgWLN" target="_blank" class="google-store">
                <?= lang('Google OTP 다운로드', 'Download Google OTP', 'Google OTPダウンロード', 'Google OTP 下载') ?>
            </a>
            <a href="http://click.gl/ZQ6oLs" target="_blank" class="app-store">
                <?= lang('Google Authenticator 다운로드', 'Download Google Authenticator', 'Google Authenticatorダウンロード', 'Google Authenticator 下载') ?>
            </a>
        </div>

        <p class="step-desc step-02">
            <?= lang('Google Authenticator 앱에서 아래의 QR코드를 스캔하세요.', 'Scan the QR code below in the Google Authenticator app.', 'Google Authenticatorアプリで下のQRコードをスキャンしてください。', '请在Google Authenticator软件中扫描以下QR码。') ?>
        </p>
        <div class="step-box otp-code-box">
            <div class="otp-loading">
                <img id="otpLoading" src="/public/img/common/loading.gif"/>
            </div>
            <div class="otp-code">
                <p><?= lang('바코드 스캔이 불가능한 경우<br>아래의 키를 입력해주세요.', 'If barcode scanning is not possible, enter the key below.', 'バーコードのスキャンが不可能な場合は、以下のキーを入力してください。', '不能扫描条形码时请输入以下键。') ?></p>
                <div class="common-input-group">
                    <input type="text" class="common-input" id="otpSecret" readonly disabled/>
                </div>
            </div>
        </div>

        <p class="step-desc step-03">
            <?= lang('인증번호를 입력해주세요.', 'Please enter the authentication number.', '認証番号を入力してください。', '请输入验证码。') ?>
        </p>
        <div class="step-box otp-auth-box">
            <input type="hidden" id="memberId" value="<?= $_SESSION['mb_id'] ?>">
            <div class="common-input-group" style="margin-bottom: 0;">
                <label for="memberOTP"><?= lang('Google OTP 인증번호', 'Google OTP authentication number', 'Google OTP認証番号', 'Google OTP认证编号') ?></label>
                <input type="text" id="memberOTP" class="common-input" maxlength="6"
                       placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
            </div>
            <p class="alert-message"></p>
        </div>
        <button type="submit" class="btn btn-full"
                disabled><?= lang('OTP 인증 완료', 'OTP Authentication Completed', 'OTP認証完了', 'OTP认证完毕') ?></button>
    </form>
</section>
<?php
}
?>