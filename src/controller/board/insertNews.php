<?php
/**
 * 공지 작성 Controller
 * Request : nw_type, nw_title_ko, nw_title_en, nw_title_ja, nw_content_ko, nw_content_en, nw_content_ja, mb_id
 */

require_once './_common.php';
include_once MD_ROOT . '/board/News.php';

$news = new News();

// METHOD 확인
getJsonData('POST');

$data = json_decode(file_get_contents('php://input'), true);

// 빈 값 체크 (title,content는 없을수도 있으므로 제외)
if (empty($data) ||
    !array_key_exists('nw_type', $data) || $data['nw_type'] == null ||
    !array_key_exists('mb_id', $data) || $data['mb_id'] == null) {
    echo json_encode($api->callError(50));
    return;
}

$nw_type = $data['nw_type'];
$mb_id = $data['mb_id'];
$nw_topfix = $data['nw_topfix'];

$nw_title_ko = ($data['nw_title_ko'] == '' || null) ? null : $data['nw_title_ko'];
$nw_title_en = ($data['nw_title_en'] == '' || null) ? null : $data['nw_title_en'];
$nw_title_ja = ($data['nw_title_ja'] == '' || null) ? null : $data['nw_title_ja'];
$nw_title_ch = ($data['nw_title_ch'] == '' || null) ? null : $data['nw_title_ch'];

$nw_content_ko = str_replace("'", "\'", ($data['nw_content_ko'] == '' || null) ? null : $data['nw_content_ko']);
$nw_content_en = str_replace("'", "\'", ($data['nw_content_en'] == '' || null) ? null : $data['nw_content_en']);
$nw_content_ja = str_replace("'", "\'", ($data['nw_content_ja'] == '' || null) ? null : $data['nw_content_ja']);
$nw_content_ch = str_replace("'", "\'", ($data['nw_content_ch'] == '' || null) ? null : $data['nw_content_ch']);

$nw_datetime = time();

try {

    $insertResult = $news->insertNews($nw_type, $mb_id, $nw_title_ko, $nw_title_en, $nw_title_ja, $nw_title_ch,
        $nw_content_ko,
        $nw_content_en, $nw_content_ja, $nw_content_ch, $nw_datetime, $nw_topfix);
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
