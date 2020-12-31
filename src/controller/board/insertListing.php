<?php
/**
 * 상장문의 Controller
 * Request : ls_email,ls_project_name,ls_project_desc,ls_corp,ls_token_name,
ls_token_theme,ls_token_type,ls_website,ls_whitepaper,ls_contract,ls_sns
 */

require_once './_common.php';
include_once MD_ROOT . '/board/Listing.php';
include_once MD_ROOT . '/common/Mail.php';
include_once MD_ROOT . '/common/MailForm.php';

$listing = new Listing();
$mail = new Mail();
$mail_form = new MailForm();

// METHOD 확인 및 JSON 가져오기
getJsonData('POST');

// 빈 값 체크
if (empty($_POST) ||
    !array_key_exists('ls_email', $_POST) || $_POST['ls_email'] == null ||
    !array_key_exists('ls_project_name', $_POST) || $_POST['ls_project_name'] == null ||
    !array_key_exists('ls_project_desc', $_POST) || $_POST['ls_project_desc'] == null ||
    !array_key_exists('ls_corp', $_POST) || $_POST['ls_corp'] == null ||
    !array_key_exists('ls_token_name', $_POST) || $_POST['ls_token_name'] == null ||
    !array_key_exists('ls_token_theme', $_POST) || $_POST['ls_token_theme'] == null ||
    !array_key_exists('ls_token_type', $_POST) || $_POST['ls_token_type'] == null ||
    !array_key_exists('ls_website', $_POST) || $_POST['ls_website'] == null ||
    !array_key_exists('ls_whitepaper', $_POST) || $_POST['ls_whitepaper'] == null ||
    !array_key_exists('ls_contract', $_POST) || $_POST['ls_contract'] == null ||
    !array_key_exists('ls_sns', $_POST) || $_POST['ls_sns'] == null) {
    echo json_encode($api->callError(50));
    return;
}


$ls_email = $_POST['ls_email'];
$ls_project_name = $_POST['ls_project_name'];
$ls_project_desc = $_POST['ls_project_desc'];
$ls_corp = $_POST['ls_corp'];
$ls_token_name = $_POST['ls_token_name'];
$ls_token_theme = $_POST['ls_token_theme'];
$ls_token_type = $_POST['ls_token_type'];
$ls_website = $_POST['ls_website'];
$ls_whitepaper = $_POST['ls_whitepaper'];
$ls_contract = $_POST['ls_contract'];
$ls_sns = $_POST['ls_sns'];
$ls_datetime = time();

try {
    // 디비 저장
    $insertResult = $listing->insertListing($ls_email,$ls_project_name,$ls_project_desc,$ls_corp,$ls_token_name,
        $ls_token_theme,$ls_token_type,$ls_website,$ls_whitepaper,$ls_contract,$ls_sns,
        $ls_datetime);

    // 메일 전송
    $listing_contents=[
        'ls_email'=>$ls_email,
        'ls_project_name'=>$ls_project_name,
        'ls_project_desc'=>$ls_project_desc,
        'ls_corp'=>$ls_corp,
        'ls_token_name'=>$ls_token_name,
        'ls_token_theme'=>$ls_token_theme,
        'ls_token_type'=>$ls_token_type,
        'ls_website'=>$ls_website,
        'ls_whitepaper'=>$ls_whitepaper,
        'ls_contract'=>$ls_contract,
        'ls_sns'=>$ls_sns
    ];

    $listing_form=$mail_form->listingForm($listing_contents);
    $mailResult=$mail->sendMail('listing@genesis-ex.com',$listing_form['title'],$listing_form['content']);

    if ($insertResult && $mailResult) {
        echo json_encode($api->callResponse());
        return;
    } else {
        echo json_encode($api->callError(99));
        return;
    }
} catch (Exception $e) {
    echo json_encode($api->callError(98));
    return;
}
