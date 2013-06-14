<?php
/*
* Deliver wiki
* route.php
* 13.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");

if( !isset($_SESSION['logged']) ) $_SESSION['logged'] = false;

$pageURL = str_replace('/deliver/', '', $_SERVER['REQUEST_URI']);
$pageParam = explode('/', $pageURL);
$route = array();
if( $pageURL != NULL ){
	$route['component']	= cln_url_string($pageParam[0]);
	$route['view']	= cln_url_string($pageParam[1]);
	
	$comDir = $global->comFolder.'/'.$route['component'].'/';
	$comDirIndex = $comDir.'index.php';
}
?>