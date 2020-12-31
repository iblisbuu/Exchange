<?php
$list = [];
$totalCoinsPrice = $totalCoinsBuyPrice = $totalCoinsResultPrice = $totalCoinsUsdtPrice = 0;

$db = new db();
$query = "SELECT ci_price FROM _coins WHERE ci_symbol = 'USDT'";
$coins = $db->fetchAll($query)[0];
$_USDT_PRICE = $coins->ci_price;
$query = "SELECT ci_usdt, ci_symbol, ci_usdt_total, ci_{$country}_name, 
            (SELECT SUM(tr_volume) as total FROM _trade T WHERE T.tr_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_type = 1) AS totalCoinsBuy, 
            (SELECT SUM(dp_coins) as total FROM _deposits D WHERE D.dp_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}') AS totalDepositAmount, 
            (SELECT SUM(tr_usdt) as total FROM _trade T WHERE T.tr_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_success_time IS NOT NULL AND tr_type = 1 AND tr_volume > 0) AS totalCoinsResult,
            (SELECT SUM(dp_price) as total FROM _deposits D WHERE D.dp_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}') AS totalDepositResult,
            (SELECT SUM(tr_volume) as total FROM _trade T WHERE T.tr_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_success_time IS NOT NULL AND tr_type = 1) AS totalBuyCount
          FROM _coins C WHERE ci_use = 0";
$coins = $db->fetchAll($query);
foreach ($coins as $row) {
    $row = objectToArray($row);

    $symbol_low = strtolower($row['ci_symbol']);

    $row['nowCoinRate'] = $nowCoinRate = round_down($row['totalCoinsBuy'] * $row['ci_usdt'], 8);
    $row['saveCoinRate'] = $saveCoinRate = round_down($row['totalCoinsBuy'] * ($row['totalCoinsResult'] * $row['totalDepositResult']), 8);

    $list[] = $row;

    $totalCoinsPrice += (double)round_down($row['ci_usdt'] * $member["mb_{$symbol_low}"], 8);
    $totalCoinsBuyPrice += (double)round_down($row['totalCoinsResult'] + $row['totalDepositResult'], 8);
    $totalCoinsResultPrice += (double)round_down($saveCoinRate, 8);
    $totalCoinsUsdtPrice += (double)round_down($member["mb_{$symbol_low}"] * $row['ci_usdt'], 8);
}
?>
<div class="title-div">
    <h1><?= lang('자산현황', 'Asset', '資産状況', '资产现状') ?></h1>
    <p class="title-calcDesc">
        <?= lang('*매수 평균가, 매수 금액, 평가 금액, 평가 수익률은 모두 USDT로 환산한 추정값으로 참고용입니다.', '*The average purchase price, purchase amount, valuation amount, and rate of return are all for reference as estimates converted to USDT.', '*枚数平均原価、枚数量は、評価額、評価利回りは全てUSDTに換算した推定値に参考用です。', '* 买入均价,买入金额,估值金额,估值收益率均以USDT换算的估值作为参考。') ?>
    </p>
</div>

<div class="balance-content">
    <div class="balance-asset">
        <div class="line asset-line">
            <span><?= lang('총 자산', 'Total Asset Valuation', '総資産', '总资产') ?></span>
            <span><?= round_down_format_fix($member['mb_usdt'] + $totalCoinsUsdtPrice, 8) ?></span>
        </div>
        <div class="line asset-line">
            <span><?= lang('총 보유 USDT', 'USDT Current Balance', '総保有USDT', '持有USDT') ?></span>
            <span><?= round_down_format_fix($member['mb_usdt'], 8) ?></span>
        </div>
        <div class="line asset-line">
            <span><?= lang('총 매수 금액', 'Total Purchase Amount', '総枚数金額', '收购金额') ?></span>
            <span><?= round_down_format_fix($totalCoinsBuyPrice, 8) ?></span>
        </div>
        <div class="line asset-line">
            <span><?= lang('총 평가 금액', 'Total Valuation', '評価額', '估值金额') ?></span>
            <span><?= round_down_format_fix($totalCoinsUsdtPrice, 8) ?></span>
        </div>
        <div class="line asset-line no-line">
            <span>
                <p><?= lang('총 평가 손익률', 'Total Return on Investment', '評価損益率', '评估损益率') ?></p>
                <p><?= lang('총 평가 손익', 'Total Profit Valuation', '評価損益', '评估损益') ?></p>
            </span>
            <span>
                <div>
                    <p class="color-balance-<?= $totalCoinsUsdtPrice > $totalCoinsBuyPrice ? 'red' : ($totalCoinsUsdtPrice < $totalCoinsBuyPrice ? 'blue' : '') ?>"><?= round_down( ($totalCoinsUsdtPrice - $totalCoinsBuyPrice) / ($totalCoinsBuyPrice) * 100, 2) ?>%</p>
                    <p class="color-balance-<?= $totalCoinsUsdtPrice > $totalCoinsBuyPrice ? 'red' : ($totalCoinsResultPrice < $totalCoinsBuyPrice ? 'blue' : '') ?>"><?= round_down_format_fix($totalCoinsUsdtPrice - $totalCoinsBuyPrice, 8) ?></p>
                </div>
            </span>
        </div>
    </div>
</div>

<div class="title-div margin-status">
    <h1><?= lang('보유 현황', 'Status of holding', '保有状況', '持有现状') ?></h1>
</div>

<div class="balance-content margin">
    <table class="balance-table retain-table">
        <colgroup>
            <col width="15%">
            <col width="20%">
            <col width="25%">
            <col width="20%">
            <col width="20%">
        </colgroup>
        <thead>
        <tr>
            <th>
                <button onclick="tableSort(this,'coin')"><?= lang('이름', 'Name', 'コイン名', '名') ?></button>
            </th>
            <th>
                <button onclick="tableSort(this,'amount',true)"><?= lang('보유 수량', 'Balance', '保有数量', '持有数量') ?></button>
            </th>
            <th>
                <button onclick="tableSort(this,'buy',true,1)" class="double-td">
                    <span><?= lang('매수 금액', 'Purchase Amount', '買収金額', '收购金额') ?> (USDT)</span><br>
                    <span><?= lang('매수 평균가', 'Average Purchase Price', '枚数の平均原価', '买进均价') ?> (USDT)</span>
                </button>
            </th>
            <th>
                <button onclick="tableSort(this,'price',true)"><?= lang('평가 금액', 'Current Valuation', '評価額', '估值金额') ?></button>
            </th>
            <th>
                <button onclick="tableSort(this,'profit',true,1)" class="double-td">
                    <span><?= lang('평가 손익률', 'Return on Investment', '評価損益率', '评估损益率') ?> (%)</span><br>
                    <span><?= lang('평가 손익', 'Profit/Loss Valuation', '評価損益', '评估损益') ?> (USDT)</span>
                </button>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($list as $row) {
            $symbol_low = strtolower($row['ci_symbol']);
            $coin_name = $row["ci_{$country}_name"];

            $row['nowCoinRate'] = $row['nowCoinRate'] < 0 ? $row['nowCoinRate'] * -1 : $row['nowCoinRate'];
            $row['saveCoinRate'] = $row['saveCoinRate'] < 0 ? $row['saveCoinRate'] * -1 : $row['saveCoinRate'];

            $resultPrice = round_down($member["mb_{$symbol_low}"] * $row['ci_usdt'], 8);
            $buyPrice = round_down(($row['totalCoinsResult'] + $row['totalDepositResult']) / ($row['totalBuyCount'] + $row['totalDepositAmount']), 8);

            $resultUSDT = ($member["mb_{$symbol_low}"] * $row['ci_usdt']) - ($row['totalCoinsResult'] + $row['totalDepositResult']);
            ?>
            <tr>
                <td class="coin-td">
                    <div>
                        <span data-type="coin"><?= $coin_name ?></span>
                        <p><?= $row['ci_symbol'] ?></p>
                    </div>
                </td>
                <td data-type="amount" class="amount-td"><?= round_down_format($member["mb_{$symbol_low}"], 8) ?></td>
                <td class="double-td buy-td" data-type="buy">
                    <span><?= round_down_format_fix($row['totalCoinsResult'] + $row['totalDepositResult'], 8) ?></span><br>
                    <span><?= round_down_format_fix(($row['totalCoinsResult'] + $row['totalDepositResult']) / ($row['totalBuyCount'] + $row['totalDepositAmount']), 8) ?></span>
                </td>
                <td data-type="price"><?= round_down_format_fix($resultPrice, 8) ?></td>
                <td class="double-td profit-td" data-type="profit">
                    <span class="color-balance-<?= $resultUSDT > 0 ? 'red' : ($resultUSDT < 0 ? 'blue' : '') ?>">
                    <?= round_down($resultUSDT / ($row['totalCoinsResult'] + $row['totalDepositResult']) * 100, 2) ?>%
                    </span><br>
                    <span class="color-balance-<?= $resultUSDT > 0 ? 'red' : ($resultUSDT < 0 ? 'blue' : '') ?>">
                    <?= round_down_format_fix($resultUSDT, 8) ?>
                </span>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>