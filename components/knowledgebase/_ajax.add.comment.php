<?php
/*
* Deliver wiki
* components/knowledgebase/_ajax.add.comment.php
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
$array = array();
$array['comment'] = strip_unsafe($_POST['comment']);
$array['to_id'] = intval($_POST['to_id']);
$array['by_user_id'] = $userData->id;
$array['created_at'] = date("Y-m-d H:i:s");

if($array['comment']!=NULL && $array['to_id']>0){
	$inserted = $db->insert($config['tbl_prefix'].'comments', $array, array('comment', 'by_user_id', 'to_id'));
	echo json_encode($inserted);
}
else echo json_encode(array('affectedRow' => 0, 'insertedId' => 0, 'duplicate' => 0));

$db->disconnect();
?>