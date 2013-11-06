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

$global                   = new StdClass;

$global->baseurl          = 'http://localhost/deliver/';
$global->pageTitle        = 'Hogarth Wiki';
$global->comFolder        = 'components'; 

$global->teamName         = 'Hogarth Dhaka';

$global->rowPerPage       = 25;

$global->smtp_host        = 'smtp.gmail.com';
$global->smtp_secure      = 'ssl';
$global->smtp_port        = 465;
$global->smtp_username    = 'hogarthwiki@gmail.com';
$global->smtp_password    = 'graphicPeople#2013';
$global->smtp_from        = 'no-reply@graphicpeoplestudio.com';
$global->smtp_from_name   = $global->teamName.' team';

$global->nofication_email = 'eyes_rainning@hotmail.com';
$global->contact_email    = 'Al.Hadi@adpeople.com';

$global->bug_report       = 'Al.Hadi@adpeople.com';
$global->version          = '1.0.1';
$global->copyright        = '&copy; '.date("Y").' to GraphicPeople ('.$global->teamName.' team)';
$global->last_updated     = '06/11/2013 at 9:50 PM by '.$global->bug_report;

$global->rep_wiki         = 30;
$global->rep_kn           = 20;
$global->rep_kn_to_wiki   = 20;
$global->rep_comment      = 2;
$global->rep_comment_up   = 5;
$global->rep_comment_down = -5;
$global->rep_add_new_wiki = 50;
$global->rep_add_new_kn   = 30;

/*
* Administrator - Can do any thing
* Author - Can manage own article but cant delete
* Moderator - Can approve and add, edit post but can't delete
* Member - Can add article but will be awaiting for moderation
*/
$global->usergroup     = array("Administrator", "Author", "Moderator", "Member");

$global->theme         = array('flat');
$global->debugMode     = true;
$global->approvalValue = 1; // 1 = auto approval, 0 = administrator need to approve

// make sure its one word with no space
$global->brands = array(
	'OCC', 'HillsPet', 'Other'
	);
?>