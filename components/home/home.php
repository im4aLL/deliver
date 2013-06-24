<?php
/*
* Deliver wiki
* home/home.php
* 19.06.2013
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
    
    <div class="row profile_info">
    	<div class="span3">
        	<div class="thumbnail"><img src="<?php echo $global->baseurl ?>images/users/<?php if($userData->avatar!=NULL) echo 'r_'.$userData->avatar; else echo 'default.jpg'; ?>" alt="<?php echo $userData->name; ?>"></div>
        </div>
        <div class="span9">
            <dl class="dl-horizontal">
                <dt>name</dt>
                <dd><a href="<?php echo $global->baseurl ?>profile/<?php echo $userData->username ?>/"><?php echo $userData->name; ?></a></dd>
                
                <dt>email</dt>
                <dd><?php echo $userData->email; ?></dd>
                
                <dt>employee ID</dt>
                <dd><?php echo $userData->emp_id; ?></dd>
                
                <dt>cell</dt>
                <dd><?php echo $userData->cell_no; ?></dd>
                
                <dt>skype</dt>
                <dd><?php echo $userData->skype; ?></dd>
                
                <dt>usergroup</dt>
                <dd><?php echo $userData->designation; ?></dd>
                
                <dt>member since</dt>
                <dd><?php echo ago( strtotime($userData->created_at) ); ?></dd>
                
            </dl>
        </div>
    </div>
    
    <hr>
    <div class="row">
    	<?php
			foreach($globalMenu as $menuKey=>$field){
				if($menuKey=='wiki' || $menuKey=='kbase' || $menuKey=='process' || $menuKey=='team'){
					echo '<div class="span3 grids">';
					echo '<a class="main" href="'.$field['link'].'"><i class="big '.$field['icon'].'"></i>';
					echo '<span>'.$field['name'].'</span></a>';
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