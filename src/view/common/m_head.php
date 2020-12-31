<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/header.css">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/m_header.css">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/head.js"></script>');
?>
<header>
    <div class="wrap-wide">
        <a href="<?= ROOT ?>" class="hd-logo"></a>
        <span class="mb-menu"></span>
    </div>
</header>
<section id="top-notice">
    <?php
    // NEWS
    require_once SRC_ROOT . '/config/DB.php';
    require_once MD_ROOT . '/board/News.php';
    $news = new News();
    $noticeResult = objectToArray($news->getNewsList($country));
    if($noticeResult){
        $type = $noticeResult[0]['nw_type'];
        switch ($type) {
            case 'notice':
                $type = lang('공지', 'notice', 'お知らせ', '公告');
                break;
            case 'event':
                $type = lang('이벤트', 'event', 'イベント', '活动');
                break;
            case 'listing':
                $type = lang('상장', 'Listing', '上場', '上市');
                break;
        }
        $time = str_replace('-', '.', explode(' ', $noticeResult[0]['nw_datetime'])[0]);
        $title = $noticeResult[0]['nw_title_' . $country];
    ?>
    <div class="wrap-wide wrap-top-notice">
        <a class="top-notice-box" href="/">
            <span class="top-notice-title">[<?= $type ?>] <?= $title ?></span>
        </a>
        <button><i class="xi-close"></i></button>
    </div>
    <?php }?>
</section>