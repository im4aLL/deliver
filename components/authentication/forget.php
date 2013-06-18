<?php
/*
* Deliver wiki
* common/footer.php
* 18.06.2013
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
<div class="container login boxed">
    
    <div class="page-header">
      <h1>Password retrieval <small><?php echo $global->teamName ?> team</small></h1>
    </div>
    
    <form action="" method="post" class="form-horizontal" id="forget">
      <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
        <div class="controls">
          <input type="text" id="inputEmail" placeholder="Email" name="email">
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn" name="forget">Send me my password</button>
        </div>
      </div>
    </form>
    
    <div class="clearfix">
    	<div class="pull-left"><a href="<?php echo $comURL ?>signin/">Sign in</a></div>
    </div>
    
    <hr>
    <?php include($global->comFolder.'/common/footer.php'); ?>

</div>

<script>
	$(document).ready(function(){
	
		$("#forget").validate({
			rules: {
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				email: {
					required: "Enter an email address",
					email: "Please enter a valid email address",
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