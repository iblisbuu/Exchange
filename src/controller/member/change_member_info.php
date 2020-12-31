<?php

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';

$member = new Member();

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
    !array_key_exists('mb_id', $data) || $data['mb_id'] == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    echo json_encode($api->callResponse($member->updateMember($data)));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}


