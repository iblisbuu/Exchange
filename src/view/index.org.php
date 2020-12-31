<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/main/index.org.css?ver=' . time() . '">');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/common/tw.slide.js"></script>');
add_javascript('<script type="text/javascript" src="' . ROOT . 'public/js/main/index.org.js"></script>');
?>
<section class="banner-section">
    <ul>
        <li>
            <div>
                <div class="ani-bg-box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="banner-txt-box">
                    <h1>Genesis·Exchange</h1>
                    <p><?= lang('가장 신뢰받는 차세대 암호화폐 거래소', 'Most Trusted Next Generation Cryptocurrency Exchange', '最も信頼される次世代仮想通貨取引所') ?></p>
                </div>
            </div>
        </li>
        <li>
            <div>
                <div class="ani-bg-box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="banner-txt-box">
                    <h1>Genesis·Exchange22</h1>
                    <p><?= lang('가장 신뢰받는 차세대 암호화폐 거래소22', 'Most Trusted Next Generation Cryptocurrency Exchange22', '最も信頼される次世代仮想通貨取引所22') ?></p>
                </div>
            </div>
        </li>
    </ul>
</section>
<section class="notice-section">
    <div class="wrap-middle">
        <div class="notice-box">
            <div class="notice-title">
                <h1><?= lang('신규상장', 'New listing', '新規上場') ?></h1>
                <a href="/notice/customer/support?type=listing"><?= lang('전체보기', 'View All', '全体表示') ?></a>
            </div>
            <div class="notice-content public">
                <img src="/public/img/main/org/notice-rbto.png"/>
                <div class="public-desc">
                    <div class="public-title">
                        <h2>RBTO</h2>
                        <span class="pc-view">2020.05.05</span>
                    </div>
                    <?= lang('<p class="pc-view">RBTO는 전세계 많은 사람들이 편리하고 안전하게 소셜&레저 서비스를 즐길 수 있도록 플랫폼을 제공하고
                        이로 인해 발생하는 부가가치를 생태계 내의 구성원과 공유하는 공유경제를 지향합니다.</p>',
                        '<p class="pc-view">RBTO is a convenient and safe place for many people all over the world. Provide a platform to enjoy social and leisure services. The added value that results from this is the value added to the ecosystem. Towards a shared economy</p>',
                        '<p class="jpn pc-view">RBTOは全世界の多くの人々が便利で安全にソーシャル&レジャーサービスを楽しめるようにプラットフォームを提供し、
                        それによって発生する付加価値を生態系内の構成員と共有する共有経済を目指します。</p>') ?>
                    <div class="public-buttons">
                        <div class="btn-gray"><?= lang('현재가격', 'Price', '価格') ?><span>USDT 150</span></div>
                        <button class="btn btn-yellow"><?= lang('거래하기', 'To make a deal', '取引すること') ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="notice-box">
            <div class="notice-title">
                <h1>NEWS</h1>
                <a href="/notice/customer/support"><?= lang('전체보기', 'View All', '全体表示') ?></a>
            </div>
            <div class="notice-content">
                <table class="news-table">
                    <colgroup>
                        <col width="75%">
                        <col width="25%">
                    </colgroup>
                    <?php
                    // NEWS
                    require_once SRC_ROOT . '/config/DB.php';
                    require_once MD_ROOT . '/board/News.php';
                    $news = new News();
                    $result = objectToArray($news->getNewsList($country, 'all', 5));
                    for ($i = 0; $i < count($result); $i++) {
                        $type = $result[$i]['nw_type'];
                        switch ($type) {
                            case 'notice':
                                $type = lang('공지', 'notice', 'お知らせ');
                                break;
                            case 'event':
                                $type = lang('이벤트', 'event', 'イベント');
                                break;
                            case 'listing':
                                $type = lang('상장', 'Listing', '上場');
                                break;
                        }
                        $time = str_replace('-', '.', explode(' ', $result[$i]['nw_datetime'])[0]);
                        $title = $result[$i]['nw_title_' . $country];
                        ?>
                        <tr>
                            <td>
                                <span class="news-type"><?= $type ?></span>
                                <a href="/notice/customer/support?no=<?=$result[$i]['nw_no']?>"><?= $title ?></a>
                            </td>
                            <td class="news-date"><?= $time ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</section>
<section class="best-section">
    <div class="wrap-middle">
        <h1>TODAY BEST 10</h1>
        <div class="table-box">
            <table class="best-table">
                <thead>
                <tr>
                    <th class="text-left">
                        <button><?= lang('코인명', 'Coin', 'コイン名') ?></button>
                    </th>
                    <th>
                        <button><?= lang('현재가', 'Price', '価格') ?></button>
                    </th>
                    <th>
                        <button><?= lang('등락률', 'Change', '騰落率') ?></button>
                    </th>
                    <th class="pc-view">
                        <button><?= lang('거래대금', 'Transaction amount', '取引数量') ?></button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $db = new db();
                $query = "SELECT * FROM _coins WHERE ci_use = 0";
                $coins = $db->fetchAll($query);
                foreach ($coins as $row) {
                    $row = objectToArray($row);
                    ?>
                    <tr>
                        <td class="text-left td-coin">
                            <div class="coin-symbol pc-view <?= $row['ci_symbol'] ?>-symbol"></div>
                            <div>
                                <span><?= $row["ci_{$country}_name"] ?></span>
                                <p><?= $row['ci_symbol'] ?></p>
                            </div>
                        </td>
                        <td><?= number_format($row['ci_price']) ?> USDT</td>
                        <td class="color-<?= $row['ci_percent'] >= 0 ? 'red' : 'blue' ?>"><?= $row['ci_percent'] ?>%
                        </td>
                        <td class="pc-view">22,215.71 <?= lang('백만', 'K', 'K') ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <a href="/exchange/main" id="best-more"
           class="btn btn-yellow pc-view"><?= lang('코인 더 보러가기', 'More', 'コインダーを見に行く') ?>
            →</a>
    </div>
</section>
<section class="about-section">
    <div class="wrap-middle">
        <h1><?= lang('가장 안전한 글로벌 암호화폐 거래소 GENESIS·EX', 'GENESIS·EX, the safest global crypto exchange', '最も安全なグローバル仮想通貨取引所 GENESIS·EX') ?></h1>
        <div class="about-items">
            <div class="about-item">
                <div class="img-div pc-view"><img src="/public/img/main/org/about-item01.png"></div>
                <div class="img-div mb-view"><img src="/public/img/main/org/m-about-item01.png"></div>
                <div class="about-desc">
                    <h2><?= lang('빠르고 안정적인 거래 시스템', 'Fast and stable trading system', '迅速かつ安定的な取引システム') ?></h2>
                    <?= lang('<p class="pc-view">인터넷 뱅킹 시스템, 보안 솔루션, 게임 서비스 플랫폼 등을 개발했던 우수한 개발진들이 모여 Genesis EX만의 특화된 거래 엔진을 개발했습니다.</p>', '<p class="pc-view">Leading developers who developed Internet banking systems, security solutions, and game service platforms gathered to develop a specialized trading engine for the Genesis EX.</p>', '<p  class="jpn pc-view">インターネットバンキングシステム、セキュリティソリューション、ゲームサービスプラットフォームなどを開発した優秀な開発スタッフが集まって、Genesis EXならではの特化した取引エンジンを開発しました。</p>') ?>
                    <p class="mb-view">GENESIS EX만의 특화된 거래 엔진</p>
                </div>
            </div>
            <div class="about-item">
                <div class="img-div"><img src="/public/img/main/org/about-item02.png"></div>
                <div class="about-desc">
                    <h2><?= lang('실시간 모니터링 시스템', 'Real-time monitoring system', 'リアルタイムモニタリングシステム') ?></h2>
                    <?= lang('<p class="pc-view">고객님의 이상 접속 시도 및 이상 거래 패턴에 대한 실시간 모니터링을 통해 감지하여 피해를 방지합니다.</p>', '<p class="pc-view">We detect your abnormal connection attempts and real-time monitoring of abnormal transaction patterns to prevent damage.</p>', '<p class="jpn pc-view">お客様の異常接続の試みと異常取引パターンに対するリアルタイムモニタリングを通じて検知し、被害を防止します。</p>') ?>
                    <p class="mb-view">실시간 모니터링을 통해 신속한 피해 방지</p>
                </div>
            </div>
            <div class="about-item">
                <div class="img-div"><img src="/public/img/main/org/about-item03.png"></div>
                <div class="about-desc">
                    <h2><?= lang('최고의 보안 수준 적용', 'Get the highest level of security', '最高のセキュリティレベル適用') ?></h2>
                    <?= lang('<p class="pc-view">개인 정보 데이터 암호화를 기본으로 네트워크 통제의 물리적 접근과 지갑 정보에 대한 3중 보안 장치를 구축했습니다.</p>', '<p class="pc-view">Based on privacy data encryption, we have built a triple security device for network control physical access and wallet information.</p>', '<p class="jpn pc-view">個人情報データの暗号化を基に、ネットワーク統制の物理的アクセスとウォレット管理情報に対する3重セキュリティ装置を構築しました。</p>') ?>
                    <p class="mb-view">지갑 정보에 대한 3중 보안장치 구축</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="partner-section">
    <div class="wrap-middle">
        <h1>PARTNERSHIP</h1>
        <div class="partner-items">
            <img src="/public/img/main/org/partnership-item01.png">
            <img src="/public/img/main/org/partnership-item02.png">
            <img src="/public/img/main/org/partnership-item03.png">
            <img src="/public/img/main/org/partnership-item04.png">
            <img src="/public/img/main/org/partnership-item05.png">
        </div>
    </div>
</section>
