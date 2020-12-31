<?php
require_once '../../../common.php';
header("Content-Type: application/json; charset=UTF-8");
include_once SRC_ROOT . '/config/api.php';
include_once SRC_ROOT . '/config/DB.php';
include_once MD_ROOT . '/board/Faq.php';

$api = new API();
?>