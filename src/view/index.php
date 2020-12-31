<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/main/index.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/main/index.js"></script>');

// 관심코인 가져오기
$interest = get_cookie('coin_interest');
?>
<section class="banner-section">
    <div class="wrap-middle">
        <div class="text-area">
            <h3><?= lang('가장 신뢰받는 글로벌 암호화폐 거래소', 'Most Trusted Global Cryptocurrency Exchange', '最も信頼される次世代暗号資産取引所', '最受信赖的全球密码货币交易所') ?></h3>
            <h1 class="helvetica">WE ARE<br>GENESIS·EX</h1>
            <p class="pc-view"><?= lang('고객님의 이상 접속 시도 및 이상 거래패턴에 대한 실시간 모니터링을 통해 신속하게 감지하여 피해를 방지합니다.',
                    'We can quickly detect and prevent damage through real-time monitoring of your abnormal connection attempts and abnormal transaction patterns.',
                    'お客様の異常アクセス試みおよび以上の取引パターンに対する<br>リアルタイムモニタリングを通じて迅速に感知して被害を防止します。',
                    '通过对客户异常登录尝试及异常交易模式的实时监控,迅速感知,防止损失。') ?></p>
            <?php
            if (empty($member)) {
                ?>
                <a href="/member/signup" class="helvetica pc-view">SIGN UP</a>
                <?php
            }
            ?>
        </div>
        <div class="img-area">
            <img src="/public/img/main/<?php if ($device == 'mobile') echo 'm-' ?>banner-phone.png" class="phone"/>
            <img src="/public/img/main/box-01.png" class="box box-01"/>
            <img src="/public/img/main/box-02.png" class="box box-02"/>
            <img src="/public/img/main/box-03.png" class="box box-03"/>
            <img src="/public/img/main/box-04.png" class="box box-04"/>
        </div>
    </div>
</section>

<section class="intro-section">
    <div class="wrap-middle">
        <div class="main-header">
            <p>GENESIS·EX</p>
            <h1>Welcome to GENESIS·EX</h1>
        </div>
        <div class="intro-top">
            <div class="intro-text">
                <h1 class="helvetica pc-view">DEVICE FOR YOU</h1>
                <p class="pc-view"><?= lang('GENESIS·EX 에 오신 것을 환영합니다! GENESIS·EX는
                    고객님들의 편의성을 위해 PC / Mobile 등 다양한 디바이스를
                    지원합니다. 또한 글로벌 암호화폐 사용자에게 최적화된
                    전용 APP 서비스도 준비 중이오니 많은 이용 부탁드립니다.',
                        'Welcome to GENESIS·EX! GENESIS·EX is
                    For your convenience, we\'re going to use various devices such as PC/Mobile.
                    I\'m applying. It\'s also optimized for global crypto users.
                    We are also preparing a dedicated APP service, so please use it a lot.',
                        'GENESIS-EXへようこそ！
                            GENESIS-EXは、お客様の利便性のために
                            PC / Mobileなど、さまざまなデバイスをサポートします。
                            また、グローバルユーザーに最適化された
                            専用APPサービスも用意されていますので、多くのご利用
                            お願いします。',
                        '欢迎光临GENESIS·EX! GENESIS·EX
                    为了顾客们的便利性,电脑/Mobile等多种设备
                    支持。 并且是最适合全球密码货币使用者的
                    专用APP服务也在准备中,请多多使用。') ?></p>
                <p class="mb-view">
                    <?= lang('GENESIS·EX는 PC / Mobile 등 다양한 디바이스를
지원하며 글로벌 암호화폐 사용자에게 최적화 된
전용 APP 서비스도 준비 중입니다.',
                        'GENESIS·EX provides various devices such as PCs/Mobile.
Supported and optimized for global crypto users
We are also preparing a dedicated APP service.',
                        'GENESIS・EXは、PC/Mobileなど様々なデバイスを
サポートし、グローバル仮想通貨ユーザーに最適な
専用のAPPサービスも準備しています。',
                        'GENESIS·EX包括PC / Mobile等多种设备
支持并最适合全球密码货币使用者的
专用APP服务也在准备中。') ?>
                </p>
                <a href="/exchange/main" class="helvetica pc-view">EXCHANGE</a>
            </div>
            <div class="intro-img">
                <img src="/public/img/main/intro-photo.png"/>
            </div>
        </div>
        <div class="intro-bottom">
            <ul class="intro-table">
                <li class="intro-head">
                    <ul>
                        <li class="pc-view"></li>
                        <li>
                            <button type="button"
                                    onclick="sort(this,'coinname')"><?= lang('코인명', 'Coin', '', '科因明') ?></button>
                        </li>
                        <li>
                            <button type="button"
                                    onclick="sort(this,'price')"><?= lang('현재가', 'Price', '', '现价') ?></button>
                        </li>
                        <li>
                            <button type="button"
                                    onclick="sort(this,'percent')"><?= lang('등락률', '24h', '', '騰落率') ?></button>
                        </li>
                        <li>
                            <button type="button"
                                    onclick="sort(this,'volumn')"><?= lang('거래대금', 'Vol', '', '成交金额') ?></button>
                        </li>
                    </ul>
                </li>
                <li class="intro-body">
                    <?php
                    $nowTime = time();
                    $yesterTime = $nowTime - 86400;

                    $db = new db();

                    $query = "SELECT ci_price FROM _coins WHERE ci_symbol = 'USDT'";
                    $coins = $db->fetchAll($query)[0];
                    $_USDT_PRICE = $coins->ci_price;

                    $query = "SELECT 
                                ci_btc, ci_usdt, ci_symbol, ci_btc_total, ci_usdt_total, ci_{$country}_name, 
                                (SELECT cd_usdt FROM _coinDaily AS D WHERE C.ci_symbol = D.cd_symbol ORDER BY cd_datetime DESC LIMIT 0, 1) as cd_usdt, 
                                (SELECT cd_btc FROM _coinDaily AS D WHERE C.ci_symbol = D.cd_symbol ORDER BY cd_datetime DESC LIMIT 0, 1) as cd_btc,(SELECT sum(tr_amount) FROM _trade AS T WHERE T.tr_symbol = C.ci_symbol AND tr_currency = 'USDT' AND tr_datetime >= '{$yesterTime}') as ci_usdt_total_price
                                
                              FROM _coins AS C WHERE ci_use = 0";
                    $coins = $db->fetchAll($query);
                    foreach ($coins as $row) {
                        $row = objectToArray($row);
                        $coinPrice = round($row['ci_usdt_total_price'] * $row['ci_usdt'] * $_USDT_PRICE / 1000000, 1); // 백만단위 나누기
                        $coinPrice .= lang('백만', 'M');
                        $coinPercent = round_down_format_fix(($row['ci_usdt'] / $row['cd_usdt'] * 100) - 100, 2);
                        ?>
                        <ul>
                            <li class="pc-view">
                                <span class="bookmark <?= strpos($interest, $row['ci_symbol']) !== false ? 'active' : '' ?>"
                                      data-type="<?= strtolower($row['ci_symbol']) ?>"></span></li>
                            <li>
                                <div class="coinname" data-sort="coinname">
                                    <span><?= $row["ci_{$country}_name"] ?></span>
                                    <p><?= $row['ci_symbol'] ?></p>
                                </div>
                            </li>
                            <li class="price" data-sort="price"><?= round_down_format($row['ci_usdt'], 8) ?> USDT</li>
                            <li class="percent color-<?= $coinPercent >= 0 ? 'red' : 'skyblue' ?>"
                                data-sort="percent"><?= $coinPercent ?>%
                            </li>
                            <li class="volumn"
                                data-sort="volumn"><?= $coinPrice ?></li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
        </div>
        <a href="/exchange/main" class="helvetica mb-view go-link">EXCHANGE</a>
    </div>
</section>

<section class="advantage-section">
    <div class="wrap-middle">
        <div class="main-header">
            <p>GENESIS·EX</p>
            <h1>Our Advantage</h1>
        </div>
        <?php
        if ($device == 'pc') {
            ?>
            <div class="advantage-items">
                <div class="advantage-rows">
                    <div>
                        <img src="/public/img/main/advantage-01.png" class="advantage-img"/>
                        <img src="/public/img/main/advantage-02.png" class="advantage-img"/>
                        <div class="advantage-items-03 advantage-item">
                            <div class="ad-item-desc">
                                <h1><?= lang('실시간 모니터링 시스템', 'Real-time monitoring system', 'リアルタイムモニタリングシステム', '实时监控系统') ?></h1>
                                <p><?= lang('고객님의 이상 접속 시도 및 이상 거래 패턴에 대한<br>실시간 모니터링을 통해 감지하여 피해를 방지합니다.',
                                        'We detect your abnormal connection attempts and real-time monitoring of abnormal transaction patterns to prevent damage.',
                                        'お客様の異常アクセス試みおよび以上の取引パターンに対するリアルタイムモニタリングを通じて迅速に感知して被害を防止します。',
                                        '通过实时监控客户异常连接及异常交易模式,防止损失。') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="advantage-items-04 advantage-item">
                        <div class="ad-item-desc">
                            <h1><?= lang('빠르고 안정적인 거래 시스템', 'Fast and stable trading system', '迅速かつ安定的な取引システム', '快速、稳定的交易系统') ?></h1>
                            <p><?= lang('인터넷 뱅킹 시스템, 보안 솔루션, 게임 서비스 플랫폼 등을 개발했던 우수한 개발진들이 모여 Genesis-EX만의 특화된 거래 엔진을 개발했습니다.',
                                    'Leading developers who developed Internet banking systems, security solutions, and game service platforms gathered to develop a specialized trading engine for the Genesis-EX.',
                                    'インターネットバンキングシステム、セキュリティソリューション、ゲームサービスプラットフォームなどを開発した優秀な開発陣が集まってGENESIS-EX だけの特化した取引エンジンを開発しました。',
                                    '曾经开发过网上银行系统,安全解决方案,游戏服务平台等的优秀开发团队齐聚一堂,开发出Genesis-EX独有的特色交易引擎。') ?></p>
                        </div>
                    </div>
                </div>
                <div class="advantage-rows">
                    <img src="/public/img/main/advantage-05.png" class="advantage-img"/>
                    <div class="advantage-items-06 advantage-item">
                        <div class="ad-item-desc">
                            <h1><?= lang('최고의 보안 수준 적용', 'Get the highest level of security', '最高のセキュリティレベル適用', '最高安全水准的使用') ?></h1>
                            <p><?= lang('개인 정보 데이터 암호화를 기본으로 네트워크 통제의 물리적 접근과 지갑 정보에 대한 3중 보안 장치를 구축했습니다.',
                                    'Based on privacy data encryption, we have built a triple security device for network control physical access and wallet information.',
                                    '個人情報データ暗号化を基にネットワーク統制の物理的アクセスとおサイフ情報についての三重のセキュリティー装置を構築しました。',
                                    '以个人信息数据加密为基础,构建了网络控制的物理接近和钱包信息的三重安全装置。') ?></p>
                        </div>
                    </div>
                    <img src="/public/img/main/advantage-07.png" class="advantage-img"/>
                </div>
            </div>
            <?php
        }

        if ($device == 'mobile'){
        ?>
        <div class="advantage-items">
            <div class="advantage-rows">
                <div class="advantage-item">
                    <div class="ad-item-desc">
                        <h1><?= lang('빠르고 안정적인 거래 시스템', 'Fast and stable trading system', '迅速かつ安定的な取引システム', '快速、稳定的交易系统') ?></h1>
                        <p><?= lang('GENESIS EX만의 특화된 거래 엔진', 'GENESIS EX\'s specialized trading engine', 'GENESIS EXならではの特化した取引エンジン', 'GENESIS EX独有的特色交易引擎') ?></p>
                    </div>
                    <div class="ad-item-img">
                    <img src="/public/img/main/m-advantage-01.png"/>
                    </div>
                </div>
                <div class="advantage-item">
                    <div class="ad-item-desc">
                        <h1><?= lang('실시간 모니터링 시스템', 'Real-time monitoring system', 'リアルタイムモニタリングシステム', '实时监控系统') ?></h1>
                        <p><?= lang('실시간 모니터링을 통해 신속한 피해 방지', 'Real-time monitoring helps prevent damage quickly', 'リアルタイムモニタリングを通じて<br>迅速な被害防止', '通过实时监控,迅速防止损失') ?></p>
                    </div>
                    <div class="ad-item-img">
                    <img src="/public/img/main/m-advantage-02.png"/>
                    </div>
                </div>
                <div class="advantage-item">
                    <div class="ad-item-desc">
                        <h1><?= lang('최고의 보안 수준 적용', 'Get the highest level of security', '最高のセキュリティレベル適用', '最高安全水准的使用') ?></h1>
                        <p><?= lang('지갑 정보에 대한 3중 보안장치 구축', 'Establishing triple security for wallet information', 'ウォレット管理情報に対する<br>三重セキュリティ装置の構築', '构建钱包信息的三重安全装置') ?></p>
                    </div>
                    <div class="ad-item-img">
                    <img src="/public/img/main/m-advantage-03.png"/>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
</section>

<section class="partner-section">
    <div class="main-header">
        <p>GENESIS·EX</p>
        <h1>PARTNERSHIP</h1>
    </div>
    <div class="wrap-middle scroll">
        <ul class="partner-items">
            <li><img src="/public/img/main/partnership-item01.png"></li>
            <li><img src="/public/img/main/partnership-item02.png"></li>
            <li><img src="/public/img/main/partnership-item03.png"></li>
            <li><img src="/public/img/main/partnership-item04.png"></li>
            <li><img src="/public/img/main/partnership-item05.png"></li>
        </ul>
    </div>
</section>
