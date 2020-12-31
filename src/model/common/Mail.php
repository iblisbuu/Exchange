<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';

class Mail
{
    private $username = 'certified@genesis-ex.com';
    private $password = 'wpsptltm1!';

    public function sendMail($member, $title, $content)
    {
        if (!$member) {
            return false;
        } else {
            include_once(PUBLIC_ROOT . '/plugin/PHPMailer/PHPMailerAutoload.php');

            $mail = new PHPMailer;
            $mail->SMTPSecure = 'ssl';
            $mail->isSMTP();

            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = 'certified@genesis-ex.com'; // 계정
            $mail->Password = 'wpsptltm1!'; // 비밀번호
            $mail->setFrom('certified@genesis-ex.com', 'GENESIS-EX'); // 보내는 사람
            $mail->addAddress($member, 'User'); // 받는사람
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $title;
            $mail->msgHTML($content);

            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        }
    }
}