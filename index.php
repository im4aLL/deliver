<?php
/*
* Deliver wiki
* index.php
* 13.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
session_start();
define("deliver", true);

include('config.php');
include('helper/index.php');
include('global.settings.php');
include('route.php');

$db = new db();
$db->connect($config);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php if($pageURL!=NULL && isset($route['component'])) echo preTitle($route['component']); echo $global->pageTitle; ?></title>
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/bootstrap/css/bootstrap-responsive.min.css">
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/font-awesome/css/font-awesome.min.css">
<!--<link rel="stylesheet" href="<?php echo $global->baseurl ?>css-js/flat.css">-->
<link rel="stylesheet" href="<?php echo $global->baseurl ?>css-js/style.css">

<script src="<?php echo $global->baseurl ?>lib/jquery/jquery-<?php if($route['component']=='wiki' && ($route['view']=='new' || $route['view']=='edit' )) echo '1.7.2'; else echo '1.10.1'; ?>.min.js"></script>
<script src="<?php echo $global->baseurl ?>lib/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $global->baseurl ?>lib/validate/jquery.validate.min.js"></script>
<script src="<?php echo $global->baseurl ?>css-js/main.js"></script>
</head>

<body>
	<?php
		// user info
		if( $_SESSION['logged_user'] > 0 ){
			$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'users', 'method' => PDO::FETCH_OBJ, 'condition' => ' WHERE id = "'.$_SESSION['logged_user'].'"');
			$db->select($qryArray);
			$p_userData = $db->result();
			$userData = $p_userData[0];
			
			$p_username = explode("@", $userData->email);
			$userData->username = strtolower($p_username[0]);
		}
		// user info
				
		if( $pageURL != NULL ){
			if( file_exists($comDirIndex) ){
				if($route['component']=='authentication') include($comDirIndex);
				elseif($_SESSION['logged'] && $userData->state == 1) include($comDirIndex);
				elseif($_SESSION['logged'] && $userData->state == 2) include($global->comFolder.'/redirect/pending.php');
				else echo redirect($global->baseurl.'authentication/signin/',0,true);
			}
			else include($global->comFolder.'/error/404.php');	
		}
		else {
			if($_SESSION['logged'])	{				
				if( $userData->state == 2 ) include($global->comFolder.'/redirect/pending.php');
				elseif( $userData->state == 1 ) echo redirect($global->baseurl.'home/',0,true);
			}
			else echo redirect($global->baseurl.'authentication/signin/',0,true);
		}
	?>
</body>
</html>
<?php
$db->disconnect();
?>