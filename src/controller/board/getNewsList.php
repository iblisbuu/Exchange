<?php
/**
 * 공지 사항 리스트 출력 Controller
 * $type : all, notice, event
 * $no : 있으면 조회, 없으면 전체 조회
 */

require_once './_common.php';

$news = new News();

// GET 확인
$REQUEST_METHOD = 'GET';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$type = (isset($_GET['type'])) ? $_GET['type'] : 'all';
$no = (isset($_GET['no'])) ? $_GET['no'] : '';

try {
    $result = $news->getNewsList($country, $type);
    echo json_encode($api->callResponse($result));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}