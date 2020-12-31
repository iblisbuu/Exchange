<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/service/terms.css?ver=' . time() . '">');
if($device == 'mobile')
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/service/m_terms.css?ver=' . time() . '">');

add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/service/terms.js?ver=' . time() . '"></script>');

include_once CTRL_ROOT . '/terms/_common.php';
$_terms = new Terms();

$menu = $segment[2] ? $segment[2] : 'guide';
$title = '';
$table = '';
if ($menu == 'guide') {
    $title = lang('GENESIS.EX 이용약관', 'Terms and Conditions of use of GENESIS.EX', 'GENESIS.EX利用約款', 'GENESIS.EX使用条款');
    $table = 'use';
}
if ($menu == 'privacy_policy') {
    $title = lang('개인정보보호정책', 'Privacy Policy', '個人情報保護方針', '个人信息保护政策');
    $table = 'privacy';
}
$result = $_terms->getTerms($country, $table)[0];
$teTitle = 'te_content_' . $country;
$result = $result->$teTitle
?>
<section class="account-section common-bg">
    <div class="wrap-middle">
        <div class="account-div">
            <div class="account-tap">
                <a href="/service/terms/guide" class="guide-tab">
                    <?= lang('이용안내', 'Information on Use', 'ご利用案内', '使用指南') ?>
                </a>
                <a href="/service/terms/privacy_policy" class="privacy_policy-tab">
                    <?= lang('개인정보보호정책', 'Privacy Policy', '個人情報保護方針', '个人信息保护政策') ?>
                </a>
            </div>
            <div class="common-yellow-box">
                <h1><?= $title ?></h1>
                <div class="terms_content">
                    <?= $result ?>
                </div>
            </div>
        </div>
</section>