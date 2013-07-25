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

if(!$_SESSION['logged_user']) {
	echo json_encode(array('affectedRow' => 0, 'insertedId' => 0, 'duplicate' => 0));
	exit();	
}

// Posted data
$array = array();
$array['comment'] = strip_unsafe($_POST['comment']);
$array['to_id'] = intval($_POST['to_id']);
$array['by_user_id'] = $_SESSION['logged_user'];
$array['created_at'] = date("Y-m-d H:i:s");

if($array['comment']!=NULL && $array['to_id']>0){
	$inserted = $db->insert($config['tbl_prefix'].'comments', $array, array('comment', 'by_user_id', 'to_id'));
	echo json_encode($inserted);
	
	// adding reputation
	$db->insert($config['tbl_prefix'].'reputation', array('rep'=>$global->rep_comment, 'to_user_id'=>$_SESSION['logged_user'], 'from_user_id'=> -1, 'for_comment_id'=>$inserted['insertedId']), array('to_user_id', 'for_comment_id', 'from_user_id'));
}
else echo json_encode(array('affectedRow' => 0, 'insertedId' => 0, 'duplicate' => 0));

$db->disconnect();
?>