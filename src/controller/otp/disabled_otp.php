<?php
/**
 * OTP 비활성화
 */

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';
include_once MD_ROOT . '/common/Certified.php';

$Member = new Member();
$Certified = new Certified();

// METHOD 확인
$REQUEST_METHOD = 'DELETE';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$mb_id = $_GET["mb_id"];
$mb_otp = $_GET["mb_otp"];

// 빈 값 체크
if ($mb_id == null || $mb_otp == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $member_info = $Member->getMember('mb_id', $mb_id);
    if ($member_info->mb_otp) { // OTP 존재 :: 비활성화 하기
        $result = $Certified->deleteGoogleOtpSecret($mb_otp);
        echo json_encode($api->callResponse($result));
    } else { // OTP 미존재 :: 이미 비활성화 상태
        echo json_encode($api->callError(99));
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
