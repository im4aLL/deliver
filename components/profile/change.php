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
            <button type="submit" name="update_pass" class="btn btn-inverse">Change password</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
    </form>
    <!--change password-->

</div>

<script>
	$(document).ready(function(){
	
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
				password: {
					required: true,
					minlength: 5
				},
				cpassword: {
					required: true,
					minlength: 5,
					equalTo: "#password"
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
				password: {
					required: "Please enter a password",
					minlength: "Password must be at least 5 char long"
				},
				cpassword: {
					required: "Please retype the password",
					minlength: "Password must be at least 5 char long",
					equalTo: "Doesn't match with above password"
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