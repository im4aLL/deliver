<?php
/*
* Deliver wiki
* authentication/logout.php
* 18.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");

unset($_SESSION['logged']);
unset($_SESSION['logged_user']);

$_SESSION['msg']['main'] = 'You have been successfully logged out!';
$_SESSION['msg']['rurl'] = $global->baseurl;
$_SESSION['msg']['timeout'] = 5;
include($global->comFolder.'/redirect/success.php');
?>