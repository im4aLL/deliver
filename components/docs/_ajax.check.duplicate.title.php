<?php
/*
* Deliver wiki
* components/wiki/_ajax.check.duplicate.title.php
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

$title = safe_string($_GET['title']);
if(isset($_GET['url'])) $url = safe_string($_GET['url']);
else $url = NULL;

if ( $title!=NULL ) {
	$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'kn_wiki', 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => ' WHERE title = "'.$title.'"  AND type="wiki" '.(($url!=NULL)?" AND url !='".$url."'":"").' ');
	$db->select($qryArray);
	
	if($db->total()>0) echo 'false';
	else echo 'true';
}
else echo 'false';

$db->disconnect();
?>