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
	 		
 		<?php
 			$db->query("SELECT * FROM ".$config['tbl_prefix']."users WHERE state = 1 ORDER by name ASC");
 			if( $db->total() > 0 ){
 				foreach($db->result() as $user){
 					echo '<div class="span2">'; 
	 					echo '<div class="user">'; 

	 						$pp_url = explode("@", $user->email);

	 						echo '<a href="'.$global->baseurl.'profile/'.strtolower($pp_url[0]).'/">';
		 						
		 						echo '<img src="'.$global->baseurl.'images/users/'.(($user->avatar=='default.jpg' || $user->avatar == null)?'default.jpg':'r_'.$user->avatar).'" alt="'.$user->name.'" />';

		 						echo '<div class="profile">'; 
		 							echo '<span class="name">'.$user->name.'</span>';
		 							echo '<span class="designation">'.beautifyWord($user->designation).'</span>';
		 						echo '</div>';

	 						echo '</a>';

	 					echo '</div>';
 					echo '</div>';
 				}
 			}
 		?>
	 </div> 
</div>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>