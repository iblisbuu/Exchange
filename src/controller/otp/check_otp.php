<?php
/**
 * OTP 확인하기
 */

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';
include_once MD_ROOT . '/common/Certified.php';

$Member = new Member();
$Certified = new Certified();

// METHOD 확인
$REQUEST_METHOD = 'GET';
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
    if ($member_info->mb_otp) { // OTP 존재 -> OTP 인증 (DB OTP 와 입력한 값 비교)
        $result = $Certified->validGoogleOtp($mb_otp, $member_info->mb_otp);
    } else { // OTP 미존재 -> OTP 신규 등록 (SESSION OTP 와 입력한 값 비교)
        $result = $Certified->checkGoogleOtpSecret($mb_otp);
    }
    echo json_encode($api->callResponse($result));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
