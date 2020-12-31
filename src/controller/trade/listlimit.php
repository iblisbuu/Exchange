<?php require_once './_common.php';

$nowTime = time();

$yesterTime = $nowTime - 86400;
$lowCurrency = strtolower($currency);

$db = new db();

$query = "SELECT ci_btc, ci_usdt FROM _coins WHERE ci_symbol = '{$coin}'";
$dailyPrice = $db->fetchAll($query)[0];
$dailyPrice = objectToArray($dailyPrice);
$coinInfo = $dailyPrice;
$dailyPrice = $dailyPrice["ci_{$lowCurrency}"];

$limits = 0;

if($_SESSION['limitReal'] == $price && $nowTime - $_SESSION['limitTime'] < 2)
    $_SESSION['limitCount']++;
else
    $_SESSION['limitCount'] = 0;

$limits = $_SESSION['limitCount'];

$query = "SELECT tr_no, tr_quantity, tr_price, 
    (SELECT SUM(tr_quantity) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = '{$type}' AND tr_quantity > 0 AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_total, 
    (SELECT SUM(tr_amount) FROM _trade S WHERE S.tr_price = T.tr_price AND tr_type = '{$type}' AND tr_quantity > 0 AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}') as tr_now, 
    (SELECT ci_{$lowCurrency} FROM _coins WHERE ci_symbol = '{$coin}') as ci_yesterday_price 
FROM _trade as T 
WHERE tr_type = '{$type}' AND tr_success_time IS NULL AND tr_price ".($type == 1 ? '<' : '>')." '{$price}' AND tr_quantity > 0 AND tr_symbol = '{$coin}' AND tr_currency = '{$currency}' GROUP BY tr_price ORDER BY tr_price ".($type == 1 ? 'DESC' : 'ASC')." LIMIT {$limits}, 1";

$row = $db->fetchAll($query);
if($row) {
    $row = objectToArray($row[0]);

    $_SESSION['limitPrice'] = $row['tr_price'];
    $_SESSION['limitTime'] = $nowTime;
    $_SESSION['limitReal'] = $price;

    $row['width'] = round_down($row['tr_total'] / $row['tr_now'] * 100, 2);
    $row['tr_total'] = round_down_format($row['tr_total'], 8);
    $row['percent'] = @round_down(($row['tr_price'] / $dailyPrice * 100) - 100, 2);
    $row['currencyPrice'] = round_down_format($coinInfo['ci_' . ($currency == 'USDT' ? 'btc' : 'usdt')] * $row['tr_total'], 8) . ' ' . ($currency == 'USDT' ? 'BTC' : 'USDT');
    $row['tr_price'] = round_down_format_fix($row['tr_price'], 8);

    $row['save'] = $_SESSION['limitPrice'];

    $row['result'] = 'success';
} else
    $row['result'] = 'fail';

echo json_encode($row);
?>