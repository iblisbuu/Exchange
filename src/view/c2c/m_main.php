<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/c2c/m_main.css">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/c2c/m_main.js"></script>');

$type = (isset($_GET['type'])) ? $_GET['type'] : 'order';
?>
<div class="account-section">
    <a href="javascript:history.back()" class="hd-back">
        <i class="xi-angle-left-thin"></i>
    </a>
    <?= lang('개인거래', 'C2C', '個人取引', '个人交易') ?>
</div>
<div class="c2c-search-box">
    <label><?= lang('개인거래', 'C2C', '個人取引', '个人交易') ?></label>
    <?php if (!empty($member)) { ?><a href="/c2c/create/<?= $createType ?>"><?= lang('개인거래 생성', 'Create a C2C', '個人取引の作成', '个人交易生成') ?></a><?php } ?>
    <input class="search" type="text" maxlength="15" placeholder="<?= lang('주문 번호, 코인명 / 심볼 검색', 'Order Number, Coin Name / Symbol Search', '注文番号、コイン名/シンボル検索', '订购编号,硬币名/搜索') ?>">
    <button class="btn-search"></button>
</div>
<div class="c2c-lists-div">
    <div class="account-tap">
        <ul>
            <li><a href="/c2c/main/buy" class="buy-tab"><?= lang('구매', 'Buy', '購入', '采购') ?></a></li>
            <li><a href="/c2c/main/sell" class="sell-tab"><?= lang('판매', 'Sell', '販売', '销售') ?></a></li>
            <?php if (!empty($member)) { ?><li><a href="/c2c/main/my" class="my-tab"><?= lang('나의 거래', 'My deal', '私の取引', '我的买卖')?></a></li><?php } ?>
        </ul>
    </div>
    <div class="c2c-lists-box">
        <div class="lists-head">
            <ul>
                <li class="sortation none"><?= lang('구분', 'Sortation', '区分け', '區分') ?></li>
                <li>NO</li>
                <li><?= lang('코인명', 'Coin Name', 'コイン名', '科因明') ?></li>
                <li><?= lang('가격', 'Price', '価格', '价格') ?></li>
                <li><?= lang('달성률', 'Rate', '達成率', '达成率') ?></li>
            </ul>
        </div>
        <div class="lists-body">
            <ul class="lists-ul">
            <?php
                $nowTime = time();
                $db = new db();
                $query = "SELECT ps_type, ps_no, ps_symbol, ps_secret, ps_price, ps_amount, ps_quantity, ps_volume, ps_oktime, ps_currency,

                    (SELECT ci_{$country}_name FROM _coins C WHERE C.ci_symbol = P.ps_symbol) AS coin_name

                    FROM _personals P
                    WHERE (1) AND
                    ps_endtime >= '{$nowTime}' " . ($_GET['type'] == 'my' ? " AND mb_id = '{$member['mb_id']}' " : ($_GET['type'] == 'buy' ? ' AND ps_type = 0' : 'AND ps_type = 1')) . "
                    ORDER BY ps_oktime IS NULL DESC, ps_datetime DESC";
                $coins = $db->fetchAll($query);
                foreach ($coins as $row) {
                    $row = objectToArray($row);

                    $thisSymbol = strtoupper($row['ps_symbol']);
                    $thisPercent = round_down($row['ps_volume'] / $row['ps_amount'] * 100, 2);
                    ?>
                    <li>
                        <ul class="list<?= $row['ps_oktime'] != null ? ' finish' : '' ?><?= $row['ps_secret'] != 0 ? ' locked' : '' ?>"data-order="<?= $row['ps_no'] ?>">
                            <li class="sortation none"><?= $row['ps_type'] != 0 ? lang('구매', 'Buy', '買い', '采购') : lang('판매', 'Sell', '売り', '销售') ?></li>
                            <li><span><?= $row['ps_no'] ?></span></li>
                            <li class="coin">
                                <img class="coin-img" src="/public/img/coin/<?= $thisSymbol ?>.png">
                                <div>
                                    <span class="td-main<?= $row['ps_secret'] != 0 ? ' locked' : '' ?>"><?= $row['coin_name'] ?></span>
                                    <p class="td-sub"><?= $thisSymbol ?></p>
                                </div>
                            </li>
                            <li>
                                <span class="td-main"><?= round_down_format($row['ps_price'], 5) ?> <?= trim($row['ps_currency']) == '' ? 'USDT' : $row['ps_currency'] ?></span>
                                <p class="td-sub">1 <?= $thisSymbol ?></p>
                            </li>
                            <li><span><?= $thisPercent ?>%</span></li>
                        </ul>
                    </li>
                <?php }
                if (count($coins) == 0) { ?>
                    <li class="no-list"><?= lang('거래목록이 없습니다.', 'No transaction list.', '取引リストがありません。', '交易目录不存在。') ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<div class="c2c-detail-div none">
    <div class="c2c-details">
        <div class="c2c-type-box">
            <div class="my-type"><?= lang('구매', 'Buy', '購入', '采购') ?></div>
        </div>
        <div class="c2c-type">
            <div class="my-c2c-type none"></div>
            <div class="c2c-no"></div>
            <img src="/public/img/coin/BTC.png">
            <div class="c2c-type-info">
                <span>비트코인</span>
                <p>BTC</p>
            </div>
        </div>
        <div class="c2c-detail-box">
            <div class="c2c-price-box">
                <span>0</span>
                <p>USDT / 1 BTC</p>
            </div>
            <div class="c2c-bar-box">
                <div class="c2c-bar">
                    <div class="percent-bar"></div>
                </div>
                <div class="c2c-bar-desc-box">
                    <span><?= lang('달성률 ', 'Rate', '達成率 ', '达成率') ?> <span>0%</span></span>
                    <p><?= lang('거래 종료일', 'Transaction End Date', '取引終了日', '交易终止日') ?> : <span>0시간</span> <?= lang(' 남음', ' left', ' 余り', ' 南音') ?></p>
                </div>
            </div>
            <div class="c2c-quantity-box">
                <span class="remain"><?= lang('잔여수량 ', 'Remaining quantity ', '残余数量 ', '剩余数量 ') ?><span>7 BTC</span></span>
                <span class="total"><?= lang(' / 목표량 ', '/ Target quantity ', ' / 目標量 ', ' / 目标量') ?><span>10 BTC</span></span>
            </div>
        </div>
        <div class="c2c-order-box">
            <div class="c2c-order">
                <span><?= lang('주문가능', 'Orderable', '注文可', '可订购') ?></span>
                <p><?= $member ? round_down_format($member['mb_usdt'], 4) : 0 ?> <span>USDT</span></p>
            </div>
            <div class="c2c-quantity">
                <span><?= lang('수량', 'Quantity', '数量', '数量') ?></span>
                <div class="order-input-div">
                    <input type="text" class="order-input">
                    <em>USDT</em>
                </div>
            </div>
            <div class="order-btn-div">
                <button>10%</button>
                <button>25%</button>
                <button>50%</button>
                <button>100%</button>
            </div>
            <button class="c2cBtn"></button>
        </div>
        <div class="c2c-desc-box">
            <span><?= lang('유의사항', 'Precaution', '留意事項', '注意事项') ?></span>
            <p>- <?= lang('GENESIS.EX는 개인거래의 중개자이며, 당사자가 아닙니다.',
                    'GENESIS.EX is the broker of personal transactions and is not a party.',
                    'GENESIS.EXは個人取引の仲介者であり、当事者ではありません。',
                    'GENESIS.EX是个人交易的中介,不是当事人。') ?></p>
            <p>- <?= lang('회원간 개인거래에 대하여 GENESIS.EX는 책임을 지지 않습니다.',
                    'GENESIS.EX is not responsible for personal transactions among members.',
                    '会員間の個人取引についてGENESIS.EXは責任を負いません。',
                    'GENESIS.EX不对会员间的个人交易负责。') ?></p>
        </div>
    </div>
</div>