<?php
add_javascript('<script src="https://www.google.com/recaptcha/api.js?render=6LdRMMEZAAAAAMzERqsu-vLOSHirAyhd0jaqZFKn"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/otp/login.js"></script>');
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
    include_once VIEW_ROOT . "/otp/m_login.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/common.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/otp/login.css?ver=' . time() . '">');
?>

<section class="login common-bg">
    <form class="login-box common-yellow-box" autocomplete="off" action="javascript:login()">
        <h1 class="common-title"><?= lang('OTP 인증', 'OTP authentication', 'OTP認証','OTP认证') ?></h1>
        <p class="common-title-desc">
            <?= lang('보안을 위해 OTP를 활성화합니다.', 'Enable OTP for security.',
                'セキュリティのためにOTPを有効にします。', '为了安全起见,激活OTP。') ?></p>
        <p class="step-desc">
            <em>STEP1</em>
            <?= lang('이메일과 비밀번호를 입력해주세요.', 'Please enter your email and password.',
                'メールアドレスとパスワードを入力してください。', '请输入电子邮件和密码。') ?>
        </p>
        <div class="common-input-group">
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス','电子邮件地址') ?></label>
            <input type="text" id="memberId" class="common-input"
                   placeholder="<?= lang('이메일 입력', 'Enter Email', 'メールアドレス入力','电子邮件输入') ?>" value="<?= $member['mb_id'] ?>"
                   readonly disabled>
            <input type="hidden" id="myId" value="<?= $member['mb_id'] ?>">
        </div>
        <div class="common-input-group" style="margin-bottom: 0;">
            <label for="memberPw"><?= lang('비밀번호', 'Password', 'パスワード','密码') ?></label>
            <input type="password" id="memberPw" class="common-input"
                   placeholder="<?= lang('비밀번호 입력', 'Enter Password', 'パスワード入力','输入密码') ?>">
        </div>
        <p class="alert-message"></p>
        <button type="submit" class="btn btn-full"
                disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送','发送认证代码') ?></button>
        <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
    </form>
</section>
<?php
}
?>