<?php
define('PROJECT', 'GS');
define('ROOT', '/');
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . ROOT);

// 상수 선언
define('SRC_ROOT', PROJECT_ROOT . 'src'); // src
define('VIEW_ROOT', PROJECT_ROOT . 'src/view'); // view
define('CTRL_ROOT', PROJECT_ROOT . 'src/controller'); // controller
define('MD_ROOT', PROJECT_ROOT . 'src/model'); // model

define('PUBLIC_ROOT', PROJECT_ROOT . 'public'); // public
define('IMG_ROOT', PROJECT_ROOT . 'public/img'); // images
define('JS_ROOT', PROJECT_ROOT . 'public/js'); // javascript
define('CSS_ROOT', PROJECT_ROOT . 'public/css'); // css
define('STYLE_ROOT', PROJECT_ROOT.'public/css'); // css
define('LIB_ROOT', PROJECT_ROOT.'public/lib'); // lib
define('FILE_ROOT', PROJECT_ROOT.'public/upload'); // lib