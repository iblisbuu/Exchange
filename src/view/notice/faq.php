<?php
include_once MD_ROOT . '/board/Faq.php';
$faq = new Faq();

add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/faq.js?v=2"></script>');

if ($device == 'mobile') {
    include_once VIEW_ROOT . "/notice/m_faq.php";
} else {
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/faq.css?ver=' . time() . '">');
    
?>
<h1><?= lang('자주 묻는 질문', 'FAQ', 'よくある質問','经常问的问题') ?></h1>
<form class="search-box common-input" action="javascript:searchFaq()">
    <input type="text" id="searchValue"
           placeholder="<?= lang('검색어를 입력해주세요.', 'Please enter a search term.', '検索ワードを入力してください。','请输入搜索词。') ?>">
    <button type="button" class="btn-close none">
        <img src="/public/img/common/close.svg"/>
    </button>
    <button type="submit" class="btn-search">
        <img src="/public/img/common/search.svg"/>
    </button>
</form>
<div class="faq-desc">
    <?= lang('원하는 정보를 찾으실 수 없다면 회원가입 또는 로그인 후', 'If you can\'t find the information you want, please leave a', '必要な情報を見つけることができない場合会員登録またはログイン後','如找不到想要的信息,请注册会员或登录后') ?>
    <a href="/notice/customer/question" class="href"><?=lang('1:1 문의','1:1 inquiry','1：1お問い合わせ','1:1咨询')?></a>
    <?=lang('를 남겨주세요.','after signing up or logging in.','を残してください。','请留下。')?>
    <a href="/notice/customer/question" class="btn-move">
        <?=lang('1:1 문의하기','1:1 Inquiry','1：1お問い合わせ','1:1咨询')?>
    </a>
</div>
<div class="notice-content">
    <?php
    $faqLists = $faq->getAllFaqList($country);
    foreach ($faqLists as $NewFaqList) {
        $faqList = $NewFaqList['faqList'];
        ?>
        <div class="faq-box">
            <div class="faq-header">
                <?= $NewFaqList["fc_name_$country"] ?>
            </div>
            <ul class="faq-body">
                <?php
                for ($i = 0; $i < count($faqList); $i++) {
                    $faq = $faqList[$i];
                    $question = "faq_q_$country";
                    $answer = "faq_a_$country";
                    ?>
                    <li class="faq-li">
                        <p class="faq-q" onclick="openAnswer(this)"><?= $faq->$question ?></p>
                        <div class="faq-a"><?= $faq->$answer ?></div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
</div>
<?php
}
?>