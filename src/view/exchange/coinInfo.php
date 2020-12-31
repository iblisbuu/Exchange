<?php
$coinRate = round_down(($coinInfo["ci_{$lowCurrency}"] / $coinInfo["cd_{$lowCurrency}"] * 100) - 100, 2);
if($coinInfo["ci_{$lowCurrency}"] == 0 && $coinInfo["cd_{$lowCurrency}"] > 0)
    $coinRate = -100;
?>
<div class="coinInfo<?=$device!='mobile'?' box':''?>">
    <div class="coinInfo01 <?= $coinRate >= 0 ? ' increase' : ' decrease' ?>">
        <?php if($device != 'mobile'){ ?>
        <div class="cTit">
            <h2><?= $coinInfo["ci_{$country}_name"] ?></h2>
            <div>
                <span class="cUnit"><?= $coin ?>/<?= $currency ?></span>
                <span class="bookmark<?= strpos($interest, $coin) !== false ? ' active' : '' ?>"
                      data-type="<?= strtolower($coin) ?>"></span>
                <span class="info">
                    <span class="none" data-type='symbol_url'><?= $coinInfo['ci_symbol_url'] ?></span>
                    <span class="none" data-type='coin_name'><?= $coinInfo["ci_{$country}_name"] ?></span>
                    <span class="none" data-type='symbol'><?= $coinInfo['ci_symbol'] ?></span>
                    <span class="none" data-type='url'><?= $coinInfo['ci_url'] ?></span>
                    <span class="none" data-type='book'><?= $coinInfo['ci_book'] ?></span>
                    <span class="none" data-type='start'><?= $coinInfo['ci_start'] ?></span>
                    <span class="none" data-type='count'><?= number_format($coinInfo['ci_count']) ?></span>
                    <span class="none" data-type='info'><?= $coinInfo["ci_{$country}_info"] ?></span>
                    <button type="button" onclick="openInfoPopup(this)"></button>
                </span>
            </div>
        </div>
        <?php }?>
        <div class="cValue">
            <span class="cMainValue droid"><?= round_down_format($coinPagePrice, 8) ?> <?= $currency ?></span>
            <span class="cSubValue droid"><?=round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')], 8)?> <?=$currency=='USDT'?'BTC':'USDT'?></span>
        </div>
        <div class="cPercent">
            <?php if($device != 'mobile'){ ?>
            <span class="color-gray-light"><?= lang('전일대비', '24h', '前日比', '全日对比') ?></span>
            <?php }?>
            <span class="droid" id="cMainPercent">
                <?= round_down_format_fix($coinRate, 2); ?>%
            </span>
            <span class="droid">
                (<?= '<span id="cdayUpdown">' . ($coinRate >= 0 ? ($coinRate == 0 ? '' : '▲') : '▼') . '</span> <span id="cdayValue">' . round_down_format($coinInfo["ci_{$lowCurrency}"] - $coinInfo["cd_{$lowCurrency}"], 8) . '</span>' ?> <?= $currency ?>)
            </span>
        </div>
    </div>
    <?php if($device != 'mobile'){ ?>
    <div class="coinInfo02 color-gray-light">
        <div class="high-low">
            <div class="high">
                <span><?= lang('고가', 'High', '高価', '高价') ?></span>
                <span><em class="color-red droid"><?= round_down_format($coinInfo['ci_high_price'] ?? 0, 0) ?></em><?= $currency ?></span>
            </div>
            <div class="low">
                <span><?= lang('저가', 'Low', '低価', '高价') ?></span>
                <span><em class="color-skyblue droid"><?= round_down_format($coinInfo['ci_low_price'] ?? 0, 0) ?></em><?= $currency ?></span>
            </div>
        </div>
        <div class="volume-amount">
            <div class="volume">
                <span><?= lang('거래량', 'Base Volume', '取引量', '交易量') ?></span>
                <span><em class="droid"><?= round_down_format($coinInfo['ci_total_coin'] ?? 0, 8) ?></em><?= $coin ?></span>
            </div>
            <div class="amount">
                <span><?= lang('거래대금', 'Quote Volume', '取引数量', '成交金额') ?></span>
                <div>
                        <span>
                            <em class="droid">
                                <?= round($coinInfo["ci_{$lowCurrency}_total_price"] * $coinInfo["ci_{$lowCurrency}"] * $_USDT_PRICE / 1000000, 1) ?>
                            </em><?= lang('백만', 'M') ?>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>