<?php
/*
* Deliver wiki
* common/footer.php
* 13.06.2013
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
<div class="container create boxed">
    
    <div class="page-header">
      <h1>Create new account <small><?php echo $global->teamName ?> team</small></h1>
    </div>
    
    <form action="" method="post" class="form-horizontal" id="register">
        
        <div class="control-group">
            <label class="control-label" for="name">Full Name</label>
            <div class="controls">
            	<input type="text" id="name" placeholder="Name" name="name">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="email">Email</label>
            <div class="controls">
            	<input type="text" id="email" placeholder="Email" name="email">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="password">Password</label>
            <div class="controls">
            	<input type="password" id="password" placeholder="Password" name="password">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="cpassword">Confirm password</label>
            <div class="controls">
            	<input type="password" id="cpassword" placeholder="Retype password" name="cpassword">
            </div>
        </div>
        
        <hr>
        
        <div class="control-group">
            <label class="control-label" for="emp_id">Employee ID</label>
            <div class="controls">
            	<input type="text" id="emp_id" placeholder="Ex- 261" name="emp_id">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="cell_no">Mobile</label>
            <div class="controls">
            	<input type="text" id="cell_no" placeholder="0161xxxxxxx" name="cell_no">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="skype">Skype ID</label>
            <div class="controls">
            	<input type="text" id="skype" placeholder="ID without @skype.com" name="skype">
            </div>
        </div>
    
        <div class="form-actions">
            <button type="submit" name="register" class="btn btn-primary">Register</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
    </form>
    
    <div class="clearfix">
    	<div class="pull-left"><a href="<?php echo $global->baseurl ?>authentication/signin/">Already have an account? Sign in</a></div>
    </div>
    
    <hr>
    <?php include($global->comFolder.'/common/footer.php'); ?>

</div>

<script>
	$(document).ready(function(){
	
		$("#register").validate({
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
					required: "Please enter you employee ID",
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