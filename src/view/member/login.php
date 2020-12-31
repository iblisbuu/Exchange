<?php
//add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/common_dark.css?ver' . time() . '">');
add_javascript('<script src="https://www.google.com/recaptcha/api.js?render=6LdRMMEZAAAAAMzERqsu-vLOSHirAyhd0jaqZFKn"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>'); 
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_login.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/login.css?ver=' . time() . '">');
?>

<section class="login common-bg">
    <form class="login-box common-yellow-box" autocomplete="off" action="javascript:login();">
        <h1 class="common-title"><?= lang('로그인', 'Login', 'ログイン', '登录') ?></h1>
        <p class="common-title-desc"><?= lang('GENESIS·EX에 오신 것을 환영합니다.', 'Welcome to GENESIS·EX.', 'GENESIS-EXへようこそ。', '欢迎来到GENESIS·EX') ?></p>
        <div class="common-input-group">
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス', '电子邮件地址') ?></label>
            <input type="email" id="memberId" class="common-input"
                   placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メールアドレス入力', '邮箱地址输入') ?>">
        </div>
        <div class="common-input-group">
            <label for="memberId"><?= lang('비밀번호', 'Password', 'パスワード', '密码') ?></label>
            <input type="password" id="memberPw" class="common-input"
                   placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>">
        </div> 
        <button type="submit" class="btn btn-yellow" id="loginBtn">
            <i class="xi-unlock-o"></i><?= lang('로그인', 'Login', 'ログイン', '登录') ?></button>
        <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
        <div class="link-box">
            <div class="link-desc">
                <div>
                    <label><?= lang(
                            '아직 GENESIS·EX 계정이 없으신가요?',
                            'Don\'t you have a GENESIS·EX account yet?',
                            'アカウントをお持ちではない方はこちら',
                            '还没有GENESIS·EX账号吗?') ?></label>
                    <div class="link-a">
                        <a href="/member/signup"><?= lang('회원가입', 'Sign up', '会員登録', '注册会员') ?></a>
                    </div>
                </div>
                <div> 
                    <label><?= lang(
                            '로그인에 문제가 있으신가요?',
                            'Is there a problem logging in ?',
                            'ログインに問題がありますか？',
                            '登录有问题吗?') ?></label>
                    <div class="link-a">
                        <a href="/member/changepassword"><?= lang('비밀번호 재설정', 'Reset Password', 'パスワードのリセット', '重新设置密码') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<?php
}
?>