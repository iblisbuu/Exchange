<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_signup.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_signUp.js"></script>'); 
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>'); 
?>

<section class="signup">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('회원가입', 'Sign up', '会員登録', '注册会员') ?>
    </div>
    <form class="sign-up-box member-box" autocomplete="off" action="javascript:recaptcha(signUp)">
        <div class="member-title">
            <h1><?= lang('회원가입', 'Sign up', '会員登録', '注册会员') ?></h1>
        </div>
        <div class="member-input-group">
            <span><i class="xi-profile-o"></i></span>
            <input type="email" id="memberId" placeholder="<?= lang('이메일 주소 입력', 'Enter Email', 'メールアドレス入力', '邮箱地址输入') ?>">
        </div>
        <div class="member-input-group">
            <span><i class="xi-lock-o"></i></span>
            <input type="password" id="memberPw" placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>" onkeyup="passwordValidate(this)">
        </div>
        <div class="member-input-group">
            <span><i class="xi-lock-o"></i></span>
            <input type="password" id="memberPwChk" placeholder="<?= lang('비밀번호 입력', 'Enter password', 'パスワード入力', '输入密码') ?>" onkeyup="passwordValidate(this)">
        </div>
        <ul class="member-alert-return common-alert-return none">
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
                <label><?= lang('8자리 이상 32자리 이하', 'not less than eight but not more than thirty-two digits', '8桁以上32桁以下', '8位以上32位以下') ?></label>
            </li>
            <li class="validate__confirm">
                <i class="xi-check"></i>
                <label><?= lang('비밀번호 일치', 'password matching', 'パスワードと一致', '密码一致') ?></label>
            </li>
        </ul>
        <div class="sign-agree-box">
            <div>
                <div class="agree-all">
                    <input type="checkbox" id="signAll" class="chk-box" onclick="agreeClickAll()">
                    <label for="signAll" class="chk-label">
                        <p>
                            <span class="checkbox-label"><?= lang('전체 동의하기', 'To agree as a whole', '全て同意する', '全体同意') ?></span>
                        </p>
                        <i class="xi-check xi-check-all none"></i>
                    </label>
                </div>
                <div class="agree">
                    <div>
                        <input type="checkbox" id="terms" class="chk-box" name="checkbox" onclick="agreeClick('terms')">
                        <label for="terms" class="chk-label">
                            <p>
                                <span class="checkbox-label"><?= lang(
                                    '이용약관 동의',
                                    'Agreement on Terms of Use',
                                    '利用規約に同意',
                                    '使用条款同意') ?></span>
                                <span>(<?= lang('필수', 'Essential', '必須', '必须') ?>)</span>
                            </p>
                            <i class="xi-check xi-check-terms none"></i>
                        </label>
                    </div>
                    <button type="button" onclick="openDesc('use',this)"><?= lang('보기', 'View', '見本', '例子') ?></button>
                </div>
                <div class="agree">
                    <div>
                        <input type="checkbox" id="privacy" class="chk-box" name="checkbox" onclick="agreeClick('privacy')">
                        <label for="privacy" class="chk-label">
                            <p>
                                <span class="checkbox-label"><?= lang(
                                    '개인정보 처리방침 동의',
                                    'Agree on privacy policy',
                                    '個人情報の処理方針に同意',
                                    '同意个人信息处理方针') ?></span>
                                <span>(<?= lang('필수', 'Essential', '必須', '必须') ?>)</span>
                            </p>
                            <i class="xi-check xi-check-privacy none"></i>
                        </label>
                    </div>
                    <button type="button" onclick="openDesc('privacy',this)"><?= lang('보기', 'View', '見本', '例子') ?></button>
                </div>
                <div class="agree">
                    <div>
                        <input type="checkbox" id="marketing" class="chk-box" name="checkbox" onclick="agreeClick('marketing')">
                        <label for="marketing" class="chk-label">
                            <p>
                                <span class="checkbox-label"><?= lang(
                                    '마케팅 메세지 수신에 대한 동의',
                                    'Agreement on receiving marketing messages',
                                    'マーケティングメッセージの受信に同意',
                                    '同意接收营销信息') ?></span>
                                <span>(<?= lang('선택', 'Choice', '選択', '选择') ?>)</span>
                            </p>
                            <i class="xi-check xi-check-marketing none"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="member-btn">
            <button type="submit" class="btn" id="loginBtn" disabled><?= lang('가입하기', 'Sign up', '登録', '加入') ?></button>
            <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
        </div>
    </form>
</section>