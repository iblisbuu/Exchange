<?php

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';

$Member = new Member();

// method check
$REQUEST_METHOD = 'POST';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$data = json_decode(file_get_contents('php://input'), true);

// 빈 값 체크
if (empty($data) ||
    !array_key_exists('mb_id', $data) || $data['mb_id'] == null ||
    !array_key_exists('mb_password', $data) || $data['mb_password'] == null ||
    !array_key_exists('mb_password_chk', $data) || $data['mb_password_chk'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$mb_id = $data['mb_id'];
$mb_password = $data['mb_password'];
$mb_password_chk = $data['mb_password_chk'];

try {
    if ($mb_password == $mb_password_chk) {
        //  비번 암호화 해서 변경
        $mb_password = create_hash($mb_password);
        $result = $Member->updateMemberPassword($mb_id, $mb_password);
        if($result){
            if (!empty($member)) {
                $url['move'] = '/member/account/info';
            } else {
                $url['move'] = '/member/login';
            }
            echo json_encode($api->callResponse($url));
            return;
        }else{
            echo json_encode($api->callResponse($result));
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


