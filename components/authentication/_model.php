<?php
/*
* Deliver wiki
* components/authentication/_model.php
* 14.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/

$array = array();
$array = $_POST;

if( isset($array['register']) ){
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}
?>