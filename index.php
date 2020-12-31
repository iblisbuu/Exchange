<?php
require_once 'config.php';
require_once 'common.php';

ini_set('display_errors', 0);
header("Content-Type: text/html; charset=UTF-8");

$html_process = new html_process();

ob_start();

/** LOCAL **/
$segment = explode('index.php', $_SERVER['PHP_SELF']);
$segment = explode('/', $segment[1]);
array_splice($segment, 0, 1);
$mainMenu = isset($segment[0]) ? $segment[0] : null;
$count = (count($segment) == 1 && $segment[0] == '/') ? 0 : count($segment);
/** SERVER **/
//$segment = explode('/', $_SERVER['PHP_SELF']);
//array_splice($segment, 0, 3);
//$count = count($segment);

// HEADER
include VIEW_ROOT . '/common/head.sub.php';

if ($device == 'mobile') {
    include VIEW_ROOT . '/common/m_head.php';
    include VIEW_ROOT . '/common/m_gnb.php';
} else {
    include VIEW_ROOT . '/common/head.php';
}

// BODY
if ($count) {
    $segment[0] = '/' . $segment[0];
    $include_file = VIEW_ROOT . $segment[0] . '/' . $segment[1] . '.php';
    if (file_exists($include_file)) {
        include $include_file;
    } else {
        include VIEW_ROOT . '/404.php';
    }

} else { // 메인 파일 위치
    include VIEW_ROOT . '/index.php';
}

// FOOTER
if ($device == 'mobile') {
    // 거래소 아닐 경우에만
    if (strpos($include_file, 'exchange') == false) {
        include VIEW_ROOT . '/common/m_footer.php';
    }
} else {
    include VIEW_ROOT . '/common/footer.php';
}
include VIEW_ROOT . '/common/footer.sub.php';

run_event('alert_hooks');

echo html_end();