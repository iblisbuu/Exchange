<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/support_detail.css?ver=' . time() . '">');
$list = $news->getDetailNews($country, $no);
if (count($list)) {
    $list = $list[0];
    switch ($list->nw_type) {
        case 'notice':
            $tag = lang('안내', 'Notice', '案内','向导');
            break;
        case 'event':
            $tag = lang('이벤트', 'Event', 'イベント','活动');
            break;
        case 'listing':
            $tag = lang('상장', 'Listing', '上場','上市');
            break;
    }
    $title = 'nw_title_' . $country;
    $content = 'nw_content_' . $country;
    $datetime = date('Y-m-d', strtotime($list->nw_datetime));
    ?>
    <div class="detail-box">
        <div class="detail-header">
            <span class="detail-tag"><?= $tag ?></span>
            <span class="detail-title"><?= $list->$title ?></span>
        </div>
        <div class="detail-body">
            <p class="detail-write">
                GENESIS·EX | <?= $datetime ?>
            </p>
            <div class="detail-content">
                <?= $list->$content ?>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="detail-box">
        <div class="detail-body text-center">
            <?= lang('존재하지 않는 게시글 입니다.', 'This post does not exist.', '存在しない書き込みです。','不存在的留言。') ?>
        </div>
    </div>
    <?php
}
?>
<div class="notice-etc-box">
    <a onclick="javascript:history.back()" class="notice-href">
        <?= lang('목록으로', 'List', 'リストに','目录') ?>
    </a>
</div>