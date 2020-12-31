<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/exchange/m_main.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/exchange/m_main.js"></script>');

$type = (isset($_GET['type'])) ? $_GET['type'] : 'order';
?>
<div id="titleHeader" class="none">
    <div class="wrap-wide wrap-title">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <span class="hd-title">
            <strong><?= $coinInfo["ci_{$country}_name"] ?></strong>
            (<?= $coin ?>/<?=$currency?>)
            <button type="button" class="hd-coinList"></button>
        </span>
        <span class="hd-bookmark bookmark<?= strpos($interest, $coin) !== false ? ' active' : '' ?>"
              data-type="<?= strtolower($coin) ?>"></span>
        <span class="mb-menu"></span>
    </div>
</div>
<div id="coinList">
    <?php include VIEW_ROOT . '/exchange/m_coinlist.php'; ?>
</div>
<div id="exchange">
    <?php include_once VIEW_ROOT . '/exchange/coinInfo.php';?>

    <ul class="coinMenu">
        <li <?php if ($type == 'order') echo "class='active'" ?>>
            <a href="/exchange/main/<?= $coin ?>?currency=<?=$currency?>&type=order"><?=lang('주문', 'Order', '注文', '秩序')?></a>
        </li>
        <li <?php if ($type == 'chart') echo "class='active'" ?>>
            <a href="/exchange/main/<?= $coin ?>?currency=<?=$currency?>&type=chart"><?=lang('차트', 'Chart', 'チャート', '图表')?></a>
        </li>
        <li <?php if ($type == 'market') echo "class='active'" ?>>
            <a href="/exchange/main/<?= $coin ?>?currency=<?=$currency?>&type=market"><?=lang('시세', 'Price', '時勢', '行情')?></a>
        </li>
        <li <?php if ($type == 'info') echo "class='active'" ?>>
            <a href="/exchange/main/<?= $coin ?>?currency=<?=$currency?>&type=info">코인정보</a>
        </li>
    </ul>

    <?php if ($type == 'order') { ?>
        <div class="box coinOrder">
            <div class="co01 mCustomScrollbar" data-mcs-theme="dark">
                <table class="tb-status">
                    <colgroup>
                        <col width="31%">
                        <col width="23%">
                        <col width="15%">
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

                        $rating = round_down($row['tr_total'] / $row['tr_now'] * 100, 2);
                        $percent = round_down($row['tr_price'] / $coinInfo["ci_{$lowCurrency}"] * 100 - 100, 2);

                        $colorClass = '';
                        if ($percent > 0 || $row['tr_price'] > $coinInfo["ci_{$lowCurrency}"])
                            $colorClass = 'color-red ';
                        else if ($percent < 0 || $row['tr_price'] < $coinInfo["ci_{$lowCurrency}"])
                            $colorClass = 'color-skyblue ';
                        ?>
                        <tr data-price="<?= round_down_format_fix($row['tr_price'], 8) ?>"
                            data-total="<?= $row['tr_now'] ?>">
                            <td>
                                <p class="amount droid"><?= round_down_format($row['tr_total'], 8) ?></p>
                                <p class="bar" style="width:<?= $rating ?>%;"></p>
                            </td>
                            <!--TODO 기준보다 상승 시 color-red, 하락 시 color-skybluek, 기준 값은 class X-->
                            <td class="status-order-price">
                                <em class="<?= $colorClass ?>droid"><?= round_down_format_fix($row['tr_price'], 8) ?></em>
                                <span class="droid"><?=round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $row['tr_quantity'], 8)?> <?=$currency=='USDT'?'BTC':'USDT'?></span>
                            </td>
                            <td class="<?= $colorClass ?> droid"><?= $percent ?>%</td>
                            <td></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <!--TODO 매수-->
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

                        $rating = round_down($row['tr_total'] / $row['tr_now'] * 100, 2);
                        $percent = @round_down($row['tr_price'] / $coinInfo["ci_{$lowCurrency}"] * 100 - 100, 2);

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
                                <span class="droid"><?=round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $row['tr_quantity'], 8)?> <?=$currency=='USDT'?'BTC':'USDT'?></span>
                            </td>
                            <td class="<?= $colorClass ?>droid"><?= $percent ?>%</td>
                            <td>
                                <p class="amount droid"><?= round_down_format($row['tr_total'], 8) ?></p>
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
                            <span><?= lang('매도잔량', 'Sell Qty', '販売数量', '卖出余量') ?></span>
                            <em id="coin_total_sell"
                                class="droid"><?= round_down_format($coinInfo['ci_total_sell'] ?? 0, 8) ?></em>
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <span><?= lang('매수잔량', 'Buy Qty', '買収数量', '买进余额') ?></span>
                            <em id="coin_total_buy"
                                class="droid"><?= round_down_format($coinInfo['ci_total_buy'] ?? 0, 8) ?></em>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="co02 ex02">
                <ul class="co02Menu order-gnb">
                    <li class="active orderBuy" data-type="buying" data-coin="<?= $coin ?>" data-origin="<?= !empty($member) ? round_down_format($member["mb_{$lowCurrency}"], 8) : 0 ?>"><?=lang('매수', 'BUY', '購入', '收购')?></li>
                    <li class="orderSell" data-type="selling" data-origin="<?= !empty($member) ? round_down_format($member['mb_' . strtolower($coin)], 8) : 0 ?>"
                        data-coin="<?= $coin ?>"><?=lang('매도', 'SELL', '販売', '卖出')?></li>
                    <li class="orderWait" data-type="wait" data-coin="<?= $coin ?>"><?= lang('미체결', 'Outstanding', '未締結', '未签署') ?></li>
                </ul>
                <div class="co02Content mCustomScrollbar <?= ($member) ? '' : 'not-member' ?>">
                    <form class="co02Box order"
                          data-type="buying"
                          data-coin='<?= $coin ?>'
                          data-coinName='<?= $coinInfo["ci_{$country}_name"] ?>'
                          action="javascript:sendOrder('buying', '<?= $coin ?>',
                      '<?= $coinInfo["ci_{$country}_name"] ?>')">
                        <div class="my-max-value">
                            <p><?= lang('주문가능', 'Balance', '注文可能', '可订购') ?><br>
                                <em class="droid"
                                    data-buying="<?= !empty($member) ? round_down_format($member["mb_{$lowCurrency}"] / $coinInfo["ci_{$lowCurrency}"], 8) : 0 ?>"
                                    data-selling="<?= !empty($member) ? round_down_format($member['mb_' . strtolower($coin)], 8) : 0 ?>">
                                    <?= !empty($member) ? round_down_format($member["mb_{$lowCurrency}"] / $coinInfo["ci_{$lowCurrency}"], 8) : 0 ?>
                                </em>
                                <span><?= $currency ?></span>
                            </p>
                            <p class="max-value"><?= $coinInfo['ci_total'] ?></p>
                        </div>
                        <div class="order-area order-price-area order-price-ipt">
                            <input type="number" step="0.00000001" placeholder="<?= lang('가격', 'Price', '価格', '价格') ?>" name="price" class="order-ipt"
                                   value="<?= $coinInfo['ci_price'] ?>">
                            <div class="order-btn">
                                <button type="button" data-amount="<?=$coinInfo["ci_minimum_{$lowCurrency}_unit"]?>"><i
                                            class="xi-plus-thin"></i></button>
                                <button type="button" data-amount="-<?=$coinInfo["ci_minimum_{$lowCurrency}_unit"]?>"><i
                                            class="xi-minus-thin"></i></button>
                            </div>
                        </div>
                        <div class="order-area order-amount-area order-price-ipt">
                            <input type="number" placeholder="<?= lang('수량', 'Quantity', '数量', '数量') ?>"
                                   class="order-ipt" name="amount" step="0.00000001">
                            <div class="order-btn order-price-select">
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
                        </div>
                        <p class="order-alert">*<?= lang('최소 주문 수량', 'Minimum Order Amount', '最小注文金額', '最低订货数量') ?> :
                            0.00000001 <?= $coin ?></p>
                        <div class="order-total-area">
                            <div class="order-total">
                                <span><?= lang('주문총액', 'Total', '注文総額', '订货总额') ?></span><br>
                                <span class="droid"><em class="totalPrice droid">0</em> <?= $currency ?></span>
                            </div>
                            <div class="order-fee" style="display: none">
                                <span class="fee"
                                      data-fee="<?= $main_fee ?>"><?= lang('수수료', 'Fee', '手数料', '手续费') ?> (<?= $main_fee ?>%)</span>
                                <span class="droid"><em class="droid">0</em> <?= $currency ?></span>
                            </div>
                        </div>
                        <button class="btn-full btn-red order-submit btn-submit"
                                type="submit"><?=lang('매수', 'BUY', '購入', '收购')?></button>
                    </form>
                    <div class="co02Box none mCustomScrollbar" data-type="wait" data-mcs-theme="dark">
                        <?php
                        $db = new db();
                        @$query = "SELECT * FROM _trade WHERE tr_success_time IS NULL AND mb_id = '{$member['mb_id']}' AND tr_symbol = '{$coin}' ORDER BY tr_no DESC LIMIT 0, 20";
                        $coins = $db->fetchAll($query);
                        foreach ($coins as $row) {
                            $row = objectToArray($row);
                            ?>
                            <div class="waitBox <?= $row['tr_type'] ?> <?= $row['tr_type'] == 0 ? 'sell' : 'buy' ?>">
                                <p class="waitType">
                                    <?= lang('지정가 ', '', '指値', '限价') . ($row['tr_type'] == 0 ? lang('매도', 'Sell', '販売') : lang('매수', 'Buy', '購買')) ?>
                                </p>
                                <p class="waitTitle">
                                    <?= $coinInfo["ci_{$country}_name"] ?> (<?= $coin ?>/<?=$row['tr_currency']?>)
                                </p>
                                <p class="waitSub">
                                    <span><?= lang('주문 수량', 'Amount', '価格', '订货数量') ?></span>
                                    <span><?= round_down_format($row['tr_amount'], 8) ?> <?= $coin ?></span>
                                </p>
                                <p class="waitSub">
                                    <span><?= lang('미체결 주문', 'Outstanding Orders', '未締結注文', '未签署订单') ?></span>
                                    <span><?= round_down_format($row['tr_quantity'], 8) ?> <?= $coin ?></span>
                                </p>
                                <button class="btn-cancel"
                                        onclick="tradeCancel(<?= $row['tr_no'] ?>, '<?= $row['tr_symbol'] ?>')"><?= lang('취소', 'Cancel', 'キャンセル') ?></button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($type == 'chart') { ?>
        <div class="box coinChart">
            <iframe src="//chart.genesis-ex.com/tx/newChart.php?lang=<?= $country == 'ch' ? 'zh' : $country ?>&coin=<?= $coin ?>&currency=<?= $currency ?>" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>
        </div>
    <?php } ?>

    <?php if ($type == 'market') { ?>
        <div class="box coinMarket">
            <ul class="cmMenu">
                <li class="active" data-type="real"><?=lang('실시간 체결 현황', 'Real-time tightening', 'リアルタイム締結履歴', '实时签署现状')?></li>
                <li data-type="daily"><?=lang('일별 체결 현황', 'Daily tightening', '日別の締結履歴', '每日签订现状')?></li>
            </ul>
            <div class="cmContent real">
                <table class="cmTable thead">
                    <colgroup>
                        <col width="23%">
                        <col width="37%">
                        <col width="40%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?= lang('체결 시각', 'Time', '完了時刻', '完成时间') ?></th>
                        <th><?= lang('체결 가격', 'Closing price', '締結価格', '签订价格') ?></th>
                        <th><?= lang('주문 수량', 'Amount', '価格', '订货数量') ?> ( <?= $coin ?>)</th>
                    </tr>
                    </thead>
                </table>
                <div class="tbody mCustomScrollbar" data-mcs-theme="dark">
                    <table class="cmTable tbody">
                        <colgroup>
                            <col width="23%">
                            <col width="37%">
                            <col width="40%">
                        </colgroup>
                        <tbody>
                        <?php
                        $db = new db();
                        $query = "SELECT * FROM _orders WHERE od_coin = '{$coin}' ORDER BY od_no DESC LIMIT 0, 20";
                        $coins = $db->fetchAll($query);
                        foreach ($coins as $row) {
                            $row = objectToArray($row);
                            ?>
                            <tr>
                                <td class="droid"><?= date('H:i:s', $row['od_datetime']) ?></td>
                                <td class="droid"><?= number_format($row['od_price']) ?></td>
                                <td class="droid color-<?= $row['od_type'] == 0 ? 'skyblue' : 'red' ?>"><?= round_down_format($row['od_amount'], 4) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="cmContent daily none">
                <table class="cmTable thead">
                    <colgroup>
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="text-center"><?= lang('일자', 'Date', '一字', '日期') ?></th>
                        <th><?= lang('종가', 'Closing price', '宗家', '收盘价') ?>(<?= $coin ?>)</th>
                        <th><?= lang('전일대비', '24h', '前日比', '与前一天相比') ?></th>
                        <th><?= lang('거래량', 'Volume', '取引数量', '交易量') ?></th>
                    </tr>
                    </thead>
                </table>
                <div class="tbody mCustomScrollbar" data-mcs-theme="dark">
                    <table class="cmTable tbody">
                        <colgroup>
                            <col width="25%">
                            <col width="25%">
                            <col width="25%">
                            <col width="25%">
                        </colgroup>
                        <tbody>
                        <?php
                        $db = new db();
                        $query = "SELECT * FROM _coinDaily WHERE cd_symbol = '{$coin}' ORDER BY cd_datetime DESC LIMIT 0, 20";
                        $coins = $db->fetchAll($query);
                        foreach ($coins as $row) {
                            $row = objectToArray($row);
                            ?>
                            <tr>
                                <td class="text-center droid"><?= date('Y-m-d', $row['cd_datetime']) ?></td>
                                <td class="droid"><?= round_down_format($row["cd_{$lowCurrency}"], 8) ?></td>
                                <td class="droid <?= $row["cd_{$lowCurrency}_percent"] < 0 ? 'color-skyblue' : ($row["cd_{$lowCurrency}_percent"] > 0 ? 'color-red' : '') ?>"><?=round_down($row["cd_{$lowCurrency}_percent"], 2)?>%</td>
                                <td class="droid <?= $row["cd_{$lowCurrency}_percent"] < 0 ? 'color-skyblue' : ($row["cd_{$lowCurrency}_percent"] > 0 ? 'color-red' : '') ?>"><?= round_down_format("cd_{$lowCurrency}_amount", 8) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($type == 'info') { ?>
        <div class="box coinData">
            <div class="cdHead">
                <div class="cd-title">
                    <img src="<?= $coinInfo['ci_symbol_url'] ?>">
                    <p><?= $coinInfo["ci_{$country}_name"] ?>(<?= $coinInfo['ci_symbol'] ?>)</p>
                </div>
                <div class="cd-link">
                    <a href="<?= $coinInfo['ci_url'] ?>" target="_blank"><?=lang('홈페이지', 'Homepage', 'ホームページ','网页')?></a>
                    <a href="<?= $coinInfo['ci_book'] ?>" target="_blank"><?=lang('백서', 'White Paper', '白書','白皮书')?></a>
                </div>
            </div>
            <div class="cdContent">
                <p class="cd-ctitle"><?=lang('코인 정보', 'Coin Information', 'コイン情報','硬币信息')?></p>
                <p><em><?=lang('최초발행', 'Initial issue', '初発','首次发行')?></em><span><?= $coinInfo['ci_start'] ?></span></p>
                <p><em><?=lang('발행량', 'Issued volume', '発行量','发行量')?></em><span><?= number_format($coinInfo['ci_count']) ?></span></p>
                <p class="cd-ctitle" style="margin-top:10px;"><?=lang('코인 소개', 'About Coin', 'コイン紹介','硬币介绍')?></p>
                <div class="cd-info"><?= nl2br($coinInfo["ci_{$country}_info"]) ?></div>
            </div>
        </div>
    <?php } ?>
</div>