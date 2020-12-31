<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_emailCertified.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
?>
<section class="email-certified">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('이메일 인증', 'Email authentication', 'メール認証', '电子邮件认证') ?>
    </div>
    <form action="javascript:checkEmailCertified(moveToEmailNext,'join')" class="member-box" autocomplete="off">
        <div class="member-title">
            <h1><?= lang('이메일 인증', 'Email authentication', 'メール認証', '电子邮件认证') ?></h1>
        </div>
        <div class="member-input-group">
            <span><i class="xi-profile-o" style="color:#fff"></i></span>
            <input type="email" id="memberId" readonly disabled>
        </div>
        <div class="member-input-group time-limit">
            <span style="background-color:#29292b"><i class="xi-lock-o" style="color:#fff"></i></span>
            <input type="text" maxlength="6" id="authNum" placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
            <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
        </div>
        <p class="common-alert alert-message"></p>
        <div class="member-btn email-certified-box">
            <span>
                <p class="certified-alert"><?= lang(
                '혹시 인증메일이 도착하지 않는다면 스팸메일함을 <br> 확인하거나
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">여기</a> 
            를 눌러 다시 발송해주세요.',
                'If the authentication mail does not arrive, <br> please check the spam mail box or press <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">here</a> to send it again.',
                'もし、認証メールが届かない場合は迷惑メールを確認したり、<br>
            <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">ここ</a>
            を押して再度送信してください。',
                '认证邮件不到时确认垃圾邮件箱或 <br> <a href="javascript:void(0);" onclick="sendEmailCertified(\'resend\');">这里</a> 按下按钮后重新发送。') ?></p>
            </span>
            <button type="submit" class="disabled" id="certifiedEmail" disabled><?= lang('인증코드 확인', 'Confirmation code', '認証コードを確認', '认证代码确认') ?></button>
        </div>
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