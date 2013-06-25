<?php
/*
* Deliver wiki
* wiki/index.php
* 25.06.2013
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
$_this->tableName = $config['tbl_prefix'].'kn_wiki';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) include($comDir.'_model.php');
elseif($route['view']=='new' ) include($comDir.'new.php');
else include($comDir.'listing.php');
?>