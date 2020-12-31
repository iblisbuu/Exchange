<?php

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
                <input type="hidden" class="contract-selected" value="<?= $type ?>" readonly data-select="all"
                       data-name="type">
                <select>
                    <option data-option="all"><?= lang('모든 거래', 'All', '全ての取引','一手买卖') ?></option>
                    <option data-option="buy"><?= lang('입금', 'Deposit', '入金','进款') ?></option>
                    <option data-option="sell"><?= lang('출금', 'Withdrawal', '出金','出禁') ?></option>
                </select>
            </div>
        </div>
        <div class="select-coin">
            <div class="input-box">
                <input type="hidden" class="coin-selected" value="<?= $coin ?>" readonly data-select="all"
                       data-name="coin">
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
        <div class="select-date">
            <div class="input-box">
                <input type="hidden" class="date-selected" value="<?= $date ?>" readonly data-select="all"
                       data-name="date">
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
            <col width="23.5%">
            <col width="10%">
            <col width="8%">
            <col width="14%">
            <col width="7.5%">
            <col width="12.5%">
            <col width="12.5%">
            <col width="12.5%">
        </colgroup>
        <thead>
        <tr>
            <th class="fixed deposit-fixed">
                <span>
                    <?= lang('거래종류', 'Transaction type', '取引タイプ','交易种类') ?>
                </span>
                <span>
                    <?= lang('통화', 'Currency', '資産','通话') ?>
                </span>
            </th>
            <th><?= lang('거래수량', 'Transaction quantity', '取引数量','交易量') ?></th>
            <th><?= lang('수수료', 'Fee', '手数料','手续费') ?></th>
            <th><?= lang('정산금액', 'Settlement amount', '精算金額','结算金额') ?>
                <br><span><?= lang('(수수료 반영)', '(Fee Reflections)', '(手数料を反映)','(反映手续费)') ?></span></th>
            <th><?= lang('상태', 'State', '状態','状态') ?>
                <span class="statusDesc">
                    <button class="descIcon" onclick="MobileOpenDepositStatusDesc()"></button>
                </span>
            </th>
            <th><?= lang('요청시간', 'Request time', 'リクエスト時間','时间要求') ?></th>
            <th><?= lang('완료시간', 'Completion time', '完了時間','完成时间') ?></th>
            <th>Txid</th>
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
        $sql_where = ['', ''];

        $sql_where[0] .= $coin != 'all' ? " AND D.dp_symbol = '{$coin}'" : '';
        $sql_where[1] .= $coin != 'all' ? " AND W.wd_type = '{$coin}'" : '';
        switch ($date) {
            case '1m':
                $sql_where[0] .= " AND D.dp_datetime >= '" . strtotime('-1 months') . "' AND D.dp_datetime <= '" . time() . "' ";
                $sql_where[1] .= " AND W.wd_datetime >= '" . strtotime('-1 months') . "' AND W.wd_datetime <= '" . time() . "' ";
                break;
            case '3m':
                $sql_where[0] .= " AND D.dp_datetime >= '" . strtotime('-3 months') . "' AND D.dp_datetime <= '" . time() . "' ";
                $sql_where[1] .= " AND W.wd_datetime >= '" . strtotime('-3 months') . "' AND W.wd_datetime <= '" . time() . "' ";
                break;
            case '6m':
                $sql_where[0] .= " AND D.dp_datetime >= '" . strtotime('-6 months') . "' AND D.dp_datetime <= '" . time() . "' ";
                $sql_where[1] .= " AND W.wd_datetime >= '" . strtotime('-6 months') . "' AND W.wd_datetime <= '" . time() . "' ";
                break;
            case '1y':
                $sql_where[0] .= " AND dp_datetime >= '" . strtotime('-1 years') . "' AND dp_datetime <= '" . time() . "' ";
                $sql_where[1] .= " AND wd_datetime >= '" . strtotime('-1 years') . "' AND wd_datetime <= '" . time() . "' ";
                break;
            default :
                break;
        }

        $db = new db();

        if ($type == 'all') {
            $query = "SELECT dp_id AS idx, dp_symbol AS symbol, dp_coins AS amount, dp_fee AS fee, dp_datetime AS datetime, dp_datetime AS ok_datetime, dp_hash AS txid, NULL AS type, mb_id, 'deposit' AS tabel_type FROM _deposits as D WHERE D.mb_id = '{$member['mb_id']}' {$sql_where[0]}
            UNION
            SELECT wd_no AS idx, wd_type AS symbold, wd_amount AS amount, wd_fee AS fee, wd_datetime AS datetime, ok_datetime, wd_hash AS type, wd_hash AS txid, mb_id, 'withdraw' AS tabel_type  FROM _withdraws as W WHERE W.mb_id = '{$member['mb_id']}' {$sql_where[1]}";

            $queryCnt = "SELECT (
                        (SELECT count(*) AS cnt FROM _deposits as D WHERE D.mb_id = '{$member['mb_id']}' {$sql_where[0]}) + (SELECT count(*) AS cnt FROM _withdraws as W WHERE W.mb_id = '{$member['mb_id']}' {$sql_where[1]})
                     ) as cnt FROM _deposits GROUP by cnt";
        } else if ($type == 'deposit') {
            $query = "SELECT dp_id AS idx, dp_symbol AS symbol, dp_coins AS amount, dp_fee AS fee, dp_datetime AS datetime, dp_datetime AS ok_datetime, dp_hash AS txid, NULL AS type, mb_id, 'deposit' AS tabel_type FROM _deposits as D WHERE D.mb_id = '{$member['mb_id']}' {$sql_where[0]}";

            $queryCnt = "SELECT (
                        (SELECT count(*) AS cnt FROM _deposits as D WHERE D.mb_id = '{$member['mb_id']}' {$sql_where[0]})
                     ) as cnt FROM _deposits GROUP by cnt";
        } else {
            $query = "SELECT wd_no AS idx, wd_type AS symbol, wd_amount AS amount, wd_fee AS fee, wd_datetime AS datetime, ok_datetime, wd_hash AS txid, wd_hash AS type, mb_id, 'withdraw' AS tabel_type  FROM _withdraws as W WHERE W.mb_id = '{$member['mb_id']}' {$sql_where[1]}";

            $queryCnt = "SELECT (
                        (SELECT count(*) AS cnt FROM _withdraws as W WHERE W.mb_id = '{$member['mb_id']}' {$sql_where[1]})
                     ) as cnt FROM _deposits GROUP by cnt";
        }
        $totalCnt = $db->fetchAll($queryCnt)[0];
        $totalCnt = $totalCnt->cnt;

        $list = $db->fetchAll($query . " LIMIT {$fromRecord}, {$limits}");
        foreach ($list as $row) {
            $row = objectToArray($row);
            ?>
            <tr>
                <td class="fixed deposit-fixed">
                    <span>
                        <?= $row['tabel_type'] == 'deposit' ? lang('입금', 'Deposit', '入金','进款') : lang('출금', 'Withdrawal', '出金','出禁') ?>
                    </span>
                    <span>
                        <?= $row['symbol'] ?>
                    </span>
                </td>
                <td class="text-right"><?= round_down_format($row['amount'], 5) ?><br><?= $row['symbol'] ?></td>
                <td><?= round_down_format($row['fee'], 5) ?><br><?= $row['symbol'] ?></td>
                <td class="text-right"><?= round_down_format($row['amount'] - $row['fee'], 5) ?>
                    <br><?= $row['symbol'] ?></td>
                <td><?= $row['tabel_type'] == 'deposit' ? lang('완료', 'Completion', '完了','完成') : ($row['ok_datetime'] ==
                    null ? lang('대기중','Waiting','待機中','待机') : lang('완료', 'Completion', '完了','完成')) ?></td>
                <td><?= date('Y-m-d H:i:s', $row['datetime']) ?></td>
                <td>
                    <?= $row['ok_datetime'] == null ? '' : date('Y-m-d', $row['ok_datetime']) ?>
                    <br>
                    <?= $row['ok_datetime'] == null ? '' : date('H:i:s', $row['ok_datetime']) ?>
                </td>
                <td class="txid"><?=$row['txid']?></td>
            </tr>
        <?php }
        if (count($list) == 0) { ?>
            <tr>
                <td colspan="8" class="no-data"><?= lang('거래내역이 없습니다.', 'No transaction details.', '取引履歴がありません。','没有交易明细。')
                    ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?= paging($totalCnt, $page, $limits) ?>
</div>
