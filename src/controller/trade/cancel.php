<?php require_once './_common.php';
if($member) {
    $db = new db();
    $query = "SELECT tr_volume, tr_type, tr_price, tr_quantity, tr_currency, tr_symbol, tr_no, tr_amount, mb_id FROM _trade WHERE mb_id = '{$member['mb_id']}' AND tr_no = '{$_POST['tr_no']}' AND tr_success_time IS NULL AND tr_symbol = '{$_POST['coin']}'";
    $row = $db->fetchAll($query);

    $nowTime = time();

    if(!$row)
        echo json_encode(array('result' => 'fail'));
    else {
        $row = objectToArray($row[0]);

        $tradeType = $row['tr_type'];

        $symbol = $row['tr_symbol'];

        $socketData = [];
        $query = "UPDATE _trade SET tr_success_time = '".time()."' WHERE mb_id = '{$member['mb_id']}' AND tr_no = '{$_POST['tr_no']}' AND tr_symbol = '{$_POST['coin']}';";
        $db->execute($query);

        $currency = isset($row['tr_currency']) ? $row['tr_currency'] : 'USDT';
        $mbPoint = 'mb_'.strtolower($currency);

        $odFee = 0;
        $odFee = round_down_format($row['tr_type'] == 0 ? ($row['tr_price'] * $row['tr_volume'] * ($main_fee / 100)) : ($row['tr_volume'] * ($main_fee / 100)), 8);

        // 주문종료로 인한 코인 또는 기축코인 지급
        $settlePrice = settleTrade($_POST['tr_no']);
        $coinName = 'mb_'.strtolower($row['tr_symbol']);

        if($row['tr_type'] == 0){ // 판매
            $plusPoint = round_down($settlePrice - $odFee, 8);
            $plusPoint = $plusPoint < 0 ? 0 : $plusPoint;

            $query = "UPDATE _members SET {$coinName} = {$coinName} + {$row['tr_quantity']}, {$mbPoint} = {$mbPoint} + {$plusPoint} WHERE mb_id = '{$member['mb_id']}';";

            pointLogInsert($member['mb_id'], $row['tr_symbol'], $row['tr_quantity'], 3, '_trade', $row['tr_no'], $nowTime);
            if($plusPoint > 0)
                pointLogInsert($member['mb_id'], $currency, $plusPoint, 3, '_trade', $row['tr_no'], $nowTime);
        } else { // 구매
            $plusPoint = round_down($row['tr_amount'] * $row['tr_price'] - $settlePrice, 8);
            $plusPoint = $plusPoint < 0 ? 0 : $plusPoint;

            $intCoin = round_down($row['tr_volume'] - ($row['tr_volume'] * ($main_fee / 100)), 8);

            $query = "UPDATE _members SET {$mbPoint} = {$mbPoint} + {$plusPoint}, {$coinName} = {$coinName} + {$intCoin} WHERE mb_id = '{$member['mb_id']}';";

            pointLogInsert($member['mb_id'], $currency, $plusPoint, 3, '_trade', $row['tr_no'], $nowTime);
            if($row['tr_quantity'] > 0)
                pointLogInsert($member['mb_id'], $row['tr_symbol'], $intCoin, 3, '_trade', $row['tr_no'], $nowTime);
        }
        $db->execute($query);

        $lowCurrency = strtolower($row['tr_currency']);

        $query = "SELECT cd_{$lowCurrency} FROM _coinDaily WHERE cd_symbol = '{$row['tr_symbol']}' ORDER BY cd_datetime DESC LIMIT 0, 1";
        $dailyPrice = $db->fetchAll($query)[0];
        $dailyPrice = objectToArray($dailyPrice);
        $dailyPrice = $dailyPrice["cd_{$lowCurrency}"];

        $sc = count($socketData);
        $socketData[$sc]['name'] = 'tradingList';
        $socketData[$sc]['datas']['no'] = $row['tr_no'];
        $socketData[$sc]['datas']['type'] = $row['tr_type'];
        $socketData[$sc]['datas']['price'] = round_down_format_fix($row['tr_price'], 8);
        $socketData[$sc]['datas']['percent'] = @round_down(($row['tr_price'] / $dailyPrice * 100) - 100, 2);
        $socketData[$sc]['datas']['amount'] = round_down_format($row['tr_quantity'] * -1, 8);
        if((double) $socketData[$sc]['datas']['amount'] > 0)
            $socketData[$sc]['datas']['amount'] = '-'.$socketData[$sc]['datas']['amount'];

        $socketData[$sc]['datas']['coin'] = $row['tr_symbol'];
        $socketData[$sc]['datas']['currency'] = $currency;

        $sc = count($socketData);
        $socketData[$sc]['name'] = 'tradingOrders';
        $socketData[$sc]['datas']['datetime'] = date('H:i:s', $nowTime);
        $socketData[$sc]['datas']['price'] = round_down_format_fix($row['tr_price'], 8);
        $socketData[$sc]['datas']['amount'] = round_down_format($row['tr_volume'], 8);
        $socketData[$sc]['datas']['total'] = round_down_format($row['tr_volume'] * $row['tr_price'], 8);
        $socketData[$sc]['datas']['type'] = $row['tr_type'];
        $socketData[$sc]['datas']['coin'] = $row['tr_symbol'];
        $socketData[$sc]['datas']['currency'] = $currency;
        $socketData[$sc]['datas']['email'] = $row['mb_id'];

        $query = "SELECT ci_price, 
        (SELECT sum(tr_quantity) FROM _trade WHERE tr_success_time IS NULL AND tr_symbol = '{$symbol}' AND tr_currency = '{$currency}') as ci_total_coin,
        (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$symbol}' AND tr_currency = '{$currency}' ORDER BY tr_price DESC LIMIT 0,1) as ci_high_price,    
        (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$symbol}' AND tr_currency = '{$currency}' ORDER BY tr_price ASC LIMIT 0,1) as ci_low_price,
        (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$symbol}' AND tr_currency = '{$currency}') as ci_total_sell,
        (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$symbol}' AND tr_currency = '{$currency}') as ci_total_buy,
        
        ci_symbol FROM _coins
        
        WHERE ci_symbol = '{$symbol}'";
        $row = $db->fetchAll($query);
        $row = objectToArray($row[0]);

        $sc = count($socketData);
        $socketData[$sc]['name'] = 'coinCounting';
        $socketData[$sc]['datas']['ci_total_coin'] = round_down_format($row['ci_total_coin'], 8);
        $socketData[$sc]['datas']['ci_high_price'] = round_down_format($row['ci_high_price'], 8);
        $socketData[$sc]['datas']['ci_low_price'] = round_down_format($row['ci_low_price'], 8);
        $socketData[$sc]['datas']['ci_total_sell'] = round_down_format($row['ci_total_sell'], 8);
        $socketData[$sc]['datas']['ci_total_buy'] = round_down_format($row['ci_total_buy'], 8);
        $socketData[$sc]['datas']['ci_price'] = round_down_format(-1, 0);
        $socketData[$sc]['datas']['coin'] = $symbol;
        $socketData[$sc]['datas']['currency'] = $currency;

        echo json_encode(array('result' => 'success', 'tradeType' => $tradeType, 'currency' => $currency, 'sockets' => $socketData));
    }
} else
    echo json_encode(array('result' => 'fail'));
?>