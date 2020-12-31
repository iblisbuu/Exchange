<?php include_once './_common.php';
require_once '../../../vendor/autoload.php';

use PsychoB\Ethereum\AddressValidator AS ethAddressCheck;
use LinusU\Bitcoin\AddressValidator AS bitAddressCheck;

$json = [];
$json['result'] = 'fail';

if(strtolower($_POST['coin']) == 'btc' && bitAddressCheck::isValid($_POST['address'])) // 비트코인 주소검증
    $json['result'] = 'success';
else if((strtolower($_POST['coin']) == 'eth' || strtolower($_POST['coin']) == 'fvc') && ethAddressCheck::isValid($_POST['address']) === ethAddressCheck::ADDRESS_VALID) // 이더리움 주소검증
    $json['result'] = 'success';

echo json_encode($json);
