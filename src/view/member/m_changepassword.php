<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_changePassword.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
?>
<section class="change-password">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('비밀번호 변경', 'Change Password', 'パスワード変更', '密码变更') ?>
    </div>
    <div class="change-password-box member-box">
        <div class="member-title">
            <p>
            <?= lang(
                '가입하신 이메일을 입력해 주세요.<br> 입력하신 이메일로 비밀번호 변경을 위한 인증코드를 발송합니다.',
                'Please enter the email you signed up for. <br> Send the authentication code for password change to the email you entered.',
                '登録されたメールアドレスを入力してください。<br>入力されたメールアドレスにパスワードを変更するための認証コードを送信します。',
                '请输入您的邮箱。用<br>输入的邮件发送为密码变更的认证代码。') ?>
            </p>
        </div>
        <div class="member-input-group">
            <input type="hidden" id="memberOrgId" value="<?=$member['mb_id']?>" readonly disabled/>
            <span><i class="xi-profile-o"></i></span>
            <input type="text" id="memberId" autocomplete="off" placeholder="<?= lang('이메일 입력', 'Enter Email', 'メールアドレス入力', '电子邮件输入') ?>">
        </div>
        <div class="member-input-group">
            <span><i class="xi-lock-o"></i></span>
            <input type="text" maxlength="6" id="authNum" placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
            <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>30:00</span></p>
        </div>
        <div class="member-btn">
            <button type="button" class="disabled" id="sendCodeBtn" onclick="javascript:sendEmailCertified()" disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?></button>
            <!-- <button type="button" class="none" id="checkCodeBtn" onclick="javascript:checkEmailCertified()"><?= lang('인증코드 확인', 'Confirmation code', '認証コード確認', '認證代碼確認') ?></button> -->
        </div>
    </div>
    <form class="change-password-form member-box" action="javascript:changePassword('','')" autocomplete="off">
        <div class="member-input-group">
            <span><i class="xi-profile-o"></i></span>
            <input type="password" id="memberPw" onkeyup="passwordValidate(this)" placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
        </div>
        <div class="member-input-group">
            <span><i class="xi-lock-o"></i></span>
            <input type="password" id="memberPwChk" onkeyup="passwordValidate(this)" placeholder="<?= lang('비밀번호 확인', 'Confirm Password', 'パスワード確認', '密码确认') ?>">
        </div>
        <ul class="common-alert-return member-alert-return none">
            <li class="validate__sm">
                <i class="xi-check"></i>
                <label><?= lang('소문자 포함', 'Include lowercase letters', '小文字を含む', '包括传闻者') ?></label>
            </li>
            <li class="validate__lg">
                <i class="xi-check"></i>
                <label><?= lang('대문자 포함', 'Include uppercase characters', '大文字を含む', '包括首都') ?></label>
            </li>
            <li class="validate__sp">
                <i class="xi-check"></i>
                <label><?= lang('특수문자 포함', 'Include Special Charactually', '特殊文字を含む', '包含特殊文字') ?></label>
            </li>
            <li class="validate__num">
                <i class="xi-check"></i>
                <label><?= lang('숫자 포함', 'Include Numbers', '数字を含む', '包括数字') ?></label>
            </li>
            <li class="validate__length">
                <i class="xi-check"></i>
                <label><?= lang('8자리 이상 32자리 이하', 'not less than eight but not more than thirty-two digits', '8文字以上32桁以下', '8位以上32位以下') ?></label>
            </li>
            <li class="validate__confirm">
                <i class="xi-check"></i>
                <label><?= lang('비밀번호 일치', 'password matching', 'パスワードと一致', '密码一致') ?></label>
            </li>
        </ul>
        <div class="member-btn">
            <button type="submit" class="disabled" id="changePwBtn" disabled><?= lang('비밀번호 변경', 'Change Password', 'パスワード変更', '密码变更') ?></button>
        </div>
    </form>
</section>