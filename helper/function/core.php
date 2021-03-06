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
	return preg_replace("/[^a-zA-Z0-9-._]+/", "", $string);	
}

function sant_str($str){
	$str = trim(strtolower($str));
	$str = str_replace(" ", "_", $str);
	$normal = preg_replace("/[^a-zA-Z0-9_]+/", "", $str);
	return $normal;	
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

function safe_string($str){
	return filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
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

function randomPassword($length=7) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i <= $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function preTitle($comString){
	$comString = str_replace("_", " ", $comString);
	$result = ucwords($comString);
	return $result.' - ';
}

// currently it support two level / need to add recursive later
function genSubMenu($menuArray){
	$subMenu = '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">';
	
	foreach($menuArray as $m){
		if($m['name']=='Logout') $subMenu .= '<li class="divider"></li>';
		$subMenu .=	'<li><a href="'.$m['link'].'"><i class="'.$m['icon'].'"></i> '.$m['name'].'</a></li>';
	}
	
	$subMenu .= '</ul>';
	return $subMenu;
}

function genMenu($menuArray, $class="nav", $active=NULL){
	$menu = '<ul class="'.$class.'">';
	
	foreach($menuArray as $menuKey=>$m){
		
		if( isset($m['dropdown']) && count($m['dropdown'])>0 ) $hasSubmenu = true;
		else $hasSubmenu = false;
		
		$menu .= '<li'.(($active==$menuKey)?'  class="active'.( ($hasSubmenu)?' dropdown':'' ).'"':( ($hasSubmenu)?' class="dropdown"':'') ).'>';
		if($hasSubmenu) {
			$menu .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$m['link'].'"><i class="'.$m['icon'].'"></i> '.$m['name'].' <b class="caret"></b></a>';
			$menu .= genSubMenu($m['dropdown']);	
		}
		else $menu .= '<a href="'.$m['link'].'"><i class="'.$m['icon'].'"></i> '.$m['name'].'</a>';
		$menu .= '</li>';	
	}
	
	$menu .= '</ul>';
	
	return $menu;
}

function ago($time, $parseMin = false) {
	
	if(!$parseMin) if( date("Y-m-d", $time) == date("Y-m-d") ) return "today";
	
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago ";
}

/*function ago($timestamp, $parseMin = false){
	if(!$parseMin) if( date("Y-m-d", $timestamp) == date("Y-m-d") ) return "today";
	
    //type cast, current time, difference in timestamps
    $timestamp      = (int) $timestamp;
    $current_time   = time();
    $diff           = $current_time - $timestamp;
   
    //intervals in seconds
    $intervals      = array (
        'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
    );
   
    //now we just find the difference
    if ($diff == 0)
    {
        return 'just now';
    }   

    if ($diff < 60)
    {
        return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
    }       

    if ($diff >= 60 && $diff < $intervals['hour'])
    {
        $diff = floor($diff/$intervals['minute']);
        return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
    }       

    if ($diff >= $intervals['hour'] && $diff < $intervals['day'])
    {
        $diff = floor($diff/$intervals['hour']);
        return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
    }   

    if ($diff >= $intervals['day'] && $diff < $intervals['week'])
    {
        $diff = floor($diff/$intervals['day']);
        return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
    }   

    if ($diff >= $intervals['week'] && $diff < $intervals['month'])
    {
        $diff = floor($diff/$intervals['week']);
        return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
    }   

    if ($diff >= $intervals['month'] && $diff < $intervals['year'])
    {
        $diff = floor($diff/$intervals['month']);
        return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
    }   

    if ($diff >= $intervals['year'])
    {
        $diff = floor($diff/$intervals['year']);
        return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
    }
}*/


function ipadr(){ 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){ 
        $ip = $_SERVER['HTTP_CLIENT_IP']; 
    } 
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    } 
    else{ 
        $ip = $_SERVER['REMOTE_ADDR']; 
    } 
    return $ip; 
} 


function secure_file_upload($file_name,$destination,$allowed_file_type=array(),$max_upload_size=2) {     
    $max_upload_size_byte = $max_upload_size * 1024 * 1024;  
     
    if(!is_dir($destination))  
        { 
            $making = mkdir($destination,0777, true); 
            chmod($destination, 0777); 
     
            if(!$making) return false; 
        } 
     
    $fileName = $file_name['name']; 
    $file_type = substr($fileName, strrpos($fileName, '.') + 1); 
    $file_type = strtolower($file_type); 
     
    if( !array_search($file_type, $allowed_file_type) && !in_array($file_type, $allowed_file_type) ) return false; 
    if( $file_name['size'] > $max_upload_size_byte ) return false; 
    if( $file_name['error'] != 0 ) return false; 
 
    $final_name = time()."_".preg_replace("/[^a-zA-Z0-9-_.]+/", "", $file_name['name']); 
    $final_des = $destination.$final_name; 
    $uploading = move_uploaded_file($file_name['tmp_name'],$final_des); 
    if( !$uploading ) return false; 
    return $final_name; 
}

function strip_unsafe($string=NULL){

    $unsafe = array(
    '/<iframe(.*?)<\/iframe>/is',
    '/<title(.*?)<\/title>/is',
    '/<frame(.*?)<\/frame>/is',
    '/<frameset(.*?)<\/frameset>/is',
    '/<object(.*?)<\/object>/is',
    '/<script(.*?)<\/script>/is',
    '/<embed(.*?)<\/embed>/is',
    '/<applet(.*?)<\/applet>/is',
    '/<meta(.*?)>/is',
    '/<!doctype(.*?)>/is',
    '/<link(.*?)>/is',
    '/<body(.*?)>/is',
    '/<\/body>/is',
    '/<head(.*?)>/is',
    '/<\/head>/is',
    '/onload="(.*?)"/is',
    '/onunload="(.*?)"/is',
	'/onclick="(.*?)"/is',
    '/<html(.*?)>/is',
    '/<\/html>/is');
	
	//'/<img(.*?)>/is';

    $string = preg_replace($unsafe, "", $string);
    return html_encode($string);
}

function html_encode($string){
	return htmlentities($string, ENT_QUOTES, "UTF-8");	
}

function html_decode($string){
	$output = html_entity_decode($string, ENT_QUOTES, "UTF-8");	
	return stripslashes($output);
}

function genUrl($string){
	$string = trim(strtolower($string));
	$string = str_replace(" ", "-", $string);
	$string = cln_url_string($string);
	$string = str_replace(array("--"), '-', $string);
	if( substr($string, -1) == '-' ) 
		$string = substr($string, 0, -1);
	return $string;
}

function genTag($string, $from_tag=false){
	$stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the", "flagged");
	
	$result = array();
	
	if( $from_tag == false ){
		$words = trim(preg_replace("/[^a-zA-Z0-9 -]+/", "", strtolower($string)));
		$tags = explode(" ", $words);
		$tags = array_unique($tags);
		
		if( is_array($tags) && count($tags) > 0 ){
			
			foreach($tags as $tag){
				if( strlen($tag) > 2 && ( !in_array($tag, $stopwords) && !array_search($tag, $stopwords) ) ){
					$result[] = trim($tag);
				}
			}
		}	
	}
	else {
		$words = strtolower($string);
		$tags = explode(",", $words);
		$tags = array_unique($tags);
		
		if( is_array($tags) && count($tags) > 0 ){
			
			foreach($tags as $tag){
				if( strlen($tag) > 2 && ( !in_array($tag, $stopwords) && !array_search($tag, $stopwords) ) ){
					$result[] = trim(preg_replace("/[^a-zA-Z0-9-]+/", "", $tag));
				}
			}
		}
		
	}
	
	return $result;
}

function limit($string, $limit = 150){
	$string = strip_tags($string);
	$stringArray = explode(" ", $string);
	$stringArray = array_splice($stringArray, 0, $limit);
	return implode(" ",$stringArray);
}

function getProfileUrl($email){
	global $global;
	
	$s_p_username = explode("@", $email);
	$pUrl = strtolower($s_p_username[0]);
	
	$ppURL = $global->baseurl.'profile/'.$pUrl.'/';
	return $ppURL;
}

function get_rep($emp_id){
	global $db;
	global $config;
	
	$db->query("SELECT SUM(rep) as reputation FROM ".$config['tbl_prefix']."reputation WHERE to_user_id = '$emp_id'");
	$rep_r = $db->result();

	return $rep_r[0]->reputation > 0 ? $rep_r[0]->reputation : 0;
}

function genBrands(){
	global $global;

	$html = '';
	if( count($global->brands) > 0 ){
		foreach($global->brands as $brands){
			$html .= '<option value="'.strtolower($brands).'">'.$brands.'</option>';
		}
	}

	return $html;
}

function beautifyWord($string){
	return preg_replace('/-/', ' ', $string);
}
?>