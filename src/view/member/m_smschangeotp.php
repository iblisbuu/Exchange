<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_smsCertified.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_smscertified.js"></script>');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>'); 

    if ($member['mb_level'] < 3) {
        alert(lang('휴대폰 번호 변경을 위해 OTP 인증 먼저 진행 해 주세요.', 'Please proceed with OTP authentication first to change the mobile phone number.', '電話番号の変更のためにOTP認証を先に進めてください。','为了变更手机号码请先进行OTP认证。'), "/member/account/certification");
        return false;
    }
?>

<section class="sms-certified">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('SMS 인증', 'SMS Authentication', 'SMS認証', 'SMS认证') ?>
    </div>
    <div class="sms-certified-box member-box">
        <div class="account-common-title">
            <p>
                <?= lang('거래소 이용 및 가상자산 입출금을 위해 SMS 인증을 진행합니다.', 'SMS authentication is carried out for exchange use and virtual asset deposit and withdrawal.', '取引所利用と暗号資産入出金のためにSMS認証を行います。','为交易所使用及虚拟资产存取款进行SMS认证。') ?>
            </p>
        </div>
        <form id="changeSms" class="account-common-box" autocomplete="off" action="javascript:changeSmsOtp()">
            <input type="hidden" name="memberId" value="<?= $_SESSION['mb_id'] ?>">
            <div class="account-common-send-info">
                <p><?= lang('OTP 인증앱에 표시된 인증번호 6자리를 입력해주세요.', 'Please enter the 6 digits of the authentication number displayed on the OTP authentication app.', 'OTP認証アプリに表示された認証番号6桁を入力してください。','请输入OTP认证应用显示的6位数认证码。') ?></p>
            </div>
            <div class="member-input-group">
                    <span><i class="xi-lock-o"></i></span>
                    <input type="text" id="otpNum" maxlength="6" placeholder="<?= lang('OTP 인증번호', 'OTP Authentication Number', 'OTP認証番号', 'OTP认证编号') ?>">
                </div>
                <div class="sms-alert-box">
                    <p class="common-alert alert-message"></p>
                </div>
            <div class="member-btn">
                <button type="submit" class="btn disabled" id="btnCheckOTP" disabled>
                    <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証','OTP认证')?>
                </button>
            </div>
        </form> 
    </div>
</section>