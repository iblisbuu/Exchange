<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/m_common.css?ver=' . time() . '">');
?>

<div class="account-section">
    <a href="javascript:history.back()" class="hd-back">
        <i class="xi-angle-left-thin"></i>
    </a>
    <?= lang('고객센터', 'Support', 'サポート','客户中心') ?>
</div>

<section class="common-bg">
    <div class="account-tap">
        <a href="/notice/customer/support" class="support-tab">
            <?= lang('공지사항', 'Notice', 'お知らせ','公告事项') ?>
        </a>
        <a href="/notice/customer/faq" class="faq-tab">
            <?= lang('자주 묻는 질문', 'FAQ', 'よくある質問','经常问的问题') ?>
        </a>
        <a href="/notice/customer/question" class="question-tab">
            <?= lang('1:1 문의하기', '1:1 Inquiry', '1：1お問い合わせ','1:1咨询') ?>
        </a>
    </div>
    <div>
        <?php
        if ($menu == 'support') { // 공지사항
            include VIEW_ROOT . '/notice/support.php';
        } else if ($menu == 'faq') { // 자주 묻는 질문
            include VIEW_ROOT . '/notice/faq.php';
        } else if ($menu == 'question') { // 1:1 문의하기
            include VIEW_ROOT . '/notice/m_question.php';
        } else if ($menu == 'write') { // 글쓰기
            include VIEW_ROOT . '/notice/write.php';
        }
        ?>
    </div>
</section>