<?php
/*
 * 1:1 문의하기 등록
 */

require_once './_common.php';

$question = new Question();

// method check
$REQUEST_METHOD = 'POST';
$method = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD != $method) {
    echo json_encode($api->callError(55));
    return;
}

// 빈 값 체크 (title,content는 없을수도 있으므로 제외)
if (empty($_POST) ||
    !array_key_exists('email', $_POST) || $_POST['email'] == null ||
    !array_key_exists('title', $_POST) || $_POST['title'] == null ||
    !array_key_exists('content', $_POST) || $_POST['content'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$data['q_email'] = $_POST['email'];
$data['q_title'] = $_POST['title'];
$data['q_content'] = $_POST['content'];
$data['q_datetime'] = time();

try {
    if ($_FILES) {
        $fileResult = $question->saveFiles($_FILES);
        if (!$fileResult) {
            echo json_encode($api->callError(95));
            return;
        }
        $fileResult = implode(',', $fileResult);
        $fileResult = str_replace('\\', '\\\\', $fileResult);
        $data['q_file'] = $fileResult;
    }

    $insertResult = $question->insertQuestion($data);
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