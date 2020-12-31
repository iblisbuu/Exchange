<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/balance/m_common.css">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/balance/m_common.js"></script>');
?>

<div class="account-section">
    <a href="javascript:history.back()" class="hd-back">
        <i class="xi-angle-left-thin"></i>
    </a>
    <?= lang('투자내역', 'Balance', '投資履歴','投资明细') ?>
</div>

<div class="account-bg common-bg">
    <div class="title-div">
        <h1><?= lang('투자내역', 'Balance', '投資履歴','投资明细') ?></h1>
        <span class="calcDescBtn" onclick="openCalcDesc()"></span>
        <p class="calcDesc popupPrint">
            <?= lang('고객님의 투자내역을 한눈에 확인하세요.', 'Check your investment details at a glance.', 'お客様の投資履歴を一目でご確認いただけます。','请将客户的投资明细一目了然。') ?>
        </p>
        <div class="account">
            <a href="/balance/main/asset" class="asset-tab"><?= lang('자산현황', 'Asset', '資産状況','资产现状') ?></a>
            <a href="/balance/main/contract" class="contract-tab"><?= lang('체결내역', 'Tightening', '締結履歴','签署明细') ?></a>
            <a href="/balance/main/c2c" class="c2c-tab"><?= lang('개인거래내역', 'Transaction', '取引履歴','个人交易明细') ?></a>
            <a href="/balance/main/deposit" class="deposit-tab"><?= lang('입출금내역', 'Deposit', '入出金履歴','存取款明细') ?></a>
        </div>
    </div>
    <div class="balance-box">
    <?php
        if ($menu == 'asset') { // 자산현황
            include VIEW_ROOT . '/balance/m_asset.php';
        } else if ($menu == 'contract') { // 체결내역
            include VIEW_ROOT . '/balance/m_contract.php';
        } else if ($menu == 'c2c') { // 개인거래내역
            include VIEW_ROOT . '/balance/m_c2c.php';
        } else if ($menu == 'deposit') { // 입출금내역
            include VIEW_ROOT . '/balance/m_deposit.php';
        }
    ?>
    </div>
</div>