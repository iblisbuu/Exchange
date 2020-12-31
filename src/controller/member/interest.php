<?php require_once './_common.php';
$json = [];
$json['type'] = '';

// 대문자 변환
$_POST['coin'] = strtoupper($_POST['coin']);

$db = new db();
$query = "SELECT * FROM _interest WHERE ".($member ? "mb_id = '{$member['mb_id']}'" : "ir_ip = '{$_SERVER['REMOTE_ADDR']}'");
$result = $db->fetchAll($query);
$count = count($result);

$interest = get_cookie('coin_interest');
if(strpos($interest, $_POST['coin'])!==false) {
    $count--;
    $interest = str_replace(array($_POST['coin'], $_POST['coin'] . '+'), '', $interest);

    $query = "DELETE FROM _interest WHERE ir_coin = '{$_POST['coin']}' AND ".($member ? "mb_id = '{$member['mb_id']}'" : "ir_ip = '{$_SERVER['REMOTE_ADDR']}'");
    $json['type'] = 'delete';
} else {
    $count++;
    $interest = trim($interest) == '' ? $_POST['coin'] : $interest . '+' . $_POST['coin'];

    $query = "INSERT INTO _interest SET ir_ip = '{$_SERVER['REMOTE_ADDR']}', ir_coin = '{$_POST['coin']}', ir_order = '{$count}', ir_datetime = '".time()."' ".($member ? ", mb_id = '{$member['mb_id']}'" : '');
    $json['type'] = 'insert';
}



$db = new db();
$db->execute($query);

// 쿠키 한달간 저장
if($count == 0)
    set_cookie('coin_interest', '', 86400 * 31);
else
    set_cookie('coin_interest', $interest, 86400 * 31);

echo json_encode($json);
?>
