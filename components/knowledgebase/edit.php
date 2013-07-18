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
      <h1>Edit post <small>knowledge base</small></h1>
    </div>
    
    <?php		
		$fetchArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition'=>" WHERE url = '".$comRoute[0]."' AND type = 'kbase'" );
		$db->select($fetchArray);
		$origDataResult = $db->result();
		$origData = $origDataResult[0];
		
		/*echo '<pre>';
		print_r($origData);
		echo '</pre>';*/
	?>
    
    <form action="" method="post" class="form-horizontal" id="update_kn_wiki_frm">
        
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
                <button id="revert_tags" class="btn tips" data-toggle="tooltip" title="Revert back to old"><i class="icon-undo"></i></button>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="modify_reason">Modify reason</label>
            <div class="controls">
            	<input type="text" id="modify_reason" placeholder="Small brief of modification reason" class="input-block-level" name="modify_reason" data-provide="typeahead" data-items="4" data-source='["updated description", "updated tags", "Category updated", "title updated", "added more description"]' autocomplete="off">
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
	
		$("#update_kn_wiki_frm").validate({
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
				},
				modify_reason: {
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
				},
				modify_reason: {
					required: "Please commit the changes"
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
	$db->query("SELECT a.tags, a.id FROM ".$config['tbl_prefix']."tags as a 
				LEFT JOIN
					".$config['tbl_prefix']."tag_relate as b ON b.tag_id = a.id
				WHERE
					b.kw_id = '".$origData->id."'");
	
	$pre_tag = array();
	$pre_tag_text = array();
	if( $db->total() > 0 ){
		foreach($db->result() as $row){
			//$pre_tag[]= '{id: "'.$row->id.'", text: "'.$row->tags.'"}';
			$pre_tag[]= '{id: "'.$row->tags.'", text: "'.$row->tags.'"}';	
		}
	}
?>

<script>

	function getActualId(text, array){
		for(var i=0; i<array.length; i++){
			if(array[i].text==text) return array[i].id;	
		}
	}

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
			$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'tags', 'field' => array('id, tags'), 'method' => PDO::FETCH_OBJ);
			$db->select($qryArray);
			$getted_tags = $db->result();
			
			$all_tag = array();
			$tag = array();
			if( count($getted_tags) > 0 ){
				foreach($getted_tags as $row){
					$all_tag[] = '{id: "'.$row->id.'", text: "'.$row->tags.'"}';
					$tag[] = '{id: "'.$row->tags.'", text: "'.$row->tags.'"}';
				}
			}
		?>
		
		var preTags = [<?php echo implode(',', $pre_tag) ?>];
		var allTags = [<?php echo implode(',', $tag) ?>];
		var allTagsWithId = [<?php echo implode(',', $all_tag) ?>];
		
		$("#tags").select2({ tags:allTags, tokenSeparators: [",", " "] });
		$("#tags").select2('data', preTags);
		$("#tags").change(function(e) {
			if( e.removed ) {
				var topic_relate = <?php echo $origData->id; ?>;
				if(typeof(e.removed.id) != 'undefined'){
					if(confirm("Remove `"+e.removed.text+"` tag?")){
						$.ajax({
						  type: "POST",
						  url: "<?php echo $global->baseurl.$comDir ?>_ajax.del.tag.php",
						  data: { tag_id: getActualId(e.removed.id, allTagsWithId), kw_id: topic_relate }
						});		
					}
				}
			}
		});
		
		$('#revert_tags').click(function(){
			$("#tags").select2('data', preTags);
			return false;	
		});
	});
</script>


<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>