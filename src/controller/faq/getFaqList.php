<?php
/**
 * 자주 묻는 질문 출력 Controller
 */

require_once './_common.php';

$faq = new Faq();

// GET 확인
$REQUEST_METHOD = 'GET';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

$search = (isset($_GET['search'])) ? $_GET['search'] : '';

try {
    $result = $faq->getAllFaqList($country, $search);
    echo json_encode($api->callResponse($result));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}