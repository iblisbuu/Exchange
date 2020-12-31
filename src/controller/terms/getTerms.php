<?php
/*
 * 약관 관리 조회
 */

require_once './_common.php';

$MODEL_TERMS = new Terms();

// method check
$REQUEST_METHOD = 'GET';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

// 빈 값 체크
if (empty($_GET) ||
    !array_key_exists('type', $_GET) || $_GET['type'] == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $result = $MODEL_TERMS->getTerms($country, $_GET['type']);
    echo json_encode($api->callResponse($result));
    return;
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}