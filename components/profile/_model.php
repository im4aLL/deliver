<?php
/*
* Deliver wiki
* components/profile/_model.php
* 20.06.2013
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

if( isset($_POST['update_info']) ){
	
	// changing profile image
	if( $_FILES['avatar']['name'] != NULL && $_FILES['avatar']['error'] == 0 ){
		
		// uploading new image
		$file_name = $_FILES['avatar'];
		$destination = 'images/users/';
		$allowed_file_type = array('jpg', 'jpeg', 'png', 'gif');
		$uploaded_file_name = secure_file_upload($file_name,$destination,$allowed_file_type);
		
		if( $uploaded_file_name != false && $uploaded_file_name != NULL ){
			
			$orig_file = $destination.$uploaded_file_name;
			$final_file = $destination.'r_'.$uploaded_file_name;
			$final_thumb_file = $destination.'r_thumb_'.$uploaded_file_name;
			
			include('../../lib/resize/resize-class.php');
			
			$resizeObj = new resize($orig_file);
			$resizeObj -> resizeImage(270, 350, 'crop');
			$resizeObj -> saveImage($final_file, 100);
			
			$resizeObj -> resizeImage(64, 64, 'crop');
			$resizeObj -> saveImage($final_thumb_file, 100);
			
			unlink($orig_file);	
		}
		
		$array['avatar'] = $uploaded_file_name;
		
		// delete old image
		if( $array['old_avatar']!=NULL && $array['old_avatar']!='default.jpg' ){
			$old_file_path = $destination.'r_'.$array['old_avatar'];
			$old_t_file_path = $destination.'r_thumb_'.$array['old_avatar'];	
			if( file_exists($old_file_path) ) {
				unlink($old_file_path);	
				unlink($old_t_file_path);	
			}
		}
		
	}
	// changing profile image
	
	$updated_email = cln_email_string($array['old_email']);
	$p_updated_email_url = explode("@", $updated_email);
	$updated_email_url = strtolower($p_updated_email_url[0]);
	
	unset($array['old_avatar']);
	unset($array['update_info']);
	unset($array['old_email']);
	$where_id = intval($array['where_id']);
	unset($array['where_id']);
	
	$array = sanitize($array, array('emp_id'=>'int'));
	
	// checking for error
	$errorList = array();
	
	if($array['name']=='flagged') $errorList[] = 'Please enter your full name. Not your nick name!';
	if($array['emp_id']=='flagged') $errorList[] = 'Please enter your employee ID (Must be number ex-250)';	
	if($array['cell_no']=='flagged') $errorList[] = 'Please enter your mobile number';	
	if($array['skype']=='flagged') $errorList[] = 'Please a valid Skype ID';
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
		
		$updated = $db->update($_this->tableName, $array, array('id'=>$where_id));	
		
		
		// if data updated
		if( $updated['affectedRow'] == 1 ){
	
			$_SESSION['msg']['main'] = 'Profile information has been updated!';
			$_SESSION['msg']['rurl'] = $comURL.$updated_email_url.'/';
			include($global->comFolder.'/redirect/success.php');
			
			SendMail( $updated_email, 'Account information updated!', 'Hi '.$array['name'].',<br /><br />Your account information is updated!<br /><br /><a href="'.$comURL.$updated_email_url.'/'.'">'.$comURL.$updated_email_url.'/'.'</a><br /><br />Thanks<br />'.$global->teamName.' Team' );
			
		}
		else {
			
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = currentURL();
			include($global->comFolder.'/redirect/error.php');
				
		}
		// if data updated
	
	}
	// if there is no error
}
// change password
elseif( isset($_POST['update_pass']) ){
	unset($array['update_pass']);
	$email = cln_email_string($array['email']);
	
	// checking for error
	$errorList = array();
	
	if( strlen($array['password']) < 5 )
		$errorList[] = 'Password must be at least 5 char long';
	if( $array['password']!=$array['cpassword'] )
		$errorList[] = 'Password doesn\'t match';
	
	if( trim($array['oldpassword']) == NULL) 
		$errorList[] = 'You must provide your old password!';
	if( $email == NULL ) 
		$errorList[] = 'Critical Error: Your token is missing!';
		
	$qryArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE email = '".$email."' AND password = '".encode($array['oldpassword'])."'" );
	$db->select($qryArray);
	
	if($db->total() != 1)
		$errorList[] = 'Your old password doesn\'t match!';
	// checking for error
	
	unset($array['oldpassword']);
	unset($array['cpassword']);
	unset($array['email']);
		
	// if there is an error
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		include($global->comFolder.'/redirect/error.php');
	}
	// if there is an error
	// if there is no error
	else {
		$email_pass = $array['password'];
		$array['password'] = encode($array['password']);
		$updated = $db->update($_this->tableName, $array, array('email'=>$email));	
		
		
		// if data updated
		if( $updated['affectedRow'] == 1 ){
			
			$p_updated_email_url = explode("@", $email);
			$updated_email_url = strtolower($p_updated_email_url[0]);
			
			$_SESSION['msg']['main'] = 'Your password has been changed!';
			$_SESSION['msg']['rurl'] = $comURL.$updated_email_url.'/';
			include($global->comFolder.'/redirect/success.php');
			
			//SendMail( $email, 'Password changed!', 'Hi,<br /><br />Your password is updated! New password - '.$email_pass.'<br /><br /><a href="'.$comURL.$updated_email_url.'/'.'">'.$comURL.$updated_email_url.'/'.'</a><br /><br />Thanks<br />'.$global->teamName.' Team' );
			
		}
		else {
			
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = currentURL();
			include($global->comFolder.'/redirect/error.php');
				
		}
		// if data updated
	
	}
	// if there is no error	
}
// change password
?>