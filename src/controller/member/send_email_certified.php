<?php
/**
 * 이메일 전송
 */

require_once './_common.php';
include_once MD_ROOT . '/common/Mail.php';
include_once MD_ROOT . '/common/MailForm.php';
include_once MD_ROOT . '/common/Certified.php';

$mail_certified = new Certified();

// METHOD 확인
$REQUEST_METHOD = 'GET';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$mb_id = $_GET["id"];

// 빈 값 체크
if ($mb_id == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    // 인증 코드 생성 후 메일 FORM 생성, 인증 테이블 저장 -> 메일 전송
    if ($result = $mail_certified->createEmailCertified($mb_id)) {
        echo json_encode($api->callResponse($result));
        return;
    } else {
        echo json_encode($api->callError(97));
        return;
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
