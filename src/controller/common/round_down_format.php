<?php require_once '../../../common.php';
if(!$_GET['price'])
    $_GET['price'] = 0;
$json = [];

if(empty($_GET['price']))
    $json['price'] = round_down_fix(sprintf('%.8f', ($_GET['price'] + $_GET['amount'] < 0 ? 0 : $_GET['price'] + $_GET['amount'])), 8);
else {
    $json['price'] = [];
    for($i=0; $i<count($_GET['price']); $i++)
        $json['price'][$i] = @round_down_fix(sprintf('%.8f', ($_GET['price'][$i] + $_GET['amount'][$i] < 0 ? 0 : $_GET['price'][$i] + $_GET['amount'][$i])), 8);
}

echo json_encode($json);
?>