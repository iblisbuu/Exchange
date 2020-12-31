<?php

require_once './_common.php';
include_once MD_ROOT . '/member/Access.php';

$access = new Access();

// METHOD 확인 및 JSON 가져오기
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null ||
    !array_key_exists('page', $_POST) || $_POST['page'] == null) {
    echo json_encode($api->callError(50));
    return;
}
$mb_id=$_POST['mb_id'];

/* 페이지 시작지점 구하기 */
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$page = $page < 1 ? 1 : $page;
$limits = 10;
$fromRecord = ($page - 1) * $limits;

try {
    $totalCnt = $access->getAccessCount($mb_id);
    $result['list'] = $access->getAccess($mb_id,$fromRecord,$limits);
    $result['count'] =$totalCnt;
    echo json_encode($api->callResponse($result));

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}