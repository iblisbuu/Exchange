<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/c2c/calendar.css?ver=' . time() . '">');
// jQuery 기본 js파일
add_stylesheet('<link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css"/>');
// jQuery 기본 js파일
add_javascript('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>');
// jQuery UI 라이브러리 js파일
add_javascript('<script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>');
add_javascript('<script src="' . ROOT . 'public/js/common/popup.js"></script>  ');
add_javascript('<script src="' . ROOT . 'public/js/c2c/create.js"></script>  ');

$_GET['type'] = $segment[2];
$type = (isset($_GET['type'])) ? $_GET['type'] : 'buy';
$type_title = ($type == 'buy') ? lang('구매', 'buy', '購入', '采购') : lang('판매', 'sell', '販売', '销售');
$type_symbol = ($type == 'buy') ? 'USDT' : 'BTC';

$waiting = [];

$db = new db();
$query = "SELECT ci_symbol, 
            ( SELECT SUM(tr_amount) AS total FROM _trade T WHERE T.tr_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_success_time IS NULL ) as trade_amount,
            ( SELECT SUM(ps_amount) AS total FROM _personals P WHERE P.ps_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND ps_oktime IS NULL ) as personal_amount 
          FROM _coins C WHERE ci_use = 0";
$coins = $db->fetchAll($query);
foreach ($coins as $row)
    $waiting[strtolower($row->ci_symbol)] = round_down_format($row->trade_amount + $row->personal_amount, 4);

    if ($device == 'mobile') {
        include_once VIEW_ROOT . "/c2c/m_create.php";
    } else {
        // PC 일 경우
        add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/c2c/create.css?ver=' . time() . '">');

?>

<section class="common-bg">
    <div class="cre-title">
        <h1><?= lang('개인거래', 'C2C', '個人取引', '个人交易') ?></h1>
        <a href="/c2c/main" class="btn-yellow-border"><?= lang('목록으로 돌아가기', 'Return to List', 'リストに戻る', '回到目录') ?></a>
    </div>
    <form class="common-yellow-box <?= $type ?>" autocomplete="off" action="javascript:createC2CSub('<?= $type ?>')">
        <h1 class="common-title"><?= lang('개인거래 생성', 'Create a C2C', '個人取引の作成', '个人交易生成') ?></h1>
        <div class="common-input-group">
            <label><?= lang('거래 유형', 'Transaction Type', '取引タイプ', '交易类型') ?></label>
            <div class="cre-chk-box">
                <input type="radio" name="c2c_type" id="buy"
                    <?php if ($type == 'buy') echo 'checked' ?>>
                <label class="lnb-chk" for="buy"><?= lang('구매', 'Buy', '購入', '采购') ?></label>
                <input type="radio" name="c2c_type" id="sell"
                    <?php if ($type == 'sell') echo 'checked' ?>>
                <label class="lnb-chk" for="sell"><?= lang('판매', 'Sell', '販売', '销售') ?></label>
            </div>
        </div>
        <div class="common-input-group">
            <label><?= lang('공개 범위', 'Public scope', '公開範囲', '公开范围') ?></label>
            <div class="cre-chk-box">
                <input type="radio" name="c2c_range" id="setPublic" checked>
                <label class="lnb-chk" for="setPublic"><?= lang('전체공개', 'All', '完全な情報開示', '全体公开') ?></label>
                <input type="radio" name="c2c_range" id="setPassword">
                <label class="lnb-chk"
                       for="setPassword"><?= lang('비밀번호 설정', 'Password Settings', 'パスワードの設定', '密码设置') ?></label>
            </div>
        </div>
        <div class="common-input-group cre-password-box none">
            <label><?= lang('비밀번호 입력', 'Enter Password', 'パスワード入力', '输入密码') ?></label>
            <div class="common-input">
                <input type="password" placeholder="<?= lang('숫자 6자리', 'six digits', '数字6桁', '数字六位数') ?>"
                       id="c2cPassword">
            </div>
        </div>
        <div class="common-input-group">
            <label><?= lang('거래 종료일', 'Transaction End Date', '取引終了日', '交易终止日') ?></label>
            <div class="common-input">
                <input type="text"
                       placeholder="<?= lang('(최대 30일까지 가능)', '(A maximum of 30 days)', '(最大30日まで可能)', '(最多可到30日)') ?>"
                       id="c2cFinalDate" readonly>
            </div>
        </div>
        <div class="common-input-group">
            <label><?= lang($type_title . '할 코인',
                    'Price of coins to ' . $type_title,
                    $type_title . 'するコイン',
                    '要求' . $type_title . '的硬币') ?></label></label>
            <div class="common-input cre-coin">
                <input type="text"
                       placeholder="<?= lang('코인명/심볼명', 'coin/symbol', 'コイン名 / シンボル名', '输入硬币名/符号') ?>"
                       id="c2cCoin" readonly>
                <ul>
                    <?php // 구매
                    if ($type == 'buy') {
                        $db = new db();
                        $query = "SELECT ci_price, ci_symbol, ci_{$country}_name, ci_percent
                                      FROM _coins
                                      WHERE ci_use = 0";
                        $coins = $db->fetchAll($query);
                        foreach ($coins as $row) {
                            $row = objectToArray($row);
                            $coinSymbol = strtoupper($row['ci_symbol']);
                            $coinName = strtoupper($row["ci_{$country}_name"]);
                            ?>
                            <li><?= $coinName . " / " . $coinSymbol ?></li>
                            <?php
                        }
                    } else { // 판매
                        $query = "SELECT ci_price, ci_symbol, ci_{$country}_name, ci_percent,
                                        (SELECT SUM(tr_quantity) as total
                                            FROM _trade T
                                            WHERE T.tr_symbol = C.ci_symbol
                                            AND mb_id = '{$member['mb_id']}'
                                            AND tr_success_time IS NULL AND tr_type = 0
                                        ) AS trading_coin
                                      FROM _coins C
                                      WHERE ci_use = 0";
                        $coins = $db->fetchAll($query);
                        $count = 0;
                        foreach ($coins as $row) {
                            $row = objectToArray($row);

                            $symbol_low = strtolower($row['ci_symbol']);
                            $coinSymbol = strtoupper($row['ci_symbol']);
                            $coinName = strtoupper($row["ci_{$country}_name"]);
                            if ($member['mb_' . $symbol_low] > 0) {
                                $count++;
                                ?>
                                <li><?= $coinName . " / " . $coinSymbol ?></li>
                                <?php
                            }
                        }
                        if ($count == 0) {
                            ?>
                            <li class="not-select"><?= lang('판매가능한 코인이 없습니다.', 'No coins available for sale.', '販売可能なコインがありません。', '没有可销售的硬币。') ?></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="common-input-group">
            <label><?= lang($type_title . '할 코인 가격',
                    'Price of coins to ' . $type_title,
                    $type_title . 'するコインの価格',
                    '要求' . $type_title . '的硬币的值') ?></label>
            <div class="common-input">
                <input type="number"
                       placeholder="<?= lang('가격을 입력해 주세요', 'Please enter price', '価格を入力してください。', '请输入价格。') ?>"
                       id="c2cPrice" data-type="price" step="0.00000001">
                <div class="cre-price-box">
                    <button type="button" data-type="plus">
                        <i class="xi-plus-thin"></i>
                    </button>
                    <button type="button" data-type="minus">
                        <i class="xi-minus-thin"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="common-input-group last-group">
            <label><?= lang($type_title . '할 코인의 수량',
                    'Quantity of coins to ' . $type_title,
                    $type_title . 'するコインの量',
                    '要求' . $type_title . '的硬币的数量') ?></label>
            <div class="common-input">
                <input type="number"
                       placeholder="<?= lang($type_title . '를 원하시는 수량을 입력해 주세요',
                           'Please enter the quantity you want to ' . $type_title,
                           $type_title . 'するご希望の数量を入力してください',
                           '请输入想要' . $type_title . '的数量。') ?>"
                       id="c2cAmount" data-type="amount" step="0.00000001">
            </div>
            <div class="cre-possible-box">
                <p><?= lang('거래 가능 자산', 'Transactionable assets', '取引可能な資産', '可交易资产') ?>
                    <span id="nowPrice"
                          data-usdt="<?= round_down_format($member['mb_usdt'], 4) ?>"
                          data-btc="<?= round_down_format($member['mb_btc'], 4) ?>"
                          data-eth="<?= round_down_format($member['mb_eth'], 4) ?>"
                          data-fvc="<?= round_down_format($member['mb_fvc'], 4) ?>">
                        <?= $type_symbol == 'USDT' ?
                            round_down_format($member['mb_usdt'], 4) : '0.0000' ?>
                    </span>
                    <span name="mainSymbol"><?= $type_symbol ?></span>
                </p>
                <p><?= lang('예상 거래대기자산', 'Estimated Transaction Waiting Assets', '予想取引待機資産', '预期交易待机资产') ?>
                    <span id="waitingPrice"
                          data-usdt="0.0000"
                          data-btc="<?= $waiting['btc'] ?>"
                          data-eth="<?= $waiting['eth'] ?>"
                          data-fvc="<?= $waiting['fvc'] ?>">
                        <?= $type_symbol == 'USDT' ? round_down_format($member['mb_usdt'], 4) : '0.0000' ?></span>
                    <span name="mainSymbol"><?= $type_symbol ?></span>
                </p>
            </div>
        </div>
        <div class="cre-total-box">
            <span><?= lang('예상 거래 총액', 'Estimated Total Transaction', '予想出来高', '预期交易总额') ?></span>
            <p>
                <em class="cre-total-price">0.0000</em>
                <span class="cre-total-unit" name="mainSymbol">USDT</span>
            </p>
        </div>
        <div class="cre-btn-box">
            <button type="button" class="btn" id="btnCancel" onclick="location.href='/c2c/main'">
                <?= lang('취소', 'Cancel', 'キャンセル', '取消') ?>
            </button>
            <button type="submit" class="btn btn-yellow" id="btnCreate">
                <?= lang('개인거래 생성', 'Create a C2C', '個人取引の作成', '个人交易生成') ?>
            </button>
        </div>
    </form>
</section>
<?php } ?>