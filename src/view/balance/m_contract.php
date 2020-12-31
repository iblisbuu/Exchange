<?php
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/balance/m_history.js"></script>');

$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$coin = isset($_GET['coin']) ? $_GET['coin'] : 'all';
$date = isset($_GET['date']) ? $_GET['date'] : 'all';

$dateArray = array(
    'all' => lang('모든 기간', 'All Periods', '全ての期間','百日'),
    '1m' => lang('지난 1개월', 'Last month', '過去1ヶ月','前一个月'),
    '3m' => lang('지난 3개월', 'Last three months', '過去3ヶ月','前三个月'),
    '6m' => lang('지난 6개월', 'Last six months', '過去6ヶ月','前六个月'),
    '1y' => lang('지난 1년', 'Past year', '過去1年','过去一年')
);
?>

<div class="balance-options">
    <div class="balance-select-boxes">
        <div class="select-contract">
            <div class="input-box">
                <input type="hidden" class="contract-selected" value="<?= $type ?>" readonly data-select="all" data-name="type">
                <select>
                    <option data-option="all"><?= lang('모든 거래', 'All', '全ての取引','一手买卖') ?></option>
                    <option data-option="buy"><?= lang('매수', 'Buy', '購入','收购') ?></option>
                    <option data-option="sell"><?= lang('매도', 'Sell', '販売','卖出') ?></option>
                </select>
            </div>
        </div>
        <div class="select-contract">
            <div class="input-box">
                <input type="hidden" class="coin-selected" value="<?= $coin ?>" readonly data-select="all" data-name="coin">
                <select>
                    <option data-option="all"><?= lang('모든 코인', 'All Coins', '全てのコイン','所有硬币') ?></option>
                    <?php
                    $db = new db();
                    $query = "SELECT ci_symbol FROM _coins WHERE ci_use = 0 ORDER BY ci_no ASC";
                    $list = $db->fetchAll($query);
                    foreach ($list as $row)
                        echo '<option data-option="' . $row->ci_symbol . '">' . $row->ci_symbol . '</option>';
                    ?>
                </select>
            </div>
        </div>
        <div class="select-contract">
            <div class="input-box">
                <input type="hidden" class="date-selected" value="<?= $date ?>" readonly data-select="all" data-name="date">
                <select>
                <?php
                    foreach ($dateArray as $dayting => $value)
                        echo "<option data-option='{$dayting}'>{$value}</option>";
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="balance-table-content">
        <table class="history-table">
            <colgroup>
                <col width="25%">
                <col width="13%">
                <col width="15%">
                <col width="15%">
                <col width="17%">
                <col width="15%">
            </colgroup>
            <thead>
                <tr>
                    <th class="fixed">
                        <span>
                            <?= lang('거래종류', 'Transaction type', '取引タイプ','交易种类') ?>
                        </span>
                        <span>
                            <?= lang('코인/마켓', 'Coin/Market', 'コイン/マーケット','硬币/市场') ?>
                        </span>
                    </th>
                    <th><?= lang('거래단가', 'Transaction unit price', '取引単価','交易单价') ?></th>
                    <th><?= lang('거래수량', 'Transaction quantity', '取引数量','交易量') ?></th>
                    <th><?= lang('거래금액', 'Transaction amount', '取引金額','交易金额') ?></th>
                    <th><?= lang('수수료', 'Fee', '手数料','手续费') ?></th>
                    <th><?= lang('완료시간', 'Completion time', '完了時間','完成时间') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            /* 페이지 시작지점 구하기 */
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $page = $page < 1 ? 1 : $page;
            $limits = 15;
            $fromRecord = ($page - 1) * $limits;

            /* 필터 조건 */
            $sql_where = '';
            $sql_where .= $type != 'all' ? ' AND od_type = ' . ($type == 'buy' ? 1 : 0) : '';
            $sql_where .= $coin != 'all' ? " AND od_coin = '{$coin}'" : '';
            switch ($date) {
                case '1m':
                    $sql_where .= " AND od_datetime >= '" . strtotime('-1 months') . "' AND od_datetime <= '" . time() . "' ";
                    break;
                case '3m':
                    $sql_where .= " AND od_datetime >= '" . strtotime('-3 months') . "' AND od_datetime <= '" . time() . "' ";
                    break;
                case '6m':
                    $sql_where .= " AND od_datetime >= '" . strtotime('-6 months') . "' AND od_datetime <= '" . time() . "' ";
                    break;
                case '1y':
                    $sql_where .= " AND od_datetime >= '" . strtotime('-1 years') . "' AND od_datetime <= '" . time() . "' ";
                    break;
                default :
                    break;
            }

            $db = new db();
            $query = "SELECT * FROM _orders WHERE mb_id = '{$member['mb_id']}' {$sql_where} ORDER BY od_datetime DESC, od_no DESC";
            $totalCnt = $db->fetchAll(str_replace('*', 'COUNT(*) AS cnt', $query))[0];
            $totalCnt = $totalCnt->cnt;

            $list = $db->fetchAll($query . " LIMIT {$fromRecord}, {$limits}");
            foreach ($list as $row) {
                $row = objectToArray($row);
                ?>
                <tr>
                    <td class="fixed">
                        <span>
                            <?= $row['od_type'] == 0 ? lang('매도', 'Sell', '販売','卖出') : lang('매수', 'Buy', '購い','收购') ?>
                        </span>
                        <span>
                            <?= $row['od_coin'] ?>/<?=$row['od_currency']?>
                        </span>    
                    </td>
                    <td class="text-right"><?= number_format($row['od_price']) ?><br><?=$row['od_currency']?></td>
                    <td class="text-right"><?= round_down_format($row['od_amount'], 8) ?><br><?= $row['od_coin'] ?></td>
                    <td class="text-right"><?= round_down_format($row['od_price'] * $row['od_amount'], 8) ?><br><?=$row['od_currency']?></td>
                    <td class="text-right"><?= round_down_format($row['od_fee'], 8) ?><?=$row['od_type']==0?$row['od_currency']:$row['od_coin']?></td>
                    <td class="text-right padding"><?= date('Y-m-d', $row['od_datetime']) ?><br><?= date('H:i:s', $row['od_datetime']) ?></td>
                </tr>
            <?php }
            if (count($list) == 0) { ?>
                <tr>
                    <td colspan="6" class="no-data"><?= lang('거래내역이 없습니다.', 'No transaction details.', '取引の履歴がありません。','没有交易明细。') ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?= paging($totalCnt, $page, $limits) ?>
</div>