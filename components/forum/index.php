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
elseif($route['view']=='edit' ) include($comDir.'edit.php');
elseif( $route['view'] == 'article' && $comRoute[0]!=NULL ) include($comDir.'single.php'); 
else include($comDir.'listing.php');
?>