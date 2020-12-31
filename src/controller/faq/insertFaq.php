<?php
/*
 * 자주 묻는 질문 추가
 */

require_once './_common.php';

$faq = new Faq();

// method check
getJsonData('POST');

// 빈 값 체크 (title,content는 없을수도 있으므로 제외)
if (empty($_POST) ||
    !array_key_exists('faq_type', $_POST) || $_POST['faq_type'] == null ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$_POST['faq_datetime'] = time();

try {
    $insertResult = $faq->insertFaq($_POST);
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