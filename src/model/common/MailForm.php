<?php


class MailForm
{
    private $subject = 'GENESISㆍEX';

    function authCodeForm($mb_id, $auth_num){

        $ko_title = "[{$this->subject}] 이메일 인증"; // 제목
        $en_title = "[{$this->subject}] Email Authentication";
        $ja_title = "[{$this->subject}] イーEメール認証";
        $ch_title = "[{$this->subject}] 电子邮件认证";

        $title=lang($ko_title,$en_title,$ja_title,$ch_title);

        $content = '<table border="0" cellspacing="0" cellpadding="0" style="width:100%; padding:0px; font-family:Malgun Gothic;"><tbody><tr>';
        $content .= '<td align="center" style="background-color: #f5f5f5; font-size: 11px; text-align: center; padding-top: 40px; padding-bottom: 40px;"><table border="0" cellspacing="0" cellpadding="0" style="width:375px; margin:0 auto; border:1px solid #ccc; background-color: #ffffff;"><tbody><tr>';
        $content .= '<td align="center" style="background-color: #ffffff; font-size: 11px;"><table border="0" cellspacing="0" cellpadding="0" width="100%"><tbody>';
        $content .='<tr><td align="center" style="margin:0; text-align:left; padding-left:20px; height: 45px; background: #0a0a0a;"><img src="https://genesis-ex.com/public/img/common/logo.png" width="125" style="height : 30px;margin-top: 7px;"></td></tr>';

        $content .= '<tr><td width="100%" style="padding: 35px 35px 40px; width: 100%; text-align: justify; font-size: 11px; text-align: justify; font-size: 11px;"><table width="100%" cellpadding="0" cellspacing="0" style="border:0px"><tbody><tr>';
        $content .= '<td align="left" style="margin:0; padding: 0px;"><table width="100%" cellpadding="0" cellspacing="0" style="border:0px; width: 100% !important;"><tbody><tr>';

        $content .='<tr><td style="text-align: center; padding-bottom: 15px; font-size: 13px; color: #777777"><span style="font-weight: bold; font-size: 20px; color: #0a0a0a; margin-bottom: 5px; display : block;">';

        $ko_content = '고객님의 인증코드입니다.</span><span style="color:#4652ff; font-size: 13px">' . $mb_id . '</span> 님 안녕하세요.<br> 고객님의 인증코드는 다음과 같습니다.</td></tr>';
        $en_content = 'This is your authentication code.</span><span style="color:#4652ff; font-size: 13px">Hello, '
            .$mb_id . '</span><br>Your authentication code is as follows.</td></tr>';
        $ja_content = 'お客様の認証コードです。</span><span style="color:#4652ff; font-size: 13px">' .$mb_id . '</span>様こんにちは。<br>お客様の認証コードは次のとおりです。</td></tr>';
        $ch_content = '是顾客的认证代码。</span><span style="color:#4652ff; font-size: 13px">' .$mb_id . '</span> 先生您好,<br> 顾客的认证代码如下。</td></tr>';

        $content.= lang($ko_content,$en_content,$ja_content,$ch_content);

        $content .= '<td align="center" style="margin:0; text-align:center; height: 70px; background:#f4f4f4; line-height:26px; border:0;font-size:25px; letter-spacing:1px; color:#0a0a0a; font-weight: bold;">';

        $content .= $auth_num;

        $content .= '</td></tr><tr><td style="padding-top: 15px; text-align:center; font-size: 13px; font-weight: bold;color: #0a0a0a;">';
        $ko_content = 'GENESIS-EX를 이용해 주셔서 감사합니다.';
        $en_content = 'Thank you for using GENESIS-EX.';
        $ja_content = 'GENESIS-EXをご利用いただきありがとうございます。';
        $ch_content = '感谢您使用GENESIS-EX';
        $content.= lang($ko_content,$en_content,$ja_content,$ch_content);
        $content .= '</td></tr>';

        $content .= '</tbody></table></td></tr></tbody></table></td></tr>';
        $content .= '<tr><td style="margin:0; font-size:13px; line-height:21px; letter-spacing:-1px;"><div style=" height: 60px; background-color: #f4f4f4; ">';

        $content .= '<div style="display: inline-block; line-height: 20px ; padding: 10px 30px 0; width:100%; box-sizing: border-box;"><div><span style="color: #4652ff; font-size: 11px; letter-spacing: normal">https://genesis-ex.com</span><a href="https://genesis-ex.com/notice/customer/question" target="_blank" style="color: #4652ff; font-size: 12px; float: right; letter-spacing: normal">';

        $ko_content = '고객센터</a>';
        $en_content = 'Customer Service</a>';
        $ja_content = 'お客様センター</a>';
        $ch_content = '客户中心</a>';
        $content.= lang($ko_content,$en_content,$ja_content,$ch_content);

        $content .='</div><span style="letter-spacing: normal; font-size: 11px">'. $this->subject . '. All Rights Reserved.</span></div>';

        $content .= '</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';

        return ['title'=>$title,'content'=>$content];
    }

    function listingForm($listing_contents){
        $title = "[{$this->subject}] 상장문의"; // 제목
        $content=
            "<strong>이메일 주소 : </strong>". $listing_contents['ls_email']."<br/>".
            "<strong>프로젝트 명 : </strong>". $listing_contents['ls_project_name']."<br/>".
            "<strong>프로젝트 소개 : </strong>". $listing_contents['ls_project_desc']."<br/>".
            "<strong>법인명 : </strong>". $listing_contents['ls_corp']."<br/>".
            "<strong>토큰명 / 심볼(국문,영문) : </strong>". $listing_contents['ls_token_name']."<br/>".
            "<strong>토큰 테마 : </strong>". $listing_contents['ls_token_theme']."<br/>".
            "<strong>토큰 계열 : </strong>". $listing_contents['ls_token_type']."<br/>".
            "<strong>웹사이트 : </strong>". $listing_contents['ls_website']."<br/>".
            "<strong>백서 링크 : </strong>". $listing_contents['ls_whitepaper']."<br/>".
            "<strong>스마트 컨트랙트 주소 : </strong>". $listing_contents['ls_contract']."<br/>".
            "<strong>소셜 미디어 : </strong>". $listing_contents['ls_sns'];
        return ['title'=>$title,'content'=>$content];
    }

}