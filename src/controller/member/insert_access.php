<?php

require_once './_common.php';
include_once MD_ROOT . '/member/Access.php';

$access = new Access();

// METHOD 확인 및 JSON 가져오기
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('mb_id', $_POST) || $_POST['mb_id'] == null ||
    !array_key_exists('ac_result', $_POST) || $_POST['ac_result'] == null) {
    echo json_encode($api->callError(50));
    return;
}

try {
// 회원 아이디
    $mb_id = $_POST['mb_id'];
    $ac_result = $_POST['ac_result'];

// 접속 국가
    $details = json_decode(file_get_contents("http://ipinfo.io/"));
    $ac_location = $details->country;

// 접속 기기 & 브라우저
    $mobile_agent = '/(iPod|iPad|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
    $ac_device = (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER["HTTP_HOST"], 'm.') !== false) ?
        'Mobile' : "Web";

// 브라우저 추가
//    $browserList = array('MSIE', 'Chrome', 'Firefox', 'iPhone', 'iPad', 'Android', 'PPC', 'Safari', '');
//    $browserName = '';
//    foreach ($browserList as $userBrowser) {
//        if ($userBrowser === 'none') break;
//        if (strpos($_SERVER['HTTP_USER_AGENT'], $userBrowser)) {
//            $browserName = ", " . $userBrowser;
//            break;
//        }
//    }
//    $ac_device = $ac_device .= $browserName;

    $userAgent = $_SERVER["HTTP_USER_AGENT"];
    $browser = '';
    if (preg_match("/Trident*/", $userAgent) && preg_match("/rv:11.0*/", $userAgent) && preg_match("/Gecko*/", $userAgent)) {
        $browser = 'IE 11';
    } else if (preg_match("/OPR*/", $userAgent)) {
        $browser = 'Opera';
    } else if (preg_match("/Edg*/", $userAgent)) {
        $browser = 'Edge';
    } else if (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Chrome';
    } else if (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Safari';
    } else {
        $browser = "";
    }
    $ac_device = $ac_device .= ($browser != '') ? ', ' . $browser : '';


    $access->insertAccess($mb_id, $ac_location, $ac_device, $ac_result);
    echo json_encode($api->callResponse());

} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}