<?php
/*
* Deliver wiki
* profule/change.php
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

<div class="container <?php echo $route['component']; ?>">
    
    <div class="page-header">
      <h1>Change <small>password</small></h1>
    </div>
    
    <!--change password-->
    <form action="" method="post" class="form-horizontal" id="changepass">
        
        <div class="control-group">
            <label class="control-label" for="oldpassword">Old Password</label>
            <div class="controls">
                <input type="password" id="oldpassword" placeholder="Old Password" name="oldpassword">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="password">New Password</label>
            <div class="controls">
                <input type="password" id="password" placeholder="New Password" name="password">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="cpassword">Confirm new password</label>
            <div class="controls">
                <input type="password" id="cpassword" placeholder="Retype password" name="cpassword">
            </div>
        </div>
    
        <div class="form-actions">
        	<input type="hidden" name="email" value="<?php echo $userData->email ?>">
            <button type="submit" name="update_pass" class="btn btn-inverse">Change password</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
    </form>
    <!--change password-->

</div>

<script>
	$(document).ready(function(){
		
		$.validator.addMethod("notEqual", function(value, element, param) {
		  return this.optional(element) || value != $(param).val();
		}, "Please specify a different value");
	
		$("#changepass").validate({
			rules: {
				oldpassword: {
					required: true,
					minlength: 5
				},
				password: {
					required: true,
					minlength: 5,
					notEqual: "#oldpassword"
				},
				cpassword: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				}
			},
			messages: {
				oldpassword: {
					required: "Please enter your old password",
					minlength: "Password must be at least 5 char long"
				},
				password: {
					required: "Please enter new password",
					minlength: "Password must be at least 5 char long",
					notEqual: "Your new password is same as old password"
				},
				cpassword: {
					required: "Please retype your new password",
					minlength: "Password must be at least 5 char long",
					equalTo: "Doesn't match with above password"
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