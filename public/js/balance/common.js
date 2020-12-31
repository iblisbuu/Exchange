$(function () {
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function(el){
        return el != ''
    });
    let menu = (pathArray[2] != null) ? pathArray[2] : 'asset';
    // 탭
    $('.account-tap > a').removeClass('active');
    $('.' + menu + '-tab').addClass('active');

    $('.balance-select-boxes ul li').click(function () {
        $(this).closest('ul').css('display', 'none')
        $(this).closest('ul').prev().prev().text($(this).text())
        $(this).closest('ul').prev().val($(this).attr('data-option'))
        $(this).closest('ul').prev().attr('data-select', $(this).attr('data-option'))

        var urlPlus = '';
        for (var i = 0; i < $('.balance-select-boxes > div').length; i++) {
            var thisVal = $('.balance-select-boxes > div').eq(i).children('div').children('input').val();
            if (thisVal != 'all') {
                let connect = (urlPlus == '') ? '?' : '&'
                urlPlus += connect + $('.balance-select-boxes > div').eq(i).children('div').children('input').attr('data-name') + '=' + thisVal;
            }
        }
        urlPlus = $(location).attr('pathname') + urlPlus;

        location.href = urlPlus;
    })

    // txid 팝업창
    $(".history-table tbody tr td.txid").click(function () {
        openOkPopup($(this).text(), undefined, lang('닫기', 'Close', '閉じる', '关闭'), true);
    })
});

function openCalcDesc() {
    if ($('.popup-box.balance.calc').length > 0) {
        closePopup();
    } else {
        const language = $("body").attr('data-lang');
        const desc = {
            "ko": {
                'all': "<em>1.총자산현황</em><br/>" +
                    "- 총 자산: 보유 USDT + 평가금액<br/>" +
                    "- 보유 USDT : 내가 가지고 있는 거래 가능한 USDT 값<br/>" +
                    "- 매수금액: 자산별 매수금액 합계<br/>" +
                    "- 평가금액: 자산별 평가금액 합계<br/>" +
                    "- 평가손익: 평가금액 - 매수금액<br/>" +
                    "- 평가수익률(%): (평가손익 / 매수금액) x 100",

                'retention': "<em>2.자산별보유현황</em><br/>" +
                    "- 보유수량 : 내가 보유한 해당 코인 수량<br/>" +
                    "- 매수금액 : 해당코인 보유수량 X매수평균가<br/>" +
                    "- 매수평균가 : 해당 코인의 매수 평균 단가<br/>" +
                    "- 평가금액 : 현재가X보유수량<br/>" +
                    "- 평가손익: 평가금액 - 매수금액<br/>" +
                    "- 평가수익률(%): (평가손익 / 매수금액) x 100",

                'caution': "※ 코인이 입금되는 시점에 USDT로 환산하여 수익률 계산에 반영합니다.<br/>" +
                    "※ 자산현황은 자산별 보유수량을 USDT 시세로 변환한 정보를 제공하는<br/>" +
                    "수익평가용 참고자료입니다<br/>" +
                    "※ GENESIS-EX는 제공된 편의정보를 기반으로 투자한 결과에 대해<br/>" +
                    "책임지지 않습니다."
            },
            "ja": {
                'all': "<em>1.総資産の現状</em><br/>" +
                    "- 総資産：保有USDT+評価額<br/>" +
                    "- 保有USDT：自分が持っている取引可能なUSDTの値<br/>" +
                    "- 枚数金額：資産別枚数金額合計<br/>" +
                    "- 評価額：資産星別価額合計<br/>" +
                    "- 評価損益：評価額 - 枚数金額<br/>" +
                    "- 評価の利回り(%)：(評価損益/買収金額) x 100",

                'retention': "<em>2.資産別保有履歴</em><br/>" +
                    "- 保有数量：私は保有している当該コインの量<br/>" +
                    "- 枚数金額：このコイン保有数量x枚数の平均原価<br/>" +
                    "- 枚数平均原価：そのコインの枚数平均単価<br/>" +
                    "- 評価額：現在x保有数量<br/>" +
                    "- 評価損益：評価額 - 枚数金額<br/>" +
                    "- 評価の利回り(%)：(評価損益/買収金額) × 100",

                'caution': "※コインが入金された時点でUSDTに換算して収益率の計算が反映されます。<br/>" +
                    "※資産の現状は資産別保有数量をUSDT相場に変換した情報を提供する収入評価用参考資料​​です<br/>" +
                    "収益評価用の参考資料です。<br/>" +
                    "※GENESIS-EXは提供された利便性情報に基づいて投資した結果について責任を負いません。"
            },
            "en": {
                'all': "<em>1.Total asset status</em><br/>" +
                    "- Total Assets: Holding USDT + Evaluation Amount<br/>" +
                    "- Holding USDT: The transactionable USDT value I have<br/>" +
                    "- Purchase amount: Total purchase amount by asset<br/>" +
                    "- Evaluation amount: Total valuation amount by asset<br/>" +
                    "- Evaluation gain or loss: valuation amount - purchase amount<br/>" +
                    "- Evaluation rate of return (%): (Evaluation gain or loss / purchase amount) × 100",

                'retention': "<em>2.Holding status by assets</em><br/>" +
                    "- Retained quantity: the corresponding coin quantity I have<br/>" +
                    "- Purchase amount: held quantity of corresponding coin × purchase average price<br/>" +
                    "- Average purchase price: average unit price of the corresponding coin<br/>" +
                    "- Evaluation amount: Present value × quantity held<br/>" +
                    "- Evaluation gain or loss: valuation amount - purchase amount<br/>" +
                    "- Evaluation rate of return (%): (Evaluation gain or loss / purchase amount) × 100",

                'caution': "※ When coins are deposited, they are converted to BTC and reflected in the yield calculation.<br/>" +
                    "※ Asset status is a reference for profit assessment that provides information<br/>" +
                    "on the conversion of the holding quantity by asset to the BTC market price.<br/>" +
                    "※ GENESIS-EX provides the results of the investment based on the<br/>" +
                    "convenience information provided." +
                    "I'm not responsible."
            },
            "ch": {
                'all': "<em>1.总资产现状</em><br/>" +
                    "- 总资产:持有的USDT +评估金额<br/>" +
                    "- 拥有的USDT:我所拥有的可以交易的USDT值<br/>" +
                    "- 收购金额:资产类别收购金额合计<br/>" +
                    "- 评估金额:按资产类别评估金额合计<br/>" +
                    "- 评估损益:评估金额 - 收购金额<br/>" +
                    "- 评估收益率(%):(评估损益/收购金额) x 100",

                'retention': "<em>2.各资产持有现状</em><br/>" +
                    "- 保留数量:我保留的相应硬币数量<br/>" +
                    "- 买入金额:相应硬币持有数量X买入均价<br/>" +
                    "- 买入均价:该硬币的买入均价<br/>" +
                    "- 评估金额:现价X持有数量<br/>" +
                    "- 评估损益:评估金额 - 收购金额<br/>" +
                    "- 评估收益率(%):(评估损益/收购金额) x 100",

                'caution': "※ 硬币到账时换算成PTC,反映在收益率计算中。<br/>" +
                    "※ 资产现况提供按资产类别的保留数量转变为BTC行情的信息<br/>" +
                    "收益评估用参考资料。<br/>" +
                    "※ GENESIS-EX 对以提供的便利信息为基础进行投资的结果<br/>" +
                    "不负责。"
            },
        }

        let html = '<div class="popup-box balance calc">';
        html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
        html += '<div class="popup-head">' + lang('계산방식', 'Calculation method', '計算方式','计算方式') + '</div>';
        html += '<div class="popup-content">';
        html += '<p>' + desc[language].all + '</p>';
        html += '<p>' + desc[language].retention + '</p>';
        html += '<p>' + desc[language].caution + '</p>';
        html += '</div>';
        html += '</div>';
        openCustomPopup(html, '.calcDesc')
    }

}
