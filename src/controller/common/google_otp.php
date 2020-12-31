<?php
include_once '../member/_common.php';

require_once '../../../vendor/autoload.php';

$json = [];

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
$json['secret'] = $secret; // 해당 정보를 회원테이블에 저장 해야함.
echo "Secret is: ".$secret." length: ".strlen($secret)."\n\n";

$qrCodeUrl = $ga->getQRCodeGoogleUrl('Genesis_Ex_Otp', $secret);
$json['qrCodeUrl'] = $qrCodeUrl;

echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";

$oneCode = $ga->getCode($secret);
echo "Checking Code '$oneCode' and Secret '$secret':\n";


# $secret = 회원 db에 저장된 값 가져오기
# $oneCode = 넘겨받은 OTP 6자리
$checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance

if ($checkResult) { // 인증성공
    echo 'OK';
} else { // 인증실패
    echo 'FAILED';
}