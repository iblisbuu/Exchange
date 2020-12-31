<?php

/**
 * 로그인 Controller
 * Request : mb_id, mb_password
 */

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';
include_once MD_ROOT . '/common/Certified.php';

$member = new Member();
$mail_certified = new Certified();

// method check
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null ||
    !array_key_exists('mb_password', $_POST) || $_POST['mb_password'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$mb_id = $_POST['mb_id'];
$mb_password = $_POST['mb_password'];

try {
    if ($member_info = $member->getMember('mb_id', $mb_id)) {
        $mb_level=$member_info->mb_level;
        if ($mb_level == 0) {
            // 회원가입 이메일 인증 미진행
            echo json_encode($api->callError(97, "please mail certificate."));
            return;
        }
        /** 비밀번호 확인 */
        if ($member_info && validate_password($mb_password, $member_info->mb_password)) {
            // 비번 일치 하므로 다시 비번 암호화 해서 저장
            $mb_password = create_hash($mb_password);
            $member->updateMemberPassword($mb_id, $mb_password);

//            $_SESSION['mb_id'] = $mb_id;

            echo json_encode($api->callResponse(array('url' => urldecode($_GET['url'] ?? '/'),'level' =>
                $mb_level)));
        } else {
            // 비밀번호 다름
            echo json_encode($api->callError(99, "id or password not correct"));
        }

    } else {
        // 아이디 없음
        echo json_encode($api->callError(14, "id not exist"));
    }

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}


