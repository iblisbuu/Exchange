<?php
// 하단 include ./_common.php로 수정하지 말것
include_once $_SERVER["DOCUMENT_ROOT"] . '/common.php';
unset($_SESSION['mb_id']);
session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함
move('/');
?>

