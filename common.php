<?php
$PROJECT = 'GS';

// VERSION 관리
define($PROJECT . '_CSS_VER', '20200921');
define($PROJECT . '_JS_VER', '20200921');

define($PROJECT . '_ESCAPE_PATTERN', '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
define($PROJECT . '_ESCAPE_REPLACE', '');
define($PROJECT . '_ESCAPE_FUNCTION', 'sql_escape_string');

// PHP MAILER
define($PROJECT . '_SMTP', 'smtp.gmail.com');
define($PROJECT . '_SMTP_PORT', 465);
define($PROJECT . '_SMTP_USER', 'certified@genesis-ex.com'); // 보내는 메일 주소
define($PROJECT . '_SMTP_PASSWORD', 'wpsptltm1!'); // 비밀번호

define('SMS_SEED', 'ACc4675fb09c54c862829002f2a1a2fb59');
define('SMS_TOKEN', '9ed56f1a15a36c35c2e0c2eb3f8880c7');

@session_start();

require_once('config.php');
require_once(LIB_ROOT . '/hook.lib.php'); // 각종 함수
require_once(LIB_ROOT . '/common.lib.php'); // 각종 함수
require_once(LIB_ROOT . '/PasswordHash.php'); // 각종 함수
require_once(SRC_ROOT . '/config/DB.php'); // 각종 함수

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if (defined('RTC_ESCAPE_PATTERN') && defined('RTC_ESCAPE_REPLACE')) {
        $pattern = RTC_ESCAPE_PATTERN;
        $replace = RTC_ESCAPE_REPLACE;

        if ($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

$_PUT = $_DELETE = [];

$_POST = array_map_deep('stripslashes', $_POST);
$_GET = array_map_deep('stripslashes', $_GET);
$_PUT = array_map_deep('stripslashes', $_PUT);
$_DELETE = array_map_deep('stripslashes', $_DELETE);
$_COOKIE = array_map_deep('stripslashes', $_COOKIE);
$_REQUEST = array_map_deep('stripslashes', $_REQUEST);

// sql_escape_string 적용
//$_POST = array_map_deep(RTC_ESCAPE_FUNCTION, $_POST);
//$_GET = array_map_deep(RTC_ESCAPE_FUNCTION, $_GET);
//$_COOKIE = array_map_deep(RTC_ESCAPE_FUNCTION, $_COOKIE);
//$_REQUEST = array_map_deep(RTC_ESCAPE_FUNCTION, $_REQUEST);
//==============================================================================


// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

$config = array();
$member = array();

@$country = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

if (@$_SESSION['mb_id']) {
    $db = new db();
    $member = $db->fetchAll("SELECT * FROM _members WHERE mb_id = '{$_SESSION['mb_id']}'");
    $member = objectToArray($member[0]);
    //print_r($member);
}

//if (!$_SESSION['lang']) $country = $member['ru_lang'];
//else $country = $_SESSION['lang'] != '' ? $_SESSION['lang'] : substr($HTTP_ACCEPT_LANGUAGE, 0, 2);

//if ($country == 'ko')
//    date_default_timezone_set("Asia/Seoul");
//else if ($country == 'ja')
    date_default_timezone_set("Asia/Tokyo");
//else if ($country == 'ch')
//    date_default_timezone_set("Asia/Shanghai");
//else
    //date_default_timezone_set("UTC");


// 디바이스 구분
$mobile_agent = '/(iPod|iPad|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
$device = (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER["HTTP_HOST"], 'm.') !== false) ? 'mobile' : "pc";

function lang($ko = '', $en = '', $ja = '', $ch = '')
{
    global $country;

    if ($country == 'ko')
        $msg = $ko;
    else if ($country == 'en')
        $msg = trim($en) != '' ? $en : $ko;
    else if ($country == 'ch') {
        if (trim($ch) != '')
            $msg = $ch;
        else if (trim($en) != '')
            $msg = $en;
        else
            $msg = $ko;
    } else if ($country == 'ja') {
        if (trim($ja) != '')
            $msg = $ja;
        else if (trim($en) != '')
            $msg = $en;
        else
            $msg = $ko;
    }

    return $msg;
}

function send_sms($country, $phone, $msg)
{
    $seed = SMS_SEED;
    $token = SMS_TOKEN;
    $url = "https://api.twilio.com/2010-04-01/Accounts/{$seed}/Messages.json";

    $phone = str_replace(array('+', '-'), '', $phone);

    if ($country == substr($phone, 0, strlen($country)))
        $phone = substr($phone, strlen($country), strlen($phone));

    $phone = preg_replace('/[^0-9]/', '', $phone);

    $from = "+19144990792";  // 발송번호
    $to = '+' . $country . $phone; // 수신번호
    $body = $msg; // 내용

    $id = $seed;

    $data = array(
        'From' => $from,
        'To' => $to,
        'Body' => $body,
    );

    $post = http_build_query($data);
    $x = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    $d = curl_exec($x);
    if (curl_errno($x)) {
        echo 'Curl error: ' . curl_error($x);
    }
    $usdt = json_decode($d, true);
    curl_close($x);

    return $usdt;
}

$main_fee = 0.25;

?>