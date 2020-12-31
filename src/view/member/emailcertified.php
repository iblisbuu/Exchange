<?php
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/emailcertified.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_emailcertified.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/emailCertified.css?ver=' . time() . '">');
?>
<section class="email-certified common-bg">
    <form class="email-certified-box common-yellow-box" autocomplete="off"
          action="javascript:checkEmailCertified(moveToEmailNext,'join')"
          id="certifiedMail">
        <h1 class="common-title"><?= lang('이메일 인증', 'Email authentication', 'メール認証', '电子邮件认证') ?></h1>
        <p class="common-title-desc">
            <?php echo (!isset($_GET['lv']) || $_GET['lv'] == "0") ? lang('GENESIS·EX 가입을 환영합니다!<br>', 'Welcome to GENESIS and EX!<br>',
                'GENESIS-EX登録を歓迎します！<br>', '欢迎加入GENESIS·EX!<br>') : "" ?>
            <?= lang(
                '가입하신 이메일 주소로 발송된 인증코드 6자리를 입력해주세요.<br>인증 메일 유효시간은 30분입니다.',
                'Please enter 6 digits of the authentication code sent to the email address you signed up for.<br>The validity of the authentication mail is 30 minutes.',
                '登録されたメールアドレスに送信された認証コード6桁を入力してください。<br>認証メールの有効時間は30分です。',
                '请输入按注册邮箱地址发送的6位数认证代码。<br>认证邮件有效时间是30分钟。') ?>
        </p>
        <div class="common-input-group">
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス', '电子邮件地址') ?></label>
            <input type="text" class="common-input" readonly disabled id="memberId">
        </div>
        <div class="common-input-group">
            <label for="authNum"><?= lang('인증코드', 'Authentication code', '認証コード', '认证代码') ?></label>
            <div class="common-input certified-input">
                <input type="text" id="authNum" class="common-input"
                       placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
                <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
            </div>
            <p class="common-alert"></p>
        </div>
        <button type="submit" class="btn" id="certifiedEmail"
                disabled><?= lang('인증코드 확인', 'Confirmation code', '認証コードを確認', '认证代码确认') ?></button>
        <p class="certified-alert">
            <?= lang(
                '혹시 인증메일이 도착하지 않는다면 스팸메일함을 확인하거나 <br>
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">여기</a> 
            를 눌러 다시 발송해주세요.',
                'If the authentication mail does not arrive, please check the spam mail box or press <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">here</a> to send it again.',
                'もし、認証メールが届かない場合は迷惑メールを確認したり、<br>
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">ここ</a>
            を押して再度送信してください。',
                '认证邮件不到时确认垃圾邮件箱或 <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">这里</a> 按下按钮后重新发送。') ?>
        </p>
    </form>
</section>

<script>
    function moveToEmailNext() {
        alert(lang("인증되었습니다.", 'Certified', '認証されました。', '已认证'))
        let url = ""
        switch (getParameter('lv')) {
            case "1":
                url = getParameter('url')
                break;
            case "2":
                url = getParameter('url')
                break;
            default:
                url = "/member/welcome"
                break;
        }
        location.href = url;
    }
</script>
<?php
}
?>