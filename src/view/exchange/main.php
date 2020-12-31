<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/jquery.mCustomScrollbar.min.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/exchange/main.js?ver='.GS_JS_VER.'"></script>');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/jquery.mCustomScrollbar.concat.min.js"></script>');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver='.GS_CSS_VER.'">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/popup.js?ver='.GS_JS_VER.'"></script>');

// 기축통화 설정
$currency = isset($_GET['currency']) ? $_GET['currency'] : 'BTC';
$currency = str_replace('#', '', $currency);
if ($currency != 'USDT' && $currency != 'BTC')
    $currency = 'BTC';

$lowCurrency = strtolower($currency);

// 관심코인 가져오기
$interest = get_cookie('coin_interest');

$coin = strtoupper((isset($segment[2]) ? $segment[2] : ($currency == 'USDT' ? 'BTC' : 'ETH')));

if ($coin == $currency)
    move('/exchange/main');

$nowTime = time();
$yesterTime = $nowTime - 86400;
$db = new db();

$query = "SELECT ci_price FROM _coins WHERE ci_symbol = 'USDT'";
$coins = $db->fetchAll($query)[0];
$_USDT_PRICE = $coins->ci_price;

$query = "SELECT 

    (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}' ORDER BY tr_price DESC LIMIT 0,1) as ci_high_price,
    
    (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}' ORDER BY tr_price ASC LIMIT 0,1) as ci_low_price,
    
    (SELECT sum(tr_amount) FROM _trade WHERE tr_symbol = '{$coin}' AND tr_currency = '{$currency}' AND tr_datetime >= '{$yesterTime}') as ci_total_coin,
    
    (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as ci_total_sell,
    
    (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as ci_total_buy,
    
    (SELECT sum(tr_price * tr_amount) FROM _trade WHERE tr_symbol = '{$coin}' AND tr_currency = '{$currency}' AND tr_datetime >= '{$yesterTime}') as ci_total_price,
    
    (SELECT sum(tr_amount) FROM _trade AS T WHERE T.tr_symbol = C.ci_symbol AND tr_currency = '{$currency}' AND tr_datetime >= '{$yesterTime}') as ci_{$lowCurrency}_total_price,
    
    ci_{$lowCurrency}, ci_symbol, ci_{$lowCurrency}_total, ci_{$country}_name, ci_{$country}_info, ci_{$lowCurrency}_percent, ci_minimum_{$lowCurrency}, ci_minimum_{$lowCurrency}_unit, ci_btc, ci_usdt, ci_count, ci_url, ci_start, ci_symbol_url, ci_book, ci_{$lowCurrency}, cd_btc, cd_usdt, cd_{$lowCurrency} AS ci_yesterday_price FROM _coinDaily AS D 
    
    LEFT JOIN _coins AS C ON D.cd_symbol = C.ci_symbol
    
    WHERE ci_symbol = '{$coin}' ORDER BY cd_datetime DESC LIMIT 0, 1";

$coinInfo = $db->fetchAll($query);
$coinInfo = objectToArray($coinInfo)[0];
$coinPagePrice = $coinInfo["ci_{$lowCurrency}"];
$coinTotalPrice = round($coinInfo['ci_total_price'] / 100000000, 1); // 억단위 나누기
$COIN_NAME = $coinInfo["ci_{$country}_name"];

// 모바일 일 경우
if ($device == 'mobile') {
    if ($segment[2]) {
        // 코인 별 확인 시
        include_once VIEW_ROOT . "/exchange/m_main.php";
    } else {
        // 코인 리스트 출력
        include_once VIEW_ROOT . "/exchange/m_coinlist.php";
    }
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/exchange/main.css">');
    ?>

    <div id="exchange" class="wrap-exchange">
        <div class="ex01">
            <?php include_once 'coinList.php';?>
        </div>
        <div class="ex02">
            <?php include_once VIEW_ROOT . '/exchange/coinInfo.php';?>
            <div class="box chart">
                <iframe src="//chart.genesis-ex.com/tx/newChart.php?lang=<?= $country == 'ch' ? 'zh' : $country ?>&coin=<?= $coin ?>&currency=<?= $currency ?>&s=<?=$_SERVER['SERVER_NAME'] == '15.164.87.167' || $_SERVER['SERVER_NAME'] == 'genesis-ex.com' ? 'genesis' : 'test'?>"
                        width="100%"
                        height="100%"
                        frameborder="0" scrolling="no"></iframe>
            </div>
            <div class="order-section">
                <div class="box left order-status">
                    <h2 class="box-title"><?= lang('주문현황', 'Order Book', '注文履歴', '订货现状') ?></h2>
                    <table class="tb-status">
                        <colgroup>
                            <col width="31%">
                            <col width="25%">
                            <col width="13%">
                            <col width="31%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th><?= lang('매도수량', 'Sell Qty', '売数量', '卖出数量') ?></th>
                            <th colspan="2"><?= lang('주문가격', 'Price', '価格', '订货价格') ?> ( <?= $currency ?>)</th>
                            <th><?= lang('매수수량', 'Buy Qty', '買数量', '收购数量') ?></th>
                        </tr>
                        </thead>
                        <!--TODO 매도-->
                        <tbody class="down">
                        <?php
                        $db = new db();
                        $query = "SELECT tr_no, tr_quantity, tr_price, 
                                (SELECT SUM(tr_quantity) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_total, 
                                (SELECT SUM(tr_amount) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_now 
                            FROM _trade as T 
                            WHERE tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}' GROUP BY tr_price ORDER BY tr_price ASC LIMIT 0, 10";
                        $coins = $db->fetchAll($query);
                        krsort($coins);
                        if (count($coins) < 10) {
                            for ($i = 0; $i < 10 - count($coins); $i++) {
                                echo(
                                    '<tr class="not">' .
                                    '<td style="background: none;"></td>' .
                                    '<td style="background: none;"></td>' .
                                    '<td style="background: none; border-left: none;"></td>' .
                                    '<td style="background: none;"></td>' .
                                    '</tr>'
                                );
                            }
                        }
                        foreach ($coins as $row) {
                            $row = @objectToArray($row);

                            $rating = round_down(sprintf('%.2f', $row['tr_total'] / $row['tr_now'] * 100), 2);
                            $percent = round_down(sprintf('%.2f', $row['tr_price'] / $coinInfo["ci_{$lowCurrency}"] * 100 - 100), 2);

                            $colorClass = '';
                            if ($percent > 0 || $row['tr_price'] > $coinInfo["ci_{$lowCurrency}"])
                                $colorClass = 'color-red ';
                            else if ($percent < 0 || $row['tr_price'] < $coinInfo["ci_{$lowCurrency}"])
                                $colorClass = 'color-skyblue ';
                            ?>
                            <tr data-price="<?= round_down_format_fix($row['tr_price'], 8) ?>"
                                data-total="<?= $row['tr_now'] ?>">
                                <td>
                                    <p class="amount droid"><?= round_down_format(sprintf('%.8f', $row['tr_total']), 8) ?></p>
                                    <p class="bar" style="width:<?= $rating ?>%;"></p>
                                </td>
                                <td class="status-order-price">
                                    <em class="<?= $colorClass ?>droid"><?= round_down_format_fix($row['tr_price'], 8) ?></em>
                                    <span class="droid"><?=round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $row['tr_total'], 8)?> <?=$currency=='USDT'?'BTC':'USDT'?></span>
                                </td>
                                <td class="<?= $colorClass ?> droid"><?= $percent ?>%</td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        </tbody>

                        <tbody class="up">
                        <?php
                        $db = new db();
                        $query = "SELECT tr_no, tr_quantity, tr_price, 
                                (SELECT SUM(tr_quantity) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_total, 
                                (SELECT SUM(tr_amount) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_now 
                            FROM _trade as T 
                            WHERE tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}' GROUP BY tr_price ORDER BY tr_price DESC LIMIT 0, 10";
                        $coins = $db->fetchAll($query);
                        foreach ($coins as $row) {
                            $row = objectToArray($row);

                            $rating = round_down(sprintf('%.2f', $row['tr_total'] / $row['tr_now'] * 100), 2);
                            $percent = @round_down(sprintf('%.2f', $row['tr_price'] / $coinInfo["ci_{$lowCurrency}"] * 100 - 100), 2);

                            $colorClass = '';
                            if ($percent > 0 || $row['tr_price'] > $coinInfo["ci_{$lowCurrency}"])
                                $colorClass = 'color-red ';
                            else if ($percent < 0 || $row['tr_price'] < $coinInfo["ci_{$lowCurrency}"])
                                $colorClass = 'color-skyblue ';
                            ?>
                            <tr data-price="<?= round_down_format_fix($row['tr_price'], 8) ?>"
                                data-total="<?= $row['tr_now'] ?>">
                                <td></td>
                                <td class="status-order-price">
                                    <em class="<?= $colorClass ?>droid"><?= round_down_format_fix($row['tr_price'], 8) ?></em>
                                    <span class="droid"><?=round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $row['tr_total'], 8)?> <?=$currency=='USDT'?'BTC':'USDT'?></span>
                                </td>
                                <td class="<?= $colorClass ?>droid"><?= $percent ?>%</td>
                                <td>
                                    <p class="amount droid"><?= round_down_format(sprintf('%.8f', $row['tr_total']), 8) ?></p>
                                    <p class="bar" style="width:<?= $rating ?>%;"></p>
                                </td>
                            </tr>
                        <?php }
                        if (count($coins) < 10) {
                            for ($i = 0; $i < 10 - count($coins); $i++) {
                                echo(
                                    '<tr class="not">' .
                                    '<td style="background: none;"></td>' .
                                    '<td style="background: none;"></td>' .
                                    '<td style="background: none; border-left: none;"></td>' .
                                    '<td style="background: none;"></td>' .
                                    '</tr>'
                                );
                            }
                        }
                        ?>
                        </tbody>
                        <tbody class="total" data-coin="<?= $coin ?>">
                        <tr>
                            <td>
                                <span><?= lang('매도잔량', 'Sell Qty', '販売残量', '卖出余量') ?></span>
                                <em id="coin_total_sell"
                                    class="droid"><?= round_down_format(sprintf('%.8f', $coinInfo['ci_total_sell'] ?? 0), 8) ?></em>
                            </td>
                            <td colspan="2"></td>
                            <td>
                                <span><?= lang('매수잔량', 'Buy Qty', '買収残量', '买进余额') ?></span>
                                <em id="coin_total_buy"
                                    class="droid"><?= round_down_format(sprintf('%.8f', $coinInfo['ci_total_buy'] ?? 0), 8) ?></em>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="right">
                    <!--TODO 매수 : buying, 매도 : selling-->
                    <div class="box order-area buying">
                        <h2 class="box-title"><?= lang('주문', 'Place Order', '注文', '咒语') ?></h2>
                        <div class="order-content <?= ($member) ? '' : 'not-member' ?>">
                            <ul class="order-gnb">
                                <li data-type="buying" data-coin="<?= $coin ?>" data-origin="<?= !empty($member) ? round_down_format($member["mb_{$lowCurrency}"], 8) : 0 ?>"
                                    class="active"><?= lang('지정가 매수', 'Buy Limit Order', '指値購入', '限价收购') ?></li>
                                <li data-type="selling" data-origin="<?= !empty($member) ? round_down_format($member['mb_' . strtolower($coin)], 8) : 0 ?>"
                                    data-coin="<?= $coin ?>"><?= lang('지정가 매도', 'Sell Limit Order', '指値販売', '限价抛售') ?></li>
                            </ul>
                            <form class="order-area-content" autocomplete="off"
                                  action="javascript:sendOrder('buying', '<?= $coin ?>')">
                                <div class="my-max-value">
                                    <span><?= lang('주문가능', 'Balance', '注文可能', '可订购') ?></span>
                                    <span class="droid">
                                        <em class="droid">
                                            <?= !empty($member) ? round_down_format($member["mb_{$lowCurrency}"], 8) : 0 ?>
                                        </em>
                                        <span><?= $currency ?></span>
                                    </span>
                                </div>
                                <div class="order-price-area">
                                    <span class="order-price-tit"><?= lang('가격', 'Price', '価格', '价格') ?></span>
                                    <div class="order-price-ipt">
                                        <input class="droid" name="price" type="number" step="0.00000001" placeholder="0">
                                        <div class="order-price-btn">
                                            <button type="button" data-amount="<?=$coinInfo["ci_minimum_{$lowCurrency}_unit"]?>"><i
                                                        class="xi-plus-thin"></i></button>
                                            <button type="button" data-amount="-<?=$coinInfo["ci_minimum_{$lowCurrency}_unit"]?>"><i
                                                        class="xi-minus-thin"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-amount-area">
                                    <span class="order-price-tit"><?= lang('수량', 'Quantity', '数量', '数量') ?></span>
                                    <div class="order-price-ipt">
                                        <input class="droid" name="amount" type="number" placeholder="0" step="0.00000001">
                                    </div>
                                    <div class="order-price-select">
                                        <button type="button"
                                                data-amount="<?= $member ? round_down($member["mb_{$lowCurrency}"] * 0.1, 8) : 0 ?>"
                                                class="droid">10%
                                        </button>
                                        <button type="button"
                                                data-amount="<?= $member ? round_down($member["mb_{$lowCurrency}"] * 0.25, 8) : 0 ?>"
                                                class="droid">25%
                                        </button>
                                        <button type="button"
                                                data-amount="<?= $member ? round_down($member["mb_{$lowCurrency}"] * 0.5, 8) : 0 ?>"
                                                class="droid">50%
                                        </button>
                                        <button type="button"
                                                data-amount="<?= $member ? round_down($member["mb_{$lowCurrency}"] * 1, 8) : 0 ?>"
                                                class="droid">100%
                                        </button>
                                    </div>
                                    <p>*<?= lang('최소 주문 금액', 'Minimum Order Price', '最小注文金額', '最低订货数量') ?> :
                                        <span><?=$coinInfo["ci_minimum_{$lowCurrency}"]?></span> <?= $currency ?></p>
                                </div>
                                <div class="order-total-area">
                                    <p class="order-total">
                                        <span><?= lang('주문총액', 'Total', '注文総額', '订货总额') ?></span>
                                        <span class="droid"><em class="totalPrice droid">0</em><span><?= $currency ?></span></span>
                                    </p>
                                    <p>
                                    <span class="fee"
                                          data-fee="<?= $main_fee ?>"><?= lang('수수료', 'Fee', '手数料', '手续费') ?> (<?= $main_fee ?>%)</span>
                                        <span class="droid"><em class="droid">0</em><span><?= $coin ?></span></span>
                                    </p>
                                </div>
                                <button class="btn-full btn-red order-submit"
                                        type="submit"><?= lang('지정가 매수', 'BUY', '指値購入', '限价收购') ?></button>
                            </form>
                        </div>
                    </div>
                    <div class="box sign-area">
                        <div>
                            <h2 class="box-title"><?= lang('체결현황', 'Recent History', '締結履歴', '签署现状') ?></h2>
                            <table class="tb-sign">
                                <colgroup>
                                    <col width="25%">
                                    <col width="25%">
                                    <col width="25%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                <tr class="head">
                                    <td><?= lang('체결시간', 'Date', '締結時間', '签署时间') ?></td>
                                    <td><?= lang('체결가격', 'Price', '締結価格', '签订价格') ?></td>
                                    <td><?= lang('수량', 'Amount', '数量', '数量') ?></td>
                                    <td><?= lang('체결금액', 'Total', '締結金額', '签署金额') ?></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tb-sign-box mCustomScrollbar" data-mcs-theme="dark">
                            <table class="tb-sign">
                                <colgroup>
                                    <col width="25%">
                                    <col width="25%">
                                    <col width="25%">
                                    <col width="25%">
                                </colgroup>
                                <tbody>
                                <?php
                                $db = new db();
                                $query = "SELECT * FROM _orders WHERE od_coin = '{$coin}' AND od_currency = '{$currency}' ORDER BY od_no DESC LIMIT 0, 20";
                                $coins = $db->fetchAll($query);
                                foreach ($coins as $row) {
                                    $row = objectToArray($row);
                                    ?>
                                    <tr>
                                        <td class="droid"><?= date('H:i:s', $row['od_datetime']) ?></td>
                                        <td class="droid"><?= round_down_format($row['od_price'], 8) ?></td>
                                        <td class="droid color-<?= $row['od_type'] == 0 ? 'skyblue' : 'red' ?>"><?= round_down_format($row['od_amount'], 8) ?></td>
                                        <td class="droid"><?= round_down_format($row['od_price'] * $row['od_amount'], 8) ?></td>
                                    </tr>
                                <?php }
                                if (count($coins) == 0) { ?>
                                    <tr class="no-data">
                                        <td colspan="4"><?= lang('체결현황이 없습니다.', 'The list is empty', '締結履歴がありません。', '无签署现状。') ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box my-order-list">
                <h2 class="box-title"><?= lang('나의 주문내역', 'My Orders', '注文履歴', '我的订货明细') ?></h2>
                <div>
                    <div class="ex01-type order-list-type">
                        <button type="button" data-type="waitOrder"
                                class="active"><?= lang('미체결 주문', 'Outstanding Orders', '未締結注文', '未签署订单') ?></button>
                        <button type="button"
                                data-type="completeOrder"><?= lang('체결 주문', 'Completed Trades', '締結注文', '签署订单') ?></button>
                    </div>
                    <div id="waitOrder" class="order-list-table">
                        <table class="order-list-table-head">
                            <colgroup>
                                <col width="15%">
                                <col width="15%">
                                <col width="5%">
                                <col width="15%">
                                <col width="15%">
                                <col width="10%">
                                <col width="15%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th><?= lang('주문 시각', 'Time', '注文時間', '订货时间') ?></th>
                                <th><?= lang('거래 자산', 'Asset', '取引資産', '交易资产') ?></th>
                                <th><?= lang('구분', 'Category', '区分', '区分') ?></th>
                                <th><?= lang('주문 가격', 'Price', '注文価格', '订货价格') ?></th>
                                <th><?= lang('주문 수량', 'Amount', '価格', '订货数量') ?></th>
                                <th><?= lang('체결 수량', 'Concluded Amount', '締結数量', '签署数量') ?></th>
                                <th><?= lang('미체결 주문', 'Outstanding Orders', '未締結注文', '未签署订单') ?></th>
                                <th><?= lang('취소', 'Cancel', 'キャンセル', '取消') ?></th>
                            </tr>
                            </thead>
                        </table>
                        <div class="order-list-table-body mCustomScrollbar" data-mcs-theme="dark">
                            <table>
                                <colgroup>
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="5%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="15%">
                                    <col width="10%">
                                </colgroup>
                                <tbody>
                                <?php
                                if (!empty($member)) {
                                    $db = new db();
                                    $query = "
                                        SELECT * FROM _trade 
                                        WHERE tr_success_time IS NULL AND mb_id = '{$member['mb_id']}' AND tr_symbol = '{$coin}'   
                                        ORDER BY tr_no DESC LIMIT 0, 20";
                                    $coins = $db->fetchAll($query);
                                } else
                                    $coins = [];

                                foreach ($coins as $row) {
                                    $row = objectToArray($row);
                                    ?>
                                    <tr data-no="<?=$row['tr_no']?>">
                                        <td class="droid"><?= date('m.d H:i', $row['tr_datetime']) ?></td>
                                        <td class="droid"><?= $row['tr_symbol'] ?>/<?= $row['tr_currency'] ?></td>
                                        <td class="droid"><?= $row['tr_type'] == 0 ? lang('판매', 'Sell', '販売', '销售') : lang('구매', 'Buy', '購買', '采购') ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_price'], 8) ?> <?= $row['tr_currency'] ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_amount'], 8) ?> <?= $row['tr_symbol'] ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_volume'], 8) ?> <?= $row['tr_symbol'] ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_quantity'], 8) ?> <?= $row['tr_symbol'] ?></td>
                                        <td class="droid">
                                            <button <?php if ($country == 'ja') echo 'style="padding: 4px 5px;"' ?>
                                                    onclick="tradeCancel(<?= $row['tr_no'] ?>, '<?= $row['tr_symbol'] ?>')"><?= lang('취소', 'Cancel', 'キャンセル') ?></button>
                                        </td>
                                    </tr>
                                <?php }
                                if (count($coins) == 0) { ?>
                                    <tr class="no-data">
                                        <td colspan="8"><?= lang('미체결중인 주문이 없습니다.', 'The list is empty', '未締結されている注文がありません。', '没有未结的订单。') ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="completeOrder" class="order-list-table none">
                        <table class="order-list-table-head">
                            <colgroup>
                                <col width="15%">
                                <col width="5%">
                                <col width="15%">
                                <col width="15%">
                                <col width="15%">
                                <col width="10%">
                                <col width="15%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th><?= lang('거래 자산', 'Asset', '取引資産', '交易资产') ?></th>
                                <th><?= lang('구분', 'Category', '区分', '区分') ?></th>
                                <th><?= lang('체결 가격', 'Closing price', '締結価格', '签订价格') ?></th>
                                <th><?= lang('체결 수량', 'Amount', '価格', '订货数量') ?></th>
                                <th><?= lang('체결 금액',  'Price', '取引金額', '交易金额') ?></th>
                                <th><?= lang('수수료', 'Fee', '手数料', '手续费') ?></th>
                                <th><?= lang('정산 금액', 'Total Price', '精算金額', '结算金额') ?></th>
                                <th><?= lang('완료 시각', 'Time', '完了時刻', '完成时间') ?></th>
                            </tr>
                            </thead>
                        </table>
                        <div class="order-list-table-body mCustomScrollbar" data-mcs-theme="dark">
                            <table>
                                <colgroup>
                                    <col width="15%">
                                    <col width="5%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="15%">
                                    <col width="10%">
                                </colgroup>
                                <tbody>
                                <?php
                                if (!empty($member)) {
                                    $db = new db();
                                    $query = "
                                    SELECT O.od_amount, O.od_datetime, O.tr_id, O.od_fee, T.tr_type, T.tr_price, T.tr_quantity, T.tr_volume, T.tr_symbol, T.tr_currency, T.tr_amount, T.tr_no FROM
                                    _orders AS O LEFT JOIN _trade AS T ON T.tr_no = O.tr_id WHERE
                                    T.tr_success_time IS NOT NULL AND O.mb_id = '{$member['mb_id']}' AND O.od_coin = '{$coin}' ORDER BY O.od_datetime DESC, O.od_no DESC LIMIT 0, 20;";
                                    $coins = $db->fetchAll($query);
                                } else
                                    $coins = [];

                                foreach ($coins as $row) {
                                    $row = objectToArray($row);
                                    ?>
                                    <tr data-no="<?=$row['tr_no']?>">
                                        <td class="droid"><?= $row['tr_symbol'] ?>/<?= $row['tr_currency'] ?></td>
                                        <td class="droid"><?= $row['tr_type'] == 0 ? lang('판매', 'Sell', '販売', '销售') : lang('구매', 'Buy', '購買', '采购') ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_price'], 8) ?> <?= $row['tr_currency'] ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_volume'], 8) ?> <?= $row['tr_symbol'] ?></td>
                                        <td class="droid"><?= round_down_format($row['tr_volume'] * $row['tr_price'], 8) ?> <?= $row['tr_currency'] ?></td>
                                        <td class="droid">
                                            <?= round_down_format($row['od_fee'], 8) ?> <?= $row['tr_type'] == 0 ? $row['tr_currency'] : $row['tr_symbol'] ?></td>
                                        <td class="droid">
                                            <?= round_down_format(($row['tr_type'] == 0 ? $row['od_amount'] * $row['tr_price'] - $row['od_fee'] : $row['od_amount'] - $row['od_fee']), 8) ?> <?= $row['tr_type'] == 0 ? $row['tr_currency'] : $row['tr_symbol'] ?>
                                        </td>
                                        <td class="droid"><?= date('m.d H:i', $row['od_datetime']) ?></td>
                                    </tr>
                                <?php }
                                if (count($coins) == 0) { ?>
                                    <tr class="no-data">
                                        <td colspan="8"><?= lang('체결된 주문이 없습니다.', 'The list is empty', '締結された注文がありません。', '没有签订的订单。') ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>