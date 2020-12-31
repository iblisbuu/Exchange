<?php
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/smscertified.js"></script>');
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_smscertified.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/smsCertified.css?ver=' . time() . '">');
?>
<section class="sms-certified common-bg">
    <div class="sms-certified-box common-yellow-box">
        <h1 class="common-title"> <?= lang('SMS 인증', 'SMS Authentication', 'SMS認証', 'SMS认证') ?></h1>
        <p class="sms-desc">
            <?= lang('거래소 이용 및 가상자산 입출금을 위해 SMS 인증을 진행합니다.', 'SMS authentication is carried out for exchange use and virtual asset deposit and withdrawal.', '取引所利用と暗号資産入出金のためにSMS認証を行います。', '为交易所使用及虚拟资产存取款进行SMS认证。') ?>
        </p>
        <form id="sendSMS" class="common-input-group" autocomplete="off"
              action="javascript:sendSMS()">
            <input type="hidden" id="mb_id" value="<?= $_SESSION['mb_id'] ?>"/>
            <p class="common-title-desc">
                <em>STEP1</em> <?= lang('본인명의의 휴대전화 번호를 입력해주세요.', 'Please enter your mobile phone number.', '本人名義の電話番号を入力してください。', '请输入本人名下的手机号码。') ?>
            </p>
            <label for="phoneNum">
                <?= lang('본인명의의 휴대전화 번호를 입력하시고,<br>
                SMS 보내기 버튼을 클릭해주세요.', 'Please enter your mobile phone number, <br>
                Please click the Send SMS button.', '本人名義の電話番号を入力し、<br>SMSの送信ボタンをクリックしてください。', '请输入本人名下的手机号码,点击<br> SMS发送按钮。') ?>
            </label>
            <div class="common-input sms-send-area">
                <div id="smsCountry">
                    <img src="/public/img/common/lang-<?= $country ?>.png" class="img-country">
                    <ul class="sms-option">
                        <li data-country="en" data-num="+1">
                            <img src="/public/img/common/lang-en.png"/>
                            United States
                            <span>+1</span>
                        </li>
                        <li data-country="ja" data-num="+81">
                            <img src="/public/img/common/lang-ja.png"/>
                            日本
                            <span>+81</span>
                        </li>
                        <li data-country="ko" data-num="+82">
                            <img src="/public/img/common/lang-ko.png"/>
                            대한민국
                            <span>+82</span>
                        </li>
                        <li data-country="ch" data-num="+86">
                            <img src="/public/img/common/lang-ch.png"/>
                            中国
                            <span>+86</span>
                        </li>
                    </ul>
                </div>
                <?php
                if ($country == 'en') $country_num = '+1';
                if ($country == 'ja') $country_num = '+81';
                if ($country == 'ko') $country_num = '+82';
                if ($country == 'ch') $country_num = '+86';
                ?>
                <input type="text" class="sms-country-num" value="<?= $country_num ?>" readonly>
                <input type="text" id="phoneNum"
                       placeholder="<?= lang('하이픈 ‘-’을 제외한 숫자만 입력', 'Enter only numbers except hyphen ‘-’', 'ハイフン「 - 」を除いた数字のみを入力', '只输入除‘-’以外的数字') ?>">
            </div>
            <button type="submit" class="btn" id="btnSendSms"
                    disabled><?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?></button>
        </form>
        <form id="certifiedSMS" class="common-input-group" autocomplete="off" action="javascript:certifiedSMS(<?php echo
        $member['mb_level'] == 3 ? '\'changeSms\'' : '' ?>)">
            <p class="common-title-desc">
                <em>STEP2</em> <?= lang('휴대전화로 발송된 6자리의 숫자를 입력해 주세요.', 'Please enter a 6 digit number sent to your cell phone.', '携帯電話に送信された6桁の数字を入力してください。', '请输入手机发送的6位数字。') ?>
            </p>
            <label for="authNum"><?= lang('인증코드', 'Authentication code', '認証コード', '认证代码') ?></label>
            <div class="common-input certified-input">
                <input type="text" id="authNum"
                       placeholder="<?= lang('인증코드 입력', 'Enter authentication code', '認証コードを入力', '输入认证代码') ?>">
                <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>03:00</span></p>
            </div>
            <div class="sms-alert-box">
                <p class="common-alert"></p>
                <button type="button" class="btn-reSend"
                        onclick="sendSMS()"><?= lang('인증코드 재전송', 'Resend authentication code', '認証コード再送', '认证代码重新发送') ?></button>
            </div>
            <ul class="sms-desc-box">
                <li><?= lang('* 입력시간 내 6자리의 인증번호를 입력해주세요.', '* Please enter a 6-digit authentication number within the input time.', '* 入力時間内、6桁の認証番号を入力してください。', '* 请在输入时间内输入6位数的认证码。') ?></li>
                <li><?= lang('* 인증번호가 발송되지 않는 경우 ‘재전송’을 클릭해주세요.', '* If the authentication number is not sent, please click \'Re-send again.', '* 認証番号が送信されない場合は、「再送」をクリックしてください。', '* 认证号码未发送时请点击"重新发送"。') ?></li>
            </ul>
            <button type="submit" class="btn" id="btnCertified"
                    disabled><?= lang('SMS 인증', 'SMS authentication', 'SMS認証', 'SMS认证') ?></button>
        </form>
    </div>
</section>

<?php
}
?>