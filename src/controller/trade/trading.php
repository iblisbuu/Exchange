<?php require_once './_common.php';
$db = new db();

$_POST['price'] = (double) str_replace(',', '', $_POST['price']);

$socketData = $datas = [];
$ci_price = -1;
$real_no = 0;

$currency = isset($_POST['currency']) ? trim($_POST['currency']) : 'USDT';
$lowCurrency = strtolower($currency);
$mbPoint = 'mb_'.strtolower($currency);

$query = "SELECT ci_price FROM _coins WHERE ci_symbol = 'USDT'";
$coinInfo = $db->fetchAll($query)[0];
$_USDT_PRICE = $coinInfo->ci_price;

$query = "SELECT * FROM _coins WHERE ci_symbol = '{$_POST['coinType']}'";
$coinInfo = $db->fetchAll($query)[0];
$coinInfo = objectToArray($coinInfo);

if($_POST['price'] == 0)
    echo json_encode(array('result'=>'price', 'currency' => $currency));
else if($_POST['price'] * $_POST['amount'] < $coinInfo["ci_minimum_{$lowCurrency}"])
    echo json_encode(array('result' => 'minimum', 'price' => $coinInfo["ci_minimum_{$lowCurrency}"], 'currency' => $currency));
//else if($coinInfo["ci_minimum_{$lowCurrency}_unit"] < 0.00000001 && decimalDrainage($_POST['amount'], $coinInfo["ci_minimum_{$lowCurrency}_unit"]) != 0)
//    echo json_encode(array('result' => 'minimum_unit', 'unit' => $coinInfo["ci_minimum_{$lowCurrency}_unit"]));
else if(!empty($member)) {
    $nowTime = time();
    $tradeType = $_POST['tradeType'] == 'buying' ? 1 : 0;

    if($tradeType == 1 && $member[$mbPoint] >= $_POST['price'] * $_POST['amount'])
        $query = "UPDATE _members SET {$mbPoint} = {$mbPoint} - '".($_POST['price'] * $_POST['amount'])."' WHERE mb_id = '{$member['mb_id']}'";
    else if($tradeType == 0 && $member['mb_'.strtolower($_POST['coinType'])] >= $_POST['amount'])
        $query = 'UPDATE _members SET mb_'.(strtolower($_POST['coinType'])).' = mb_'.(strtolower($_POST['coinType']))." - '{$_POST['amount']}' WHERE mb_id = '{$member['mb_id']}'";
    else {
        echo json_encode(array('result'=>'money', 'currency' => $currency));
        exit();
    }

    $db->execute($query);

    // 트레이드 DB에 추가
    $tr_usdt = $currency == 'USDT' && $tradeType == 1 ? $_POST['price'] : $coinInfo['ci_usdt'];
    $query = "INSERT INTO _trade SET mb_id = '{$member['mb_id']}', tr_type = '{$tradeType}', tr_usdt = '{$tr_usdt}', tr_price = '{$_POST['price']}', tr_amount = '{$_POST['amount']}', tr_quantity = '{$_POST['amount']}', tr_datetime = '{$nowTime}', tr_symbol = '{$_POST['coinType']}', tr_currency = '{$currency}'";
    $db->execute($query);
    $tr_no = $db->lastInsertId();
    $real_no = $tr_no;
    pointLogInsert($member['mb_id'], ($tradeType == 1 ? $currency : $_POST['coinType']), ($tradeType == 1 ? $_POST['price'] * $_POST['amount'] : $_POST['amount']) * -1, 2, '_trade', $tr_no, $nowTime);

    $query = "UPDATE _coins SET ci_{$lowCurrency}_total = ci_{$lowCurrency}_total + {$_POST['amount']} WHERE ci_symbol = '{$_POST['coinType']}'";
    $db->execute($query);

    $dailyPrice = $coinInfo["ci_{$lowCurrency}"];

    $saveAmount = $_POST['amount'];

    $query = "SELECT * FROM _trade WHERE tr_success_time IS NULL AND tr_type = ".(1-$tradeType)." AND tr_datetime < '{$nowTime}' AND tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}' ORDER BY tr_price ".($tradeType == 0 ? 'DESC' : 'ASC').", tr_datetime ASC";
    $trading = $db->fetchAll($query);
    if(count($trading) > 0){
        foreach ($trading as $row) {
            $row = objectToArray($row);

            if(($tradeType == 0 && $row['tr_price'] < $_POST['price']) || ($tradeType == 1 && $row['tr_price'] > $_POST['price']) || $saveAmount <= 0)
                break;

            $nowAmount = 0;

            if($row['tr_quantity'] <= $saveAmount) // 등록된 수량이 넘어온 수량이 같거나 작을때
                $saveAmount -= $row['tr_quantity'];
            else { // 넘어온 수량이 등록된 수량보다 작을때
                $nowAmount = $row['tr_quantity'] - $saveAmount;
                $saveAmount = 0;
            }

            if($nowAmount == 0) {
                $query = "UPDATE _trade SET tr_volume = tr_volume + tr_quantity, tr_quantity = 0, tr_success_time = '{$nowTime}' WHERE tr_no = '{$row['tr_no']}' AND tr_symbol = '{$_POST['coinType']}'";
                $db->execute($query);

                $query = "UPDATE _coins SET ci_{$lowCurrency}_total = ci_{$lowCurrency}_total - {$row['tr_quantity']} WHERE ci_symbol = '{$_POST['coinType']}'";
                $db->execute($query);
            } else {
                $query = "UPDATE _trade SET tr_volume = tr_amount - '{$nowAmount}', tr_quantity = '{$nowAmount}' WHERE tr_no = '{$row['tr_no']}' AND tr_symbol = '{$_POST['coinType']}'";
                $db->execute($query);

                $query = "UPDATE _coins SET ci_{$lowCurrency}_total = ci_{$lowCurrency}_total - {$nowAmount} WHERE ci_symbol = '{$_POST['coinType']}'";
                $db->execute($query);
            }

            $sc = count($socketData);
            $socketData[$sc]['name'] = 'tradingList';
            $socketData[$sc]['datas']['no'] = $row['tr_no'];
            $socketData[$sc]['datas']['type'] = 1-$tradeType;
            $socketData[$sc]['datas']['price'] = round_down_format_fix(sprintf('%.8f', $row['tr_price']), 8);
            $socketData[$sc]['datas']['percent'] = @round_down(sprintf('%.2f', ($row['tr_price'] / $dailyPrice * 100) - 100), 2);
            $socketData[$sc]['datas']['amount'] = round_down_format(($nowAmount > 0 ? round_down((double) $row['tr_quantity'] - (double) $nowAmount, 8) : $row['tr_quantity']) * -1, 8);
            if((double) $socketData[$sc]['datas']['amount'] > 0)
                $socketData[$sc]['datas']['amount'] = '-'.$socketData[$sc]['datas']['amount'];

            $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
            $socketData[$sc]['datas']['currency'] = $currency;
            $socketData[$sc]['datas']['currencyPrice'] = round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $nowAmount, 8).' '.($currency=='USDT'?'BTC':'USDT');

            if($nowAmount == 0){ // 체결 완료
                $odFee = 0;
                $odFee = round_down_format($row['tr_type'] == 0 ? ($row['tr_price'] * $row['tr_amount'] * ($main_fee / 100)) : ($row['tr_amount'] * ($main_fee / 100)), 8); // 수수료 계산

                $query = "INSERT INTO _orders SET mb_id = '{$row['mb_id']}', tr_id = '{$row['tr_no']}', od_price = '{$row['tr_price']}', od_amount = '{$row['tr_amount']}', od_datetime = '{$nowTime}', od_type = '{$row['tr_type']}', od_coin = '{$_POST['coinType']}', od_currency = '{$currency}', td_no = '{$tr_no}', td_amount = '{$row['tr_quantity']}', td_price = '{$_POST['price']}', od_fee = '{$odFee}';";
                $db->execute($query);
                $od_no = $db->lastInsertId();

                if($od_no){
                    $ci_price = $row['tr_price'];
                    $query = "UPDATE _coins SET ci_{$lowCurrency} = '{$row['tr_price']}', ci_updatetime = '{$nowTime}' WHERE ci_symbol = '{$_POST['coinType']}';";
                    $db->execute($query);

                    $sc = count($socketData);
                    $socketData[$sc]['name'] = 'tradingOrders';
                    $socketData[$sc]['datas']['datetime'] = date('H:i:s', $nowTime);
                    $socketData[$sc]['datas']['price'] = round_down_format(sprintf('%.8f', $row['tr_price']), 8);
                    $socketData[$sc]['datas']['amount'] = round_down_format(sprintf('%.8f', $row['tr_amount']), 8);
                    $socketData[$sc]['datas']['total'] = round_down_format($row['tr_amount'] * $row['tr_price'], 8);
                    $socketData[$sc]['datas']['type'] = 1-$tradeType;
                    $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
                    $socketData[$sc]['datas']['currency'] = $currency;
                    $socketData[$sc]['datas']['email'] = $row['mb_id'];

                    // 체결완료로 인한 코인 또는 기축코인 지급
                    if($row['tr_type'] == 0) { // 판매
                        $thisPrice = $row['tr_price'] > $_POST['price'] ? $row['tr_price'] : $_POST['price'];

                        $coinName = strtolower("mb_{$row['tr_currency']}");
                        $intCoin = round_down($row['tr_price'] * $row['tr_amount'] - $odFee, 8);
                    } else { // 구매
                        $thisPrice = $row['tr_price'] < $_POST['price'] ? $row['tr_price'] : $_POST['price'];

                        $coinName = strtolower("mb_{$row['tr_symbol']}");
                        $intCoin = round_down($row['tr_amount'] - $odFee, 8);
                    }

                    //$settlePrice = settleTrade($row['tr_no']);

                    $query = "UPDATE _members SET  {$coinName} = {$coinName} + '{$intCoin}' WHERE mb_id = '{$row['mb_id']}';";
                    $db->execute($query);

                    pointLogInsert($row['mb_id'], $currency, $intCoin, 3, '_trade', $row['tr_no'], $nowTime);
                }
            }

            if($saveAmount == 0) { // 수량이 0이 되었을때
                $query = "UPDATE _trade SET tr_volume = '{$_POST['amount']}', tr_quantity = 0, tr_success_time = '{$nowTime}' WHERE tr_no = '{$tr_no}' AND tr_symbol = '{$_POST['coinType']}';";
                $db->execute($query);

                $query = "UPDATE _coins SET ci_{$lowCurrency}_total = ci_{$lowCurrency}_total - {$_POST['amount']} WHERE ci_symbol = '{$_POST['coinType']}'";
                $db->execute($query);

                $odFee = 0;
                $odFee = round_down_format($tradeType == 0 ? ($_POST['price'] * $_POST['amount'] * ($main_fee / 100)) : ($_POST['amount'] * ($main_fee / 100)), 8);

                $query = "INSERT INTO _orders SET mb_id = '{$member['mb_id']}', tr_id = '{$tr_no}', od_price = '{$_POST['price']}', od_amount = '{$_POST['amount']}', od_datetime = '{$nowTime}', od_type = '{$tradeType}', od_coin = '{$_POST['coinType']}', od_currency = '{$currency}', td_no = '{$row['tr_no']}', td_amount = '{$_POST['amount']}', td_price = '{$row['tr_price']}', od_fee = '{$odFee}';";
                $db->execute($query);
                $od_no = $db->lastInsertId();

                if($od_no){
                    $ci_price = $_POST['price'];
                    $query = "UPDATE _coins SET ci_{$lowCurrency} = '{$_POST['price']}', ci_updatetime = '{$nowTime}' WHERE ci_symbol = '{$_POST['coinType']}';";
                    $db->execute($query);

                    $real_no = 0;
                    $sc = count($socketData);
                    $socketData[$sc]['name'] = 'tradingOrders';
                    $socketData[$sc]['datas']['datetime'] = date('H:i:s', $nowTime);
                    $socketData[$sc]['datas']['price'] = round_down_format(sprintf('%.8f', $_POST['price']), 8);
                    $socketData[$sc]['datas']['amount'] = round_down_format(sprintf('%.8f', $_POST['amount']), 8);
                    $socketData[$sc]['datas']['total'] = round_down_format($_POST['amount'] * $_POST['price'], 8);
                    $socketData[$sc]['datas']['type'] = $tradeType;
                    $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
                    $socketData[$sc]['datas']['currency'] = $currency;
                    $socketData[$sc]['datas']['email'] = $member['mb_id'];

                    // 체결완료로 인한 코인 또는 기축코인 지급
                    if($tradeType == 0) { // 판매
                        $thisPrice = $row['tr_price'] > $_POST['price'] ? $row['tr_price'] : $_POST['price'];

                        $coinName = strtolower("mb_{$currency}");
                        $intCoin = round_down($_POST['price'] * $_POST['amount'] - $odFee, 8);
                    } else { // 구매
                        $thisPrice = $row['tr_price'] < $_POST['price'] ? $row['tr_price'] : $_POST['price'];

                        $coinName = strtolower("mb_{$_POST['coinType']}");
                        $intCoin = round_down($_POST['amount'] - $odFee, 8);
                    }

                    //$settlePrice = settleTrade($tr_no);

                    $query = "UPDATE _members SET {$coinName} = {$coinName} + '{$intCoin}' WHERE mb_id = '{$member['mb_id']}';";
                    $db->execute($query);

                    pointLogInsert($member['mb_id'], $_POST['coinType'], $intCoin, 3, '_trade', $tr_no, $nowTime);
                }
            } else {
                $query = "UPDATE _trade SET tr_volume = tr_amount - '{$saveAmount}', tr_quantity = '{$saveAmount}' WHERE tr_no = '{$tr_no}' AND tr_symbol = '{$_POST['coinType']}'";
                $db->execute($query);
            }
        } if(count($socketData) == 0){
            $sc = count($socketData);
            $socketData[$sc]['name'] = 'tradingList';
            $socketData[$sc]['datas']['no'] = $real_no;
            $socketData[$sc]['datas']['type'] = $tradeType;
            $socketData[$sc]['datas']['price'] = round_down_format_fix(sprintf('%.8f', $_POST['price']), 8);
            $socketData[$sc]['datas']['percent'] = @round_down(sprintf('%.2f', ($_POST['price'] / $dailyPrice * 100) - 100), 2);
            $socketData[$sc]['datas']['amount'] = round_down_format(sprintf('%.8f', $_POST['amount']), 8);
            $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
            $socketData[$sc]['datas']['currency'] = $currency;
            $socketData[$sc]['datas']['currencyPrice'] = round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $_POST['amount'], 8).' '.($currency=='USDT'?'BTC':'USDT');
        } else if($saveAmount > 0){
            $sc = count($socketData);
            $socketData[$sc]['name'] = 'tradingList';
            $socketData[$sc]['datas']['no'] = $real_no;
            $socketData[$sc]['datas']['type'] = $tradeType;
            $socketData[$sc]['datas']['price'] = round_down_format_fix(sprintf('%.8f', $_POST['price']), 8);
            $socketData[$sc]['datas']['percent'] = @round_down(sprintf('%.2f', ($_POST['price'] / $dailyPrice * 100) - 100), 2);
            $socketData[$sc]['datas']['amount'] = round_down_format(sprintf('%.8f', $saveAmount), 8);
            $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
            $socketData[$sc]['datas']['currency'] = $currency;
            $socketData[$sc]['datas']['currencyPrice'] = round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $saveAmount, 8).' '.($currency=='USDT'?'BTC':'USDT');
        }
    } else {
        $sc = count($socketData);
        $socketData[$sc]['name'] = 'tradingList';
        $socketData[$sc]['datas']['no'] = $real_no;
        $socketData[$sc]['datas']['type'] = $tradeType;
        $socketData[$sc]['datas']['price'] = round_down_format_fix(sprintf('%.8f', $_POST['price']), 8);
        $socketData[$sc]['datas']['percent'] = @round_down(sprintf('%.2f', ($_POST['price'] / $dailyPrice * 100) - 100), 2);
        $socketData[$sc]['datas']['amount'] = round_down_format(sprintf('%.8f', $_POST['amount']), 8);
        $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
        $socketData[$sc]['datas']['currency'] = $currency;
        $socketData[$sc]['datas']['currencyPrice'] = round_down_format($coinInfo['ci_'.($currency=='USDT'?'btc':'usdt')] * $_POST['amount'], 8).' '.($currency=='USDT'?'BTC':'USDT');
    }

    $yesterTime = $nowTime - 86400;
    $query = "SELECT 

    (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}' ORDER BY tr_price DESC LIMIT 0,1) as ci_high_price,
    
    (SELECT tr_price FROM _trade WHERE tr_type = 0 AND tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}' ORDER BY tr_price ASC LIMIT 0,1) as ci_low_price,
    
    (SELECT sum(tr_amount) FROM _trade WHERE tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}' AND tr_datetime >= '{$yesterTime}') as ci_total_coin,
    
    (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 0 AND tr_success_time IS NULL AND tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}') as ci_total_sell,
    
    (SELECT sum(tr_quantity) FROM _trade WHERE tr_type = 1 AND tr_success_time IS NULL AND tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}') as ci_total_buy,
    
    (SELECT sum(tr_amount) FROM _trade WHERE tr_symbol = '{$_POST['coinType']}' AND tr_currency = '{$currency}' AND tr_datetime >= '{$yesterTime}') as ci_total_price,
    
    ci_{$lowCurrency}, ci_symbol, ci_{$lowCurrency}_total, ci_{$country}_name, ci_{$country}_info, ci_{$lowCurrency}_percent, ci_minimum_{$lowCurrency}_unit, ci_count, ci_url, ci_start, ci_symbol_url, ci_book, ci_{$lowCurrency}, cd_btc, cd_usdt, cd_{$lowCurrency} AS ci_yesterday_price FROM _coinDaily AS D 
    
    LEFT JOIN _coins AS C ON D.cd_symbol = C.ci_symbol
    
    WHERE ci_symbol = '{$_POST['coinType']}' ORDER BY cd_datetime DESC LIMIT 0, 1";
    $row = $db->fetchAll($query);
    $row = objectToArray($row[0]);

    $coinTotalPrice = round($row['ci_total_price'] * $row["ci_{$lowCurrency}"] * $_USDT_PRICE / 1000000, 1); // 억단위 나누기

    $sc = count($socketData);
    $socketData[$sc]['name'] = 'coinCounting';
    $socketData[$sc]['datas']['ci_total_coin'] = round_down_format(sprintf('%.8f', $row['ci_total_coin']), 8);
    $socketData[$sc]['datas']['ci_high_price'] = round_down_format(sprintf('%.8f', $row['ci_high_price']), 8);
    $socketData[$sc]['datas']['ci_low_price'] = round_down_format(sprintf('%.8f', $row['ci_low_price']), 8);
    $socketData[$sc]['datas']['ci_total_sell'] = round_down_format(sprintf('%.8f', $row['ci_total_sell']), 8);
    $socketData[$sc]['datas']['ci_total_buy'] = round_down_format(sprintf('%.8f', $row['ci_total_buy']), 8);
    $socketData[$sc]['datas']['ci_total_price'] = $coinTotalPrice;
    $socketData[$sc]['datas']['ci_price'] = round_down_format(($ci_price >= 0 ? $row["ci_{$lowCurrency}"] : $ci_price), 8);
    $socketData[$sc]['datas']['coin'] = $_POST['coinType'];
    $socketData[$sc]['datas']['currency'] = $currency;
    $socketData[$sc]['datas']['percent'] = @round_down(sprintf('%.8f', ($row["ci_{$lowCurrency}"] / $row["cd_{$lowCurrency}"] * 100) - 100), 2);
    $socketData[$sc]['datas']['yesterdayPrice'] = round_down_format($row["ci_{$lowCurrency}"] - $row["cd_{$lowCurrency}"], 8);

    echo json_encode(array('result'=>'success', 'od_no' => (int) $real_no, 'nowAmount' => round_down_format($_POST['amount'] - $saveAmount, 8), 'saveAmount' => round_down_format($saveAmount, 8), 'datetime'=> date('m.d H:i', $nowTime), 'sockets' => $socketData, 'datas' => $datas));
} else
    echo json_encode(array('result'=>'login'));
?>
