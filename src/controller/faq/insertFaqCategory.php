<?php
/*
 * 자주 묻는 질문 카테고리 추가
 */

require_once './_common.php';

$faq = new Faq();

// method check
getJsonData('POST');

$data = json_decode(file_get_contents('php://input'), true);

// 빈 값 체크 (title,content는 없을수도 있으므로 제외)
if (empty($data)) {
    echo json_encode($api->callError(50));
    return;
}

$data['fc_datetime'] = time();

try {
    $insertResult = $faq->insertFaqCategory($data);
    if ($insertResult) {
        echo json_encode($api->callResponse($insertResult));
        return;
    } else {
        echo json_encode($api->callError(99));
        return;
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}