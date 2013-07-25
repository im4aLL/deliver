<?php
/*
* Deliver wiki
* components/knowledgebase/_ajax.update.comment.php
* 17.07.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
session_start();
if(!isset($_SESSION['logged']) || !$_SESSION['logged']) die();

define("deliver", true);

include('../../config.php');
include('../../helper/index.php');
include('../../global.settings.php');

$db = new db();
$db->connect($config);

if(!$_SESSION['logged_user']) {
	echo 'false';
	exit();	
}

// Posted data
$warray = array();
$warray['id'] = intval($_POST['id']);

$array = array();
$array['comment'] = strip_unsafe($_POST['comment']);
$array['modified_at'] = date("Y-m-d H:i:s");
$array['modified_by'] = $_SESSION['logged_user'];

if( $warray['id'] > 0 && $array['comment'] != NULL ){

	if( $userData->usergroup == 'Administrator' ){
		$updated = $db->update($config['tbl_prefix'].'comments', $array, $warray);
		
		if($updated['affectedRow']==1) echo 'true';
		else echo 'false';
	}
	else {
		$warray['by_user_id'] = $_SESSION['logged_user'];
		$updated = $db->update($config['tbl_prefix'].'comments', $array, $warray);
		
		if($updated['affectedRow']==1) echo 'true';
		else echo 'false';	
	}
	
}
else echo 'false';

$db->disconnect();
?>