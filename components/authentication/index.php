<?php
/*
* Deliver wiki
* common/footer.php
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

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) include($comDir.'_model.php');
elseif($route['view']=='new') include($comDir.'new.php');
elseif($route['view']=='signin') include($comDir.'login.php');
elseif($route['view']=='logout') include($comDir.'logout.php');
elseif($route['view']=='forget') include($comDir.'forget.php');
else include($global->comFolder.'/error/404.php');
?>