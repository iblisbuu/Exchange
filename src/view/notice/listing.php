<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/common.css?ver=' . time() . '">');
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/popup.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/common/popup.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/notice/listing.js"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/notice/m_listing.php";
} else {
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/listing.css?ver=' . time() . '">');
?>

<section class="account-section common-bg">
    <div class="wrap-middle">
        <div class="title-div">
            <h1><?=lang('상장문의','Listing','リスティング','上市咨询')?></h1>
            <p><?=lang(
                    'Genesis.EX에 상장을 원하는 프로젝트팀은 아래 양식을 빠짐없이 작성하여 주세요. 검토 후, 담당부서에서 별도 연락을 드립니다.',
                    'Please fill out the form below for the project team that wants to be listed in Genesis.EX.<br>After the review, the department in charge will contact you separately.',
                    'Genesis.EXに上場を希望するプロジェクトチームは、下記のフォームをもれなく記入してください。<br>検討後、担当部署から連絡をいたします。',
                    'EX上市,.Genesis的项目组在下面的表格,填写全部请。 讨论后,担当部门进行联系。另')?></p>
        </div>
        <div class="account-div">
            <div class="common-yellow-box">
                <h2><?=lang('상장문의','Listing','リスティング','上市咨询')?></h2>
                <form action="javascript:sendListing()" autocomplete="off">
                    <div class="listing-content">
                        <div class="listing-input-group">
                            <span><?= lang('이메일 주소', 'Email Address', 'メールアドレス','电子邮件地址') ?></span>
                            <input type="text" placeholder="<?=lang('이메일을 입력','Enter your email','Eメールを入力','输入邮件')?>"
                                   name="email">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('프로젝트 명','Project Name','プロジェクト名','项目名')?></span>
                            <input type="text" placeholder="<?=lang('프로젝트 명을 입력','Enter Project Name','プロジェクト名を入力','输入项目名')
                            ?>" name="projectName">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('프로젝트 소개','Introduction to the Project','プロジェクト紹介','项目介绍')?></span>
                            <input type="text" placeholder="<?=lang('프로젝트 소개 입력','Enter Project Introduction','プロジェクトの紹介を入力','项目介绍输入')?>" name="projectDesc">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('법인명','Corporate name','法人名','法人名称')?></span>
                            <input type="text" placeholder="<?=lang('법인명 입력','Enter corporate name','法人名を入力','输入法人名')?>"
                                   name="corp">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('토큰명/심볼(국문,영문)','Token Name/Simball (English, English)','トークン名/シンボル(日本語、英文)','汤大明/沈波(国门,英文)')?></span>
                            <input type="text" placeholder="<?=lang('토큰명/심볼(국문,영문) 입력','Enter token name/symbol (English, English)','トークン名 / シンボル（国語、英語）を入力','输入大名/符号(韩文,英文)')?>" name="tokenName">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('토큰 테마','Token theme','トークンテーマ','令牌主题')?></span>
                            <input type="text" placeholder="Ex) Platform, SNS, Payment, Game, Credit service, etc" name="tokenTheme">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('토큰 계열','Token series','トークン系','令牌系列')?></span>
                            <input type="text" placeholder="Ex) ERC-20, etc" name="tokenType">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('웹사이트','Website','ウェブサイト','网站')?></span>
                            <input type="text" placeholder="<?=lang('웹사이트 주소 입력','Enter website address','ウェブサイトのアドレスを入力','网址输入')?>" name="website">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('백서 링크','White Paper Link','白書リンク','白皮书链接')?></span>
                            <input type="text" placeholder="<?=lang('링크 입력','Link Input','ホワイトペーパーのリンク','链接输入')?>" name="whitePaper">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('스마트컨트랙트 주소','SmartContact Address','スマートコントラクトアドレス','智能集装箱地址')?></span>
                            <input type="text" placeholder="<?=lang('스마트컨트랙트 주소 입력','SmartContact Address Input','スマートコントラクトアドレス入力','输入智能控制地址')?>" name="contract">
                        </div>
                        <div class="listing-input-group">
                            <span><?=lang('소셜 미디어','Social media','ソーシャルメディア','社交媒体')?></span>
                            <input type="text" placeholder="<?=lang('소셜 미디어 주소 입력','Enter your social media address','ソーシャルメディアアドレスを入力','输入社交媒体地址')?>" name="sns">
                        </div>
                    </div>
                    <div class="listing-buttons">
                        <button type=submit class="btn btn-yellow"><?=lang('제출하기','Submit','提出する','提交')?></button>
                        <a class="btn" id="listing-cancel-btn" onclick="javascript:history.back()"><?= lang('작성취소', 'Cancellation', '作成キャンセル','取消写作') ?></a>
                    </div>
                </form>
            </div>
        </div>
</section>
<?php
}
?>