<?php
/**
 * 공지 사항 업데이트 Controller
 */

require_once './_common.php';

$news = new News();

// GET 확인
getJsonData('PUT');

$data = json_decode(file_get_contents('php://input'), true);

// 빈 값 체크
if (empty($data) ||
    !array_key_exists('nw_no', $data) || $data['nw_no'] == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
    $data['nw_updatetime'] = time();
    $result = $news->updateNews($data);
    if ($result) {
        echo json_encode($api->callResponse($result));
    } else {
        echo json_encode($api->callError(99));
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}