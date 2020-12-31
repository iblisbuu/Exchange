<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/question.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/write.css?ver=' . time() . '">');
add_stylesheet('<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
add_javascript(' <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/write.js"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/notice/m_write.php";
} else {
?>
<h1><?= lang('공지사항 글쓰기', 'Writing Announcements', 'お知らせ事項書き込み','公告事项写作') ?></h1>

<form id="write" action="javascript:saveNotice()">
    <div class="que-content">
        <div class="que-header que-category">
            <div class="que-input-group">
                <span><?= lang('카테고리', 'Category', 'カテゴリー','类别') ?></span>
                <div class="multi">
                    <div class="que-input-group">
                        <input type="radio" id="topfixFalse" class="noti-chk-inp"
                               name="topfix" value="false" checked>
                        <label for="topfixFalse" class="noti-chk-lnb"><?= lang('일반', 'General', '一般','一般') ?></label>
                        <input type="radio" id="topfixTrue" class="noti-chk-inp"
                               name="topfix" value="true">
                        <label for="topfixTrue" class="noti-chk-lnb"><?= lang('상단고정', 'Top fixation', '上段固定','上端固定')
                            ?></label>
                    </div>
                    <div class="que-input-group">
                        <input type="radio" id="langKo" class="noti-chk-inp"
                               name="language" value="ko" checked>
                        <label for="langKo" class="noti-chk-lnb">한국어</label>
                        <input type="radio" id="langJa" class="noti-chk-inp"
                               name="language" value="ja">
                        <label for="langJa" class="noti-chk-lnb">日本語</label>
                        <input type="radio" id="langEn" class="noti-chk-inp"
                               name="language" value="en">
                        <label for="langEn" class="noti-chk-lnb">English</label>
                        <input type="radio" id="langCh" class="noti-chk-inp"
                               name="language" value="ch">
                        <label for="langCh" class="noti-chk-lnb">汉语</label>
                    </div>
                    <div class="que-input-group">
                        <input type="radio" id="Notice" class="noti-chk-inp"
                               name="category" value="notice" checked>
                        <label for="Notice" class="noti-chk-lnb"><?= lang('안내', 'Notice', '案内','向导') ?></label>
                        <input type="radio" id="Event" class="noti-chk-inp"
                               name="category" value="event">
                        <label for="Event" class="noti-chk-lnb"><?= lang('이벤트', 'Event', 'イベント','活动') ?></label>
                        <input type="radio" id="Listing" class="noti-chk-inp"
                               name="category" value="listing">
                        <label for="Listing" class="noti-chk-lnb"><?= lang('상장', 'Listing', '上場','上市') ?></label>
                    </div>
                </div>
            </div>
            <div class="que-input-group">
                <span><?= lang('제목', 'Title', 'タイトル','题目') ?></span>
                <input type="text" id="nwTitle" name="title"
                       placeholder="<?= lang('제목을 입력해주세요.', 'Please enter a title.', 'タイトルを入力してください。','请输入题目。') ?>">
            </div>
        </div>
        <div class="que-body">
            <div class="que-input-group">
                <span><?= lang('내용', 'Content', '内容','内容') ?></span>
                <textarea id="nwContent" name="content"></textarea>
            </div>
        </div>
    </div>
    <div class="que-btn">
        <input type="hidden" id="mb_id" value="<?= $member['mb_id'] ?>">
        <button type="submit" class="notice-href" id="btnSend"><?= lang('등록하기', 'Register', '登録する','登记') ?></button>
        <button type="button" class="notice-href" onclick="history.back()"
                id="goBack"><?= lang('작성취소', 'Cancellation', '作成キャンセル','取消写作') ?></button>
    </div>
</form>
<?php
}
?>