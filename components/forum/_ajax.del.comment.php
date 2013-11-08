<?php
/*
* Deliver wiki
* _ajax.del.comment.php
* 07.11.2013
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

if( isset($_POST['id']) ){
	$id = intval( $_POST['id'] );

	$db->delete($config['tbl_prefix'].'reputation', array('for_comment_id'=>$id));
	$deleted = $db->delete($config['tbl_prefix'].'comments', array('id'=>$id));

	if( $deleted['affectedRow'] == 1 ) echo 'true';
	else echo 'false';
}

$db->disconnect();
?>