<?php
/*
* Deliver wiki
* home/index.php
* 19.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");

$_this = new StdClass;
$_this->tableName = $config['tbl_prefix'].'users';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) include($comDir.'_model.php');
/*elseif($route['view']=='new' && !$_SESSION['logged'] ) include($comDir.'new.php');*/
else include($comDir.'home.php');
?>