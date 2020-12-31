<?php
/**
 * OTP Secret 가져오기
 * DB에 있으면 가져오고 DB에 없으면 생성
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

// 빈 값 체크
if ($mb_id == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $member_info = $Member->getMember('mb_id', $mb_id);
    if ($member_info->mb_otp) { // OTP 존재
        $result['type'] = 'already';
        $result['secret'] = $member_info->mb_otp;
    } else { // OTP 미존재
        $otp_into = $Certified->createGoogleOtpSecret();
        $result['type'] = 'new';
        $result = array_merge($result, $otp_into);
    }
    echo json_encode($api->callResponse($result));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
