<?php
/**
 * 문자 전송
 */

require_once './_common.php';
include_once MD_ROOT . '/common/Certified.php';

$certified = new Certified();

// METHOD 확인
$REQUEST_METHOD = 'POST';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$data = json_decode(file_get_contents('php://input'), true);

$cf_type = 'phone';
$cf_id = (isset($data['mb_hp'])) ? $data['mb_hp'] : null;
$auth_num = sprintf('%06d', rand(000000, 999999));

// 빈 값 체크
if ($cf_id == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    if ($insertResult = $certified->insertCertified($cf_type, $cf_id, $auth_num)) {
        $msg = lang('GENESIS-EX 휴대폰 인증번호','GENESIS-EX mobile phone authentication number','GENESIS-EX電話認証番号','GENESIS-EX手机验证码').' ['
            . $auth_num . ']';
        $result = send_sms($data['mb_country'], $cf_id, $msg);
        echo json_encode($api->callResponse($insertResult));
        return;
    } else {
        echo json_encode($api->callError(97));
        return;
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
