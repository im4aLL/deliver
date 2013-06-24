<?php
/*
* Deliver wiki
* profule/edit.php
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
	$db->query("SELECT * FROM ".$_this->tableName." WHERE email LIKE '".$comRoute[0]."@%'");
	$db->result();
	
	$profileResult = $db->result();
	$profileInfo = $profileResult[0];
	
	$s_p_username = explode("@", $profileInfo->email);
	$profileInfo->username = strtolower($s_p_username[0]);
?>

<div class="container <?php echo $route['component']; ?>">
    
    <div class="page-header">
      <h1>Profile information <small>update</small></h1>
    </div>
    
    <?php
		if( $profileInfo->id != $userData->id ) {
			echo '<div class="alert alert-error">';
			echo 'Oh snap! you don\'t have enough privilege to access this page!';
			echo '</div>';
			
			echo '<hr>';
			require_once($global->comFolder.'/common/footer.php');
			exit();
		}
	?>
    
    <!--profile-->
    <form action="" method="post" class="form-horizontal" id="update" enctype="multipart/form-data">
    
    	<div class="row">
        	<div class="span6">
        
                <!---->
                <div class="control-group">
                    <label class="control-label" for="name">Full Name</label>
                    <div class="controls">
                        <input type="text" id="name" placeholder="Name" name="name" value="<?php echo $profileInfo->name ?>">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input type="text" id="email" placeholder="Email" name="email" value="<?php echo $profileInfo->email ?>" disabled>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="emp_id">Employee ID</label>
                    <div class="controls">
                        <input type="text" id="emp_id" placeholder="Ex- 261" name="emp_id" value="<?php echo $profileInfo->emp_id ?>">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="cell_no">Mobile</label>
                    <div class="controls">
                        <input type="text" id="cell_no" placeholder="0161xxxxxxx" name="cell_no" value="<?php echo $profileInfo->cell_no ?>">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="skype">Skype ID</label>
                    <div class="controls">
                        <input type="text" id="skype" placeholder="ID without @skype.com" name="skype" value="<?php echo $profileInfo->skype ?>">
                    </div>
                </div>
                <!---->
            
            </div>
            <div class="span6 text-right">
            	<img class="openfile" src="<?php echo $global->baseurl ?>images/users/<?php if($profileInfo->avatar==NULL) echo 'default.jpg'; else echo 'r_'.$profileInfo->avatar; ?>" alt="<?php echo $profileInfo->name; ?>">
                <br><small><span class="changePath"></span> <a href="javascript:void(0)" class="openfile">edit avatar</a></small>
                <br><small class="muted">recommended 270 x 350</small>
                <br><input type="file" name="avatar" class="hide" id="avatar">
                <input type="hidden" name="old_avatar" value="<?php echo $profileInfo->avatar; ?>">
                <input type="hidden" name="where_id" value="<?php echo $profileInfo->id; ?>">
                <input type="hidden" name="old_email" value="<?php echo $profileInfo->email; ?>">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="update_info" class="btn btn-success">Update information</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
        
        
    </form>
    <!--profile-->
    
</div>

<script>
	$(document).ready(function(){
		
		$('.openfile').click(function(){
			$('#avatar').click();
			return false;	
		});
		
		$('#avatar').change(function(){
			var ext = $(this).val().split('.').pop();
			ext = ext.toLowerCase();
			
			if( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' ){
				$('.changePath').text($(this).val());	
			}
			else {
				$(this).val('');
				$('.changePath').html('<span class="label label-important">Invalid file type!</span>');
			}	
		});
	
		$("#update").validate({
			rules: {
				name: {
					required: true,
					minlength: 6	
				},
				email: {
					required: true,
					email: true,
					remote: "<?php echo $global->baseurl.$comDir ?>_ajax.check.duplicate.email.php"
				},
				emp_id: {
					required: true,
					number: true	
				},
				cell_no: {
					required: true,
					minlength: 11	
				},
				skype: {
					required: true,
					minlength: 3	
				}
			},
			messages: {
				name: {
					required: "Please enter your full name",
					minlength: "Not your nick name!"	
				},
				email: {
					required: "Enter an email address",
					email: "Please enter a valid email address",
					remote: "Sorry, this email address is already taken"
				},
				emp_id: {
					required: "Please enter your employee ID",
					number: "Must be number ex-250"
				},
				cell_no: {
					required: "Please enter your mobile number",
					minlength: "Your mobile should be 11 char long"	
				},
				skype: {
					required: "Please enter your Skype ID",
					minlength: "Please a valid Skype ID"
				}
			},
			highlight: function(element) {
				$(element).parent().parent().removeClass("success");
				$(element).parent().parent().addClass("error");
			},
			unhighlight: function(element) {
				$(element).parent().parent().removeClass("error");
				$(element).parent().parent().addClass("success");
			},
			submitHandler: function(form) {
				form.submit();
			}
		});
		
	});
</script>


<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>