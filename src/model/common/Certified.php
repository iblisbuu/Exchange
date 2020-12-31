<?php
require_once '../../../vendor/autoload.php';

class Certified
{
    public function insertCertified($type, $member, $auth)
    {
        $db = new db();
        $time = time();
        $query = "INSERT INTO
                _certified (cf_type, cf_id, cf_auth, cf_datetime)
                VALUES ('{$type}','{$member}','{$auth}','{$time}')";
        return $db->execute($query);
    }

    public function checkCertified($type, $member, $auth)
    {
        $db = new db();
        $nowTime = time();
        $expireTime = ($type == 'mail') ? 1800 : 180;
        $query = "SELECT
            cf_no,
            IF((cf_auth = '{$auth}'), true, false) as isCertified,
            IF(({$nowTime} - cf_datetime < {$expireTime}), true, false) as isValid
            FROM _certified
            WHERE cf_type = '{$type}' and cf_id = '{$member}'
            ORDER BY cf_datetime DESC 
            LIMIT 1";
        $result = get_object_vars(($db->fetchAll($query))[0]);

        if ($result['isCertified'] != true || $result['isCertified'] != 1) {
            return 'false';
        }
        if ($result['isValid']) {
            if ($type == 'mail') {
                $this->updateMailCertified($member);
            }
            return 'true';
        } else {
            return 'expire';
        }
    }

    public function validCertified($type, $member)
    {
        $db = new db();
        $query = "SELECT
            cf_no, cf_datetime
            FROM _certified
            WHERE cf_type = '{$type}' and cf_id = '{$member}'
            ORDER BY cf_datetime DESC 
            LIMIT 1";
        $result = get_object_vars(($db->fetchAll($query))[0]);

        return $result;
    }

    public function updateMailCertified($member)
    {
        $db = new db();
        $query = "UPDATE _members SET mb_level = 1 WHERE mb_id='$member' AND mb_level = 0";
        return $db->execute($query);
    }

    /** 인증코드 메일 전송
     * @param $mb_id *메일 주소
     * @return bool
     */
    public function createEmailCertified($mb_id)
    {
        // 인증 코드 생성 후 메일 FORM 생성, 인증 테이블 저장
        $mail = new Mail();
        $mail_form = new MailForm();

        $auth_num = sprintf('%06d', rand(000000, 999999));

        $form = $mail_form->authCodeForm($mb_id, $auth_num);

        $this->insertCertified('mail', $mb_id, $auth_num);

        // 메일 전송
        if ($mail->sendMail($mb_id, $form['title'], $form['content'])) {
            return true;
        } else {
            return false;
        }
    }

    /** 구글 OTP 인증번호 확인
     * @param $otpCode = 구글OTP 6자리 번호
     * @return int
     */
    public function validGoogleOtp($otpCode, $otpSet = '')
    {
        $GOOGLE = new PHPGangsta_GoogleAuthenticator();

        if (trim($otpSet) == '')
            return 10;
        else {
            $checkResult = $GOOGLE->verifyCode($otpSet, $otpCode);

            if ($checkResult)
                return true;
            else
                return false;
        }
    }

    /** 구글 OTP Secret 생성
     * @return array
     */
    public function createGoogleOtpSecret()
    {
        global $member;

        if (!empty($member) && ($member['mb_otp'] == null || trim($member['mb_otp']) == '')) {
            $GOOGLE = new PHPGangsta_GoogleAuthenticator();

            $secret = $GOOGLE->createSecret(); // Secret 코드 생성
            $qrCodeUrl = $GOOGLE->getQRCodeGoogleUrl('Genesis-EX', $secret); // QR코드 생성

            $json = [];
            $json['secret'] = $secret;
            $json['qrcode'] = $qrCodeUrl;

            $_SESSION['googleSecret'] = $secret; // 인증 완료 전까진 세션에 저장 후 인증 완료되면 DB에 저장시킴
            return $json;
        }
    }

    /** 구글 OTP Secret 확인
     * @param $otpCode = 구글OTP 6자리 번호
     * @return bool
     */
    public function checkGoogleOtpSecret($otpCode)
    {
        global $member;

        if (!empty($member) && strlen($_SESSION['googleSecret']) == 16) {
            $GOOGLE = new PHPGangsta_GoogleAuthenticator();

            $checking = $GOOGLE->verifyCode($_SESSION['googleSecret'], $otpCode); // Secret 코드 확인
            if ($checking) {
                $db = new db();
                $query = "UPDATE _members SET mb_otp = '{$_SESSION['googleSecret']}', mb_level = 3 WHERE mb_id = '{$member['mb_id']}'";
                $result = $db->execute($query);

                if ($result) {
                    unset($_SESSION['googleSecret']);
                    return true;
                } else
                    return false;
            } else
                return false;
        }
    }

    /** 구글 OTP 연결 해제
     * @param $otpCode = 구글OTP 6자리 번호
     * @return bool
     */
    public function deleteGoogleOtpSecret($otpCode){
        global $member;

        if(!empty($member) && strlen($member['mb_otp']) == 16) {
            $GOOGLE = new PHPGangsta_GoogleAuthenticator();

            $checking = $GOOGLE->verifyCode($member['mb_otp'], $otpCode); // Secret 코드 확인
            if($checking){
                $db = new db();
                $query = "UPDATE _members SET mb_otp = NULL, mb_level = 2 WHERE mb_id = '{$member['mb_id']}'";

                return $db->execute($query);
            } else
                return false;
        }
    }

}