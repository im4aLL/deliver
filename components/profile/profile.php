<?php
/*
* Deliver wiki
* profile/profile.php
* 20.06.2013
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

<?php
	//$qryArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE email LIKE '".$route['view']."@%'" );
	//$db->select($qryArray);
	
	$db->query("SELECT * FROM ".$_this->tableName." WHERE email LIKE '".$route['view']."@%'");
	$db->result();
	
	$profileResult = $db->result();
	$profileInfo = $profileResult[0];
	
	$s_p_username = explode("@", $userData->email);
	$profileInfo->username = strtolower($s_p_username[0]);
?>

<!--container-->
<div class="container <?php echo $route['component']; ?>">
    
    <div class="row profile_info">
    	<div class="span3">
        	<div class="thumbnail"><img src="<?php echo $global->baseurl ?>images/users/default.jpg" alt="<?php echo $profileInfo->name; ?>"></div>
        </div>
        <div class="span9">
            <dl class="dl-horizontal">
                <dt>name</dt>
                <dd><a href="<?php echo $global->baseurl ?>profile/<?php echo $profileInfo->username ?>/"><?php echo $profileInfo->name; ?></a></dd>
                
                <dt>email</dt>
                <dd><?php echo $profileInfo->email; ?></dd>
                
                <dt>employee ID</dt>
                <dd><?php echo $profileInfo->emp_id; ?></dd>
                
                <dt>cell</dt>
                <dd><?php echo $profileInfo->cell_no; ?></dd>
                
                <dt>skype</dt>
                <dd><?php echo $profileInfo->skype; ?></dd>
                
                <dt>usergroup</dt>
                <dd><?php echo $profileInfo->designation; ?></dd>
                
                <dt>member since</dt>
                <dd><?php echo ago( strtotime($profileInfo->created_at) ); ?></dd>
                
                <dt>&nbsp;</dt>
                <dd>&nbsp;</dd>

                <?php
					if($profileInfo->id == $userData->id){
						echo '<dt>&nbsp;</dt>';
						echo '<dd><a class="btn" href="'.$comURL.'edit/'.$profileInfo->username.'/">Update profile information</a></dd>';	
					}
				?>
                
            </dl>
        </div>
    </div>
    
</div>
<!--container-->

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>