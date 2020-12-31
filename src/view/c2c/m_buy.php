<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/c2c/m_buy.css">');

$type = (isset($_GET['type'])) ? $_GET['type'] : 'order';
?>
<div class="account-section">
    개인거래
</div>
<div class="c2c-content">
    구매
</div>
<div class="c2c-details">
    <div class="c2c-no">
        NO.132134
    </div>
    <div class="c2c-type">
        <span>비트코인</span>
        <p>BTC</p>
    </div>
    <div class="c2c-detail-box">
        <div class="c2c-price-box"></div>
        <div class="c2c-bar-box"></div>
        <div class="quantity-box"></div>
    </div>
</div>
<div class="c2c-order-box">
    <div class="order-possible"></div>
    <div class="order-num">
        <p>수량</p>
        <input type="text">
    </div>
    <div class="order-btn-box">
        <button>10%</button>
        <button>25%</button>
        <button>50%</button>
        <button>100%</button>
    </div>
</div>
<button class="btn-order"></button>
<div class="c2c-desc-box">
    <span>유의사항</span>
    <p>GENESIS.EX는 개인거래의 중개자이며, 당사자가 아닙니다.</p>
    <p>회원간 개인거래에 대하여 GENESIS.EX는 책임을 지지 않습니다.</p>
</div>
<div class="c2c-order-popup none">
    <span>X</span>
    <div>구매내역 확인</div>
    <div>
        <ul>
            <li>거래구분</li>
            <li>개인거래 구매</li>
            <li>거래수량</li>
            <li>1.000BTC</li>
            <li>거래가격</li>
            <li>15,000 RBTO</li>
            <li>거래총액</li>
            <li>15,000 RBTO</li>
        </ul>
        <p>위의 내용으로 구매를 진행할까요?</p>
    </div>
    <div>
        <button>아니오</button>
        <button>예</button>
    </div>
</div>