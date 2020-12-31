<!--우측 메뉴-->
<div class="gnb-div gnb-none">
    <div class="gnb-bg"></div>
    <div class="gnb">
        <div class="gnb-button-box">
            <?php if (!isset($_SESSION['mb_id'])) { ?>
                <a href="/member/login" class="btn btn-yellow-border"><?= lang('로그인', 'LOG IN', 'ログイン', '登录') ?></a>
                <a href="/member/signup" class="btn btn-yellow-border"><?= lang('회원가입', 'SIGN IN', '会員登録', '注册会员') ?></a>
            <?php } else {?>
                <a href="/member/account/info" class="btn btn-yellow-border" style="padding:14px 0;"><?= lang('계정관리', 'Account', 'アカウント管理','账户管理') ?></a>
                <a href="/src/controller/member/logout.php" class="btn btn-yellow-border">LOGOUT</a>
            <?php }?>
        </div>
        <div class="gnb-list">
            <ul>
                <li class="start-lnb">
                    <a href="/exchange/main">
                        <img src="/public/img/common/mobile/exchange.png"/>
                        <?= lang('거래소', 'Exchange', '取引所', '交易所') ?>
                    </a>
                </li>
                <li class="has-lnb">
                    <a><img src="/public/img/common/mobile/c2c.png"/><?= lang('개인거래', 'C2C', '個人取引', '个人交易') ?></a>
                    <ul class="mb-lnb">
                        <li><a href="/c2c/main/buy"><?= lang('구매', 'Purchase', '購買', '采购') ?></a></li>
                        <li><a href="/c2c/main/sell"><?= lang('판매', 'Sales', '販売', '销售') ?></a></li>
                        <?php if (!empty($member)) { ?>
                            <li><a href="/c2c/main/my"><?= lang('나의 거래', 'My Trading', '私の取引', '我的买卖') ?></a></li>
                            <li><a href="/c2c/create/buy"><?= lang('개인거래 생성', 'Create C2C', '個人取引の作成', '个人交易生成') ?></a>
                            </li><?php } ?>
                    </ul>
                </li>
                <li>
                    <a href="/balance/main/asset"><img
                                src="/public/img/common/mobile/balance.png"/><?= lang('투자내역', 'Balance', '投資履歴', '投资明细') ?>
                    </a>
                </li>
                <li>
                    <a href="/wallet/main"><img
                                src="/public/img/common/mobile/wallet.png"/><?= lang('지갑관리', 'Wallet', 'ウォレット管理', '钱包管理') ?>
                    </a>
                </li>
                <li class="last-lnb">
                    <a href="/notice/customer/support"><img
                                src="/public/img/common/mobile/support.png"/><?= lang('공지사항', 'Notice', 'お知らせ', '公告事项') ?>
                    </a></li>
                <li>
                    <a href="/notice/customer/support"><img
                                src="/public/img/common/mobile/notice.png"/><?= lang('고객센터', 'Support', 'サポート', '客户服务中心') ?>
                    </a>
                </li>
                <li class="last-lnb">
                    <a href="/notice/listing"><img
                                src="/public/img/common/mobile/listing.png"/><?= lang('상장문의', 'Listing', 'リスティング', '上市咨询') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="gnb-setting">
            <div class="lang-box">
                <span class="lang-title helvetica">LANGUAGE</span>
                <div class="lang-items">
                    <a class="lang-item helvetica <?= $country == 'ko' ? 'active' : '' ?>" data-lang="ko">
                        <img src="/public/img/common/mobile/lang-ko-<?= $country == 'ko' ? 'on' : 'off' ?>.png">
                        KOR
                    </a>
                    <a class="lang-item helvetica <?= $country == 'ja' ? 'active' : '' ?>" data-lang="ja">
                        <img src="/public/img/common/mobile/lang-ja-<?= $country == 'ja' ? 'on' : 'off' ?>.png">
                        JPN
                    </a>
                    <a class="lang-item helvetica <?= $country == 'en' ? 'active' : '' ?>" data-lang="en">
                        <img src="/public/img/common/mobile/lang-en-<?= $country == 'en' ? 'on' : 'off' ?>.png">
                        ENG
                    </a>
                    <a class="lang-item helvetica <?= $country == 'ch' ? 'active' : '' ?>" data-lang="ch">
                        <img src="/public/img/common/mobile/lang-ch-<?= $country == 'ch' ? 'on' : 'off' ?>.png">
                        CHN
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!--//우측 메뉴-->
<div id="main">