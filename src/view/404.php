<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/main/404.css?ver=' . time() . '">');
?>
<div class="nfp">
    <div class="nfp-box">
        <h1>PAGE NOT FOUND</h1>
        <p>
            <?=lang('존재하지 않는 주소를 입력하셨거나, 요청하신 페이지의 주소가 변경, 삭제되어 찾을 수 없습니다.',
                'You have entered an address that does not exist, or the address of the page you requested has been changed and deleted.',
                '存在しないアドレスを入力したか、リクエストしたページのアドレスが変更・削除されたため見つかりません。',
                '已输入不存在的地址或已申请页面地址变更,删除,无法查找。')?>
        </p>
        <a href="/">GO MAIN</a>
    </div>
</div>
