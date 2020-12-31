<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/m_question.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/question.js?ver='.GS_JS_VER.'"></script>');
?>

<form id="question" action="javascript:sendQuestion()">
    <div class="que-content">
        <div class="que-header">
            <div class="que-input-header-group">
                <span><?= lang('이메일 주소 입력', 'Enter an email address', 'メールアドレス入力','輸入電子郵箱地址') ?></span>
                <input type="text" id="queEmail" name="email">
            </div>
            <div class="que-input-header-group">
                <span><?= lang('제목 입력', 'Enter an Title', 'タイトル入力','題目輸入') ?></span>
                <input type="text" id="queTitle" name="title">
            </div>
        </div>
        <div class="que-body">
            <div class="que-input-body-group">
                <span><?= lang('내용', 'Content', '内容','内容') ?></span>
                <textarea id="queContent" placeholder="<?= lang(
                        '※ 문의하시는 내용을 상세하게 적어주세요. 정확하고 빠른 답변에 도움이 됩니다.&#13;&#10;전화번호, 계좌번호 등의 중요한 개인정보는 등록되지 않도록 유의하여 주세요.',
                        '※ Please write down the details of your inquiry. Helps you answer correctly and quickly.&#13;&#10;Please be careful not to register important personal information such as phone numbers and account numbers.',
                        '※ お問い合わせ頂く内容を詳細に記入してください。 正確で迅速な回答に役立ちます。&#13;&#10;電話番号、口座番号などの重要な個人情報は、登録されてないように注意してください。',
                        '※ 请填写详细咨询内容。 有助于正确快速的答复。&#13;&#10;请注意电话号码,账号等重要个人信息不被登记。'
                    ) ?>"></textarea>
            </div>
            <div class="que-input-body-group file">
                <span><?= lang('첨부파일', 'Attached file', '添付ファイル','附件') ?></span>
                <label class="file-btn" for="queFile"><?= lang('파일 첨부', 'File Attachment', 'ファイル添付','附件') ?></label>
                <input type="file" name="file[]" id="queFile" multiple maxlength="5">
                <ul class="file-name-box"></ul>
            </div>
        </div>
    </div>
    <div class="que-btn">
        <button type="button" class="notice-href" onclick="history.back()" id="goBack"><?= lang('취소', 'Cancel', '取り消し','取消') ?></button>
        <button type="submit" class="notice-href" id="btnSend"><?= lang('1:1 문의하기', '1:1 Inquire', '1:1お問い合わせ','1:1咨询') ?></button>
    </div>
</form>