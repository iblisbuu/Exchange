<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/paging.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/common.js"></script>');

$_GET['menu'] = $segment[2];
$menu = $_GET['menu'];

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/notice/m_customer.php";
} else {
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/common.css?ver=' . time() . '">');
?>

<section class="account-section common-bg">
    <div class="wrap-middle">
        <h1><?= lang('고객센터', 'Support', 'サポート','客户中心') ?></h1>
        <div class="account-div">
            <div class="account-tap">
                <div>
                    <a href="/notice/customer/support"
                       class="support-tab"><?= lang('공지사항', 'Notice', 'お知らせ','公告事项') ?></a>
                    <a href="/notice/customer/faq" class="faq-tab"><?= lang('자주 묻는 질문', 'FAQ', 'よくある質問','经常问的问题') ?></a>
                    <a href="/notice/customer/question"
                       class="question-tab"><?= lang('1:1 문의하기', '1:1 Inquiry', '1：1お問い合わせ','1:1咨询') ?></a>
                </div>
            </div>
            <div class="common-yellow-box">
                <?php
                if ($menu == 'support') { // 공지사항
                    include VIEW_ROOT . '/notice/support.php';
                } else if ($menu == 'faq') { // 자주 묻는 질문
                    include VIEW_ROOT . '/notice/faq.php';
                } else if ($menu == 'question') { // 1:1 문의하기
                    include VIEW_ROOT . '/notice/question.php';
                } else if ($menu == 'write') { // 글쓰기
                    include VIEW_ROOT . '/notice/write.php';
                }
                ?>
            </div>
        </div>
</section>
<?php
}
?>