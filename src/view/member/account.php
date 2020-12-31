<?php
if (empty($member)) {
    add_event('alert_hooks', 'not_login');
    return false;
}

add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/common/paging.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/account.js"></script>');

// 모바일 일 경우
if ($device == 'mobile') {
    include_once VIEW_ROOT . "/member/m_account.php";
} else {
    // PC 일 경우
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/notice/common.css?ver=' . time() . '">');
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/account.css?ver=' . time() . '">');

?>

<section class="account-section common-bg">
    <div class="wrap-middle">
        <h1><?=lang('계정관리','Account management','アカウント管理','账户管理')?></h1>
        <div class="account-div">
            <div class="account-tap">
                <div>
                <a href="/member/account/info" class="info-tab"><?=lang('회원 정보','Account','会員情報','会员信息')?></a>
                <a href="/member/account/certification" class="certification-tab"><?=lang('인증센터','Certification','認証センター','认证中心')?></a>
                </div>
            </div>
            <!--     회원 정보       -->
            <div id="info-content" class="common-yellow-box none">
                <div>
                    <h2><?=lang('회원 정보','Member information','会員情報','会员信息')?></h2>
                    <table class="member-table">
                        <colgroup>
                            <col width="15%">
                        </colgroup>
                        <tr>
                            <th><?=lang('이메일 주소','Email Address','メールアドレス','电子邮件地址')?></th>
                            <td class="td-500" id="memberId"><?= $_SESSION['mb_id'] ?></td>
                        </tr>
                        <tr>
                            <th><?=lang('보안레벨','Security Level','セキュリティレベル','安全水平')?></th>
                            <td>
                                Level.<?= $member['mb_level'] ?>
                                <a class="member-btn go" href="/member/account/certification"><?=lang('인증센터','Certification Center','認証センター','认证中心')?></a>
                            </td>
                        </tr>
                        <tr>
                            <th><?=lang('휴대폰번호','Phone','携帯番号','手机号')?></th>
                            <td>
                                <?php echo ($member['mb_hp'] != null) ? $member['mb_hp'] . '<a class="member-btn 
                                go" href="/member/smschangeotp" >'.lang('번호 변경','Number change','番号変更','变更号码').'</a>' : lang
                                    ('미인증','Unauthenticated','米認証','未认证')
                                    .'<a href="/member/smscertified" class="member-btn go">'.lang('인증하기','To authenticate','認証','认证').'</a>'; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?=lang('비밀번호 변경','Change Password','パスワード変更','密码变更')?></th>
                            <td><a class="member-btn" href="/member/changepassword"><?=lang('비밀번호 변경','Change Password','パスワード変更','密码变更')?></a></td>
                        </tr>
                        <tr>
                            <th><?=lang('마케팅 수신동의','Marketing Receipt','マーケティング受信に同意','营销同意')?></th>
                            <td>

                                <input type="radio" id="agree" class="chk-radio" name="marketing" value="1"
                                       checked>
                                <label for="agree"><?=lang('동의','Agreement','同意','同意')?></label>
                                <input type="radio" id="disagree" class="chk-radio" name="marketing" value="0"
                                    <?php echo ($member['mb_marketing'] == 0) ? "checked" : '' ?>>
                                <label for="disagree"><?=lang('동의 안함','Disagreement','同意しない','不同意')?></label>
                            </td>
                        </tr>
                    </table>
                    <a href="/wallet/main" class="wallet-btn member-btn"><?=lang('지갑관리','Wallet management',
                            'ウォレット管理','钱包管理')?></a>
                </div>
                <div>
                    <div class="access">
                        <h2><?=lang('최근 접속기록','Recent Access Records','最近アクセス記録','近期登录记录')?></h2><span class="access-btn close
"></span>
                    </div>
                    <div class=" access-table-div">
                        <table class="member-table">
                            <colgroup>
                                <col width="20%">
                                <col width="12%">
                                <col width="15%">
                                <col width="25%">
                                <col width="28%">
                            </colgroup>
                            <thead>
                            <th><?=lang('접속시간','Access time','接続時間','连接时间')?></th>
                            <th>IP</th>
                            <th class="text-center"><?=lang('위치','Location','位置','位置')?></th>
                            <th class="text-center"><?=lang('기종 / 브라우저','Device / Browser','機種 / ブラウザ','机种/浏览器')?></th>
                            <th><?=lang('결과','Result','結果','结果')?></th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="paging"></div>
                    </div>
                </div>
            </div>
            <!--     인증 센터       -->
            <div id="certification-content" class="common-yellow-box none">
                <div>
                    <h2><?=lang('인증 센터','Certification Center','認証センター','认证中心')?></h2>
                    <div class="level-box"><?=lang('회원님의 현재 보안등급은 <span>LEVEL '.$member["mb_level"].'</span> 입니다
                    .','Your current security grade is <span>LEVEL '.$member["mb_level"].'</span>','お客様の現在のセキュリティ評価は<span>LEVEL'.$member["mb_level"].'</span>です。','会员目前的安全等级为 <span>LEVEL '.$member["mb_level"].'</span>')?></div>
                </div>
                <div>
                    <h2><?=lang('GENESIS·EX 인증 단계','GENESIS·EX Certification Steps','GENESIS・EX認証段階','GENESIS·EX认证阶段')?></h2>
                    <div class="level-items">
                        <div class="level-item <?php echo $member['mb_level'] >= 1 ? 'complete' : '' ?>">
                            <h3><?=lang('','','')?>LEVEL.1</h3>
                            <img src="/public/img/account/level-item1.png">
                            <div>
                                <span><?=lang('이메일 인증','Email','メール認証','邮箱认证')?></span>
                                <p><?=lang('회원가입','Sign up','会員登録','注册会员')?></p>
                            </div>
                        </div>
                        <div class="level-item  <?php echo ($member['mb_level'] >= 2 &&
                            $member['mb_hp'] != '' && $member['mb_hp'] != null) ? 'complete' : '' ?>">
                            <h3>LEVEL.2</h3>
                            <img src="/public/img/account/level-item2.png">
                            <div>
                                <span><?=lang('SMS 인증','SMS','SMS認証','SMS认证')?></span>
                                <p><?=lang('거래소 주문 · 가상자산 입출금','Exchange orders · <br>deposit and withdrawal','取引所注文・暗号資産入出金','交易所订单·虚拟资产存取款')?></p>
                            </div>
                        </div>
                        <div class="level-item  <?php echo ($member['mb_level'] >= 3 &&
                            $member['mb_otp'] != '' && $member['mb_otp'] != null) ? 'complete' : '' ?>">
                            <h3>LEVEL.3</h3>
                            <img src="/public/img/account/level-item3.png">
                            <div>
                                <span>OTP</span>
                                <p><?=lang('보안 강화','Increased security','セキュリティ強化','加强保安')?></p>
                            </div>
                        </div>
                    </div>
                    <div class="level-list">
                        <div class="level-row">
                            <div class="level-title">
                                <span>Lv1.</span>
                                <?=lang('이메일 인증','Email','メール認証','电子邮件认证')?>
                            </div>
                            <div class="level-content">
                                <input type="text" class="level-email" value="<?= $_SESSION['mb_id'] ?>" readonly/>
                            </div>
                        </div>
                        <div class="level-row">
                            <div class="level-title">
                                <span>Lv2.</span>
                                <?=lang('SMS 인증','SMS','SMS認証','SMS认证')?>
                            </div>
                            <div class="level-content">
                                <?php echo $member['mb_level'] >= 2 ? "<strong>" . $member['mb_hp'] . " </strong><a class='member-btn go' href='/member/smschangeotp' >".lang('번호변경','Number change','番号変更','变更号码')."</a>"
                                    : lang('본인명의 휴대폰 번호를 등록해주세요.','Please register your mobile phone number.','本人名義の携帯の番号を登録してください。','本人的名义登记的电话号码,一下。')."<a class='member-btn go' href='/member/smscertified'>".lang('인증하기','To authenticate','認証する','认证')."</a>" ?>
                            </div>
                        </div>
                        <div class="level-row">
                            <div class="level-title">
                                <span>Lv3.</span>
                                <?=lang('OTP 인증','OTP','OTP認証','OTP认证')?>
                            </div>
                            <div class="level-content">
                                <?php echo $member['mb_level'] >= 3 ? lang('인증완료','Certification completed','認証完了','完成认证')."<a class='member-btn' href='/otp/disabled'>".lang('비활성','Inactive','非活性','非活动')."</a>"
                                    : lang('미인증','Unauthenticated','米認証','未认证')."<a class='member-btn go' href='/otp/login'>".lang('인증하기','To authenticate','認証','认证')."</a>" ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php
}
?>