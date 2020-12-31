<?php
/**
 * 회원 가입 Controller
 * Request : mb_id, mb_password, mb_password_check
 */

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';
include_once MD_ROOT . '/common/Mail.php';
include_once MD_ROOT . '/common/MailForm.php';
include_once MD_ROOT . '/common/Certified.php';

$member = new Member();
$mail = new Mail();
$mail_form = new MailForm();
$mail_certified = new Certified();

// METHOD 확인 및 JSON 가져오기
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null ||
    !array_key_exists('mb_password', $_POST) || $_POST['mb_password'] == null ||
    !array_key_exists('mb_password_chk', $_POST) || $_POST['mb_password_chk'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$mb_id = $_POST['mb_id'];
$mb_password = $_POST['mb_password'];
$mb_password_chk = $_POST['mb_password_chk'];
$mb_marketing = ($_POST['mb_marketing']==true)?1:0;

// 비밀 번호, 비밀 번호 재확인 체크
if ($mb_password != $mb_password_chk) {
    echo json_encode($api->callError(99));
    return;
}

try {
    // 아이디(이메일) 중복 체크
    $memberSeq = $member->getMemberSeq('mb_id', $mb_id);
    if ($memberSeq > 0) {
        echo json_encode($api->callError(19));
        return;
    }

    // 회원 가입
    $insertResult = $member->insertMember($mb_id, create_hash($mb_password),$mb_marketing);
    if ($insertResult) {
        // 인증 코드 생성 후 회원가입 메일 FORM 생성, 인증 테이블 저장 -> 메일 전송
        if ($mail_certified->createEmailCertified($mb_id)) {
            echo json_encode($api->callResponse($insertResult));
            return;
        } else {
            echo json_encode($api->callError(97));
            return;
        }
    } else {
        echo json_encode($api->callError(99));
        return;
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
