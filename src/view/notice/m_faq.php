<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/m_faq.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/m_faq.js?ver=' . time() . '"></script>');

include_once MD_ROOT . '/board/Faq.php';
$faq = new Faq();
?>

<div class="search-div">
    <form class="search-box" action="javascript:searchFaq()">
        <input type="text" id="searchValue" autocomplete="off" placeholder="<?=lang('무엇이 궁금하신가요?','What do you want to know?','何が気になりますか？','想知道什麼？') ?>">
        <button type="submit" class="btn-search">
            <img src="/public/img/c2c/search.svg"/>
        </button>
    </form>
</div>

<div class="notice-content">
    <h1>GENESIS EX</h1>
    <?php
    $faqLists = $faq->getAllFaqList($country);
    foreach ($faqLists as $NewFaqList) {
        $faqList = $NewFaqList['faqList'];
        ?>
        <div class="faq-box">
            <div class="faq-header">
                <?= str_replace('GENESIS EX','',$NewFaqList["fc_name_$country"]) ?>
            </div>
            <div class="faq-body">
                <?php
                for ($i = 0; $i < count($faqList); $i++) {
                    $faq = $faqList[$i];
                    $question = "faq_q_$country";
                    $answer = "faq_a_$country";
                    ?>
                    <p class="faq-q" onclick="openAnswer(this)">
                        <span class="faq-ico"></span>
                        <span><?= $faq->$question ?></span>
                        <i class="xi-angle-down-min"></i>
                    </p>
                    <div class="faq-li">
                        <?= $faq->$answer ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <hr>
        <?php
    }
    ?>
</div>