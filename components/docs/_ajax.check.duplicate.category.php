<?php
/*
* Deliver wiki
* components/wiki/_ajax.check.duplicate.category.php
* 14.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
define("deliver", true);

include('../../config.php');
include('../../helper/index.php');
include('../../global.settings.php');

$db = new db();
$db->connect($config);

$cat = strtolower(sant_str($_GET['new_category']));

if ( $cat!=NULL ) {
	$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'kn_wiki', 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => ' WHERE category = "'.$cat.'" AND type="wiki"');
	$db->select($qryArray);
	
	if($db->total()>0) echo 'false';
	else echo 'true';
}
else echo 'false';

$db->disconnect();
?>