<?php

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_welcome.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/welcome.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/member.js"></script>');
?>
<section class="welcome common-bg">
    <div class="welcome common-yellow-box" autocomplete="off">
        <h1 class="common-title">
            <?= lang(
                '<strong>GENESIS · EX</strong>에<br>
            가입하신 것을 환영합니다!',
                'Welcome to<br><strong>GENESIS · EX</strong>!',
                '<strong>GENESIS-EX</strong>に<br>登録されたことを歓迎します！',
                '欢迎加入<br><strong>GENESIS · EX</strong>!') ?>
            <img class="title-img" src="/public/img/welcome/welcome.png"/>
        </h1>
        <hr>
        <div class="welcome-box">
            <button type="button" class="btn btn-full btn-yellow"
                    onclick="location.href='/member/account/certification'">
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
                    <li>
                        <strong><?= lang('1단계 SMS 인증', 'Step 1 SMS Authentication', 'ステップ1 SMS認証', '第一阶段SMS认证') ?></strong><br>
                        <?= lang('가상자산(암호화폐) 입금 활성화', 'Activate virtual asset (cryptocurrency) deposits', '仮想資産（パスワード貨幣）入金有効', '虚拟资产(暗号货币)存款活跃') ?>
                    </li>
                    <li>
                        <strong><?= lang('2단계 OTP인증', 'Step 2 OTP Certification', '2段階OTP認証', '第二阶段OTP认证') ?></strong><br>
                        <?= lang('사용자 보안 강화', 'User Security Enhancements', 'ユーザーのセキュリティ強化', '使用者加强保安') ?>
                    </li>
                </ul>
            </div>
        </div>
        <button type="button" class="btn btn-full btn-gray" onclick="location.href='/'">
            <?= lang('나중에 하기', 'To do later', '後でする', '以后做') ?>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</section>
<?php
}
?>