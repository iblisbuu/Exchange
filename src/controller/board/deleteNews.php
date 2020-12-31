<?php
/**
 * 공지 삭제 Controller
 * Request : nw_no
 */

require_once './_common.php';
include_once MD_ROOT . '/board/News.php';

$news = new News();

// METHOD 확인
getJsonData('DELETE');

$nw_no = $_GET["no"];

// 빈 값 체크
if ( $nw_no == null ) {
    echo json_encode($api->callError(50));
    return;
}

$data['nw_no']= $nw_no;
$data['nw_deletetime']= time();

try {
    $deleteResult = $news->updateNews($data);
    if ($deleteResult) {
        echo json_encode($api->callResponse($deleteResult));
        return;
    }else{
        echo json_encode($api->callError(99));
        return;
    }

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
