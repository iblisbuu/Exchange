<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_smsCertified.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_member.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_member.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_smscertified.js"></script>');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>'); 
?>
<section class="sms-certified">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('SMS 인증', 'SMS Authentication', 'SMS認証', 'SMS认证') ?>
    </div>
    <div class="sms-certified-box member-box">
        <div class="account-common-title">
            <p>
                <?= lang('거래소 이용 및 가상자산 입출금을 위해 SMS 인증을 진행합니다.', 'SMS authentication is carried out for exchange use and virtual asset deposit and withdrawal.', '取引所利用と暗号資産入出金のためにSMS認証を行います。', '为交易所使用及虚拟资产存取款进行SMS认证。') ?>
            </p>
        </div>
        <form id="sendSMS" class="account-common-box" autocomplete="off" action="javascript:sendSMS()">
            <div class="account-common-send-info">
                <input type="hidden" id="mb_id" value="<?= $_SESSION['mb_id'] ?>"/>
                <p>
                    <em>STEP 1.</em> <?= lang('본인명의의 휴대전화 번호를 입력해주세요.', 'Please enter your mobile phone number.', '本人名義の電話番号を入力してください。', '请输入本人名下的手机号码。') ?>
                </p>
                <label for="phoneNum">
                    <?= lang('본인명의의 휴대전화 번호를 입력하시고, SMS 보내기 버튼을 클릭해주세요.',
                    'Please enter your mobile phone number, Please click the Send SMS button.',
                    '本人名義の電話番号を入力し、SMSの送信ボタンをクリックしてください。',
                    '请输入本人名下的手机号码,点击 SMS发送按钮。') ?>
                </label>
                <label>
                    <?= lang('*국가 코드를 선택한 후 휴대전화 번호를 입력해주세요.',
                    '*Please select a country code and enter your mobile phone number.',
                    '*国コードを選択し、携帯電話番号を入力してください。',
                    '*請選擇國家代碼後輸入手機號碼。') ?>
                </label>
            </div>
            <div class="sms-send-area">
                <div id="smsCountry">
                    <img src="/public/img/common/lang-<?= $country ?>.png" class="img-country">
                    <ul class="sms-option">
                        <li data-country="en" data-num="+1">
                            <img src="/public/img/common/lang-en.png"/>
                            <span>+1</span>
                        </li>
                        <li data-country="ja" data-num="+81">
                            <img src="/public/img/common/lang-ja.png"/>
                            <span>+81</span>
                        </li>
                        <li data-country="ko" data-num="+82">
                            <img src="/public/img/common/lang-ko.png"/>
                            <span>+82</span>
                        </li>
                        <li data-country="ch" data-num="+86">
                            <img src="/public/img/common/lang-ch.png"/>
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
                <input type="number" type="text" id="phoneNum" placeholder="<?= lang('하이픈 ‘-’을 제외한 숫자만 입력', 'Enter only numbers except hyphen ‘-’', 'ハイフン「 - 」を除いた数字のみを入力', '只输入除‘-’以外的数字') ?>">
            </div>
            <div class="member-btn">
                <button type="submit" class="btn disabled" id="btnSendSms" disabled>
                    <?= lang('인증코드 발송', 'Send authentication code', '認証コード発送', '发送认证代码') ?>
                </button>
            </div>
        </form>
        <form id="certifiedSMS" class="sms-common-box member-box" autocomplete="off" action="javascript:certifiedSMS(<?php echo $member['mb_level'] == 3 ? '\'changeSms\'' : '' ?>)">
            <div class="account-common-send-info">
                <p>
                    <em>STEP 2.</em> <?= lang('휴대전화로 발송된 6자리의 숫자를 입력해 주세요.', 'Please enter a 6 digit number sent to your cell phone.', '携帯電話に送信された6桁の数字を入力してください。', '请输入手机发送的6位数字。') ?>
                </p>
            </div>
            <div class="member-input-group time-limit">
                <span><i class="xi-lock-o"></i></span>
                <input type="text" id="authNum" placeholder="<?= lang('인증코드 입력', 'Enter certified code', '認証コードを入力', '输入认证代码') ?>">
                <p class="auth-time"><?= lang('남은시간', 'Time', '残り時間', '剩下的时间') ?> : <span>03:00</span></p>
            </div> 
            <div class="sms-alert-box">
                <p class="common-alert alert-message"></p>
                <button type="button" class="btn-reSend" onclick="sendSMS()"><?= lang('재전송', 'Retransmission', '再送信', '再轉送') ?></button>
            </div>
            <ul class="sms-desc-box">
                <li><?= lang('* 입력시간 내 6자리의 인증번호를 입력해주세요.', '* Please enter a 6-digit authentication number within the input time.', '* 入力時間内、6桁の認証番号を入力してください。', '* 请在输入时间内输入6位数的认证码。') ?></li>
                <li><?= lang('* 인증번호가 발송되지 않는 경우 ‘재전송’을 클릭해주세요.', '* If the authentication number is not sent, please click \'Re-send again.', '* 認証番号が送信されない場合は、「再送」をクリックしてください。', '* 认证号码未发送时请点击"重新发送"。') ?></li>
            </ul>
            <div class="member-btn">
                <button type="submit" class="btn disabled" id="btnCertified" disabled>
                    <?= lang('SMS 인증', 'SMS authentication', 'SMS認証', 'SMS认证') ?>
                </button>
            </div>  
        </form>
    </div>
</section>