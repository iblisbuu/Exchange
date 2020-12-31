<?php
/**
 * 유효한 인증인지 Controller
 * Request
 *   - cf_type : 인증 타입
 *   - cf_id : 인증 아이디
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
    !array_key_exists('cf_id', $data) || $data['cf_id'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$cf_type = $data['cf_type'];
$cf_id = $data['cf_id'];

try {
    $certified = $mail_certified->validCertified($cf_type, $cf_id);
    echo json_encode($api->callResponse($certified));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}