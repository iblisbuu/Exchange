<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/m_support_detail.css?ver=' . time() . '">');
$list = $news->getDetailNews($country, $no);
if (count($list)) {
    $list = $list[0];
    switch ($list->nw_type) {
        case 'notice':
            $tag = lang('안내', 'Notice', '御案内','向导');
            break;
        case 'event':
            $tag = lang('이벤트', 'Event', 'イベント','活动');
            break;
        case 'listing':
            $tag = lang('상장', 'Listing', '賞状','上市');
            break;
    }
    $title = 'nw_title_' . $country;
    $content = 'nw_content_' . $country;
    $datetime = date('Y-m-d', strtotime($list->nw_datetime));
    ?>
    <div class="detail-box">
        <div class="detail-header">
            <p class="detail-tag">
                <span>[<?= $tag ?>]</span>
            </p>
            <p class="detail-title"><?= $list->$title ?></p>
        </div>
        <div class="detail-body">
            <p class="detail-write"><?= $datetime ?></p>
            <div class="detail-content">
                <?= $list->$content ?>
            </div>
        </div>
    </div>


<?php
} else {
    ?>
    <div class="detail-box">
        <div class="detail-body text-center no-exist">
            <?= lang('존재하지 않는 게시글 입니다.', 'This post does not exist.', '存在しない書き込みです。','不存在的留言。') ?>
        </div>
    </div>
<?php
}
?>
<div class="notice-etc-box">
    <div onclick="javascript:history.back()" class="notice-href">
        <?= lang('목록으로 돌아가기', 'Return to List', 'リストに戻る','回到目錄') ?>
    </div>
</div>