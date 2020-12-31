<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_login.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>'); 
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>'); 
?>

<section class="login">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('로그인', 'Login', 'ログイン', '登录') ?>
    </div>
    <form class="member-box" autocomplete="off" action="javascript:login();">
        <div class="member-title">
            <h1><?= lang('로그인', 'Login', 'ログイン', '登录') ?></h1>
            <p><?= lang('GENESIS·EX에 오신 것을 환영합니다.', 'Welcome to GENESIS·EX.', 'GENESIS-EXへようこそ。', '欢迎来到GENESIS·EX') ?></p>
        </div>
        <div class="member-input-group">
            <span><i class="xi-profile-o"></i></span>
            <input type="email" id="memberId" placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メールアドレス入力', '邮箱地址输入') ?>">
        </div>
        <div class="member-input-group">
            <span><i class="xi-lock-o"></i></span>
            <input type="password" id="memberPw" placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
        </div>
        <div class="member-btn">
            <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
            <button type="submit" id="loginBtn"><?= lang('로그인', 'Login', 'ログイン', '登录') ?></button>
            <span class="link-desc">
                <p class="link-a"><a href="/member/signup"><?= lang('회원가입', 'Sign up', '会員加入', '注册会员') ?></a></p>
                <p class="link-a"><a href="/member/changepassword"><?= lang('비밀번호 재설정', 'Reset Password', 'パスワードのリセット', '重新设置密码') ?></a></p>
            </span>
        </div>
    </form>
</section>