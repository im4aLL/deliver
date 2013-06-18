<?php
/*
* Deliver wiki
* components/redirect/error.php
* 17.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");
?>
<div class="container error">
	<i class="icon-frown"></i>
	<h1>Oops, you've got an error!</h1>
    <?php 
		if( isset($_SESSION['msg']['main']) && $_SESSION['msg']['main']!=NULL ) 
			echo '<h3>'.$_SESSION['msg']['main'].'</h3>'; 
		
		if( isset($_SESSION['msg']['more']) && $_SESSION['msg']['more']!=NULL )
			echo '<p class="info-msg">'.$_SESSION['msg']['more'].'</p>';
			
		if( isset($_SESSION['msg']['rurl']) && $_SESSION['msg']['rurl']!=NULL )
			echo redirect($_SESSION['msg']['rurl'], ((isset($_SESSION['msg']['timeout']) && $_SESSION['msg']['timeout']>0)?$_SESSION['msg']['timeout']:120) );
	?>
</div>
<?php unset($_SESSION['msg']); ?>