<?php
if (empty($member)) {
    add_event('alert_hooks', 'not_login');
    return false;
}
$interest = get_cookie('coin_interest'); // 관심 코인

add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/wallet/deposit.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/otp.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/wallet/deposit.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/wallet/wallet.js"></script>');

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

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/wallet/m_main.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/wallet/main.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/wallet/withdraw.css?ver=' . time() . '">');
?>

<section class="common-bg">
    <div class="wrap-middle">
        <h1><?= lang('지갑관리', 'Wallet', 'ウォレット管理', '钱包管理') ?></h1>
        <div class="common-yellow-box">
            <div class="wallet-manage">
                <h2><?= lang('지갑관리', 'Wallet', 'ウォレット管理', '钱包管理') ?></h2>
                <table class="wallet-table wm-border-right">
                    <thead>
                    <tr>
                        <th><?= lang('총 자산 평가액', 'Estimated Balance', '総資産評価額', '总资产评估额') ?></th>
                        <th><?= lang('매수 금액', 'Total Purchase Amount', '買収金額', '收购金额') ?></th>
                        <th><?= lang('평가 금액', 'Total Valuation', '評価額', '估值金额') ?></th>
                        <th><?= lang('평가 수익률', 'Total Return on Investment', '評価の利回り', '评估收益率') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="wm-p-total"><?= round_down_format_fix($member['mb_usdt'] + $totalCoinsUsdtPrice, 8) ?><br>USDT</td>
                        <td class="wm-p-buy"><?= round_down_format_fix($totalCoinsBuyPrice, 8) ?><br>USDT</td>
                        <td class="wm-p-evaluation"><?= round_down_format_fix($totalCoinsUsdtPrice, 8) ?><br>USDT</td>
                        <td class="wm-p-rate <?= $totalCoinsUsdtPrice > $totalCoinsBuyPrice ? 'up' : ($totalCoinsUsdtPrice < $totalCoinsBuyPrice ? 'down' : '') ?>">
                            <p><?= round_down(($totalCoinsUsdtPrice - $totalCoinsBuyPrice) / ($totalCoinsBuyPrice) * 100, 2) ?>%</p>
                            <p><?= round_down_format_fix($totalCoinsUsdtPrice - $totalCoinsBuyPrice, 8) ?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="wallet-bank">
                <div class="wb-title">
                    <h2><?= lang('입출금', 'Deposit And Withdrawal', '入出金', '存款') ?></h2>
                    <input type="hidden" id="memberId" value="<?= $member['mb_id'] ?>"/>
                    <div>
                        <input type="checkbox" id="myCoin" class="chk-box"/>
                        <label for="myCoin" class="chk-label">
                            <?= lang('보유 코인', 'Holding coin', '保有コイン', '保留硬币') ?>
                        </label>
                        <input type="checkbox" id="interestCoin" class="chk-box"/>
                        <label for="interestCoin" class="chk-label">
                            <?= lang('관심 코인', 'Interest Coin', '関心コイン', '关注线') ?>
                        </label>
                        <div class="search-box">
                            <input type="text" class="search"
                                   placeholder="<?= lang('이름/심볼 검색', 'Search Coin/Symbol', 'コイン名/シンボル検索', '姓名/符号搜索') ?>">
                            <button type="button" class="btn-search"></button>
                        </div>
                    </div>
                </div>
                <table class="wallet-table ">
                    <colgroup>
                        <col width="20%">
                        <col width="19%">
                        <col width="19%">
                        <col width="19%">
                        <col width="23%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?= lang('코인명', 'Coin', 'コイン名', '科因明') ?></th>
                        <th><?= lang('총 보유 수량', 'Total Amount', '総保有数量', '总持有量') ?></th>
                        <th><?= lang('거래 대기 수량', 'Outstanding  Amount', '取引待機数量', '待售数量') ?></th>
                        <th><?= lang('거래 가능 수량', 'Tradable Amount', '取引可能数量', '可交易数量') ?></th>
                        <th><?= lang('입/출금', 'Manage Withdrawal Address', '入/出金', '入/出') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $db = new db();
                    $query = "SELECT ci_price, ci_symbol, ci_usdt_total, ci_{$country}_name, ci_percent, ci_with_fee, ci_minimum_with, ci_with_level{$member['mb_level']}, 
                                (SELECT SUM(tr_quantity) as total FROM _trade T WHERE T.tr_symbol = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_success_time IS NULL AND tr_type = 0) AS trading_coin, 
                                (SELECT SUM(tr_quantity * tr_price) as total FROM _trade T WHERE T.tr_currency = C.ci_symbol AND mb_id = '{$member['mb_id']}' AND tr_success_time IS NULL AND tr_type = 1) AS trading_currency 
                              FROM _coins C WHERE ci_use = 0 OR ci_use = 2";
                    $coins = $db->fetchAll($query);
                    foreach ($coins as $row) {
                        $row = objectToArray($row);

                        $symbol_low = strtolower($row['ci_symbol']);

                        $coin_name = $row['ci_' . $country . '_name'];
                        $coin_symbol = $row['ci_symbol'];
                        $total_amount = round_down_format($member["mb_{$symbol_low}"] + $row['trading_coin'], 8);
                        $trading_coin = round_down_format($row['trading_coin'] + $row['trading_currency'], 8);
                        $possible_amount = round_down_format(($member["mb_{$symbol_low}"] + $row['trading_coin']) - ($row['trading_coin'] + $row['trading_currency']), 8);
                        ?>
                        <tr>
                            <td class="td-coin-symbol <?= strpos($interest, $coin_symbol) !== false ? 'interest' : ''
                            ?><?=
                            @$member['mb_' . strtolower($coin_symbol)] > 0 ? ' have' : '' ?>"><?= $coin_name ?>
                                (<?= $coin_symbol ?>)
                            </td>
                            <td><?= $total_amount ?></td>
                            <td><?= $trading_coin ?></td>
                            <td><?= $possible_amount ?></td>
                            <td class="wb-btn-box">
                                <?php if ($member['mb_level'] < 2) {
                                    ?>
                                    <div>
                                        <?= lang('입금', 'Deposit', '入金', '入金') ?>
                                    </div>
                                    <div>
                                        <?= lang('출금', 'Withdraw', '出金', '出金') ?>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <button type="button" data-type="deposit"
                                            data-coin="<?= $symbol_low ?>">
                                        <?= lang('입금', 'Deposit', '入金', '入金') ?>
                                    </button>
                                    <button type="button" data-type="withdraw"
                                            data-coin="<?= $symbol_low ?>">
                                        <?= lang('출금', 'Withdraw', '出金', '出金') ?>
                                    </button>
                                    <?php
                                } ?>
                            </td>
                        </tr>
                        <tr class="hide deposit <?= $symbol_low ?>">
                            <td class="wb-content deposit" colspan="5">
                                <h2 class="wp-title"><?= $coin_name ?> (<?= $coin_symbol ?>)
                                    <?= lang('입금 계좌 정보', 'Deposit Account Information', 'アドレス情報', '汇款账户信息') ?></h2>
                                <div class="wp-content wp-deposit">
                                    <div class="wp-left <?php if (trim($member['mb_' . $symbol_low . '_addr'])) echo "padding" ?>">
                                        <?php if (trim($member['mb_' . $symbol_low . '_addr']) == '') { ?>
                                            <p><?= lang('입금 받을 ' . $coin_name . ' 지갑을 생성해 주세요.', 'Please create a ' .
                                                    $coin_name . ' wallet for the deposit.', 'ご入金される'
                                                    . $coin_name . 'のウォレットを生成してください。',
                                                    '请生成收到汇款的' . $coin_name . '钱包。'
                                                ) ?></p>
                                            <button type="button" class="btn-full btn-yellow" id="createWallet"
                                                    onclick='createWallet("<?= $symbol_low ?>","<?= $coin_name ?>")'>
                                                <?= lang('새 지갑 주소 생성하기', 'To generate a new wallet address', '新しいウォレットアドレスを作成する', '生成新钱包地址') ?>
                                            </button>
                                        <?php } else { ?>
                                            <div class="wallet-address-div">
                                                <p class="wallet-address-title"><?= lang('QR 코드', 'QR code', 'QRコード', 'QR代码'
                                                    ) ?></p>
                                                <div class="wallet-loading<?=$symbol_low=='usdt'?' two':''?>">
                                                    <img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=500x500&amp;chl=<?= $member["mb_{$symbol_low}_addr"] ?>"
                                                         width="147px" class="wallet-address-qr">
                                                </div>
                                                <?php
                                                if($symbol_low == 'usdt'){
                                                ?>
                                                    <div class="wallet-loading two" data-img="usdt_btc">
                                                        <?php if($member["mb_{$symbol_low}_btc_addr"] != null){?>
                                                        <img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=500x500&amp;chl=<?= $member["mb_{$symbol_low}_btc_addr"] ?>"
                                                             width="147px" class="wallet-address-qr">
                                                        <?php }?>
                                                    </div>
                                                    <div class="wallet-qr-name">
                                                        <span>ERC20</span>
                                                        <span>BTC</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="wallet-address-div">
                                                <p class="wallet-address-title text-left">
                                                    <?= lang('내 ' . $coin_name . ' 입금 주소', 'My ' . $coin_name . ' deposit address.', '私の' . $coin_name . '入金アドレス', '我的' . $coin_name . '汇款地址')
                                                    ?><?=$symbol_low=='usdt'?' (ERC20)':''?></p>
                                                <div class="wallet-address-input-box">
                                                    <input type="text"
                                                           value="<?= $member["mb_{$symbol_low}_addr"] ?>"
                                                           readonly>
                                                    <button type="button" class="btn-clipboard btn btn-yellow"
                                                            data-clipboard-text=<?= $member["mb_{$symbol_low}_addr"] ?>>
                                                        <?= lang('복사하기', 'Copy', 'コピーする', '复印') ?>
                                                    </button>
                                                </div>
                                                <?php if($symbol_low == 'usdt'){?>
                                                    <p class="wallet-address-title text-left" style="margin-top: 20px">
                                                        <?= lang('내 ' . $coin_name . ' 입금 주소', 'My ' . $coin_name . ' deposit address.', '私の' . $coin_name . '入金アドレス', '我的' . $coin_name . '汇款地址')
                                                        ?><?=$symbol_low=='usdt'?' (BTC)':''?></p>
                                                    <div class="wallet-address-input-box">
                                                        <input type="text" data-input="usdt_btc"
                                                               value="<?= $member["mb_{$symbol_low}_btc_addr"] ?>"
                                                               readonly>
                                                        <button type="button" class="btn-clipboard btn btn-yellow" data-btn="usdt_btc"
                                                                <?php if($member["mb_{$symbol_low}_btc_addr"] == null){?>
                                                                    onclick='createWallet("<?= $symbol_low ?>_btc","<?= $coin_name ?>")'
                                                                <?php }?>
                                                                data-clipboard-text=<?= $member['mb_' . $symbol_low . '_btc_addr'] ?>>
                                                            <?= $member["mb_{$symbol_low}_btc_addr"] == null ? lang('생성하기', 'To create', '生成する', '生成') : lang('복사하기', 'Copy', 'コピーする', '复印') ?>
                                                        </button>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="wp-right">
                                        <p>
                                            <img src="/public/img/common/attention.svg">
                                            <?= lang('입금 전 알아두세요.', 'Please know before you make the deposit.', '入金の前に知っておいてください。', '汇款前请先了解一下。') ?>
                                        </p>
                                        <ul>
                                            <li>
                                                <?= lang('생성된 주소는 ' . $coin_name . ' (' . $coin_symbol . ')
                                                입금 전용 주소입니다.', 'The generated address is ' . $coin_name . ' (' . $coin_symbol . ')
                                                This is a deposit-only address.', '生成されたアドレスは' . $coin_name . '('
                                                    . $coin_symbol . ')
                                                入金専用のアドレスです。',
                                                    '生成的地址是' . $coin_name . '(' . $coin_symbol . ')
                                                是汇款专用地址。') ?>
                                            </li>
                                            <li>
                                                <?= lang('생성된 ' . $coin_name . ' (' . $coin_symbol . ')
                                                지갑은 ' . $coin_name . ' (' . $coin_symbol . ') 만 입금할 수 있습니다.', 'Created ' . $coin_name . ' (' . $coin_symbol . ')
                                                The wallet is Only ' . $coin_name . '(' . $coin_symbol . ') can be 
                                                deposited.', '生成された'
                                                    . $coin_name . ' (' . $coin_symbol . ')
                                                のウォレットは、' . $coin_name . '(' . $coin_symbol . ')のみ入金することができます。',
                                                    '神圣的' . $coin_name . ' (' . $coin_symbol . ')
                                                钱包是"仅能汇款' . $coin_name . '(' . $coin_symbol . ')。') ?>
                                            </li>
                                            <li><?= lang('타 가상화폐를 입금하여 생기는 손실에 대해 책임지지 않습니다.', 'We are not responsible for the loss caused by depositing other virtual currencies.', '他の暗号資産を入金して生じる損失については責任を負いません。', '不负责汇入其他虚拟货币造成的损失。') ?></li>
                                            <li><?= lang('컨펌 완료에 소요되는 총 시간은 블록체인 네트워크의 혼잡도에 따라 영향을 받을 수 있습니다.', 'The total time required to complete the confirmation may be affected by the congestion of the block chain network.', 'コンフォーム完了に要する合計時間は、ブロックチェーンネットワークの混雑に応じて影響を受けることができます。', '完成按压所需时间可能会因积木网络的拥挤程度而受到影响。') ?></li>
                                            <li class="color-red">
                                                <strong><?= lang('부정 거래가 의심되는 경우 거래가 제한될 수 있습니다.', 'If you suspect a fraudulent transaction, the transaction may be restricted.', '否定取引が疑われる場合の取引制限されることがあります。', '疑似不正当交易时可能会限制交易。') ?></strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="hide withdraw <?= $symbol_low ?>" data-symbol="<?= strtoupper($symbol_low) ?>">
                            <td class="wb-content withdraw" colspan="5">
                                <h2 class="wp-title"><?= $coin_name ?> (<?= $coin_symbol ?>)
                                    <?= lang('출금 신청', 'Withdrawal Application', '出金の申請', '申请出纳') ?></h2>
                                <div class="wp-content wp-withdraw">
                                    <form class="wp-left" autocomplete="off"
                                          action="javascript:withdraw(<?= $member['mb_level'] ?>,'<?= strtolower($coin_symbol) ?>')">
                                        <div class="wallet-input-box">
                                            <span><?= lang('받을 지갑 주소', 'Wallet Address to Receive', '受信ウォレットアドレス', '收到的钱包地址')
                                                ?></span>
                                            <input type="text" class="ipt-paste" name="address">
                                            <button type="button" class="btn btn-yellow btn-paste"
                                                    onclick="addressCheck('<?= $coin_symbol ?>');"><?= lang('주소검증', 'Address Verification', 'アドレス検証', '地址验证') ?>
                                            </button>
                                        </div>
                                        <div class="box-withdraw-price">
                                            <div class="wallet-input-box">
                                                <span> <?= lang('출금 금액', 'Withdrawal Amount', '出金金額', '出纳金额') ?></span>
                                                <input type="number" class="text-right" placeholder="<?=lang('최소', 'Minimun', '最小', '最少').' '.$row['ci_minimum_with'].$coin_symbol?>"
                                                       name="price" step="0.00000001"
                                                       data-symbol="<?= $coin_symbol ?>" data-minimum="<?=$row['ci_minimum_with']?>" data-maximum="<?=round_down_format(str_replace(',', '', $possible_amount) - $row['ci_with_fee'], 8)?>">
                                                <button type="button" class="btn btn-yellow" id="maxPrice" data-fee="<?=$row['ci_with_fee']?>"
                                                        data-amount="<?= $possible_amount ?>"><?= lang('최대', 'Maximum', '最大', '最大') ?>
                                                </button>
                                            </div>
                                            <p class="alert-tag">
                                                <span><?= lang('현재 출금 가능 금액', 'Current Withdrawable amount', '現在出金可能額', '现款') ?></span>
                                                <span class="w-possible-price">
                                                    <?= round_down_format(str_replace(',', '', $possible_amount) - $row['ci_with_fee'], 8) ?>
                                                    <?= $row['ci_symbol'] ?>
                                                </span>
                                            </p>
                                            <p class="alert-message alert-tag color-red"></p>
                                        </div>
                                        <hr class="h-w-top-bar">
                                        <div class="box-total-price">
                                            <p class="<?= strtolower($coin_symbol) ?>-wallet-fee"
                                               data-fee="<?= $row['ci_with_fee'] ?>">
                                                <span><?= lang('출금 수수료', 'Withdrawal Fee', '出金手数料', '出纳手续费') ?></span>
                                                <span name="fee"><?= round_down_format($row['ci_with_fee'], 8) ?> <?= $coin_symbol ?></span>
                                            </p>
                                            <p class="color-yellow <?= strtolower($coin_symbol) ?>-wallet-total">
                                                <span><?= lang('총 출금', 'Withdrawal Receiving Quantity', '出金受領数量', '收款数量')?></span>
                                                <span name="total"><?= round_down_format(0, 8) ?> <?= $coin_symbol ?></span>
                                            </p>
                                        </div>
                                        <hr class="h-w-down-bar">
                                        <?php if ($member['mb_level'] == 2) { ?>
                                            <div class="box-sms wallet-input-box">
                                                <span><?= lang('SMS 인증', 'SMS authentication', 'SMS認証', 'SMS认证') ?></span>
                                                <input type="text" class="phoneNum" value="<?= $member['mb_hp'] ?>"
                                                       data-country="<?= $member['mb_country'] ?>" readonly>
                                                <button type="button" class="btn btn-yellow"
                                                        onclick="sendSMS('<?= strtolower($coin_symbol) ?>')"><?= lang('인증번호 발송', 'Send authentication number', '認証番号発送', '发送验证码') ?>
                                                </button>
                                            </div>
                                            <div class="box-sms-number">
                                                <div class="box-sms wallet-input-box">
                                                    <span><?= lang('인증번호', 'Authentication number', '認証番号', '认证编号') ?></span>
                                                    <input type="text" class="text-right authNum">
                                                    <button type="button" class="btn btn-yellow"
                                                            onclick="certifiedSMS('<?= strtolower($coin_symbol) ?>')">
                                                        <?= lang('SMS 인증', 'Authentication number', 'SMS認証', 'SMS认证') ?>
                                                    </button>
                                                </div>
                                                <p class="alert-message alert-tag
                                                color-red auth-time"></p>
                                            </div>
                                        <?php } else if ($member['mb_level'] == 3) { ?>
                                            <div class="box-otp wallet-input-box">
                                                <span><?= lang('OTP 인증코드', 'OTP Authentication Code', 'OTP認証コード', 'OTP认证代码')
                                                    ?></span>
                                                <input type="text" id="memberOtp" maxlength="6"
                                                       data-type="<?= strtolower($coin_symbol) ?>">
                                                <p class="alert-message alert-tag
                                                color-red alert-otp-auth"></p>
                                            </div>
                                        <?php } ?>
                                        <div class="wp-agree-box">
                                            <input type="checkbox" id="withdrawAgree<?= $coin_symbol ?>"
                                                   class="chk-box wd-agree"/>
                                            <label for="withdrawAgree<?= $coin_symbol ?>"
                                                   class="chk-label wd-agree">
                                                <?= lang('유의사항을 모두 읽었으며 이에 동의합니다.', 'I have read and accept all the precautions.', '注意事項を全て読み、これに同意します。', '注意事项我都读了,同意。') ?>
                                            </label>
                                        </div>
                                        <button type="submit" class="applyWithdraw"
                                                disabled><?= lang('출금 신청하기', 'To apply for withdrawal', '出金の申請する', '申请出纳') ?></button>
                                    </form>
                                    <div class="wp-right">
                                        <div class="wp-left wp-w-limit">
                                            <h3><?= lang('나의 출금 한도', 'My Withdrawal Limit', '出金限度', '我的出禁额度') ?></h3>
                                            <hr>
                                            <ul>
                                                <li>
                                                    <span><?= lang('1회 출금 한도', 'One-time limit', '1回出金限度', '一次出禁限')
                                                        ?></span>
                                                    <span><?= round_down_format($row["ci_with_level{$member['mb_level']}"], 8) . ' ' . $coin_symbol ?></span>
                                                </li>
                                                <li>
                                                    <span><?= lang('1일 출금 한도', 'A one-day limit',
                                                            '1日出金限度', '1日取款上限') ?></span>
                                                    <span><?= round_down_format($row["ci_with_level{$member['mb_level']}"], 8) . ' ' . $coin_symbol ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="wp-bottom">
                                            <p>
                                                <img src="/public/img/common/attention.svg">
                                                <?= lang('유의사항', 'Precaution', '注意事項', '注意事项') ?>
                                            </p>
                                            <ul>
                                                <li>
                                                    <?= lang('수취 지갑의 주소를 반드시 재확인해 주세요.', 'Please make sure to double-check the address of your wallet.', '受取ウォレットのアドレスを必ず再確認してください。', '请务必再次确认收款钱包的地址。') ?>
                                                </li>
                                                <li>
                                                    <?= lang('주소를 혼동하여 타인 또는 타 가상화폐 지갑주소로 잘못 송금되었을 시, GENESIS-EX는 책임지지 않습니다.', 'GENESIS-EX will not be responsible for confusing addresses and sending them to others or other virtual currency wallets.', 'アドレスを混同して、他人または他の暗号資産ウォレットアドレスに間違って送金された時に、GENESIS-EXは、責任を負いません。', '混淆地址误汇至他人或其他虚拟货币钱包地址时,GENESIS-EX不负责。') ?>
                                                </li>
                                                <li class="color-red">
                                                    <strong><?= lang('부정 거래가 의심되는 경우 거래가 제한될 수 있습니다.', 'If you suspect a fraudulent transaction, the transaction may be restricted.', '否定取引が疑われる場合の取引制限されることがあります。', '不正当交易如果怀疑交易可能会受到限制。') ?></strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
}
?>