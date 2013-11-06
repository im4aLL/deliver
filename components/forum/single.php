<?php
/*
* Deliver wiki
* wiki/single.php
* 01.07.2013
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
	<div class="clearfix">
        <div class="pull-left">
        	
			<?php echo '<h2 class="no-mp"><a href="'.$comURL.'"><i class="'.$globalMenu['knowledgebase']['icon'].'"></i> '.$globalMenu['knowledgebase']['name'].'</a></h2>'; ?>
        
        </div>
        <div class="pull-right visible-desktop">
        
            <div class="text-right">   
                <form>
                    <div class="input-append">
                        <input class="span4" id="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword; ?>">
                        <div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown" type="button">Search in <span class="caret"></span></button>
                            <ul class="dropdown-menu text-left src_in">
                                <!--<li class="disabled"><a href="javascript:void(0)">Category</a></li>-->
                                <li><a href="javascript:void(0)" rel="tags">Tags</a></li>
                                <?php
                                    $qryArray = array( 'tbl_name' => $_this->tableName, 'field' => array('category'), 'method' => PDO::FETCH_OBJ, 'groupby'=>'category', 'orderby'=>'category' );
                                    $db->select($qryArray);
                                    $category = $db->result();
                                    
                                    if($db->total() > 0){
                                        foreach($category as $row){
                                            echo '<li><a href="javascript:void(0)" rel="'.$row->category.'">'.ucwords($row->category).'</a></li>';	
                                        }
                                    }
                                ?>
                                <li><a href="javascript:void(0)" rel="all">All</a></li>
                            </ul>
                        </div>
                    </div>
                </form>    
            </div>
            
        </div>
	</div>
    
    <hr>
    
    <div class="article">   
		<?php
            $db->query("SELECT a.*, b.name, b.email, b.avatar, b.created_at as member_since, b.designation, b.id as author_id
                                FROM ".$_this->tableName." as a 
                                LEFT JOIN
                                    ".$config['tbl_prefix']."users as b ON b.id = a.by_id
                                WHERE a.type='kbase' AND a.url='".$comRoute[0]."'");
            if($db->total() == 1){
                $singleArray = $db->result();
                $single = $singleArray[0];
				
				echo '<div class="row">';
					
					echo '<div class="span2 text-right art-info visible-desktop">';
						echo '<div class="profile-img"><img src="'.$global->baseurl.'images/users/r_thumb_'.$single->avatar.'" alt="'.$single->name.'" class="img-circle"></div>';
						echo '<div class="author"><a href="'.getProfileUrl($single->email).'" target="_blank" class="muted"><strong>'.$single->name.'</strong></a></div>';
						echo '<div class="reputation" title="Reputation"><i class="icon-star"></i> '.get_rep($single->author_id).'</div>';
						echo '<div class="member_since muted">member since '.ago(strtotime($single->member_since)).'</div>';
						echo '<div class="author_deg">'.str_replace('-', ' ',$single->designation).'</div>';
						
						echo '<div class="additional-info">';
						echo '<a href="'.$comURL.'?keyword='.$single->category.'&in=tags">@'.ucwords($single->category).'</a> / ';
						echo $single->hit.' <i class="icon-eye-open"></i>';
						echo '</div>';
						
						echo '<div class="published"><strong>Published</strong><br>'.date("jS F, Y", strtotime($single->created_at)).'<br><span class="muted">'.ago(strtotime($single->created_at)).'</span></div>';
					echo '</div>';
					
					echo '<div class="span10">';
						
						// edit link
						if($userData->id == $single->author_id){
							echo '<a class="pull-right tips" href="'.$comURL.'edit/'.$comRoute[0].'/" data-toggle="tooltip" data-placement="bottom" title="update this knowledge base"><small><i class="icon-pencil"></i> edit</small></a>';	
						}
						// edit link
						
						echo '<div class="art-description">';
							echo '<h1 class="art-title">'.$single->title.'</h1>';
							echo '<div class="art-author-info hidden-desktop">by <a href="'.getProfileUrl($single->email).'" target="_blank" class="muted"><strong>'.$single->name.'</strong></a> added '.date("jS F, Y", strtotime($single->created_at)).'</div>';
							echo html_decode($single->description);
							
							if($single->modified_at!='0000-00-00'){
								echo '<hr><div class="art-modify muted">';
								echo '<small><span class="dimmed">last modified '.date("jS F, Y", strtotime($single->modified_at)).'</span><br>'.$single->modify_reason.'</small>';
								echo '</div>';
							}
			
						echo '</div>';
						
						echo '<div class="well">';
						
						$db->query("SELECT a.*, b.email as voter_email, b.name as voter_name
									FROM ".$config['tbl_prefix']."reputation as a
										LEFT JOIN ".$config['tbl_prefix']."users as b ON b.id = a.from_user_id
									WHERE a.for_kn_id = '$single->id' AND a.from_user_id != -1
									GROUP BY a.id");
						
						function is_helpful_current_user($array){
							global $userData;
							foreach($array as $key=>$val){
								if($val->from_user_id == $userData->id ) return true;	
							}
							return false;
						}
						$is_helpful_current_user = is_helpful_current_user($db->result());
						$jsonString = encode(json_encode(array('rep'=>$global->rep_kn, 'to_user_id'=>$single->author_id, 'for_kn_id'=>$single->id, 'from_user_id'=> $userData->id)));
						
						echo '<div class="pull-left '.(($is_helpful_current_user)?'hide':'').' make_helpful_block">
						<strong class="mark_as_helpful">Did you find this helpful?</strong> 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0)" class="helpful" rel="'.$jsonString.'"><i class="icon-ok"></i> Yes</a>
						<span class="loading muted hide"><i class="icon-refresh icon-spin"></i> <span>Submitting ...</span></span>
						&nbsp;&nbsp; <span class="error-occured hide">An error occurred. Please try again/later.</span>
						</div>';
							
						echo '<div class="pull-left '.(($is_helpful_current_user)?'':'hide').' is_helpful_block"><span class="is_helpful">This found helpful by you.</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="helpful is_not_helpful" rel="'.$jsonString.'@@no"><i class="icon-remove"></i> No, this is not!</a>
						<span class="loading muted hide"><i class="icon-refresh icon-spin"></i> <span>Submitting ...</span></span>
						&nbsp;&nbsp; <span class="error-occured hide">An error occurred. Please try again/later.</span>
						</div>';
						
						$datas = array();
						if($db->total()>0){
							foreach($db->result() as $row){
								$datas[] = '<a href="'.getProfileUrl($row->voter_email).'" target="_blank">'.$row->voter_name.'</a>';	
							}
						}
						
						echo '<a href="#userlist" data-toggle="modal" class="pull-right make_helpful_block">'.$db->total().' people found this helpful</a>';
						
						echo '</div>';
						
						if(count($datas)>0) echo '<div class="found_this_helpful">'.implode(", ", $datas).' <i class="icon-thumbs-up"></i> this</div>';
						
						echo '<hr>';
						
					echo '</div>';
					
				echo '</div>';
				
				$db->update($_this->tableName, array('hit'=> ($single->hit + 1)), array('id'=>$single->id));
            }
        ?>
    </div>
        
</div>


<div id="userlist" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Following user found this helpful</h3>
    </div>
    <div class="modal-body">
    	<?php if(count($datas)>0) echo implode(", ", $datas); else echo 'No user found!'; ?>
    </div>
    <div class="modal-footer">
    	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<script>
	
	$(document).ready(function(){
		$('.src_in a').click(function(){
			var keyword = $.trim($('#keyword').val().toLowerCase());
			keyword = keyword.replace(/ /g, "+");
			var search_in = $.trim($(this).attr('rel'));
			var baseurl = '<?php echo $comURL ?>';
			if(keyword.length>0) window.location.href = baseurl+'?keyword='+keyword+'&in='+search_in;
			return false;	
		});
		
		$("#keyword").keypress(function(event) {
			if ( event.which == 13 ) {
				var keyword = $.trim($('#keyword').val().toLowerCase());
				keyword = keyword.replace(/ /g, "+");
				var search_in = 'tags';
				var baseurl = '<?php echo $comURL ?>';
				if(keyword) window.location.href = baseurl+'?keyword='+keyword+'&in='+search_in;
				return false;
			}
		});
		
		$('.make_helpful_block a.helpful').click(function(){
			var jsonData = $(this).attr('rel');
			
			$('.error-occured').addClass('hide');
			$(this).parent().children('.loading').removeClass('hide');
			$(this).addClass('hide');
			
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.helpful.php",
				type: "GET",
				data: {json : jsonData}
			});
			
			request.done(function(msg) {
				if(msg=='true') {
					$('.make_helpful_block').addClass('hide');
					$('.is_helpful_block').removeClass('hide');
					$('.is_helpful_block .helpful').removeClass('hide');
					$('.is_helpful_block .loading').addClass('hide');
				}
				else {
					$('.helpful').removeClass('hide');
					$('.helpful').parent().children('.loading').addClass('hide');
					$('.error-occured').removeClass('hide');
				}
			});
			
			request.fail(function(jqXHR, textStatus) {
				$('.helpful').parent().children('.loading').addClass('hide');
				$('.helpful').removeClass('hide');
			});
			
			return false;	
		});
		
		$('.is_helpful_block a.is_not_helpful').click(function(){
			var jsonData = $(this).attr('rel');
			
			$('.error-occured').addClass('hide');
			$(this).parent().children('.loading').removeClass('hide');
			$(this).addClass('hide');
			
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.helpful.php",
				type: "GET",
				data: {json : jsonData}
			});
			
			request.done(function(msg) {
				if(msg=='true') {
					$('.is_helpful_block').addClass('hide');
					$('.make_helpful_block').removeClass('hide');
					$('.make_helpful_block .helpful').removeClass('hide');
					$('.make_helpful_block .loading').addClass('hide');
					
					$('.make_helpful_block:last').addClass('hide');
				}
				else {
					$('.is_helpful_block').removeClass('hide');
					$('.is_not_helpful').parent().children('.loading').addClass('hide');
					$('.error-occured').removeClass('hide');
				}
			});
			
			request.fail(function(jqXHR, textStatus) {
				$('.is_not_helpful').parent().children('.loading').addClass('hide');
				$('.is_not_helpful').removeClass('hide');
			});
			
			return false;	
		});
		
		
	});
</script>

<?php require_once($comDir.'comment.php'); ?>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>