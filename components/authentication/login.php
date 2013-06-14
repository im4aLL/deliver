<?php
/*
* Deliver wiki
* authentication/login.php
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
<div class="container login boxed">
    
    <div class="page-header">
      <h1>Sign in <small><?php echo $global->teamName ?> team</small></h1>
    </div>
    
    <form action="" method="post" class="form-horizontal">
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
        <div class="controls">
          <button type="submit" class="btn" name="submit">Sign in</button>
        </div>
      </div>
    </form>
    
    <div class="clearfix">
    	<div class="pull-left"><a href="<?php echo $global->baseurl ?>authentication/new/">Don't have an account?</a></div>
        <div class="pull-right"><a href="<?php echo $global->baseurl ?>authentication/forget/">I forget my password</a></div>
    </div>
    
    <hr>
    <?php include($global->comFolder.'/common/footer.php'); ?>

</div>