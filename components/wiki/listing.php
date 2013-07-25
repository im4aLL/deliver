<?php
/*
* Deliver wiki
* wiki/listing.php
* 27.06.2013
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

<div class="container wiki">
	
    <?php echo '<h2><a href="'.$comURL.'"><i class="'.$globalMenu['wiki']['icon'].'"></i> '.$globalMenu['wiki']['name'].'</a></h2>'; ?>
    
    <hr>
    
	<div class="row">
    	<div class="span3">
        	<?php require_once($comDir.'navlist.php'); ?>
        </div>
        <div class="span9">
        	<?php
            	if( $route['view'] == 'article' && $comRoute[0]!=NULL ) include($comDir.'single.php');
				else include($comDir.'wiki.home.php');
			?>
        </div>
    </div>
    
</div>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>