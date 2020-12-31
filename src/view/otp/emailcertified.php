<?php
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/emailcertified.js"></script>');
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
    include_once VIEW_ROOT . "/otp/m_emailcertified.php";
} else {
    // PC 일 경우
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/common.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/emailCertified.css?ver=' . time() . '">');
?>
<section class="email-certified common-bg">
    <form class="email-certified-box common-yellow-box" autocomplete="off"
          action="javascript:checkEmailCertified(moveToOTP,'join')"
          id="certifiedMail">
        <h1 class="common-title"><?= lang('OTP 인증', 'OTP Authentication', 'OTP認証', 'OTP认证') ?></h1>
        <p class="common-title-desc"><?= lang('보안을 위해 OTP를 활성화합니다.', 'Enable OTP for security.', 'セキュリティのためにOTPを有効にします。', '为了安全起见,激活OTP。') ?></p>
        <p class="step-desc">
            <em>STEP2</em><?= lang('이메일로 발송된 인증코드를 입력해 주세요.', 'Please enter the authentication code sent by email.', 'Eメールで送信された認証コードを入力してください。', '请输入邮件发送的认证代码。') ?>
        </p>
        <div class="common-input-group">
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス', '电子邮件地址') ?></label>
            <input type="text" class="common-input" readonly disabled id="memberId">
        </div>
        <div class="common-input-group">
            <label for="authNum"><?= lang('인증코드', 'Authentication Code', '認証コード', '认证代码') ?></label>
            <div class="common-input certified-input">
                <input type="text" id="authNum" class="common-input"
                       placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
                <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
            </div>
            <p class="common-alert"></p>
        </div>
        <button type="submit" class="btn" id="certifiedEmail" disabled
                style="margin-top: 13px;"><?= lang('메일 인증하기', 'To authenticate your mail', 'Eメール認証する', '验证邮件') ?>
        </button>
        <p class="certified-alert">
            <?= lang('혹시 인증메일이 도착하지 않는다면 스팸메일함을 확인하거나 ', 'If the authentication mail does not arrive, check your spam mail box or', 'もし認証メールが届いていない場合は、迷惑メールボックスを確認したり、', '认证邮件不到时确认垃圾邮件箱或') ?>
            <?= lang('<br><a href="javascript:void(0);" onclick="sendEmailCertified();">여기</a>를 눌러 다시 발송해주세요.', 'Please send it again by clicking <a href="javascript:void(0);" onclick="sendEmailCertified();"> here.</a>', '<a href="javascript:void(0);" onclick="sendEmailCertified();">ここ</a>を押して再度発送してください。', '按一下<a href="javascript:void(0);" onclick="sendEmailCertified();">这个</a>再发货吧。') ?>
        </p>
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
<?php
}
?>