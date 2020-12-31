<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>'); 
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
?>

<section class="login">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?>
    </div>
    <form autocomplete="off" action="javascript:login()" class="login-box member-box">
        <div class="account-common-title">
            <p>
                <?= lang('보안을 위해 OTP를 활성화합니다.', 'Enable OTP for security.',
                'セキュリティのためにOTPを有効にします。', '为了安全起见,激活OTP。') ?>
            </p>
        </div>
        <div class="account-common-send-info">
            <p>
                <em>STEP 1.</em>
                <?= lang('이메일과 비밀번호를 입력해주세요.', 'Please enter your email and password.',
                'メールアドレスとパスワードを入力してください。', '请输入电子邮件和密码。') ?>
            </p>
        </div>
        <div class="member-input-group">
            <span><i class="xi-profile-o" style="color:#fff"></i></span>
            <input type="email" id="memberId" placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メール入力', '邮箱地址输入') ?>" value="<?= $member['mb_id'] ?>" readonly disabled>
            <input type="hidden" id="myId" value="<?= $member['mb_id'] ?>">
        </div>
        <div class="member-input-group">
            <span style="background-color:#29292b"><i class="xi-lock-o" style="color:#fff"></i></span>
            <input type="password" id="memberPw" placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
            <p class="common-alert alert-message"></p>
        </div>
        <div class="member-btn">
            <button type="submit" class="btn btn-full" disabled>
                <?= lang('인증코드 발송', 'Send authentication code', '認証コード送信', '发送认证代码') ?>
            </button>
        </div>
        <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
    </form>
</section>