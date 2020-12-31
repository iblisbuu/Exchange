<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/c2c/main.js"></script>');

$_GET['type'] = ($segment[2]) ?? 'buy';
if (empty($member) && $_GET['type'] == 'my') {
    add_event('alert_hooks', 'not_login');
    return false;
}

$createType = ($_GET['type'] == 'my') ? 'buy' : $_GET['type'];
// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/c2c/m_main.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/c2c/main.css?ver=' . time() . '">');
    ?>
<section class="account-section common-bg">
    <div class="wrap-middle">
        <h1><?= lang('개인거래', 'C2C', '個人取引', '个人交易') ?></h1>
        <div class="account-div c2c-div">
            <div class="account-tap">
                <div>
                    <a href="/c2c/main/buy" class="buy-tab"><?= lang('구매', 'Buy', '購入', '采购') ?></a>
                    <a href="/c2c/main/sell" class="sell-tab"><?= lang('판매', 'Sell', '販売', '销售') ?></a>
                    <?php if (!empty($member)) { ?><a href="/c2c/main/my"
                                                      class="my-tab"><?= lang('나의 거래', 'My deal', '私の取引', '我的买卖')
                        ?></a><?php } ?>
                </div>
                <?php if (!empty($member)) { ?><a href="/c2c/create/<?= $createType ?>"
                                                  class="btn btn-yellow-border"><?= lang('개인거래 생성', 'Create a C2C', '個人取引の作成', '个人交易生成') ?></a><?php } ?>
            </div>
            <!--    ************* 구매 / 판매 **************       -->
            <div class="common-yellow-box ">
                <div class="c2c-lists-div ">
                    <div class="c2c-search-box">
                        <input type="text" class="search"
                               placeholder="<?= lang('주문 번호, 코인명 / 심볼 검색', 'Order Number, Coin Name / Symbol Search', '注文番号、コイン名/シンボル検索', '订购编号,硬币名/搜索') ?>">
                        <button type="button" class="btn-search"></button>
                        </div>
                        <div class="c2c-lists-box">
                            <div class="lists-head">
                                <ul>
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
                                    $query = "SELECT ps_no, ps_symbol, ps_secret, ps_price, ps_amount, ps_quantity, ps_volume, ps_oktime, ps_currency,

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
                                        <ul class="list<?= $row['ps_oktime'] != null ? ' finish' : '' ?><?= $row['ps_secret'] != 0 ? ' locked' : '' ?>"
                                            data-order="<?= $row['ps_no'] ?>">
                                            <li><span><?= $row['ps_no'] ?></span></li>
                                            <li class="coin">
                                                <img src="/public/img/coin/<?= $thisSymbol ?>.png">
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
                <div class="c2c-detail-div">
                    <div class="no-content">
                        <div class="no-content-desc">
                            <?= lang('왼쪽 목록에서 원하시는 거래를 선택해 주세요.<br>선택 시 해당 거래의 상세 내용으로 전환됩니다.',
                                'Select the transaction you want from the list on the left.<br>When selected, the details of the transaction will be converted.',
                                '左側のリストからご希望の取引を選択してください。<br>選択時に、その取引の詳細に切り替わります。',
                                '请选择左侧目录中想要的交易。<br>选择时可转换为相应交易的详细内容。') ?>
                            </div>
                        </div>
                        <div class="c2c-details none">
                            <div class="c2c-type-box">
                                <div>
                                    <div class="my-type none"></div>
                                    <div class="c2c-no"></div>
                                </div>
                                <div class="c2c-type">
                                    <img src="/public/img/coin/BTC.png" width="40">
                                    <div>
                                        <span>비트코인</span>
                                        <p>BTC</p>
                                    </div>
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
                                        <span><?= lang('달성률 ', 'Rate', '達成率 ', '达成率') ?> <b>0%</b></span>
                                        <p><?= lang('거래 종료일', 'Transaction End Date', '取引終了日', '交易终止日') ?> :
                                            <span>0시간</span><?= lang(' 남음', ' left', ' 余り', ' 南音') ?></p>
                                    </div>
                                </div>
                                <div class="c2c-quantity-box">
                                    <span class="remain"><?= lang('잔여수량', 'Remaining quantity', '残余数量', '剩余数量') ?> <span>0 BTC</span></span>
                                    <span class="total"><?= lang(' / 목표량 ', '/ Target quantity ', ' / 目標量 ', ' / 目标量') ?><span>0
                                            BTC</span></span>
                                </div>
                            </div>

                            <div class="c2c-order-box">
                                <div>
                                    <span><?= lang('주문가능', 'Orderable', '注文可', '可订购') ?></span>
                                    <p><?= $member ? round_down_format($member['mb_usdt'], 4) : 0 ?> USDT</p>
                                </div>
                                <div>
                                    <span><?= lang('수량', 'Quantity', '数量', '数量') ?></span>
                                    <div class="order-input-div">
                                        <input type="text" class="order-input"><em>USDT</em>
                                    </div>
                                </div>
                                <div class="order-btn-div">
                                    <button>10%</button>
                                    <button>25%</button>
                                    <button>50%</button>
                                    <button>100%</button>
                                </div>
                                <button class="btn btn-yellow c2cBtn"></button>
                            </div>

                            <div class="c2c-desc-box">
                                <span> <?= lang('유의사항', 'Precaution', '留意事項', '注意事项') ?></span>
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
                </div>
            </div>
        </div>
    </section>
    <?php
}
?>