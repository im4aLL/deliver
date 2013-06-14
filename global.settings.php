<?php
/*
* Deliver wiki
* global.settings.php
* 13.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
date_default_timezone_set('Asia/Dhaka');

$global = new StdClass;

$global->baseurl = 'http://localhost/deliver/';
$global->pageTitle = 'Deliver Wiki';
$global->comFolder = 'components';

$global->teamName = 'Deliver';

$global->version = '1.0.0 beta';
$global->copyright = '&copy; '.date("Y").' to GraphicPeople ('.$global->teamName.' team)';
$global->last_updated = '13/6/2013 at 12:31 PM by Al.Hadi@adpeople.com';
?>