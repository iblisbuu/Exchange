<?php include_once './_common.php';
require_once '../../../vendor/autoload.php';

use \PsychoB\Ethereum\AddressValidator AS ethAddressCheck;
use \LinusU\Bitcoin\AddressValidator AS bitAddressCheck;

$json = [];
$json['result'] = 'fail';

$nowTime = time();
$monthStart = date('Y-m-d', $nowTime). ' 00:00:00';
$monthStart = strtotime($monthStart);

if(!empty($member)) {
    $amount = $_POST['amount'];
    $coin = $_POST['coin'];
    $address = $_POST['address'];
    $symbol = strtoupper($coin);
    $price = 0;

    $db = new db();
    $query = "SELECT ci_price, ci_with_fee, ci_minimum_with, ci_with_level{$member['mb_level']} AS ci_user_maximun FROM _coins WHERE ci_symbol = '{$symbol}'";

    $row = $db->fetchAll($query)[0];
    $row = objectToArray($row);
    $price = $row['ci_price'];

    $amount += $row['ci_with_fee'];

    if (strtolower($coin) == 'btc' && bitAddressCheck::isValid($address) && $member['mb_btc'] >= $amount){
        // 비트코인 주소검증 성공
        $json['result'] = 'success';
    } else if (((strtolower($coin) == 'eth' && $member['mb_eth'] >= $amount) || (strtolower($coin) == 'fvc' && $member['mb_fvc'] >= $amount)) && ethAddressCheck::isValid($address) === ethAddressCheck::ADDRESS_VALID) {
        // 이더리움 주소검증 성공
        $json['result'] = 'success';
    }

    if($_POST['amount'] < $row['ci_minimum_with'] || $_POST['amount'] <= 0 || $member['mb_'.strtolower($symbol)] < $amount) {
        $json['result'] = 'amount';
        $json['amount'] = $row['ci_minimum_with'];
    }

    // 출금 한도 체크
    $query = "SELECT SUM(wd_amount) AS total FROM _withdraws WHERE mb_id = '{$member['mb_id']}' AND wd_type = '{$symbol}' AND wd_datetime <= '{$nowTime}' AND wd_datetime >= '{$monthStart}'";
    $withSum = $db->fetchAll($query)[0];
    $withSum = objectToArray($withSum);
    $withSum = $withSum['total']??0;

    if($withSum + $amount > $row['ci_user_maximun']){
        $json['result'] = 'user_maximum';
        $json['amount'] = round_down_format(sprintf('%.8f', $row['ci_user_maximun'] - ($withSum + $amount)), 8);
        $json['symbol'] = $symbol;
    }

    if($symbol == 'FVC' || $symbol == 'USDT')
        $json['result'] = 'fail';

    if($json['result'] == 'success'){
        $pointType = 'mb_'.strtolower($coin);
        $beforePoint = $member[$pointType];
        $afterPoint = $member[$pointType] - $amount;
        $fromAddress = $member[$pointType.'_addr'];

        $query = "INSERT INTO _withdraws SET mb_id = '{$member['mb_id']}', wd_type = '{$symbol}', wd_addr_fee = '{$row['ci_with_fee']}', wd_amount = '{$amount}', wd_before = '{$beforePoint}', wd_after = '{$afterPoint}', wd_from = '{$fromAddress}', wd_to = '{$address}', wd_price = '{$price}', wd_datetime = '".time()."';";
        $result = $db->execute($query);

        if($result) {
            $query = "UPDATE _members SET {$pointType} = {$pointType} - {$amount} WHERE mb_id = '{$member['mb_id']}';";
            $db->execute($query);
        } else
            $json['result'] = 'fail';
    }
}

echo json_encode($json);