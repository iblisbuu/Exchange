<?php
include_once '../member/_common.php';

$REQUEST_METHOD = 'POST';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data) ||
    !array_key_exists('recaptcha_response', $data) || $data['recaptcha_response'] == null ) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LdRMMEZAAAAAAE2lRQ3VR5tzwBvUA4094bodOhj';
    $recaptcha_response = $data['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // 0.5로 변경해야함
    if ($recaptcha->score >= 0) {
        echo json_encode($api->callResponse($recaptcha));
    } else {
        echo json_encode($api->callError(98));
    }

}catch (Exception $e){
    echo json_encode($api->callError(98));
    return;
}