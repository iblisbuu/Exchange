<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_welcome.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
?>

<section class="welcome">
    <div class="welcome-title">
        <img src="/public/img/welcome/mobile/welcome.png">
        <p>
            <?= lang('고객님', 'Dear customer', 'お客様', '顧客') ?><br>
            <?= lang('환영합니다!', 'Welcome!', '歓迎します！', '歡迎！') ?>
        </p>
    </div>
    <div class="welcome-box">
        <button type="button" onclick="location.href='/member/account/certification'">
            <?= lang('인증 시작하기', 'Initiate Authentication', '認証開始する', '开始认证') ?>
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="welcome-subBox">
            <p class="welcome-desc">
                <?= lang(
                    'GENESIS · EX 에서는 입출금 및 보안을<br>
                    유지하기 위해 본인확인 절차를 도입하고 있습니다.<br>
                    아래의 단계를 순서대로 진행해 주세요.',
                        'At GENESIS  · EX, deposit and withdrawal and security <br>
                    We are introducing a personal identification procedure to maintain it. <br>
                    Please proceed the steps below in order.',
                        'GENESIS-EXは、入出金やセキュリティを維持するために<br>
                    本人確認の手続きを導入しています。<br>
                    以下の手順を順番に進めてください。',
                        'GENESIS · EX 收款和保安<br>
                    为了维持,正在引进本人确认程序。<br>
                    请按顺序进行以下步骤。') ?>
            </p>
            <ul class="welcome-content">
                <li><strong><?= lang('1단계 SMS 인증', 'Step 1 SMS Authentication', 'ステップ1 SMS認証', '第一阶段SMS认证') ?></strong></li>
                <li><?= lang('가상자산(암호화폐) 입금/출금 활성화', 'Activating Virtual Assets (Cryptocurrency) Deposit/Exit', '仮想資産(暗号通貨)の入金出金活性化', '虛擬資產（密碼貨幣）存入/取款活性化') ?></li>
                <li><?= lang('(일 출금 한도 1,000한도)','(daily withdrawal limit of 1,000)','(日の出金限度1,000限度)','(日出账限额1000限度)')?></li>
                <li><strong><?= lang('2단계 OTP인증', 'Step 2 OTP Certification', '2段階OTP認証', '第二阶段OTP认证') ?></strong></li>
                <li><?= lang('사용자 보안 강화', 'User Security Enhancements', 'ユーザーのセキュリティ強化', '使用者加强保安') ?></li>
            </ul>
        </div>
    </div>
    <div class="welcome-box-later">
        <button type="button" onclick="location.href='/'">
            <?= lang('나중에 하기', 'To do later', '後でする', '以后做') ?>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</section>