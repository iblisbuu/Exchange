<?php require_once './_common.php';

date_default_timezone_set('Asia/Seoul');

$json = [];
$json['result'] = 'fail';

$db = new db();
$query = "SELECT * FROM _personals WHERE ps_no = '{$_POST['orderNumber']}';";
$row = $db->fetchAll($query)[0];
$row = objectToArray($row);

$nowTime = time();
$amount = $_POST['amount'];
$currency = isset($_POST['currency']) ? $_POST['currency'] : 'USDT';
$mbPoint = 'mb_'.strtolower($currency);

if(!empty($member)) {
    if ($row['mb_id'] != $member['mb_id']) {
        if ($amount <= $row['ps_quantity']) {
            $result = false;

            $price = round_down($row['ps_price'] * $amount, 4); // 가격 = 등록가격 * 주문수량

            if ($member[$mbPoint] >= $price && $row['ps_type'] == 0) {
                // 코인 판매
                $query = "UPDATE _members SET {$mbPoint} = {$mbPoint} - {$price}, mb_" . strtolower($row['ps_symbol']) . " = mb_" . (strtolower($row['ps_symbol'])) . " + {$amount} WHERE mb_id = '{$member['mb_id']}';";
                $result = $db->execute($query);

                pointLogInsert($member['mb_id'], $currency, $price * -1, 5, '_personals', $_POST['orderNumber'], $nowTime);
                pointLogInsert($member['mb_id'], $row['ps_symbol'], $amount, 5, '_personals', $_POST['orderNumber'], $nowTime);
            } else if ($member['mb_' . strtolower($row['ps_symbol'])] >= $amount && $row['ps_type'] == 1) {
                // 코인 구매
                $query = "UPDATE _members SET {$mbPoint} = {$mbPoint} + {$price}, mb_" . strtolower($row['ps_symbol']) . " = mb_" . (strtolower($row['ps_symbol'])) . " - {$amount} WHERE mb_id = '{$member['mb_id']}';";
                $result = $db->execute($query);

                pointLogInsert($member['mb_id'], $currency, $price, 5, '_personals', $_POST['orderNumber'], $nowTime);
                pointLogInsert($member['mb_id'], $row['ps_symbol'], $amount * -1, 5, '_personals', $_POST['orderNumber'], $nowTime);
            } else
                $json['result'] = 'money';

            if ($result) {
                $query = "UPDATE _personals SET ps_quantity = ps_quantity - {$amount}, ps_volume = ps_volume + {$amount}, ps_oktime = (IF({$row['ps_quantity']} - {$amount} > 0, NULL, '{$nowTime}')) WHERE ps_no = '{$_POST['orderNumber']}';";
                $db->execute($query);

                $query = "INSERT INTO _c2cOrders SET mb_id = '{$member['mb_id']}', co_type = '" . (1 - $row['ps_type']) . "', co_symbol = '{$row['ps_symbol']}', co_currency = '{$currency}', co_id = '{$_POST['orderNumber']}', co_price = '{$row['ps_price']}', co_amount = '{$amount}', co_datetime = '{$nowTime}';";
                $db->execute($query);

                if ($row['ps_quantity'] - $amount <= 0) {
                    $query = "UPDATE _members SET mb_" . strtolower($row['ps_symbol']) . " = mb_" . strtolower($row['ps_symbol']) . " + {$row['ps_amount']} WHERE mb_id = '{$row['mb_id']}';";
                    $db->execute($query);

                    pointLogInsert($row['mb_id'], $row['ps_symbol'], $row['ps_amount'], 5, '_personals', $_POST['orderNumber'], $nowTime);
                }

                $json['result'] = 'success';
            }
        } else
            $json['result'] = 'count';
    } else
        $json['result'] = 'self';
} else
    $json['result'] = 'login';

echo json_encode($json);