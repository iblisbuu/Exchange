<?php

/**
 * Session 로그인 Controller
 * Request : mb_id
 */

require_once './_common.php';

// method check
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$mb_id = $_POST['mb_id'];

try {
    $_SESSION['mb_id'] = $mb_id;
    echo json_encode($api->callResponse());
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}

