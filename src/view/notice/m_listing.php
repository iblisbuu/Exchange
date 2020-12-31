<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/m_listing.css?ver=' . time() . '">');
?>
<section class="common-bg">
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?=lang('상장문의','Listing','リスティング','上市咨询')?>
    </div>
    <div class="account-div">
        <div class="account-info">
            <p>
                <?=lang(
                    'Genesis.EX에 상장을 원하는 프로젝트팀은 아래 양식을 빠짐없이 작성하여 주세요. 검토 후, 담당부서에서 별도 연락을 드립니다.',
                    'Please fill out the form below for the project team that wants to be listed in Genesis.EX.<br>After the review, the department in charge will contact you separately.',
                    'Genesis.EXに上場を希望するプロジェクトチームは、下記のフォームをもれなく記入してください。<br>検討後、担当部署から連絡をいたします。',
                    'EX上市,.Genesis的项目组在下面的表格,填写全部请。 讨论后,担当部门进行联系。另')?>
            </p>
        </div>
        <form action="javascript:sendListing()" autocomplete="off">
            <div class="listing-content">
                <div class="listing-input-group">
                    <span><?= lang('이메일 주소', 'Email Address', 'メールアドレス','电子邮件地址') ?></span>
                    <input type="text" placeholder="abc123@genesisex.com" name="email">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('프로젝트 명','Project Name','プロジェクト名','项目名')?></span>
                    <input type="text" placeholder="<?=lang('프로젝트 명 입력','Enter Project Name','プロジェクト名を入力','输入项目名')?>" name="projectName">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('프로젝트 소개','Introduction to the Project','プロジェクト紹介','项目介绍')?></span>
                    <input type="text" placeholder="<?=lang('프로젝트 소개 입력','Enter Project Introduction','プロジェクトの紹介を入力','项目介绍输入')?>" name="projectDesc">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('법인 명','Corporate name','法人名','法人名称')?></span>
                    <input type="text" placeholder="<?=lang('법인 명 입력','Enter corporate name','法人名を入力','输入法人名')?>" name="corp">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('토큰명/심볼(국문,영문)','Token Name/Simball (English, English)','トークン名/シンボル(日本語、英文)','汤大明/沈波(国门,英文)')?></span>
                    <input type="text" placeholder="<?=lang('토큰명/심볼 입력','Enter token name/symbol','トークン名 / シンボルを入力','输入大名/符号')?>" name="tokenName">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('토큰 테마','Token theme','トークンテーマ','令牌主题')?></span>
                    <input type="text" placeholder="<?=lang('토큰 테마 입력','Enter Token theme','トークンテーマ入力','令牌主題輸入')?>" name="tokenTheme">
                    <p>Ex) Platform, SNS, Payment, Game, Credit service, etc</p>
                </div>
                <div class="listing-input-group">
                    <span><?=lang('토큰 계열','Token series','トークン系','令牌系列')?></span>
                    <input type="text" placeholder="<?=lang('토큰 계열 입력','Enter Token series','トークン系入力','輸入令牌系列')?>" name="tokenType">
                    <p>Ex) ERC-20, etc</p>
                </div>
                <div class="listing-input-group">
                    <span><?=lang('웹사이트','Website','ウェブサイト','网站')?></span>
                    <input type="text" placeholder="<?=lang('웹사이트 주소 입력','Enter website address','ウェブサイトのアドレスを入力','网址输入')?>" name="website">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('백서 링크','White Paper Link','白書リンク','白皮书链接')?></span>
                    <input type="text" placeholder="<?=lang('백서 링크 입력','Enter White Paper Link','白書リンク入力','輸入白皮書鏈接')?>" name="whitePaper">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('스마트컨트렉트 주소','SmartContact Address','スマートコントラクトアドレス','智能集装箱地址')?></span>
                    <input type="text" placeholder="<?=lang('스마트 컨트렉트 주소 입력','SmartContact Address Input','スマートコントラクトアドレス入力','输入智能控制地址')?>" name="contract">
                </div>
                <div class="listing-input-group">
                    <span><?=lang('소셜 미디어','Social media','ソーシャルメディア','社交媒体')?></span>
                    <input type="text" placeholder="<?=lang('소셜미디어 입력','Enter your social media','ソーシャルメディア入力','社交媒體輸入法')?>" name="sns">
                </div>
            </div>
            <div class="listing-btns">
                <button type="button" id="listing-cancel-btn" onclick="javascript:history.back()"><?= lang('작성취소', 'Cancellation', '作成キャンセル','取消写作') ?></button>
                <button type="submit"><?=lang('제출하기','Submit','提出する','提交')?></button>
            </div>
        </form>
    </div>
</section>