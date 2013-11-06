<?php
/*
* Deliver wiki
* components/wiki/_ajax.del.tag.php
* 12.07.2013
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

$tag_id = (isset($_POST['tag_id']))? intval($_POST['tag_id']):0;
$kw_id = (isset($_POST['kw_id']))? intval($_POST['kw_id']):0;

if($tag_id>0 && $kw_id>0) $db->delete($config['tbl_prefix'].'tag_relate', array('tag_id'=>$tag_id, 'kw_id'=>$kw_id));

$db->disconnect();
?>