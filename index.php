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
<title><?php echo $global->pageTitle; ?></title>
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/bootstrap/css/bootstrap-responsive.min.css">
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $global->baseurl ?>css-js/style.css">

<script src="<?php echo $global->baseurl ?>lib/jquery/jquery-1.10.1.min.js"></script>
<script src="<?php echo $global->baseurl ?>lib/validate/jquery.validate.min.js"></script>
<script src="<?php echo $global->baseurl ?>css-js/main.js"></script>
</head>

<body>
	<?php
		if( $pageURL != NULL ){
			if( file_exists($comDirIndex) ){
				if($route['component']=='authentication' && !$_SESSION['logged']) include($comDirIndex);
				elseif($_SESSION['logged']) include($comDirIndex);
				else include($global->comFolder.'/error/404.php');
			}
			else include($global->comFolder.'/error/404.php');	
		}
		else {
			if($_SESSION['logged'])	echo 'welcome!';
			else include($global->comFolder.'/authentication/login.php');
		}
	?>
</body>
</html>
<?php
$db->disconnect();
?>