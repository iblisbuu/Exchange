<?php
/**
 * 인증 Controller
 * Request
 *   - cf_type : 인증 타입
 *   - cf_id : 인증 아이디
 *   - cf_auth : 인증 번호
 */

require_once './_common.php';
include_once MD_ROOT . '/common/Certified.php';

$mail_certified = new Certified();

// METHOD 확인
$REQUEST_METHOD = 'POST';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$data = json_decode(file_get_contents('php://input'), true);

// 빈 값 체크
if (empty($data) ||
    !array_key_exists('cf_type', $data) || $data['cf_type'] == null ||
    !array_key_exists('cf_id', $data) || $data['cf_id'] == null ||
    !array_key_exists('cf_auth', $data) || $data['cf_auth'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$cf_type = $data['cf_type'];
$cf_id = $data['cf_id'];
$cf_auth = $data['cf_auth'];
$mail_type = isset($data['mail_type']) ? $data['mail_type'] : '';
$otp_level = isset($data['otp_level']) ? $data['otp_level'] : false;

try {

    $certified = $mail_certified->checkCertified($cf_type, $cf_id, $cf_auth);
    if ($certified == 'expire') {
        echo json_encode($api->callError(96));
        return;
    } else if ($certified == 'true') {
        if ($cf_type == 'mail' && $mail_type == 'join') {
            if(!$otp_level) {
                $_SESSION['mb_id'] = $cf_id;
            }
        }
        echo json_encode($api->callResponse($certified));
        return;
    } else {
        echo json_encode($api->callError(99));
        return;
    }

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}