<?php

/**
 * 회원 존재 여부 Controller
 * Request : mb_id
 */

require_once './_common.php';
include_once MD_ROOT . '/member/Member.php';

$member = new Member();

// method check
$REQUEST_METHOD = 'GET';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$mb_id = $_GET["id"];

// 빈 값 체크
if ( $mb_id == null ) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $member_info = $member->getMember('mb_id', $mb_id);
    if($member_info){
        echo json_encode($api->callResponse());
        return;
    }else{
        echo json_encode($api->callError(14));
        return;
    }

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}


