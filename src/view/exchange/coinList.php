<div class="searchBox">
    <div class="search-box">
        <input type="text" class="search"
               placeholder="<?= lang('이름/심볼 검색', 'Search Name/symbol', 'コイン/シンボル検索', '姓名/符号搜索') ?>">
        <button type="button" class="btn-search"></button>
    </div>
    <div class="search-type-box">
        <button type="button" class="active" data-search="all"><?= lang('전체', 'ALL', '全て', '全体') ?></button>
        <button type="button"
                data-search="have"><?= lang('보유코인', 'Holding Coin', '保有コイン', '保留硬币') ?></button>
        <button type="button"
                data-search="bookmark"><?= lang('관심코인', 'Interest Coin', '関心コイン', '关注线') ?></button>
    </div>
</div>
<div class="coinBox">
    <div class="ex01-type coin-type">
        <button type="button" data-type="btc"<?= $currency == 'BTC' ? ' class="active"' : '' ?>>BTC</button>
        <button type="button" data-type="usdt"<?= $currency == 'USDT' ? ' class="active"' : '' ?>>USDT
        </button>
    </div>
    <table class="tb-highlight">
        <colgroup>
            <col width="10%">
            <col width="21%">
            <col width="23%">
            <col width="23%">
            <col width="27%">
        </colgroup>
        <thead>
        <tr>
            <td colspan="2">
                <button onclick="tableSort(this,'name')" type="button"
                        class="sort"><?= lang('코인명', 'Coin', '', '科因明') ?></button>
            </td>
            <td>
                <button onclick="tableSort(this,'price',true)" type="button"
                        class="sort"><?= lang('현재가', 'Price', '', '现价') ?></button>
            </td>
            <td>
                <button onclick="tableSort(this,'change')" type="button"
                        class="sort"><?= lang('전일대비', '24h', '', '全日对比') ?></button>
            </td>
            <td>
                <button onclick="tableSort(this,'vol',true)" type="button"
                        class="sort"><?= lang('거래대금', 'Vol', '', '成交金额') ?></button>
            </td>
        </tr>
        </thead>
        <tbody>
        <?php
        $db = new db();
        $query = "SELECT 
                    ci_btc, ci_usdt, ci_symbol, ci_btc_total, ci_usdt_total, ci_{$country}_name, ci_{$lowCurrency}_percent, 
                    (SELECT cd_usdt FROM _coinDaily AS D WHERE C.ci_symbol = D.cd_symbol ORDER BY cd_datetime DESC LIMIT 0, 1) as cd_usdt, 
                    (SELECT cd_btc FROM _coinDaily AS D WHERE C.ci_symbol = D.cd_symbol ORDER BY cd_datetime DESC LIMIT 0, 1) as cd_btc, 
                    (SELECT sum(tr_amount) FROM _trade AS T WHERE T.tr_symbol = C.ci_symbol AND tr_currency = 'BTC' AND tr_datetime >= '{$yesterTime}') as ci_btc_total_price,
                    (SELECT sum(tr_amount) FROM _trade AS T WHERE T.tr_symbol = C.ci_symbol AND tr_currency = 'USDT' AND tr_datetime >= '{$yesterTime}') as ci_usdt_total_price
                  FROM _coins AS C WHERE ci_use = 0 ";
        $coins = $db->fetchAll($query);
        foreach ($coins as $row) {
            $row = objectToArray($row);

            $coinName = strtolower($row['ci_symbol']);

            $coinPrice = $yesPercent = [];

            $coinPrice['btc'] = round($row['ci_btc_total_price'] * $row['ci_btc'] * $_USDT_PRICE / 1000000, 1); // 백만단위 나누기
            $coinPrice['btc'] = $coinPrice['btc'] . lang('백만', 'M');

            $coinPrice['usdt'] = round($row['ci_usdt_total_price'] * $row['ci_usdt'] * $_USDT_PRICE / 1000000, 1); // 백만단위 나누기
            $coinPrice['usdt'] = $coinPrice['usdt'] . lang('백만', 'M');

            $yesPercent['btc'] = round_down_format_fix(($row['ci_btc'] / $row['cd_btc'] * 100) - 100, 2);
            $yesPercent['usdt'] = round_down_format_fix(($row['ci_usdt'] / $row['cd_usdt'] * 100) - 100, 2);

            if($row['ci_btc'] == 0 && $row['cd_btc'] > 0)
                $yesPercent['btc'] = '-100.00';
            if($row['ci_usdt'] == 0 && $row['cd_usdt'] > 0)
                $yesPercent['usdt'] = '-100.00';

            $color = $yesPercent[$lowCurrency] > 0 ? 'color-red' : ($yesPercent[$lowCurrency] < 0 ? 'color-skyblue' : '');

            $href = "/exchange/main/{$row['ci_symbol']}" . ($currency != '' ? '?currency=' . trim($currency) : '');
            ?>
            <tr class="<?= $coin == $row['ci_symbol'] ? ' select ' : '' ?><?= $currency == $row['ci_symbol'] ? ' none ' : '' ?>">
                <td>
                    <span class="bookmark<?= strpos($interest, $row['ci_symbol']) !== false ? ' active' : '' ?><?= @$member['mb_' . strtolower($row['ci_symbol'])] > 0 ? ' have' : '' ?>" data-type="<?= $coinName ?>"></span>
                </td>
                <td class="tit">
                    <a data-type="name" href="<?=$href?>"><?=$row["ci_{$country}_name"]?></a>
                    <em style="cursor: pointer" onclick="location.href='<?=$href?>'"><?= $row['ci_symbol'] ?>/<span class="currency"><?= $currency ?></span></em>
                </td>
                <td class="droid <?= $color ?>" id="nowPirce"
                    data-coin="<?= $row['ci_symbol'] ?>"
                    data-type="price" data-btc="<?= round_down_format($row['ci_btc'], 8) ?>"
                    data-usdt="<?= round_down_format($row['ci_usdt'], 8) ?>"><?= round_down_format($row["ci_{$lowCurrency}"], 8) ?></td>
                <td class="droid <?= $color ?>" data-coin="<?= $row['ci_symbol'] ?>"
                    data-type="change" data-btc="<?= $yesPercent['btc'] ?>%" data-usdt="<?= $yesPercent['usdt'] ?>%"><?= $yesPercent[$lowCurrency] ?>%
                </td>
                <td class="droid" id="nowTotal" data-coin="<?= $row['ci_symbol'] ?>"
                    data-type="vol" data-btc="<?= $coinPrice['btc'] ?>"
                    data-usdt="<?= $coinPrice['usdt'] ?>"><?= $coinPrice[$lowCurrency] ?></td>
            </tr>
            <?php
        }
        unset($coinPrice);
        unset($yesPercent);
        ?>
        </tbody>
    </table>
</div>