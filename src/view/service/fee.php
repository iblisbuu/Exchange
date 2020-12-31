<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/service/fee.css?ver=' . time() . '">');
if($device == 'mobile')
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/service/m_fee.css?ver=' . time() . '">');
?>
<section class="account-section common-bg">
    <div class="wrap-middle">
        <h1><?= lang('수수료 안내', 'Fee Guidance', '手数料のご案内', '手续费介绍') ?></h1>
        <div class="common-yellow-box">
            <div>
                <div>
                    <h2><?= lang('수수료 안내', 'Fee Guidance', '手数料のご案内', '手续费介绍') ?></h2>
                </div>
                <table class="member-table">
                    <colgroup>
                        <col width="50%">
                        <col width="50%">
                    </colgroup>
                    <thead>
                    <th><?= lang('마켓', 'Market', 'マーケット', '市场') ?></th>
                    <th><?= lang('수수료', 'Fee', '手数料', '佣金') ?></th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>BTC</td>
                        <td>0.25%</td>
                    </tr>
                    <tr>
                        <td>USDT</td>
                        <td>0.25%</td>
                    </tr>
                    </tbody>
                </table>
                <p class="fee-desc">- <?=lang('거래 수수료는 이벤트에 따라 달라질 수 있습니다.','Transaction fees may vary depending on the event.','取引手数料は、イベントに応じて異なります。','交易手续费会根据活动的不同而有所不同。')?></p>
            </div>

            <div>
                <div>
                    <h2><?= lang('입출금 수수료', 'Deposit And Withdrawal Fee', '入出金手数料', '存取款手续费') ?></h2>
                </div>
                <table class="member-table">
                    <colgroup>
                        <col width="33%">
                        <col width="33%">
                        <col width="33%">
                    </colgroup>
                    <thead>
                    <th><?= lang('화폐', 'Currency', '資産', '货币') ?></th>
                    <th><?= lang('입금', 'Deposit', '入金', '进款') ?></th>
                    <th><?= lang('출금', 'Withdrawal', '出金', '出钱') ?></th>
                    </thead>
                    <tbody>
                    <?php
                    $db = new db();
                    $query = "SELECT ci_with_fee, ci_symbol FROM _coins WHERE ci_use = 0 OR ci_use = 2 ";
                    $coins = $db->fetchAll($query);
                    foreach ($coins as $row) {
                        $row = objectToArray($row);
                    ?>
                    <tr>
                        <td><?=$row['ci_symbol']?></td>
                        <td><?=lang('무료','Free.','無料','免费')?></td>
                        <td><?=$row['ci_with_fee']==0?'-':$row['ci_with_fee']?></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
                <p class="fee-desc">- <?=lang('입금 수수료는 무료입니다.','The deposit fee is free.','振込手数料は無料です。','汇款手续费是免费的。')?><br>
                    - <?=lang('출금 수수료는 1건당 정액으로 부과됩니다.','The withdrawal fee will be charged at a fixed price per case
                    .','出金手数料は、1件当たり定額で課金されます。','出金手续费按每件定额收取。')?></p>
            </div>
        </div>
    </div>
</section>
