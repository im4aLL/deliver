<?php
/*
* Deliver wiki
* authentication/activition.php
* 18.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");

// if md5 string exists
if( $comRoute[0] != NULL && isValidMd5($comRoute[0]) ) {
	$qryArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE temp_code = '".$comRoute[0]."'" );
	$db->select($qryArray);
	$result = $db->result();
	
	if( $db->total() == 1 ){
		$updated = $db->update($_this->tableName, array('state'=> 2, 'temp_code'=> ''), array('temp_code'=> $comRoute[0]));
			
			// if data updated
			if( $updated['affectedRow'] == 1 ){
	
				$_SESSION['msg']['main'] = 'Thank you for validating your account!';
				$_SESSION['msg']['more'] = 'Please wait for approval and it may take up to 1-2 business day(s)';
				$_SESSION['msg']['rurl'] = $comURL.'signin/';
				include($global->comFolder.'/redirect/success.php');
				
				SendMail( $global->nofication_email, 'Account approval request', 'Hi, <br /><br />Someone created a new account at '.$global->pageTitle.' - <br /><br />'.implode('<br />', (array) $result[0]) );
				
			}
			else {
				
				$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
				$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
				$_SESSION['msg']['rurl'] = $comURL.'new/';
				include($global->comFolder.'/redirect/error.php');
					
			}
			// if data updated
	}
	else {
		$_SESSION['msg']['main'] = 'Invalid activation link';
		$_SESSION['msg']['more'] = 'Your activation link is broken/invalid please click again the link from email or copy and paste the link into browser. Still having trouble? <a href="mailto:'.$global->contact_email.'">email us</a> for further support.';
		$_SESSION['msg']['rurl'] = $global->baseurl;
		include($global->comFolder.'/redirect/error.php');	
	}
}
// if md5 string exists
else include($global->comFolder.'/error/404.php');
?>