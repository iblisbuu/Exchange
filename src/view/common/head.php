<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/header.css">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/head.js?v=2"></script>');


// NEWS
require_once SRC_ROOT . '/config/DB.php';
require_once MD_ROOT . '/board/News.php';
$news = new News();
$result = objectToArray($news->getNewsList($country));
$type = $result[0]['nw_type'];

if($result){
?>
<section id="top-notice">
    <div class="wrap-wide wrap-top-notice">
        <?php
        switch ($type) {
            case 'notice':
                $type = lang('공지', 'notice', 'お知らせ','公告');
                break;
            case 'event':
                $type = lang('이벤트', 'event', 'イベント','活动');
                break;
            case 'listing':
                $type = lang('상장', 'Listing', '上場','上市');
                break;
        }
        $time = str_replace('-', '.', explode(' ', $result[0]['nw_datetime'])[0]);
        $title = $result[0]['nw_title_' . $country];
        ?>
        <a class="top-notice-box" href="/notice/customer/support?no=<?= $result[0]['nw_no'] ?>">
            <span class="top-notice-title">[<?= $type ?>] <?= $title ?></span>
            <span class="top-notice-date"><?= $time ?></span>
        </a>
        <button><i class="xi-close"></i></button>
    </div>
</section>
<?php }?>
<header>
    <div class="wrap-wide">
        <a href="<?= ROOT ?>" class="hd-logo"></a>
        <div class="hd-content">
            <div class="hd-gnb">
                <ul>
                    <li <?php if ($mainMenu == 'exchange') echo "class='active'" ?>>
                        <a href="/exchange/main"><?= lang('거래소', 'Exchange', '取引所','交易所') ?></a>
                    </li>
                    <li <?php if ($mainMenu == 'c2c') echo "class='active'" ?>>
                        <a href="/c2c/main"><?= lang('개인거래', 'C2C', '個人取引','个人交易') ?></a>
                        <ul class="hd-sub-gnb">
                            <li><a href="/c2c/main/buy"><?= lang('구매', 'Purchase', '購買','采购') ?></a></li>
                            <li><a href="/c2c/main/sell"><?= lang('판매', 'Sales', '販売','销售') ?></a></li>
                            <?php if (!empty($member)) { ?>
                                <li><a href="/c2c/main/my"><?= lang('나의 거래', 'My Trading', '私の取引','我的买卖') ?></a></li>
                                <li><a href="/c2c/create/buy"><?= lang('개인거래 생성', 'Create C2C', '個人取引の作成','个人交易生成') ?></a>
                                </li><?php } ?>
                        </ul>
                    </li>
                    <li <?php if ($mainMenu == 'balance') echo "class='active'" ?>>
                        <a href="/balance/main/asset"><?= lang('투자내역', 'Balance', '投資履歴','投资明细') ?></a>
                    </li>
                    <li <?php if ($mainMenu == 'wallet') echo "class='active'" ?>>
                        <a href="/wallet/main"><?= lang('지갑관리', 'Wallet', 'ウォレット管理','钱包管理') ?></a>
                    </li>
                    <li <?php if ($mainMenu == 'notice') echo "class='active'" ?>>
                        <a href="/notice/customer/support"><?= lang('공지사항', 'Notice', 'お知らせ','公告事项') ?></a>
                    </li>
                </ul>
            </div>
            <div class="hd-etc">
                <div class="etc-member">
                    <?php if (!isset($_SESSION['mb_id'])) { ?>
                        <a href="/member/login">LOG IN</a>
                        <a href="/member/signup" class="sign-up-btn">SIGN UP</a>
                    <?php } else { ?>
                        <?php
                        if($member['mb_admin']){
                            ?>
                            <a href="http://admin.genesis-ex.com/" class="menu-admin" target="_blank"><?= lang('관리자', 'ADMIN', 'ADMIN','ADMIN') ?></a>
                            <?php
                        }
                        ?>
                        <div class="member-manage">
                            <a href="/member/account/info"><?= lang('계정관리', 'Account', 'アカウント管理','账户管理') ?></a>
                            <ul class="hd-sub-gnb">
                                <li><span class="member-account"><?= $_SESSION['mb_id'] ?></span><a
                                            href="/member/account/info"><?=
                                        lang
                                        ('회원정보', 'Member Info', '会員情報','会员信息') ?></a></li>
                                <li>
                                    <span><?= lang('보안 레벨', 'security level', 'セキュリティレベル','安全等级') ?><strong>Level.<?=
                                            $member['mb_level'] ?></strong></span><a
                                            href="/member/account/certification"><?= lang('인증센터', 'Certification Center', '認証センター','认证中心')
                                        ?></a></li>
                            </ul>
                        </div>
                        <a href="/src/controller/member/logout.php">LOGOUT</a>
                    <?php } ?></div>
                <div class="etc-support">
                    <a href="/notice/customer/support"><?= lang('고객센터', 'Support', 'サポート','客户服务中心') ?></a>
                    <a href="/notice/listing"><?= lang('상장문의', 'Listing', 'リスティング','上市咨询') ?></a>
                </div>
                <div class="etc-lang">
                    <div id="lang"><?php
                        switch ($country) {
                            case 'ko' :
                                echo "KOR";
                                break;
                            case 'ja' :
                                echo "JPN";
                                break;
                            case 'en' :
                                echo "ENG";
                                break;
                            case 'ch' :
                                echo "CHN";
                                break;
                        } ?></div>
                    <ul class="hd-sub-gnb lang-ul">
                        <li><a href="/" data-lang="ja"><img src="/public/img/common/lang-ja.png">JPN</a></li>
                        <li><a href="/" data-lang="en"><img src="/public/img/common/lang-en.png">ENG</a></li>
                        <li><a href="/" data-lang="ch"><img src="/public/img/common/lang-ch.png">CHN</a></li>
                        <li><a href="/" data-lang="ko"><img src="/public/img/common/lang-ko.png">KOR</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="main">