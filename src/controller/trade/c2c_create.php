<?php require_once './_common.php';

date_default_timezone_set('Asia/Seoul');

$_POST['price'] = (double) str_replace(',', '', $_POST['price']);
$_POST['amount'] = (double) str_replace(',', '', $_POST['amount']);

$json = [];
$json['result'] = 'fail';

$start = date('Y-m-d'); // 등록일자
$finish = $_POST['finalData']; // 종료일자

$dayting = ( strtotime($finish) - strtotime($start) ) / 86400; // 종료일 까지 남은 일수 구하기

$ps_datetime = time();

$ps_endtime = $ps_datetime + 86400 * $dayting;
$ps_endtime = date('Y-m-d', $ps_endtime).' 23:59:59';
$ps_endtime = strtotime($ps_endtime);

$symbol = strtoupper($_POST['coin']);
$amount = $_POST['amount'];
$ps_type = $_POST['type'] == 'buy' ? 1 : 0;
$ps_secret = 0;
$ps_password = null;
if(!empty($_POST['password'])){
    $ps_secret++;
    $ps_password = create_hash($_POST['password']);
}

$currency = 'USDT';
$mbPoint = 'mb_'.strtolower($currency);

if(!empty($member)){
    if (($member['mb_'.strtolower($symbol)] >= $amount && $ps_type == 0) || ($member[$mbPoint] >= $_POST['price'] * $amount && $ps_type == 1)) {
        $db = new db();
        $query = "INSERT INTO _personals SET ps_type = '{$ps_type}', ps_symbol = '{$symbol}', mb_id = '{$member['mb_id']}', ps_price = '{$_POST['price']}', ps_amount = '{$amount}', ps_quantity = '{$amount}', ps_secret = '{$ps_secret}', ps_password = '{$ps_password}', ps_datetime = '{$ps_datetime}', ps_endtime = '{$ps_endtime}', ps_currency='{$currency}';";
        $result = $db->execute($query);
        $ps_no = $db->lastInsertId();

        if($result){
            $json['result'] = 'success';

            if($ps_type == 0) {
                $query = 'UPDATE _members SET mb_' . strtolower($symbol) . ' = mb_' . strtolower($symbol) . " - {$amount} WHERE mb_id = '{$member['mb_id']}';";
                pointLogInsert($member['mb_id'], $symbol, $amount * -1, 4, '_personals', $ps_no, $nowTime);
            } else {
                $query = "UPDATE _member SET {$mbPoint} = {$mbPoint} - ({$_POST['price']} * {$amount}) WHERE mb_id = '{$member['mb_id']}';";
                pointLogInsert($member['mb_id'], $currency, ($_POST['price'] * $amount) * -1, 4, '_personals', $ps_no, $nowTime);
            }

            $db->execute($query);
        }
    } else
        $json['result'] = 'money';
}

echo json_encode($json);