<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/wallet/m_main.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
    add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/popup.js"></script>');
?>

<section class="common-bg">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?= lang('지갑관리', 'Wallet', 'ウォレット管理', '钱包管理') ?>
    </div>
    <div class="wallet-manage">
        <div class="wallet-title">
            <?= lang('지갑관리', 'Wallet', 'ウォレット管理', '钱包管理') ?>
        </div>
        <div class="wallet-table">
            <div>
                <span><?= lang('총 자산 평가액', 'Estimated Balance', '総資産評価額', '总资产评估额') ?></span>
                <span class="wm-p-total"><p><?= round_down_format_fix($member['mb_usdt'] + $totalCoinsUsdtPrice, 8) ?></p></span>
            </div>
            <div>
                <span><?= lang('매수 금액', 'Total Purchase Amount', '買収金額', '收购金额') ?></span>
                <span class="wm-p-buy"><p><?= round_down_format_fix($totalCoinsBuyPrice, 8) ?></p></span>
            </div>
            <div>
                <span><?= lang('평가 금액', 'Total Valuation', '評価額', '估值金额') ?></span>
                <span class="wm-p-evaluation"><p><?= round_down_format_fix($totalCoinsUsdtPrice, 8) ?></p></span>
            </div>
            <div>
                <span>
                    <p><?= lang('평가 수익률', 'Total Return on Investment', '評価の利回り', '评估收益率') ?></p>
                    <p><?= lang('평가 손익', 'Valuation gains and losses', '評価損益', '評估損益') ?></p>
                </span>
                <span class="wm-p-rate <?= $totalCoinsUsdtPrice > $totalCoinsBuyPrice ? 'up' : ($totalCoinsUsdtPrice < $totalCoinsBuyPrice ? 'down' : '') ?>">
                    <p>
                        <span><?= round_down(($totalCoinsUsdtPrice - $totalCoinsBuyPrice) / ($totalCoinsBuyPrice) * 100, 2) ?>%</span>
                        <span><?= round_down_format_fix($totalCoinsUsdtPrice - $totalCoinsBuyPrice, 8) ?></span>
                    </p>
                </span>
            </div>
        </div>
    </div>
    <div class="wallet-bank">
        <div class="wallet-title">
            <?= lang('입출금', 'Deposit And Withdrawal', '入出金', '存款') ?>
        </div>
        <div class="search-box">
            <div class="radio">
                <input type="checkbox" id="myCoin">
                <label><?= lang('보유코인', 'Holding coin', '保有コイン', '保留硬币') ?></label>
                <i class="xi-check xi-check-coin none"></i>
            </div>
            <div class="search">
                <input type="text" placeholder="<?= lang('이름/심볼 검색', 'Search Coin/Symbol', 'コイン名/シンボル検索', '姓名/符号搜索') ?>">
                <button class="btn-search"></button>
            </div>
        </div>
    </div>
    <div class="bank-table">
        <div class="table-title">
            <span><?= lang('코인명', 'Coin name', 'コイン名', '科因明') ?></span>
            <span><?= lang('총 보유수량', 'Total Amount', '総保有数量', '总持有量') ?></span>
        </div>
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
                $trading_coin = round_down_format($row['trading_coin'], 8);
                $possible_amount = round_down_format($member['mb_' . $symbol_low] - $row['trading_coin'], 8);
        ?>
        <div class="table-content">
            <span>
                <p class="td-coin-symbol <?= strpos($interest, $coin_symbol) !== false ? 'interest' : ''
                    ?><?=
                    @$member['mb_' . strtolower($coin_symbol)] > 0 ? ' have' : '' ?>"><?= $coin_name ?></p>
                <p>(<?= $coin_symbol ?>)</p>
            </span>
            <span>
                <p><?= $total_amount ?></p>
                <p class="color">
                    <label><?= lang('거래대기', 'Outstanding', '取引待機', '待售') ?></label>
                    <label><?= $trading_coin ?></label>
                </p>
                <p class="color">
                    <label><?= lang('거래가능', 'Tradable', '取引可能', '可交易') ?></label>
                    <label><?= $possible_amount ?></label>
                </p>
                <p class="bank-table-btns wb-btn-box">
                <?php if ($member['mb_level'] < 2) {
                    ?>
                    <button type="button" class="btn-disabled">
                        <?= lang('입금', 'Deposit', '入金', '入金') ?>
                    </button>
                    <button type="button" class="btn-disabled">
                        <?= lang('출금', 'Withdraw', '出金', '出金') ?>
                    </button>
                <?php
                    } else {
                ?>
                    <button type="button" data-type="deposit" data-coin="<?= $symbol_low ?>"><?= lang('입금', 'Deposit', '入金', '入金') ?></button>
                    <button type="button" data-type="withdraw" data-coin="<?= $symbol_low ?>"><?= lang('출금', 'Withdraw', '出金', '出金') ?></button>
                <?php
                    } ?>
                </p>
            </span>
        </div>
        <div class="deposit <?= $symbol_low ?> hide">
            <div class="wb-title">
            <?= $coin_name ?> (<?= $coin_symbol ?>)<?= lang('입금 계좌 정보', 'Deposit Account Information', 'アドレス情報', '汇款账户信息') ?>
            </div>
            <div class="wb-content">
                <div class="wp-left">
                <?php if (trim($member['mb_' . $symbol_low . '_addr']) == '') { ?>
                    <?= lang('입금 받을 ' . $coin_name . ' 지갑을 생성해 주세요.', 'Please create a ' .
                        $coin_name . ' wallet for the deposit.', 'ご入金される'
                        . $coin_name . 'のウォレットを生成してください。',
                        '请生成收到汇款的' . $coin_name . '钱包。'
                ) ?>
                    <button type="button" class="btn-full btn-yellow" id="createWallet" onclick='createWallet("<?= $symbol_low ?>","<?= $coin_name ?>")'><?= lang('새 지갑 주소 생성하기', 'To generate a new wallet address', '新しいウォレットアドレスを作成する', '生成新钱包地址') ?></button>
                <?php } else { ?>
                    <p><?= lang('QR 코드', 'QR code', 'QRコード', 'QR代码') ?></p>
                    <div class="wp-left-qr">
                        <div class="wallet-loading">
                            <img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=500x500&amp;chl=<?= $member['mb_' . $symbol_low . '_addr'] ?>" width="178.5px" class="wallet-address-qr">
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
                        <?php } ?>  
                    </div> 
                        <?php
                        if($symbol_low == 'usdt'){
                        ?>
                            <div class="wallet-qr-name">
                                <span>ERC20</span>
                                <span>BTC</span>
                            </div>
                        <?php } ?>   
                    <p><?= lang('내 ' . $coin_name . ' 입금 주소', 'My ' . $coin_name . ' deposit address.', '私の' . $coin_name . '入金アドレス', '我的' . $coin_name . '汇款地址')
                        ?><?=$symbol_low=='usdt'?' (ERC20)':''?></p>
                    <input type="text" value="<?= $member["mb_{$symbol_low}_addr"] ?>" readonly>
                    <button type="button" class="btn-clipboard btn-yellow" data-clipboard-text=<?= $member["mb_{$symbol_low}_addr"] ?>><?= lang('복사하기', 'Copy', 'コピーする', '复印') ?></button>
                <?php } ?>
                
                <?php if($symbol_low == 'usdt'){?>
                    <p>
                        <?= lang('내 ' . $coin_name . ' 입금 주소', 'My ' . $coin_name . ' deposit address.', '私の' . $coin_name . '入金アドレス', '我的' . $coin_name . '汇款地址')
                        ?><?=$symbol_low=='usdt'?' (BTC)':''?></p>
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
                <?php }?>
                </div>
            </div>
            <div class="wb-right">
                <p>
                    <img src="/public/img/common/attention.svg">
                    <?= lang('입금 전 알아두세요.', 'Please know before you make the deposit.', '入金の前に知っておいてください。', '汇款前请先了解一下。') ?>
                </p>
                <ul>
                    <li>
                        <?= lang('- 생성된 주소는 ' . $coin_name . ' (' . $coin_symbol . ') 입금 전용 주소입니다.',
                        '- The generated address is ' . $coin_name . ' (' . $coin_symbol . ') This is a deposit-only address.',
                        '- 生成されたアドレスは' . $coin_name . '(' . $coin_symbol . ') 入金専用のアドレスです。',
                        '- 生成的地址是' . $coin_name . '(' . $coin_symbol . ') 是汇款专用地址。') ?>
                    </li>
                    <li>
                        <?= lang('- 생성된 ' . $coin_name . ' (' . $coin_symbol . ') 지갑은 ' . $coin_name . ' (' . $coin_symbol . ') 만 입금할 수 있습니다.',
                        '- Created ' . $coin_name . ' (' . $coin_symbol . ') The wallet is Only ' . $coin_name . '(' . $coin_symbol . ') can be deposited.',
                        '- 生成された' . $coin_name . ' (' . $coin_symbol . ') のウォレットは、' . $coin_name . '(' . $coin_symbol . ')のみ入金することができます。',
                        '- 神圣的' . $coin_name . ' (' . $coin_symbol . ') 钱包是"仅能汇款' . $coin_name . '(' . $coin_symbol . ')。') ?>
                    </li>
                    <li><?= lang('- 타 가상화폐를 입금하여 생기는 손실에 대해 책임지지 않습니다.', '- We are not responsible for the loss caused by depositing other virtual currencies.', '- 他の暗号資産を入金して生じる損失については責任を負いません。', '- 不负责汇入其他虚拟货币造成的损失。') ?></li>
                    <li><?= lang('- 컨펌 완료에 소요되는 총 시간은 블록체인 네트워크의 혼잡도에 따라 영향을 받을 수 있습니다.', '- The total time required to complete the confirmation may be affected by the congestion of the block chain network.', '- コンフォーム完了に要する合計時間は、ブロックチェーンネットワークの混雑に応じて影響を受けることができます。', '- 完成按压所需时间可能会因积木网络的拥挤程度而受到影响。') ?></li>
                    <li class="color-red">
                        <strong><?= lang('- 부정 거래가 의심되는 경우 거래가 제한될 수 있습니다.', '- If you suspect a fraudulent transaction, the transaction may be restricted.', '- 否定取引が疑われる場合の取引制限されることがあります。', '- 疑似不正当交易时可能会限制交易。') ?></strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="hide withdraw <?= $symbol_low ?>" data-symbol="<?= strtoupper($symbol_low) ?>">
            <div class="wb-title">
                <?= $coin_name ?> (<?= $coin_symbol ?>)<?= lang('출금 신청', 'Withdrawal Application', '出金の申請', '申请出纳') ?>
            </div>
            <div class="wb-content">
                <div>
                    <div class="limit-title"><?= lang('나의 출금 한도', 'My Withdrawal Limit', '私の出金限度', '我的出禁额度') ?></div>
                    <div class="limit-content">
                        <p>
                            <span><?= lang('1회 출금 한도', 'One-time limit', '1回出金限度', '一次出禁限')?></span>
                            <span><?= round_down_format(50, 8) . ' ' . $coin_symbol ?></span>
                        </p>
                        <p>
                            <span><?= lang('1일 출금 한도', 'A one-day limit', '1日出金限度', '1日取款上限') ?></span>
                            <span><?= round_down_format(100, 8) . ' ' . $coin_symbol ?></span>
                        </p>
                        <p>
                            <span><?= lang('월 출금 한도', 'Monthly limit', '月出金限度', '月出禁限')?></span>
                            <span><?= round_down_format(100, 8) . ' ' . $coin_symbol ?></span>
                        </p>
                    </div>
                </div>
                <form class="wp-left" autocomplete="off" action="javascript:withdraw(<?= $member['mb_level'] ?>,'<?= strtolower($coin_symbol) ?>')">
                    <div class="wallet-input-box">
                        <span><?= lang('받을 지갑 주소', 'Wallet Address to Receive', '受け取る財布の住所', '收到的钱包地址')?></span>
                        <input type="text" class="ipt-paste" name="address">
                        <button type="button" class="btn btn-yellow btn-paste" onclick="addressCheck('<?= $coin_symbol ?>');"><?= lang('주소검증', 'Address Verification', 'アドレス検証', '地址验证') ?></button>
                    </div>
                    <div class="wallet-input-box">
                        <span><?= lang('출금 금액', 'Withdrawal Amount', '出金金額', '出纳金额') ?></span>
                        <input type="number" class="text-right padding-right" name="price" step="0.00000001" data-symbol="<?= $coin_symbol ?>" data-minimum="<?=$row['ci_minimum_with']?>" data-maximum="<?=round_down_format(str_replace(',', '', $possible_amount) - $row['ci_with_fee'], 8)?>">
                        <p class="w-possible-price">
                            <?= lang('현재 출금 가능 금액', 'Current Withdrawable amount', '現在出金可能額', '现款') ?>
                            <span><?= round_down_format(str_replace(',', '', $possible_amount) - $row['ci_with_fee'], 8) ?> <?= $row['ci_symbol'] ?></span>
                        </p>
                        <p class="alert-message withdrawl-amount-warning none">
                            <?= lang('최소 출금 가능액보다 적습니다. (0.001 BTC)', 'Less than minimum withdrawal possible (0.001 BTC)', '最小出金可能額より少ないです。(0.001 BTC)', '少於最低可取款額。 (0.001 BTC)') ?>
                        </p>
                        <button type="button" class="btn-yellow" id="maxPrice" data-amount="<?= $possible_amount ?>"><?= lang('최대', 'Maximum', '最大', '最大') ?></button>
                    </div>
                    <div class="total-price">
                        <p class="<?= strtolower($coin_symbol) ?>-wallet-fee" data-fee="<?= $row['ci_with_fee'] ?>">
                            <span><?= lang('출금 수수료', 'Withdrawal Fee', '出金手数料', '出纳手续费') ?></span>
                            <span name="fee"><?= round_down_format($row['ci_with_fee'], 8) ?> <?= $coin_symbol ?></span>
                        </p>
                        <p class="<?= strtolower($coin_symbol) ?>-wallet-total">
                            <span class="color"><?= lang('출금 수령 수량', 'Withdrawal Receiving Quantity', '出金受領数量', '收款数量')?></span>
                            <span class="color middle" name="total">
                                <label>
                                    <?= round_down_format(0, 8) ?> <?= $coin_symbol ?>
                                </label>
                            </span>
                        </p>
                    </div>
                    <?php if ($member['mb_level'] == 2) { ?>
                    <div class="box-sms">
                        <div class="wallet-input-box">
                            <span><?= lang('SMS 인증', 'SMS authentication', 'SMS認証', 'SMS认证') ?></span>
                            <input type="text" class="phoneNum text-left" value="<?= $member['mb_hp'] ?>" data-country="<?= $member['mb_country'] ?>" readonly>
                            <button type="button" class="btn-yellow" onclick="sendSMS('<?= strtolower($coin_symbol) ?>')"><?= lang('인증번호 발송', 'Send authentication number', '認証番号送信', '发送验证码') ?></button>
                        </div>
                        <div class="wallet-input-box">
                            <span><?= lang('인증번호', 'Authentication number', '認証番号', '认证编号') ?></span>
                            <input type="text" class="authNum text-left">
                            <p class="withdrawl-amount-warning auth-time none">
                                남은시간 : 
                            </p>
                            <button type="button" class="btn-yellow" onclick="certifiedSMS('<?= strtolower($coin_symbol) ?>')"><?= lang('SMS 인증', 'Authentication number', 'SMS認証', 'SMS认证') ?></button>
                        </div>
                        <div class="wallet-input-box agree-box">
                            <p class="wallet-withdraw-agree">
                                <input type="checkbox" id="withdrawAgree<?= $coin_symbol ?>" class="chk-box wd-agree">
                                <label>
                                <?= lang('유의사항을 모두 읽었으며 이에 동의합니다.', 'I have read and accept all the precautions.', '注意事項を全て読み、これに同意します。', '注意事项我都读了,同意。') ?>
                                    <i class="xi-check xi-check-agree none"></i>
                                </label>
                            </p>
                            <button type="submit" class="applyWithdraw" disabled><?= lang('출금 신청하기', 'To apply for withdrawal', '出金の申請する', '申请出纳') ?></button>
                        </div>
                    </div>
                    <?php } else if ($member['mb_level'] == 3) { ?>
                    <div class="box-otp none">
                        <div class="wallet-input-box">
                            <span><?= lang('OTP 인증코드', 'OTP Authentication Code', 'OTP認証コード', 'OTP认证代码')?></span>
                            <input type="text">
                        </div>
                        <div class="wallet-input-box agree-box">
                            <p class="wallet-withdraw-agree">
                                <input type="checkbox" id="withdrawAgree<?= $coin_symbol ?>" class="chk-box wd-agree">
                                <label>
                                <?= lang('유의사항을 모두 읽었으며 이에 동의합니다.', 'I have read and accept all the precautions.', '注意事項を全て読み、これに同意します。', '注意事项我都读了,同意。') ?>
                                    <i class="xi-check xi-check-agree none"></i>
                                </label>
                            </p>
                            <button type="submit" class="applyWithdraw" disabled><?= lang('출금 신청하기', 'To apply for withdrawal', '出金申請する', '申请出纳') ?></button>
                        </div>
                    </div>
                    <?php } ?>
                </form>
                <div class="wb-right">
                <p>
                    <img src="/public/img/common/attention.svg">
                    <?= lang('유의사항', 'Precaution', '留意事項', '注意事项') ?>
                </p>
                <ul>
                    <li>
                        <?= lang('- 수취 지갑의 주소를 반드시 재확인해 주세요.',
                        '- Please make sure to double-check the address of your wallet.',
                        '- 受取ウォレットのアドレスを必ず再確認してください。',
                        '- 请务必再次确认收款钱包的地址。') ?>
                    </li>
                    <li>
                        <?= lang('- 주소를 혼동하여 타인 또는 타 가상화폐 지갑주소로 잘못 송금되었을 시, GENESIS-EX는 책임지지 않습니다.',
                        '- GENESIS-EX will not be responsible for confusing addresses and sending them to others or other virtual currency wallets.',
                        '- アドレスを混同して、他人または他の暗号資産ウォレットアドレスに間違って送金された時に、GENESIS-EXは、責任を負いません。',
                        '- 混淆地址误汇至他人或其他虚拟货币钱包地址时,GENESIS-EX不负责。') ?>
                    </li>
                    <li class="color-red">
                        <strong><?= lang('- 부정 거래가 의심되는 경우 거래가 제한될 수 있습니다.', '- If you suspect a fraudulent transaction, the transaction may be restricted.', '- 否定取引が疑われる場合の取引制限されることがあります。', '- 不正当交易如果怀疑交易可能会受到限制。') ?></strong>
                    </li>
                </ul>
            </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>