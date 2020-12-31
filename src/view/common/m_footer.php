<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/footer.css">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/m_footer.css">');
?>
<footer>
    <div class="wrap-wide">
        <div class="footer-box">
            <div class="logo-box">
                <div class="ft-logo"></div>
<!--                <div class="ft-mode">-->
<!--                    <span class="ft-title">다크모드</span>-->
<!--                    <label class="switch">-->
<!--                        <input type="checkbox"--><?//= $_COOKIE['theme'] == 'dark' ? ' checked' : '' ?><!---->
<!--                        <span class="slider round"></span>-->
<!--                    </label>-->
<!--                </div>-->
            </div>
            <div class="ft-list">
                <span class="ft-title"><?= lang('서비스 지원', 'service support', 'サポート', '服务支持') ?></span>
                <div class="footer-a-box">
                    <a href="/service/terms/guide" class="ft-content">
                        <?= lang('이용안내', 'Information on Use', 'ご利用案内', '使用指南') ?>
                    </a>
                    <a href="/service/terms/privacy_policy" class="ft-content">
                        <?= lang('개인정보보호정책', 'Privacy Policy', '個人情報保護方針', '个人信息保护政策') ?>
                    </a>
                    <a href="/service/fee" class="ft-content">
                        <?= lang('수수료 안내', 'Fee', '手数料のご案内', '手续费通知') ?>
                    </a>
                    <a href="/notice/customer/support" class="ft-content">
                        <?= lang('고객센터', 'Support', 'サポート', '客户中心') ?>
                    </a>
                </div>
            </div>
            <div class="ft-list contact">
                <span class="ft-title">Contact</span>
                <div class="footer-a-box">
                    <a href="">listing@genesis.com</a>
                    <a href="">support@genesis.com</a>
                </div>
            </div>
            <div class="ft-copy">
                Copyright ⓒ 2019 GENESIS・EX. All rights reserved.
            </div>
        </div>
    </div>
</footer>
</div>