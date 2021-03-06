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

// if hit register button
if( isset($array['register']) ){	
	
	unset($array['register']);
	$array = sanitize($array, array('emp_id'=>'int', 'email'=>'email'), array('password', 'cpassword'));
	
	// checking for error
	$errorList = array();
	
	if( strlen($array['password']) < 5 )
		$errorList[] = 'Password must be at least 5 char long';
	if( $array['password']!=$array['cpassword'] )
		$errorList[] = 'Password doesn\'t match';
	
	if($array['name']=='flagged') $errorList[] = 'Please enter your full name. Not your nick name!';
	if($array['email']=='flagged') $errorList[] = 'Please enter a valid email address';	
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
		unset($array['cpassword']);
		$array['password'] = encode($array['password']);
		$array['created_at'] = date("Y-m-d");
		$array['temp_code'] = md5(time());
		$array['designation'] = 'unknown';
		$array['avatar'] = 'default.jpg';
		$array['usergroup'] = 'Author';
		
		$inserted = $db->insert($_this->tableName, $array, array('email'));
		
		// if data inserted
		if( $inserted['affectedRow'] == 1 ){
			
			// preparing email
			$activate_link = $comURL.'activation/'.$array['temp_code'].'/';
			
			$message = 'Dear '.$array['name'].',<br /><br />';
			$message .= 'In order to validate your account please click in link below - <br /><br />';
			$message .= '<a href="'.$activate_link.'">'.$activate_link.'</a>';
			$message .= '<br /><br />Thanks<br />'.$global->teamName.' Team';
			
			// send email
			$mailSent = SendMail( $array['email'], 'Action required!', $message );	
			
			// if mail sent to 
			if($mailSent){
				$_SESSION['msg']['main'] = 'Please check your email and click verify link to validate your email.';
				$_SESSION['msg']['more'] = 'An email has been dispatched to your email. Please check your inbox/spam folder and click on verify link to validate your account. After validate you will be waiting for approval. It may take up to 1-2 business day(s).';
				$_SESSION['msg']['rurl'] = $comURL.'signin/';
				include($global->comFolder.'/redirect/success.php');	
			}
			// if unable to sent email
			else {
				$_SESSION['msg']['main'] = 'Sorry, we are having trouble with e-mail';
				$_SESSION['msg']['more'] = 'Please contact to your team leader to manually verify your account.';
				$_SESSION['msg']['rurl'] = $comURL.'signin/';
				include($global->comFolder.'/redirect/error.php');	
			}
			
		}
		
		// if email already exists
		elseif( $inserted['duplicate'] == true ){
			$_SESSION['msg']['main'] = 'Sorry, this email address <strong>'.$array['email'].'</strong> has been already registered!';
			$_SESSION['msg']['rurl'] = $comURL.'new/';
			include($global->comFolder.'/redirect/error.php');	
		}
		// if email already exists
		
		// if nothing happens
		else {
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = $comURL.'new/';
			include($global->comFolder.'/redirect/error.php');	
		}
		// if nothing happens
			
	}
	// if there is no error
}
// if hit register button

// login
elseif( isset($_POST['signin']) ){
	
	unset($array['signin']);
	$array = sanitize($array, array('email'=>'email'), array('password'));
	
	// checking for error
	$errorList = array();
	
	if( strlen($array['password']) < 5 ) $errorList[] = 'Password must be at least 5 char long';
	if($array['email']=='flagged') $errorList[] = 'Please enter a valid email address';	
	// checking for error
	
	// if error found
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		$_SESSION['msg']['timeout'] = 20;
		include($global->comFolder.'/redirect/error.php');
	}
	// if error found
	// if there is no error
	else {
		$qryArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE email = '".$array['email']."' AND password = '".encode($array['password'])."'" );
		$db->select($qryArray);
		$user = $db->result();
		
		// if match found
		if( $db->total() == 1 ){
			$_SESSION['logged'] = true;
			$_SESSION['logged_user'] = $user[0]->id;
			
			$_SESSION['msg']['main'] = 'You have been successfully logged in';
			$_SESSION['msg']['rurl'] = $global->baseurl;
			$_SESSION['msg']['timeout'] = 5;
			include($global->comFolder.'/redirect/success.php');
		}
		// if match found
		// if login not matchs
		else {
			$_SESSION['msg']['main'] = '<span class="error-m">Invalid email or password. Please try again!</span>';
			$_SESSION['msg']['rurl'] = $comURL.'signin/';
			include($global->comFolder.'/redirect/success.php');
		}
		// if login not matchs
	}
	// if there is no error
}
// login

// forget
elseif( isset($_POST['forget']) ){
	unset($array['forget']);
	$array = sanitize($array, array('email'=>'email'));
	
	// checking for error
	$errorList = array();
	
	if($array['email']=='flagged') $errorList[] = 'Please enter a valid email address';
	else {
		$qryArray = array( 'tbl_name' => $_this->tableName, 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE email = '".$array['email']."'" );
		$db->select($qryArray);
		
		if($db->total() != 1){
			$errorList[] = 'This email address doesn\'t exists in our database.';
		}
	}
	// checking for error
	
	// if error found
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		$_SESSION['msg']['timeout'] = 20;
		include($global->comFolder.'/redirect/error.php');
	}
	// if error found
	// if there is no error
	else {
		$newPassword = randomPassword();
		$updated = $db->update($_this->tableName, array('password'=> encode($newPassword)), array('email'=> $array['email']));
			
			// if data updated
			if( $updated['affectedRow'] == 1 ){
	
				$_SESSION['msg']['main'] = 'You have successfully reset your password';
				$_SESSION['msg']['more'] = 'An email has been dispatched into your email address. Please check your inbox/spam folder to get new password.';
				$_SESSION['msg']['rurl'] = $comURL.'signin/';
				include($global->comFolder.'/redirect/success.php');
				
				$mailSent = SendMail( $global->nofication_email, 'New password from '.$global->teamName.' Team!', 'Hi! Here is your new password - '.$newPassword.'<br /><br />Thanks<br />'.$global->teamName.' Team' );
				
				if(!$mailSent){
					$_SESSION['msg']['main'] = 'Sorry, we are having trouble with e-mail';
					$_SESSION['msg']['more'] = 'Please contact to your team leader to get your new password.';
					$_SESSION['msg']['rurl'] = $comURL.'signin/';
					include($global->comFolder.'/redirect/error.php');	
				}
				
			}
			else {
				
				$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
				$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
				$_SESSION['msg']['rurl'] = $global->baseurl;
				include($global->comFolder.'/redirect/error.php');
					
			}
			// if data updated
	}
	// if there is no error
	
}
// forget
?>