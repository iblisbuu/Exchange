<?php
/**
 * 자주 묻는 질문 카테고리 출력 Controller
 */

require_once './_common.php';

$faq = new Faq();

// GET 확인
getJsonData('GET');

try {
    $result = $faq->getFaqCategory($country);
    echo json_encode($api->callResponse($result));
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}