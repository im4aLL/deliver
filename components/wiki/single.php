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
        	
			<?php echo '<h2 class="no-mp"><a href="'.$comURL.'"><i class="'.$globalMenu['wiki']['icon'].'"></i> '.$globalMenu['wiki']['name'].'</a></h2>'; ?>
        
        </div>
        <div class="pull-right">
        
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
    
    <div class="article">   
		<?php
            $db->query("SELECT a.*, b.name, b.email, b.avatar, b.created_at as member_since, b.designation, b.id as author_id
                                FROM ".$_this->tableName." as a 
                                LEFT JOIN
                                    ".$config['tbl_prefix']."users as b ON b.id = a.by_id
                                WHERE a.type='wiki' AND a.url='".$comRoute[0]."'");
            if($db->total() == 1){
                $singleArray = $db->result();
                $single = $singleArray[0];
				
				echo '<div class="row">';
					
					echo '<div class="span2 text-right art-info">';
						echo '<div class="profile-img"><img src="'.$global->baseurl.'images/users/r_thumb_'.$single->avatar.'" alt="'.$single->name.'" class="img-circle"></div>';
						echo '<div class="author"><a href="'.getProfileUrl($single->email).'" target="_blank" class="muted"><strong>'.$single->name.'</strong></a></div>';
						echo '<div class="member_since muted">member since '.ago(strtotime($single->member_since)).'</div>';
						echo '<div class="author_deg">'.$single->designation.'</div>';
						
						echo '<div class="additional-info">';
						echo '<a href="'.$comURL.'?keyword='.$single->category.'&in=tags">@'.ucwords($single->category).'</a> / ';
						echo $single->hit.' <i class="icon-eye-open"></i>';
						echo '</div>';
						
						echo '<div class="published"><strong>Published</strong><br>'.date("jS F, Y", strtotime($single->created_at)).'<br><span class="muted">'.ago(strtotime($single->created_at)).'</span></div>';
					echo '</div>';
					
					echo '<div class="span10">';
						echo '<div class="art-description">';
							echo '<h1 class="art-title">'.$single->title.'</h1>';
							echo html_decode($single->description);
							
							if($single->modified_at!='0000-00-00'){
								echo '<hr><div class="art-modify muted">';
								echo '<small><span class="dimmed">last modified '.date("jS F, Y", strtotime($single->modified_at)).'</span><br>'.$single->modify_reason.'</small>';
								echo '</div>';
							}
			
						echo '</div>';
						
						echo '<div class="well">';
						echo '<div class="pull-left make_helpful_block">
							<strong class="mark_as_helpful">Did you find this helpful?</strong> 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:void(0)" class="helpful" rel="'.encode(json_encode(array('rep'=>$global->rep_wiki, 'to_user_id'=>$single->author_id, 'for_kn_id'=>$single->id, 'from_user_id'=> $userData->id))).'"><i class="icon-ok"></i> Yes</a>
							<span class="loading muted hide"><i class="icon-refresh icon-spin"></i> <span>Submitting ...</span></span>
							&nbsp;&nbsp; <span class="error-occured hide">An error occurred. Please try again/later.</span>
							</div>';
						echo '<div class="pull-left hide is_helpful_block"><span class="is_helpful">This found helpful by you.</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="helpful is_not_helpful"><i class="icon-remove"></i> No, this is not!</a></div>';
						echo '<div class="pull-right make_helpful_block">11 people found this helpful</div>';
						echo '</div>';
						
						echo '<hr>';
						
					echo '</div>';
					
				echo '</div>';
				
				$db->update($_this->tableName, array('hit'=> ($single->hit + 1)), array('id'=>$single->id));
            }
        ?>
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
		
		$('.helpful').click(function(){
			var jsonData = $(this).attr('rel');
			
			$('.error-occured').addClass('hide');
			$(this).parent().children('.loading').removeClass('hide');
			$(this).hide();
			
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.helpful.php",
				type: "GET",
				data: {json : jsonData}
			});
			
			request.done(function(msg) {
				if(msg=='true') {
					$('.make_helpful_block').remove();
					$('.is_helpful_block').removeClass('hide');
				}
				else {
					$('.helpful').show();
					$('.helpful').parent().children('.loading').addClass('hide');
					$('.error-occured').removeClass('hide');
				}
			});
			
			request.fail(function(jqXHR, textStatus) {
				$('.helpful').parent().children('.loading').addClass('hide');
				$('.helpful').show();
			});
			
			return false;	
		});
	});
</script>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>