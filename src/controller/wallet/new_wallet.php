<?php include_once './_common.php';
$json = [];
$json['result'] = 'fail';
$json['coin'] = $_POST['coin'];


if($member["mb_{$_POST['coin']}_addr"] == null && !empty($member)) {
    $newWallet = newWallet($_POST['coin'], $member['mb_no']);

    if (trim($newWallet['address']) != '' && $newWallet['code'] == 0) {
        $db = new db();
        $query = 'UPDATE _members SET mb_' . strtolower($_POST['coin']) . "_addr = '{$newWallet['address']}' WHERE mb_id = '{$member['mb_id']}'";
        $db->execute($query);

        $json['result'] = 'success';

        if($_POST['coin'] == 'usdt') {
            $json['address'] = [];
            $json['address'][0] = $newWallet['address']; // ERC20

            $newWallet = newWallet("{$_POST['coin']}_btc", $member['mb_no']);
            if (trim($newWallet['address']) != '' && $newWallet['code'] == 0) {
                $query = 'UPDATE _members SET mb_' . strtolower($_POST['coin']) . "_btc_addr = '{$newWallet['address']}' WHERE mb_id = '{$member['mb_id']}'";
                $db->execute($query);

                $json['address'][1] = $newWallet['address']; // BTC;
            } else
                $json['address'][1] = '';
        } else
            $json['address'] = $newWallet['address'];
    }
}

echo json_encode($json);