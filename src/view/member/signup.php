<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script src="https://www.google.com/recaptcha/api.js?render=6LdRMMEZAAAAAMzERqsu-vLOSHirAyhd0jaqZFKn"></script>');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/jquery.mCustomScrollbar.min.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/jquery.mCustomScrollbar.concat.min.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/signUp.js"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_signup.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/signup.css?ver=' . time() . '">');
?>
<section class="sign-up common-bg">
    <form class="sign-up-box common-yellow-box" autocomplete="off" action="javascript:recaptcha(signUp)">
        <h1 class="common-title"><?= lang('회원가입', 'Sign up', '会員登録', '注册会员') ?></h1>
        <p class="common-title-desc">
            <?= lang(
                'GENESIS·EX 회원 가입 시에 이메일 인증을 진행합니다.<br>실제로 사용하는 이메일을 입력해주세요.',
                'Email authentication is carried out when joining GENESIS and EX members.<br>Please enter the email that you actually use.',
                'GENESIS-EXの会員登録の時にメール認証を行います。<br>実際に使用するメールアドレスを入力してください。',
                '加入GENESIS·EX会员时进行电子邮件认证请输入<br>实际使用的电子邮件。') ?>
        </p>
        <div class="common-input-group">
            <label for="memberId"><?= lang('이메일 주소', 'Email Address', 'メールアドレス', '电子邮件地址') ?></label>
            <input type="text" id="memberId" class="common-input"
                   placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メールアドレス入力', '邮箱地址输入') ?>">
        </div>
        <div class="common-input-group">
            <label for="memberPw"><?= lang('비밀번호', 'Password', 'パスワード', '密码') ?></label>
            <input type="password" id="memberPw" class="common-input"
                   placeholder="<?= lang('비밀번호를 입력', 'Enter password', 'パスワード入力', '输入密码') ?>"
                   onkeyup="passwordValidate(this)">
        </div>
        <div class="common-input-group">
            <label for="memberPwChk"><?= lang('비밀번호 확인', 'Confirm Password', 'パスワード確認', '密码确认') ?></label>
            <input type="password" id="memberPwChk" class="common-input"
                   placeholder="<?= lang('비밀번호를 입력', 'Enter password', 'パスワード入力', '输入密码') ?>"
                   onkeyup="passwordValidate(this)">
        </div>
        <ul class="common-alert-return none">
            <li class="validate__sm"><?= lang('소문자 포함', 'Include lowercase letters', '小文字を含む', '包括传闻者') ?></li>
            <li class="validate__lg"><?= lang('대문자 포함', 'Include uppercase characters', '大文字を含む', '包括首都') ?></li>
            <li class="validate__sp"><?= lang('특수문자 포함', 'Include Special Charactually', '特殊文字を含む', '包含特殊文字') ?></li>
            <li class="validate__num"><?= lang('숫자 포함', 'Include Numbers', '数字を含む', '包括数字') ?></li>
            <li class="validate__length"><?= lang('8자리 이상 32자리 이하', 'not less than eight but not more than thirty-two digits', '8文字以上32桁以下', '8位以上32位以下') ?></li>
            <li class="validate__confirm"><?= lang('비밀번호 일치', 'password matching', 'パスワードと一致', '密码一致') ?></li>
        </ul>
        <hr>
        <div class="sign-agree-box">
            <div class="agree-all">
                <input type="checkbox" id="signAll" class="chk-box" onclick="agreeClickAll()"/>
                <label for="signAll"
                       class="chk-label"><?= lang('전체 동의하기', 'To agree as a whole', 'すべて同意する', '全体同意') ?></label>
            </div>
            <div>
                <input type="checkbox" id="terms" class="chk-box" name="checkbox"/>
                <label for="terms" class="chk-label">
                    <?= lang(
                        '이용약관 동의',
                        'Agreement on Terms of Use',
                        '利用規約に同意',
                        '使用条款同意') ?>
                    <span>(<?= lang('필수', 'Essential', '必須', '必须') ?>)</span>
                    <button type="button"
                            onclick="openDesc('use',this)"><?= lang('자세히 보기', 'Take a closer look', '続きを読む', '显示细节') ?></button>
                </label>
            </div>
            <div>
                <input type="checkbox" id="privacy" class="chk-box" name="checkbox"/>
                <label for="privacy" class="chk-label">
                    <?= lang(
                        '개인정보 처리방침 동의',
                        'Agree on privacy policy',
                        '個人情報の処理方針に同意',
                        '同意个人信息处理方针') ?>
                    <span>(<?= lang('필수', 'Essential', '必須', '必须') ?>)</span>
                    <button type="button"
                            onclick="openDesc('privacy',this)"><?= lang('자세히 보기', 'Take a closer look', '続きを読む', '显示细节') ?></button>
                </label>
            </div>
            <div>
                <input type="checkbox" id="marketing" class="chk-box" name="checkbox"/>
                <label for="marketing" class="chk-label">
                    <?= lang(
                        '마케팅 메세지 수신에 대한 동의',
                        'Agreement on receiving marketing messages',
                        'マーケティングメッセージの受信に同意',
                        '同意接收营销信息') ?>
                    <span>(<?= lang('선택', 'Choice', '選択', '选择') ?>)</span>
                </label>
            </div>
        </div>
        <button type="submit" class="btn" id="loginBtn" disabled><?= lang('가입하기', 'Sign up', '登録', '加入') ?></button>
        <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
    </form>
</section>

<?php
}
?>