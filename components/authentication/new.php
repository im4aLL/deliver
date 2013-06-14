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
    
    <form action="" method="post" class="form-horizontal">
        
        <div class="control-group">
            <label class="control-label" for="name">Full Name</label>
            <div class="controls">
            	<input type="text" id="name" placeholder="Name" name="name">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="inputEmail">Email</label>
            <div class="controls">
            	<input type="text" id="inputEmail" placeholder="Email" name="email">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
            	<input type="password" id="inputPassword" placeholder="Password" name="password">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="cpassword">Confirm password</label>
            <div class="controls">
            	<input type="password" id="cpassword" placeholder="Retype password">
            </div>
        </div>
    
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
    </form>
    
    <div class="clearfix">
    	<div class="pull-left"><a href="<?php echo $global->baseurl ?>authentication/signin/">Already have an account? Sign in</a></div>
    </div>
    
    <hr>
    <?php include($global->comFolder.'/common/footer.php'); ?>

</div>