<?php require_once './_common.php';

date_default_timezone_set('Asia/Seoul');

$json = [];
$json['result'] = 'fail';
$json['value'] = [];

$db = new db();
$query = "SELECT 
            ps_no AS orderNumber, ps_amount AS amount, ps_price AS price, ps_quantity AS quantity, ps_secret AS secret, ps_symbol AS symbol, ps_oktime, ps_datetime, ps_endtime, ps_volume, ps_password, mb_id, ps_type AS type, ps_currency AS currency
          FROM _personals 
          WHERE ps_no = '{$_POST['orderNumber']}'";
$row = $db->fetchAll($query)[0];
$row = objectToArray($row);

if(!empty($member)) {
    if ($row) {
        if ($row['ps_oktime'] != null)
            $json['result'] = 'finish';
        else if ($row['secret'] == 1 && !validate_password($_POST['password'], $row['ps_password']))
            $json['result'] = 'fail_password';
        else {
            $nowTime = time();
            $days = floor(($row['ps_endtime'] - $nowTime) / 86400);
            $hours = ceil((($row['ps_endtime'] - $nowTime) - $days * 86400) / 3600);
            $thisPercent = round_down($row['ps_volume'] / $row['amount'] * 100, 2);
            $myCheck = $member['mb_id'] == $row['mb_id'] ? true : false;

            unset($row['ps_oktime']);
            unset($row['ps_datetime']);
            unset($row['ps_endtime']);
            unset($row['ps_volume']);
            unset($row['ps_password']);
            unset($row['mb_id']);

            $row['finishTime'] = $days > 0 ? lang($days . '일 ' . $hours . '시간',$days . 'day ' . $hours . 'hours',$days . '日 ' . $hours . '時間') :
                lang
            ($hours . ' 시간', $hours . 'hours', $hours . '時間');
            $row['percent'] = $thisPercent;
            $row['myCheck'] = $myCheck;

            $json['value'] = $row;

            $json['result'] = 'success';
        }
    }
} else
    $json['result'] = 'login';

echo json_encode($json);