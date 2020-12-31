<?php
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/support.js"></script>');

include_once MD_ROOT . '/board/News.php';
$news = new News();

$type = (isset($_GET['type'])) ? $_GET['type'] : 'all';
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;

// 모바일 일 경우
if ($device == 'mobile') {
    if ($type == 'write') {
        include VIEW_ROOT . '/notice/m_write.php';
        return;
    } else {
    }

    if (isset($_GET['no']) && $_GET['no']) { // 게시글 상세
        include VIEW_ROOT . "/notice/m_support_detail.php";

    } else { // 게시글 목록
        include_once VIEW_ROOT . "/notice/m_support.php";
    }
} else {
    if ($type == 'write') {
        include VIEW_ROOT . '/notice/write.php';
        return;
    } else {
        ?>
        <h1><?= lang('공지사항', 'Notice', 'お知らせ', '公告事项') ?></h1>
        <?php
    }

    if (isset($_GET['no']) && $_GET['no']) { // 게시글 상세
        include VIEW_ROOT . "/notice/support_detail.php";
} else { // 게시글 목록
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/support.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/support.js"></script>');
    ?>
    <ul class="support-lnb">
        <li class="all-tab"><a href="/notice/customer/support"><?= lang('전체', 'ALL', '全て', '全て') ?></a></li>
        <li class="notice-tab"><a
                    href="/notice/customer/support?type=notice"><?= lang('안내', 'Notice', '案内', '向导') ?></a></li>
        <li class="listing-tab"><a
                    href="/notice/customer/support?type=listing"><?= lang('상장', 'Listing', '上場', '上市') ?></a></li>
        <li class="event-tab"><a
                    href="/notice/customer/support?type=event"><?= lang('이벤트', 'Event', 'イベント', '活动') ?></a>
        </li>
    </ul>
    <div class="notice-content">
        <table class="support-tb">
            <colgroup>
                <col width="7%">
                <col width="73%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th colspan="2"><?= lang('제목', 'Title', 'タイトル', '题目') ?></th>
                <th><?= lang('등록일', 'Date', '登録日', '登记日') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $result = $news->getNewsTopList($country, $type);
            $paging = ($page == 1) ? $page - 1 : ($page - 1) * 10;
            $result = array_merge($result,
                $news->getNewsList($country, $type, "{$paging}, 10"));
            if (count($result)) {
                foreach ($result as $list) {
                    switch ($list->nw_type) {
                        case 'notice':
                            $tag = lang('안내', 'Notice', '案内', '向导');
                            break;
                        case 'event':
                            $tag = lang('이벤트', 'Event', 'イベント', '活动');
                            break;
                        case 'listing':
                            $tag = lang('상장', 'Listing', '上場', '上市');
                            break;
                        }
                        $title = 'nw_title_' . $country;
                        $datetime = date('Y-m-d', strtotime($list->nw_datetime));
                        $topfix = ($list->nw_topfix == 'true') ? 'class = "support-topfix"' : '';
                        ?>
                        <tr <?= $topfix ?>>
                            <td class="support-tag"><?= $tag ?></td>
                            <td class="support-title">
                                <a href="/notice/customer/support?no=<?= $list->nw_no ?>">
                                    <?= $list->$title ?>
                                </a>
                            </td>
                            <td class="support-date"><?= $datetime ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td class="support-empty"
                            colspan="3"><?= lang('작성된 게시글이 없습니다.', 'No postings have been created.', '作成された投稿がありません。', '没有写好的留言。')
                            ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

            <div class="notice-etc-box">
                <?php
                $list_cnt = count($news->getNewsList($country, $type)); // 게시글 전체 수 (topfix 제외)
                $type_url = ($type == 'all') ? '' : "?type={$type}";
                paging($list_cnt, $page);
                ?>

                <?php
                if ($member['mb_admin'] ?? '') {
                    ?>
                    <a href="/notice/customer/support?type=write" class="notice-admin notice-href">
                        <?= lang('글쓰기', 'Writing', '物書き', '写作') ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
<?php
}
?>