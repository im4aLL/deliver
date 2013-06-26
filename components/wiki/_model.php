<?php
/*
* Deliver wiki
* components/authentication/_model.php
* 14.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/

$array = array();
$array = $_POST;

// if hit kn_wiki button
if( isset($array['kn_wiki']) ){	
	
	unset($array['kn_wiki']);
	if( $array['new_category'] != NULL ) $array['category'] = $array['new_category'];
	unset($array['new_category']);
	$array = sanitize($array, array(), array('description'));
	$array['description'] = strip_unsafe($array['description']);
	
	// checking for error
	$errorList = array();
	
	if($array['category']=='flagged') $errorList[] = 'Invalid category name!';
	if($array['title']=='flagged') $errorList[] = 'Please enter a valid title';	
	if(trim($array['description'])==NULL) $errorList[] = 'Please write something in description';	
	// checking for error
	
	// if there is an error
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		$_SESSION['msg']['timeout'] = 20;
		include($global->comFolder.'/redirect/error.php');
	}
	// if there is an error
	// if there is no error
	else {
		$array['url'] = genUrl($array['title']);
		$array['created_at'] = date("Y-m-d");
		$array['state'] = 0;
		$array['by_id'] = $userData->id;
		$array['type'] = 'wiki';
		
		$inserted = $db->insert($_this->tableName, $array, array('title', 'url'));
		
		// if data inserted
		if( $inserted['affectedRow'] == 1 ){
			
			$_SESSION['msg']['main'] = 'Thank you for submit article into wiki!';
			$_SESSION['msg']['more'] = 'An administrator must approve this article, Before appears to wiki. It may take up to 1-2 business day(s).';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/success.php');
			
			//SendMail( $global->nofication_email, 'Wiki: Action required!', "A new article has been submitted to wiki waiting for moderation.<br /><br /><strong>Title:</strong> ".$array['title']."<br /><br />Please go to your account dashboard and approve/disapprove pending submission(s)." );
		}
		
		// if email already exists
		elseif( $inserted['duplicate'] == true ){
			$_SESSION['msg']['main'] = 'Sorry, similar type of article is already exists!';
			$_SESSION['msg']['rurl'] = $comURL.'new/';
			include($global->comFolder.'/redirect/error.php');	
		}
		// if email already exists
		
		// if nothing happens
		else {
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/error.php');	
		}
		// if nothing happens
			
	}
	// if there is no error
}
// if hit kn_wiki button
?>