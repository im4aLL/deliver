<?php
/*
* Deliver wiki
* components/wiki/_ajax.helpful.php
* 01.07.2013
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

if( strstr($_GET['json'], "@@no") ){
	
	$encoded = decode( str_replace(array(" ", "@@no"), array("+",""), safe_string($_GET['json'])) );
	$getted = json_decode($encoded);
	
	foreach($getted as $key=>$val){
		$data[$key]	= (int) $val;
	}
	
	if( $data['to_user_id'] == $data['from_user_id'] ){
		echo 'false';	
	}
	else {
		$deleted = $db->delete($config['tbl_prefix'].'reputation', $data);
		if($deleted['affectedRow'] == 1) echo 'true';
		else echo 'false';
	}
	
}
else {

	$encoded = decode( str_replace(" ", "+", safe_string($_GET['json'])) );
	$getted = json_decode($encoded);
	
	foreach($getted as $key=>$val){
		$data[$key]	= (int) $val;
	}
	
	if( $data['to_user_id'] == $data['from_user_id'] ){
		echo 'false';	
	}
	else {
		$inserted = $db->insert($config['tbl_prefix'].'reputation', $data, array('to_user_id', 'for_kn_id', 'from_user_id'));
		if($inserted['affectedRow'] == 1) echo 'true';
		else echo 'false';
	}
	
}

$db->disconnect();
?>