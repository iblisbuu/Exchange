<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_emailCertified.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
?>
<section class="email-certified">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?>
    </div>
    <form action="javascript:checkEmailCertified(moveToOTP,'join')" autocomplete="off" class="email-certified-box member-box">
        <div class="account-common-title">
            <p>
                <?= lang('보안을 위해 OTP를 활성화합니다.', 'Enable OTP for security.', 'セキュリティのためにOTPを有効にします。', '为了安全起见,激活OTP。') ?>
            </p>
        </div>
        <div class="account-common-send-info">
            <p>
                <em>STEP 2. </em><?= lang('이메일로 발송된 인증코드를 입력해 주세요.', 'Please enter the authentication code sent by email.', 'Eメールで送信された認証コードを入力してください。', '请输入邮件发送的认证代码。') ?>
            </p>
        </div>
        <div class="member-input-group">
            <span><i class="xi-profile-o" style="color:#fff"></i></span>
            <input type="email" id="memberId" placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メール入力', '邮箱地址输入') ?>" value="<?= $member['mb_id'] ?>" readonly disabled>
        </div>
        <div class="member-input-group time-limit">
            <span style="background-color:#29292b"><i class="xi-lock-o" style="color:#fff"></i></span>
            <input type="text" maxlength="6" id="authNum" placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
            <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
        </div>
        <p class="common-alert alert-message"></p>
        <div class="member-btn">
        <span>
            <p class="certified-alert" style="color: #979797;"><?= lang(
                '혹시 인증메일이 도착하지 않는다면 스팸메일함을 <br> 확인하거나
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">여기</a> 
            를 눌러 다시 발송해주세요.',
                'If the authentication mail does not arrive, <br> please check the spam mail box or press <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">here</a> to send it again.',
                'もし認証メールが届いていない場合は、迷惑メールボックスを確認したり、<br>
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">ここ</a>
            を押して再度発送してください。',
                '认证邮件不到时确认垃圾邮件箱或 <br> <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">这个</a>再发货吧。') ?></p>
            </span>
            <button type="submit" class="btn" id="certifiedEmail" disabled>
                <?= lang('메일 인증하기', 'To authenticate your mail', 'メール認証する', '验证邮件') ?>
            </button>
        </div>
    </form>
</section>

<script>
    $(function () {
        let email = getParameter('auth');
        email = hex2bin(email);
        $('#memberId').val(email)
    });

    function moveToOTP() {
        alert(lang('인증되었습니다.', 'authenticated.', '認証された。', '已认证'))
        location.href = "/otp/otpcertified";
    }
</script>