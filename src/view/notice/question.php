<?php
add_stylesheet('<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css?ver=' . time() . '">');
add_javascript('<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/popup.js?ver='.GS_JS_VER.'"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/question.js?ver='.GS_JS_VER.'"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/notice/m_question.php";
} else {
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/question.css?ver=' . time() . '">');
?>
<h1><?= lang('1:1 문의하기', '1:1 Inquiry', '1：1お問い合わせ','1:1咨询') ?></h1>

<form id="question" action="javascript:sendQuestion()">
    <div class="que-content">
        <div class="que-header">
            <div class="que-input-group">
                <span><?= lang('이메일 주소', 'Email Address', 'メールアドレス','电子邮件地址') ?></span>
                <input type="text" id="queEmail" name="email"
                       placeholder="<?= lang('이메일을 입력해주세요.', 'Please enter your email.', 'メールを入力してください。','请输入电子邮件。') ?>">
            </div>
            <div class="que-input-group">
                <span><?= lang('제목', 'Title', 'タイトル','标题') ?></span>
                <input type="text" id="queTitle" name="title"
                       placeholder="<?= lang('제목을 입력해주세요.', 'Please enter a title.', 'タイトルを入力してください。','请输入题目。') ?>">
            </div>
        </div>
        <div class="que-body">
            <div class="que-input-group">
                <span><?= lang('내용', 'Content', '内容','内容') ?></span>
                <textarea id="queContent" name="content"></textarea>
            </div>
            <div class="que-input-group margin-bottom">
                <span></span>
                <p><?= lang(
                        '※ 문의하시는 내용을 상세하게 적어주세요. 정확하고 빠른 답변에 도움이 됩니다.<br>전화번호, 계좌번호 등의 중요한 개인정보는 등록되지 않도록 유의하여 주세요.',
                        '※ Please write down the details of your inquiry. Helps you answer correctly and quickly.<br>Please be careful not to register important personal information such as phone numbers and account numbers.',
                        '※お問い合わせ頂く内容を詳細に記入してください。 正確で迅速な回答に役立ちます。<br>電話番号、口座番号などの重要な個人情報は、登録されてないように注意してください。',
                        '请填写详细咨询内容。 有助于正确快速的答复。<br>请注意电话号码,账号等重要个人信息不被登记。'
                    ) ?></p>
            </div>
            <div class="que-input-group file">
                <span><?= lang('첨부파일', 'Attached file', '添付ファイル','附件') ?></span>
                <label for="queFile"><?= lang('파일 첨부', 'File Attachment', 'ファイル添付','附件') ?></label>
                <input type="file" name="file[]"
                       id="queFile" multiple maxlength="5">
                <ul class="file-name-box"></ul>
            </div>
        </div>
    </div>
    <div class="que-btn">
        <button type="submit" class="notice-href" id="btnSend"><?= lang('문의하기', 'Inquire', 'お問い合わせ','咨询') ?></button>
        <button type="button" class="notice-href" onclick="history.back()"
                id="goBack"><?= lang('작성취소', 'Cancellation', '作成キャンセル','取消写作') ?></button>
    </div>
</form>
<?php
}
?>