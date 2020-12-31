<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/m_otpcertified.css?ver=' . time() . '">');
?>
<section class="login">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?>
    </div>
    <form action="javascript:checkOtp()" class="login-box member-box" autocomplete="off">
        <div class="account-common-title">
            <p>
                <?= lang('OTP 인증을 통하여 고객님의 지갑을 안전하게 보호하세요.', 'Protect your wallet safely with OTP authentication.', 'OTP認証を通じて、お客様のウォレットを保護します。', '请通过OTP认证保护顾客的钱包。') ?>
            </p>
        </div>

        <!-- step1 -->
        <div class="account-common-send-info">
            <p>
                <?= lang('1. OTP앱을 스마트폰에 다운로드 하세요.', '1. Download the OTP app to your smartphone.', '1. OTPアプリをスマートフォンにダウンロードしてください。', '1. 请把OTP应用程序下载到智能手机上。') ?>
            </p>
        </div>
        <div class="otp-store-box">
            <a href="http://click.gl/cCgWLN" target="_blank" class="google-store">
                <p><?= lang('안드로이드', 'Android', 'アンドロイド', '安卓系統') ?></p>
                <img src="/public/img/otp/mobile/google-store.png">
                <label><?= lang('Google OTP<br>다운로드', 'Google OTP<br>Download', 'Google OTP<br>ダウンロード', 'Google OTP<br>下载') ?></label>
            </a>
            <a href="http://click.gl/ZQ6oLs" target="_blank" class="app-store">
                <p><?= lang('아이폰', 'IPhone', 'アイフォン', 'iphone') ?></p>
                <img src="/public/img/otp/mobile/app-store.png">
                <label><?= lang('Google Authenticator<br>다운로드', 'Google Authenticator<br>Download', 'Google Authenticator<br>ダウンロード', 'Google Authenticator<br>下载') ?></label>
            </a>
        </div>

        <!-- step2 -->
        <div class="account-common-send-info">
            <p>
                <?= lang('2. Google Authenticator 앱에서 “바코드스캔”을 이용하여 아래의 QR코드를 스캔하세요.', '2. Scan the QR code below using "Barcode Scan" in the Google Authenticator app.', '2. Google Authenticatorアプリから「バーコードスキャン」を利用して、以下のQRコードを読み取ってください。', '2. 在Google Authenticator應用中使用"條形碼掃描"掃描下面的QR碼。') ?>
            </p>
        </div>
        <div class="otp-code-box">
            <div class="otp-qr-box">
                <div class="otp-loading">
                    <img id="otpLoading" src="/public/img/common/loading.gif"/>
                </div>
            </div>
            <p><?= lang('바코드 스캔이 불가능한 경우 아래의 키를 입력해주세요.', 'If barcode scanning is not possible, enter the key below.', 'バーコードのスキャンが不可能な場合は、以下のキーを入力してください。', '不能扫描条形码时请输入以下键。') ?></p>
            <input type="text" class="otp-input" id="otpSecret" readonly disabled/>
        </div>

        <!-- step3 -->
        <div class="account-common-send-info">
            <p>
                <?= lang('3. 인증번호를 입력해주세요.', '3. Please enter the authentication number.', '3. 認証番号を入力してください。', '3. 请输入验证码。') ?>
            </p>
        </div>
        <input type="hidden" id="memberId" value="<?= $_SESSION['mb_id'] ?>">
        <div class="member-input-group">
            <input type="text" id="memberOTP" maxlength="6" placeholder="<?= lang('OTP 인증번호', 'OTP Authentication Number', 'OTP認証番号', 'OTP驗證碼') ?>">
            <p class="common-alert alert-message"></p>
        </div>

        <!-- btn -->
        <div class="member-btn">
            <button type="submit" class="btn" disabled><?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?></button>
        </div>
    </form>
</section>