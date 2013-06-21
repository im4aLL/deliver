<?php
/*
* Deliver wiki
* profile/index.php
* 20.06.2013
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
elseif($route['view']=='edit' ) include($comDir.'edit.php');
elseif($route['view']=='change-password' ) include($comDir.'change.php');
elseif($route['view']!=NULL ) include($comDir.'profile.php');
else include($global->comFolder.'/error/404.php');
?>