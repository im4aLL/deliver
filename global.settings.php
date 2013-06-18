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

$global->smtp_host = 'smtp.gmail.com';
$global->smtp_secure = 'ssl';
$global->smtp_port = 465;
$global->smtp_username = 'hadi.graphicpeople@gmail.com';
$global->smtp_password = '@lH@D!2012';
$global->smtp_from = 'no-reply@graphicpeoplestudio.com';
$global->smtp_from_name = $global->teamName.' team';

$global->nofication_email = 'eyes_rainning@hotmail.com';
$global->contact_email = 'Al.Hadi@adpeople.com';

$global->bug_report = 'Al.Hadi@adpeople.com';
$global->version = '1.0.0 beta';
$global->copyright = '&copy; '.date("Y").' to GraphicPeople ('.$global->teamName.' team)';
$global->last_updated = '13/6/2013 at 12:31 PM by '.$global->bug_report;
?>