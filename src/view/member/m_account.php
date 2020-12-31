<?php
    add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/member/m_account.css?ver=' . time() . '">');
    add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/member/m_account.js"></script>');
?>

<section class="common-bg">
    <!-- 회원 정보 -->
    <div class="account-section">
        <a href="javascript:history.back()" class="hd-back">
            <i class="xi-angle-left-thin"></i>
        </a>
        <?=lang('계정관리','Account management','ウォレット管理','账户管理')?>
    </div>

    <div class="account-tap">
        <a href="/member/account/info" class="info-tab"><?=lang('회원 정보','Account','会員情報','会员信息')?></a>
        <a href="/member/account/certification" class="certification-tab"><?=lang('인증센터','Certification','認証センター','认证中心')?></a>
    </div>

    <div class="none" id="info-content">
        <div class="info-content">
            <div class="account-title">
                <?=lang('회원 정보','Member information','会員情報','会员信息')?>
                <a href="/wallet/main" class="wallet-btn"><?=lang('지갑관리','Wallet management','ウォレット管理','钱包管理')?></a>
            </div>
            <table>
                <tr>
                    <td><?=lang('이메일 주소','Email Address','メールアドレス','电子邮件地址')?></td>
                    <td class="color-blue" id="memberId"><?= $_SESSION['mb_id'] ?></td>
                </tr>
                <tr>
                    <td><?=lang('보안레벨','Security Level','セキュリティレベル','安全水平')?></td>
                    <td class="color-red">
                        Level.<?= $member['mb_level'] ?>
                        <a class="member-btn" href="/member/account/certification"><?=lang('인증센터','Certification Center','認証センター','认证中心')?><i class="xi-angle-right-min"></i></a>
                    </td>
                </tr>
                <tr>
                    <td><?=lang('휴대폰번호','Phone','携帯番号','手机号')?></td>
                    <td class="color-red">
                    <?php echo ($member['mb_hp'] != null) ? $member['mb_hp'] . '<a class="member-btn 
                        go" href="/member/smschangeotp" >'.lang('번호변경','Number change','番号変更','变更号码').'<i class="xi-angle-right-min"></i></a>' : lang
                            ('미인증','Unauthenticated','未認証','未认证')
                            .'<a href="/member/smscertified" class="member-btn go">'.lang('인증센터','Certification Center','認証センター','认证中心').'<i class="xi-angle-right-min"></i></a>'; ?>
                    </td>
                </tr>
                <tr>
                    <td><?=lang('비밀번호 변경','Change Password','パスワード変更','密码变更')?></td>
                    <td class="password-td">
                        <a class="member-btn" href="/member/changepassword"><?=lang('변경하기','To change','変更する','變更')?><i class="xi-angle-right-min"></i></a>
                    </td>
                </tr>
                <tr>
                    <td><?=lang('마케팅 수신동의','Marketing Receipt','マーケティング受信に同意','营销同意')?></td>
                    <td class="marketing-td">
                        <input type="checkbox" class="chk-radio" name="marketing" value="0" <?php echo ($member['mb_marketing'] == 0) ? '' : "checked" ?>>
                        <i class="xi-check xi-check-marketing none"></i>
                    </td>
                </tr>
            </table>
        </div>

        <div class="access-content">
            <div class="account-title">
                <?=lang('최근 접속기록','Recent Access Records','最近アクセス記録','近期登录记录')?>
                <span class="access-btn close">
                    <i class="xi-angle-up"></i>
                </span>
            </div>
            <div class="access-table-div">
                <table class="member-table">
                    <colgroup>
                        <col width="28%">
                        <col width="32%">
                        <col width="13%">
                        <col width="27%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="padding-left"><?=lang('접속시간','Access time','接続時間','连接时间')?></th>
                            <th>IP</th>
                            <th><?=lang('위치','Location','位置','位置')?></th>
                            <th class="padding-right text-center"><?=lang('기종 / 브라우저','Device / Browser','機種 / ブラウザ','机种/浏览器')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="paging"></div>
            </div>
        </div>
    </div>

    <!-- 인증센터 -->
    <div id="certification-content"  class="none">
        <div class="certification-content">
            <div class="account-title">
                <?=lang('인증 센터','Certification Center','認証センター','认证中心')?>
            </div>
        </div>
        <div class="level-box">
        <?=lang('회원님의 현재 보안등급은 <span>LEVEL. '.$member["mb_level"].'</span> 입니다.','Your current security grade is <span>LEVEL. '.$member["mb_level"].'</span>','お客様の現在のセキュリティ評価は<span>LEVEL. '.$member["mb_level"].'</span>です。','会员目前的安全等级为 <span>LEVEL. '.$member["mb_level"].'</span>')?>
        </div>
        <div class="level-items-div">
            <div class="account-title">
                <?=lang('인증 현황','Certification status','認証状況','認證現狀')?>
            </div>
            <div class="level-items">
                <div class="level-item <?php echo $member['mb_level'] >= 1 ? 'complete' : '' ?>">
                    <span class="level-dot">
                        <div></div><div></div><div></div><div></div><div></div>
                    </span>
                    <span class="level">Level 1</span>
                    <img src="/public/img/account/level-ck-off.png" class="level-item-check">
                    <img src="/public/img/account/level-item1.png" class="level-item-img">
                    <span><?=lang('이메일 인증','Email','メール認証','邮箱认证')?></span>
                </div>
                <div class="level-item  <?php echo ($member['mb_level'] >= 2 &&
                    $member['mb_hp'] != '' && $member['mb_hp'] != null) ? 'complete' : '' ?>">
                    <span class="level-dot">
                        <div></div><div></div><div></div><div></div><div></div>
                    </span>
                    <span class="level">Level 2</span>
                    <img src="/public/img/account/level-ck-off.png" class="level-item-check">
                    <img src="/public/img/account/level-item2.png" class="level-item-img">
                    <span><?=lang('SMS 인증','SMS','SMS認証','SMS认证')?></span>
                </div>
                <div class="level-item  <?php echo ($member['mb_level'] >= 3 &&
                    $member['mb_otp'] != '' && $member['mb_otp'] != null) ? 'complete' : '' ?>">
                    <span class="level">Level 3</span>
                    <img src="/public/img/account/level-ck-off.png" class="level-item-check">
                    <img src="/public/img/account/level-item3.png" class="level-item-img">
                    <span>OTP</span>
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
                <div class="level-content sms-content">
                <?php echo $member['mb_level'] >= 2 ? "<p>" . $member['mb_hp'] . " </p><a class='member-btn go' href='/member/smschangeotp' >".lang('번호변경','Number change','番号変更','变更号码')."<i class='xi-angle-right-min'></i></a>":"<a class='member-btn go' href='/member/smscertified'>".lang('인증하기','To authenticate','認証する','认证')."<i class='xi-angle-right-min'></i></a>" ?>
                </div>
            </div>
            <div class="level-row">
                <div class="level-title">
                    <span>Lv3.</span>
                    <?=lang('OTP 인증','OTP','OTP認証','OTP认证')?>
                </div>
                <div class="level-content">
                    <?php echo $member['mb_level'] >= 3 ? lang('인증완료','Certification completed','認証済み','完成认证')."<a class='member-btn' href='/otp/disabled'>".lang('비활성','Inactive','非活性','非活动')."</a>": lang('미인증','Unauthenticated','未認証','未认证')."<a class='member-btn go' href='/otp/login'>".lang('인증하기','To authenticate','認証','认证')."<i class='xi-angle-right-min'></i></a>" ?>
                </div>
            </div>
        </div>
    </div>
</section>