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

// user info
if( $_SESSION['logged_user'] > 0 ){
	$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'users', 'method' => PDO::FETCH_OBJ, 'condition' => ' WHERE id = "'.$_SESSION['logged_user'].'"');
	$db->select($qryArray);
	$p_userData = $db->result();
	$userData = $p_userData[0];
	
	$p_username = explode("@", $userData->email);
	$userData->username = strtolower($p_username[0]);
}
// user info

// Posted data
$warray = array();
$warray['id'] = intval($_POST['id']);

$array = array();
$array['comment'] = strip_unsafe($_POST['comment']);
$array['modified_at'] = date("Y-m-d H:i:s");
$array['modified_by'] = $userData->id;

if( $warray['id'] > 0 && $array['comment'] != NULL ){

	if( $userData->usergroup == 'Administrator' ){
		$updated = $db->update($config['tbl_prefix'].'comments', $array, $warray);
		
		if($updated['affectedRow']==1) echo 'true';
		else echo 'false';
	}
	else {
		$warray['by_user_id'] = $userData->id;
		$updated = $db->update($config['tbl_prefix'].'comments', $array, $warray);
		
		if($updated['affectedRow']==1) echo 'true';
		else echo 'false';	
	}
	
}
else echo 'false';

$db->disconnect();
?>