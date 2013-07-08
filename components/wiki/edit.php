<?php
/*
* Deliver wiki
* wiki/edit.php
* 08.07.2013
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
      <h1>Edit article <small>wiki</small></h1>
    </div>
    
    <?php
		$fetchArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition'=>" WHERE url = '".$comRoute[0]."' AND type = 'wiki'" );
		$db->select($fetchArray);
		$origDataResult = $db->result();
		$origData = $origDataResult[0];
		
		/*echo '<pre>';
		print_r($origData);
		echo '</pre>';*/
	?>
    
    <form action="" method="post" class="form-horizontal" id="kn_wiki_frm">
        
        <div class="control-group">
            <label class="control-label" for="category">Category</label>
            <div class="controls">
                <select name="category" id="category">
                	<option value="uncategorized">Uncategorized</option>
                    <?php
						$qryArray = array( 'tbl_name' => $_this->tableName, 'field' => array('category'), 'method' => PDO::FETCH_OBJ, 'groupby'=>'category' );
						$db->select($qryArray);
						$category = $db->result();
						
						if($db->total() > 0){
							foreach($category as $row){
								echo '<option value="'.$row->category.'"'.(($origData->category==$row->category)?' selected="selected"':'').'>'.ucwords($row->category).'</option>';	
							}
						}
					?>
                    <option value="create_new">Create new</option>
                </select>
                <span class="new_cat">New: <input type="text" name="new_category" id="new_category"></span>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="title">Title</label>
            <div class="controls">
            	<input type="text" id="title" placeholder="Article title here" class="input-xxlarge" name="title" value="<?php echo $origData->title ?>">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
            	<textarea id="description" name="description"><?php echo $origData->description ?></textarea>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="tags">Tags</label>
            <div class="controls">
            	<input type="text" id="tags" placeholder="tags" class="input-xxlarge" name="tags">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="modify_reason">Modify reason</label>
            <div class="controls">
            	<input type="text" id="modify_reason" placeholder="Small brief of modification reason" class="input-block-level" name="modify_reason">
            </div>
        </div>
    
        <div class="form-actions">
            <button type="submit" name="update_kn_wiki" class="btn btn-info">Update</button>
        </div>
    </form>

</div>

<script>
	$(document).ready(function(){
		
		$('.new_cat').hide();
		$('#category').change(function(){
			var selected_category = $(this).val();
			if( selected_category == 'create_new' ) {
				$('.new_cat').show();
				$('#new_category').val('');
			}
			else $('.new_cat').hide();
		});
		
		/*jQuery.validator.addMethod('cvalid', function(value, element, param) {
			if( value != null ){
				
				var request = $.ajax({
					url: "<?php echo $global->baseurl.$comDir ?>_ajax.check.duplicate.category.php",
					type: "GET",
					data: {cat : value}
				});
				
				request.done(function(msg) {
					if( msg == 'true' ) return true;
					else return false;
				});
				
			}
			else return false;
		}, 'Already exists!');*/
	
		$("#kn_wiki_frm").validate({
			rules: {
				new_category: {
					required: true,
					remote: "<?php echo $global->baseurl.$comDir ?>_ajax.check.duplicate.category.php"	
				},
				title: {
					required: true,
					remote: "<?php echo $global->baseurl.$comDir ?>_ajax.check.duplicate.title.php?url=<?php echo $comRoute[0] ?>"
				},
				description: {
					required: true
				}
			},
			messages: {
				new_category: {
					required: "Please enter a category name",
					remote: "Please, try different category name"	
				},
				title: {
					required: "Please give a article title (hopefully unique)",
					remote: "This article is already exists"
				},
				description: {
					required: "Please enter brief description"
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

<!--select2-->
<link rel="stylesheet" href="<?php echo $global->baseurl ?>lib/select2/select2.css">
<script src="<?php echo $global->baseurl ?>lib/select2/select2.js"></script>

<?php
	$db->query("SELECT a.tags FROM ".$config['tbl_prefix']."tags as a 
				LEFT JOIN
					".$config['tbl_prefix']."tag_relate as b ON b.tag_id = a.id
				WHERE
					b.kw_id = '".$origData->id."'");
	
	$pre_tag = array();
	if( $db->total() > 0 ){
		foreach($db->result() as $row){
			$pre_tag[] = '"'.$row->tags.'"';	
		}
	}
?>

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
		
		$("#category").select2();
		<?php
			$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'tags', 'field' => array('tags'), 'method' => PDO::FETCH_OBJ);
			$db->select($qryArray);
			$getted_tags = $db->result();
			
			$tag = array();
			if( count($getted_tags) > 0 ){
				foreach($getted_tags as $row){
					$tag[] = '"'.$row->tags.'"';	
				}
			}
		?>
		$("#tags")<?php if(count($pre_tag)>0) echo '.val(['.implode(',', $pre_tag).'])'; ?>.select2({ tags:[<?php echo implode(",", $tag) ?>], tokenSeparators: [",", " "] });
	});
</script>


<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>