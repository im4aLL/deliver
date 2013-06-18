<?php
/*
* Deliver wiki
* authentication/index.php
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

$_this = new StdClass;
$_this->tableName = $config['tbl_prefix'].'users';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) include($comDir.'_model.php');
elseif($route['view']=='new' && !$_SESSION['logged'] ) include($comDir.'new.php');
elseif($route['view']=='signin' && !$_SESSION['logged'] ) include($comDir.'login.php');
elseif($route['view']=='logout' && $_SESSION['logged']) include($comDir.'logout.php');
elseif($route['view']=='forget' && !$_SESSION['logged']) include($comDir.'forget.php');
elseif($route['view']=='activation' && !$_SESSION['logged']) include($comDir.'activation.php');
else include($global->comFolder.'/error/404.php');
?>