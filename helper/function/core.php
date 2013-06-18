<?php
/*
* Deliver wiki
* helper/function/core.php
* 13.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
function encode($sValue, $sSecretKey = 'hadi') {
    return rtrim(
        base64_encode(
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256,
                $sSecretKey, $sValue, 
                MCRYPT_MODE_ECB, 
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256, 
                        MCRYPT_MODE_ECB
                    ), 
                    MCRYPT_RAND)
                )
            ), "\0"
        );
}

function decode($sValue, $sSecretKey = 'hadi') {
    return rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256, 
            $sSecretKey, 
            base64_decode($sValue), 
            MCRYPT_MODE_ECB,
            mcrypt_create_iv(
                mcrypt_get_iv_size(
                    MCRYPT_RIJNDAEL_256,
                    MCRYPT_MODE_ECB
                ), 
                MCRYPT_RAND
            )
        ), "\0"
    );
}

function cln_url_string($string){
	return preg_replace("/[^a-zA-Z0-9_-~#!]+/", "", $string);	
}

function currentURL(){
	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	return filter_var($url, FILTER_SANITIZE_URL);	
}

function redirect($url, $timeout = 5, $nomsg = false){
	if(!$nomsg) $html = '<p class="muted redirect-msg"><small><a href="'.$url.'">click here</a> if your browser doesn\'t automatically redirect you in '.$timeout.' second(s)</small></p>';
	else $html = '';
	$html .= '<script>';
	$html .= 'setTimeout(function() { window.location.href = "'.$url.'"; }, '.($timeout * 1000).');';
	$html .= '</script>';
	return $html;
}

function cln_email_string($email){
	return preg_replace("/[^a-z0-9+_.@-]/i", "", $email);	
}

function cln_data($string){
	
	$search = array(
		'@<script[^>]*?>.*?</script>@si',
		'@<[\/\!]*?[^<>]*?>@si',
		'@<style[^>]*?>.*?</style>@siU',
		'@<![\s\S]*?--[ \t\n\r]*>@'
	);
	
	$string = preg_replace($search, '', $string);
	
	$string = strip_tags(trim($string));
	$string = htmlentities($string, ENT_QUOTES, "UTF-8");
	
	if (get_magic_quotes_gpc())
        $string = stripslashes($string);
		
	return $string;
}

function sanitize($data,$type=array(),$exception=array()){
	if(is_array($data)){
		$sant = array();
		foreach($data as $key=>$val){
			if( !array_search($key,$exception) && !in_array($key,$exception) ) {
				if( isset($type[$key]) && $type[$key]=='int' && trim($val)!=NULL) {
					if( is_numeric($val) )
						$sant[$key] = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
					else
						$sant[$key] = 'flagged';
				}
				elseif( isset($type[$key]) && $type[$key]=='email' && trim($val)!=NULL){
					if( !filter_var($val, FILTER_VALIDATE_EMAIL) ) 
						$sant[$key] = 'flagged';
					else { 
						$val = cln_email_string($val);
						$sant[$key] = filter_var($val, FILTER_SANITIZE_EMAIL);
					}
				}
				elseif( trim($val)!=NULL ) {
					$normal = cln_data($val);
					$sant[$key] = filter_var($normal, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				}
				elseif( trim($val)==NULL ) $sant[$key] = 'flagged';
				else $sant[$key] = 'flagged';
			}
		}
		
		foreach($exception as $key){
			$sant[$key] = $data[$key];	
		}
	}
	return $sant;
}

function genList($lists=array()){
	$html = '<ul class="msg-list">';
	foreach($lists as $list){
		$html .= '<li>'.$list.'</li>';	
	}
	$html .= '</ul>';
	return $html;	
}


function SendMail( $to, $subject, $message ) {
	global $global;
	
	require_once( 'lib/PHPMailer/class.phpmailer.php' );
	require_once( 'lib/html2text/html2text.php' );
	
	$Mail = new PHPMailer();
	$Mail->IsSMTP();
	$Mail->Host        = $global->smtp_host;
	$Mail->SMTPDebug   = 0;
	$Mail->SMTPAuth    = TRUE;
	$Mail->SMTPSecure  = $global->smtp_secure;
	$Mail->Port        = $global->smtp_port;
	$Mail->Username    = $global->smtp_username;
	$Mail->Password    = $global->smtp_password;
	$Mail->Priority    = 1;
	$Mail->CharSet     = 'UTF-8';
	$Mail->Encoding    = '8bit';
	$Mail->Subject     = $subject;
	$Mail->ContentType = 'text/html; charset=utf-8\r\n';
	$Mail->From        = $global->smtp_from;
	$Mail->FromName    = $global->smtp_from_name;
	$Mail->WordWrap    = 900;
	
	$Mail->AddReplyTo($global->smtp_from, $global->smtp_from_name);
	
	$Mail->AddAddress( $to );
	$Mail->isHTML( TRUE );
	
	$html_header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>'.$subject.'</title><body>';
	$html_footer = '</body></html>';
	$Mail->Body    = $html_header.$message.$html_footer;
	
	$Mail->AltBody = convert_html_to_text($message);
	$Mail->Send();
	$Mail->SmtpClose();
	
	if ( $Mail->IsError() )
		return false;
	else
		return true;
}

function isValidMd5($md5) {
    return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}
?>