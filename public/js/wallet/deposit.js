$(function () {
    var clipboard = new Clipboard('.btn-clipboard');
    clipboard.on('success', function (e) {
        alert(lang('복사되었습니다.','It\'s been copied.','コピ―トされました','已复印。'));
    });
    clipboard.on('error', function (e) {
        console.log(e);
    });
})

function createWallet(coin, coinName) {
    var loader = createLoader(lang('지갑주소를 생성중입니다.<br>잠시만 기다려 주세요.','Generating wallet address. <br>Please wait a' +
        ' moment.','ウォレット管理アドレスを作成中です。<br> 少々お待ちください。','正在生成钱包地址。<br> 请稍等。'));
    $('body').append(loader);
    $.ajax({
        url: '/src/controller/wallet/new_wallet.php',
        type: 'POST',
        data: {'coin': coin},
        dataType: 'json',
        success: function (data) {
            $('.loader').remove();

            if (data.result == 'success') {
                $("tr.deposit." + coin + " .wp-left, div.deposit." + coin + " .wp-left, div.deposit." + coin + " .wp-left, div.deposit." + coin + " .wp-left").html(coin == 'usdt' ? createWalletHtmlTwo(data.address, coinName) : createWalletHtml(data.address, coinName)).addClass('padding');
                if(coin == 'usdt') {
                    $("tr.deposit." + coin + " .wp-left .wallet-loading:eq(0) .wallet-address-qr, div.deposit." + coin + " .wp-left .wallet-loading:eq(0) .wallet-address-qr").on('load', function () {
                        $("tr.deposit." + coin + " .wp-left .wallet-loading:eq(0) #walletLoading, div.deposit." + coin + " .wp-left .wallet-loading:eq(0) #walletLoading").remove();
                    })
                    $("tr.deposit." + coin + " .wp-left .wallet-loading:eq(1) .wallet-address-qr, div.deposit." + coin + " .wp-left .wallet-loading:eq(1) .wallet-address-qr").on('load', function () {
                        $("tr.deposit." + coin + " .wp-left .wallet-loading:eq(1) #walletLoading, div.deposit." + coin + " .wp-left .wallet-loading:eq(1) #walletLoading").remove();
                    })
                } else {
                    $("tr.deposit." + coin + " .wp-left .wallet-address-qr, div.deposit." + coin + " .wp-left .wallet-address-qr").on('load', function () {
                        $("#walletLoading").remove();
                    })
                }

                if(coin == 'usdt_btc') {
                    $('input[data-input="' + coin + '"]').val(data.address);
                    $('button[data-btn="' + coin + '"]').attr('data-clipboard-text', data.address).removeAttr('onclick').text(lang('복사하기', 'Copy', 'コピーする', '复印'));
                    $('div[data-img="' + coin + '"]').html('<img id="walletLoading" src="/public/img/common/loading.gif"/><img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=500x500&amp;chl=' + data.address + '" width="147px" class="wallet-address-qr">');
                    $('div[data-img="' + coin + '"] .wallet-address-qr').on('load', function () {
                        $("#walletLoading").remove();
                    })
                }

            } else {
                alert(lang('지갑주소 생성에 실패하였습니다.\n잠시 후 다시 시도해주세요.','Failed to generate wallet address.\n' +
                    'Please try again in a moment.','ウォレット管理アドレス作成に失敗しました。\n' +
                    'しばらくして、もう一度やり直してください。','钱包地址生成失败。\n' +
                    '请稍后再试。'));
            }
        }, error: function (a, b, c) {
            console.log(a, b, c)
            alert(lang('지갑주소 생성에 실패하였습니다.\n잠시 후 다시 시도해주세요.','Failed to generate wallet address.\n' +
                'Please try again in a moment.','ウォレット管理アドレス作成に失敗しました。\n' +
                'しばらくして、もう一度やり直してください。','钱包地址生成失败。\n' +
                '请稍后再试。'));
        }
    })
}

function createWalletHtml(walletAddress, coinName) {
    let html = '';
    html += '<div class="wallet-address-div">';
    html += '<p class="wallet-address-title">' + lang('QR 코드', 'QR code', 'QRコード', 'QR代码') + '</p>';
    html += '<div class="wallet-loading">';
    html += '<img id="walletLoading" src="/public/img/common/loading.gif"/>';
    html += '<img src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=' + walletAddress + '"' +
        'width="147px" class="wallet-address-qr"/>';
    html += '</div>';
    html += '</div>';
    html += '<div class="wallet-address-div">';
    html += '<p class="wallet-address-title text-left">' + lang('내 ' + coinName + ' 입금 주소', 'My ' + coinName + ' Deposit Address', '私の' + coinName + '入金住所', '我的' + coinName + '汇款地址') + '</p>';
    html += '<div class="wallet-address-input-box">';
    html += '<input type="text" value="' + walletAddress + '" readonly>';
    html += '<button type="button" class="btn-clipboard btn btn-yellow" data-clipboard-text="' + walletAddress + '">' + lang('복사하기', 'Copy', 'コピーする', '已复印。') + '</button>';
    html += '</div>';
    html += '</div>';
    return html;
}

function createWalletHtmlTwo(walletAddress, coinName) {
    let html = '';
    html += '<div class="wallet-address-div">';
        html += '<p class="wallet-address-title">'+lang('QR 코드','QR code','QRコード','QR代码')+'</p>';

        html += '<div class="wallet-loading two">';
            html += '<img id="walletLoading" src="/public/img/common/loading.gif"/>';
            html += '<img src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=' + walletAddress[0] + '"' +
                'width="147px" class="wallet-address-qr"/>';
        html += '</div>';

        html += '<div class="wallet-loading two">';
            html += '<img id="walletLoading" src="/public/img/common/loading.gif"/>';
            html += '<img src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=' + walletAddress[1] + '"' +
            'width="147px" class="wallet-address-qr"/>';
        html += '</div>';

        html += '<div class="wallet-qr-name"><span>ERC20</span><span>BTC</span></div>';

    html += '</div>';
    html += '<div class="wallet-address-div">';
        html += '<p class="wallet-address-title text-left">'+lang('내 ' + coinName + ' 입금 주소','My ' + coinName + ' Deposit Address','私の' + coinName + '入金住所','我的'+ coinName +'汇款地址')+' (ERC20)</p>';
        html += '<div class="wallet-address-input-box">';
            html += '<input type="text" value="' + walletAddress[0] + '" readonly>';
            html += '<button type="button" class="btn-clipboard btn btn-yellow" data-clipboard-text="'+walletAddress[0]+'">'+lang('복사하기', 'Copy', 'コピーする','已复印。')+'</button>';
        html += '</div>';

        html += '<p class="wallet-address-title text-left" style="margin-top: 20px">'+lang('내 ' + coinName + ' 입금 주소','My ' + coinName + ' Deposit Address','私の' + coinName + '入金住所','我的'+ coinName +'汇款地址')+' (BTC)</p>';
        html += '<div class="wallet-address-input-box">';
            html += '<input type="text" value="' + walletAddress[1] + '" readonly>';
            html += '<button type="button" class="btn-clipboard btn btn-yellow" data-clipboard-text="'+walletAddress[1]+'">'+lang('복사하기', 'Copy', 'コピーする','已复印。')+'</button>';
        html += '</div>';
    html += '</div>';
    return html;
}