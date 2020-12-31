<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/footer.css">');
?>
<footer>
    <div class="wrap-wide">
        <div class="ft-box">
            <div class="ft-logo"><img src="/public/img/common/logo-footer.png"></div>
            <div class="ft-list-box">
                <div class="ft-list">
                    <span class="ft-title">
                        <?= lang('서비스 지원', 'service support', 'サポート', '服务支持') ?>
                    </span>
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
                <div class="ft-list contact">
                    <span class="ft-title">Contact</span>
                    <span class="ft-content">listing@genesis-ex.com</span>
                    <span class="ft-content">support@genesis-ex.com</span>
                </div>
            </div>

        </div>
        <!--        <div class="ft-mode">-->
        <!--            <span class="ft-title">--><? //= lang('다크모드', 'Dark mode', 'ダークモード', '暗蓝色') ?><!--</span>-->
        <!--            <label class="switch">-->
        <!--                <input type="checkbox"--><? //= $_COOKIE['theme'] == 'dark' ? ' checked' : '' ?>
        <!--                <span class="slider round"></span>-->
        <!--            </label>-->
        <!--        </div>-->
        <div class="ft-copy">
            Copyright ⓒ 2019 GENESIS・EX. All rights reserved.
        </div>
    </div>
</footer>