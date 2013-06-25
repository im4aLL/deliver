<?php
/*
* Deliver wiki
* wiki/new.php
* 25.06.2013
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

<div class="container">
    
    <div class="page-header">
      <h1>Add new article <small>wiki</small></h1>
    </div>
    
    <form action="" method="post" class="form-horizontal" id="register">
        
        <div class="control-group">
            <label class="control-label" for="category">Category</label>
            <div class="controls">
                <select name="category" id="category">
                	<option value="uncategorized">Uncategorized</option>
                    <option value="create_new">Create new</option>
                </select>
                <span class="new_cat">New: <input type="text" name="new_category"></span>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="title">Title</label>
            <div class="controls">
            	<input type="text" id="title" placeholder="Article title here" class="input-xxlarge" name="title">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
            	<textarea id="description" name="description"></textarea>
            </div>
        </div>
    
        <div class="form-actions">
            <button type="submit" name="kn_wiki" class="btn btn-info">Submit</button>
            <!--<button type="reset" class="btn pull-right">Reset</button>-->
        </div>
    </form>

</div>

<script>
	$(document).ready(function(){
		
		$('.new_cat').hide();
		$('#category').change(function(){
			var selected_category = $(this).val();
			if( selected_category == 'create_new' ) $('.new_cat').show();
			else $('.new_cat').hide();
		});
	
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

<!--jQuery UI-->
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/elrte/css/deliver-wiki/jquery-ui-1.10.3.custom.css">
<script src="<?php echo $global->baseurl ?>lib/elrte/js/jquery-ui-1.10.3.custom.min.js"></script>

<!--elrte-->
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/elrte/css/elrte.full.css">
<script src="<?php echo $global->baseurl ?>lib/elrte/js/elrte.min.js"></script>

<!--elfinder-->
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/elfinder/css/elfinder.css">
<script src="<?php echo $global->baseurl ?>lib/elfinder/js/elfinder.min.js"></script>


<script>

	$(document).ready(function(){
		var opts = {
			absoluteURLs: false,
			cssClass : 'el-rte',
			lang     : 'en',
			height   : 420,
			toolbar  : 'maxi',
			cssfiles : ['<?php echo $global->baseurl ?>lib/elrte/css/elrte-inner.css'],
			fmOpen : function(callback) {				
				$('<div />').elfinder({
					url : '<?php echo $global->baseurl ?>lib/elfinder/connectors/',
					lang : 'en',
					dialog : { width : 800, modal : true, title : 'Files' }, // open in dialog window
					closeOnEditorCallback : true, // close after file select
					editorCallback : callback     // pass callback to file manager
				})
			}
		};
		$('#description').elrte(opts);
	});
</script>


<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>