<?php
/*
* Deliver wiki
* process/index.php
* 25.07.2013
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
<?php require_once($global->comFolder.'/common/header.php'); ?>

<div class="container <?php echo $route['component']; ?>">
	<div class="row">
	 	<div class="span-12">Hey! <?php echo $userData->name ?>, <br><br>Team page is in under construction. Please come back later on this page! <br><br>Thank you <br>Hogarth team Dhaka</div>
	 </div> 
</div>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>