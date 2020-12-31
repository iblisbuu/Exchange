<?php require_once './_common.php';

date_default_timezone_set('Asia/Seoul');

$json = [];
$json['result'] = 'fail';

$db = new db();
$query = "SELECT * FROM _personals WHERE ps_no = '{$_POST['orderNumber']}';";
$row = $db->fetchAll($query)[0];
$row = objectToArray($row);

if($row['mb_id'] == $member['mb_id']){
    $query = "UPDATE _personals SET ps_oktime = '".time()."' WHERE ps_no = '{$_POST['orderNumber']}';";
    $result = $db->execute($query);

    $currency = isset($row['ps_currency']) ? $row['ps_currency'] : 'USDT';
    $mbPoint = 'mb_'.strtolower($currency);

    if($result){
        if($row['ps_type'] == 1 && $row['ps_quantity'] > 0) {
            $price = round_down($row['ps_price'] * $row['ps_quantity']); // 반환금 = 등록가격 * 남은수량

            $query = "UPDATE _members SET {$mbPoint} = {$mbPoint} + {$price} WHERE mb_id = '{$member['mb_id']}'";
            $db->execute($query);

            pointLogInsert($member['mb_id'], $currency, $price, 5, '_personals', $_POST['orderNumber'], $nowTime);
        } else if($row['ps_type'] == 0 && $row['ps_quantity'] > 0) {
            $query = "UPDATE _members SET mb_" . (strtolower($row['ps_symbol'])) . " = mb_" . (strtolower($row['ps_symbol'])) . " + {$row['ps_quantity']} WHERE mb_id = '{$member['mb_id']}'";
            $db->execute($query);

            pointLogInsert($member['mb_id'], $row['ps_symbol'], $row['ps_quantity'], 5, '_personals', $_POST['orderNumber'], $nowTime);
        }

        $json['result'] = 'success';
}
}

echo json_encode($json);