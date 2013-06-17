<?php
/*
* Deliver wiki
* components/authentication/_ajax.check.duplicate.email.php
* 14.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
define("deliver", true);

include('../../config.php');
include('../../helper/index.php');
include('../../global.settings.php');

$db = new db();
$db->connect($config);

$emailAddress = cln_email_string($_GET['email']);

if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
	$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'users', 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => ' WHERE email = "'.$emailAddress.'"');
	$db->select($qryArray);
	
	if($db->total()>0) echo 'false';
	else echo 'true';
}
else echo 'false';

$db->disconnect();
?>