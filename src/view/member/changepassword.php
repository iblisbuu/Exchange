<?php
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/changePassword.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_changepassword.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/changePassword.css?ver=' . time() . '">');
?>

<section class="change-password common-bg">
    <div class="change-password-box common-yellow-box">
        <h1 class="common-title"><?= lang('비밀번호 변경', 'Change Password', 'パスワード変更', '密码变更') ?></h1>
        <p class="common-title-desc"><?= lang(
                '가입하신 이메일을 입력해 주세요.<br> 입력하신 이메일로 비밀번호 변경을 위한 인증코드를 발송합니다.',
                'Please enter the email you signed up for. <br> Send the authentication code for password change to the email you entered.',
                '登録されたメールアドレスを入力してください。<br>入力されたメールアドレスにパスワードを変更するための認証コードを送信します。',
                '请输入您的邮箱。用<br>输入的邮件发送为密码变更的认证代码。') ?></p>
        <div class="common-input-group margin-none">
            <input type="hidden" id="memberOrgId" value="<?=$member['mb_id']?>" readonly disabled/>
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス', '电子邮件地址') ?></label>
            <input type="text" id="memberId" class="common-input" autocomplete="off"
                   placeholder="<?= lang('이메일 입력', 'Enter Email', 'メールアドレス入力', '电子邮件输入') ?>">
        </div>
        <button type="button" class="btn btn-yellow" id="sendCodeBtn"
                onclick="javascript:sendEmailCertified()"
                disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?></button>
        <form class="change-password-form" autocomplete="off" action="javascript:changePassword()">
            <div class="common-input-group">
                <label for="authNum"><?= lang('인증코드', 'Authentication code', '認証コード', '认证代码') ?></label>
                <div class="common-input certified-input">
                    <input type="text" id="authNum" class="common-input"
                           placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
                    <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
                </div>
                <p class="common-alert"></p>
            </div>
            <div class="common-input-group">
                <label for="memberPw"><?= lang('비밀번호', 'Password', 'パスワード', '密码') ?></label>
                <input type="password" id="memberPw" class="common-input" onkeyup="passwordValidate(this)"
                       placeholder="<?= lang('비밀번호를 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
            </div>
            <div class="common-input-group margin-none">
                <label for="memberPwChk"><?= lang('비밀번호 확인', 'Confirm Password', 'パスワード確認', '密码确认') ?></label>
                <input type="password" id="memberPwChk" class="common-input" onkeyup="passwordValidate(this)"
                       placeholder="<?= lang('비밀번호를 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
            </div>
            <ul class="common-alert-return none">
                <li class="validate__sm"><?= lang('소문자 포함', 'Include lowercase letters', '小文字を含む', '包括传闻者') ?></li>
                <li class="validate__lg"><?= lang('대문자 포함', 'Include uppercase characters', '大文字を含む', '包括首都') ?></li>
                <li class="validate__sp"><?= lang('특수문자 포함', 'Include Special Charactually', '特殊文字を含む', '包含特殊文字') ?></li>
                <li class="validate__num"><?= lang('숫자 포함', 'Include Numbers', '数字を含む', '包括数字') ?></li>
                <li class="validate__length"><?= lang('8자리 이상 32자리 이하', 'not less than eight but not more than thirty-two digits', '8文字以上32桁以下', '8位以上32位以下') ?></li>
                <li class="validate__confirm"><?= lang('비밀번호 일치', 'password matching', 'パスワードと一致', '密码一致') ?></li>
            </ul>
            <button type="submit" class="btn btn-yellow" id="changePwBtn"
                    disabled><?= lang('비밀번호 변경', 'Change Password', 'パスワード変更', '密码变更') ?></button>
        </form>
    </div>
</section>
<?php
}
?>