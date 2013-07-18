<?php
/*
* Deliver wiki
* kbase/comment.php
* 15.07.2013
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

<div class="container comments" id="comment-panel">
	
    <div class="user_comments">
    <?php
		$db->query('SELECT a.*, 
						b.id as commenter_id, b.name,b.email,b.avatar,
						SUM( CASE WHEN c.rep >0 THEN 1 ELSE 0 END ) AS up_vote, SUM( CASE WHEN c.rep <0 THEN 1 ELSE 0 END ) AS down_vote,
						GROUP_CONCAT( d.email, "::", d.name, "::", IF(c.rep>0,"up","down") ) as voted_users
						FROM '.$config['tbl_prefix'].'comments as a 
							LEFT JOIN '.$config['tbl_prefix'].'users as b ON b.id = a.by_user_id
							LEFT JOIN '.$config['tbl_prefix'].'reputation as c ON c.for_comment_id = a.id
							LEFT JOIN '.$config['tbl_prefix'].'users as d ON d.id = c.from_user_id
						WHERE a.to_id = "'.$single->id.'"
						GROUP BY a.id');
		$comments = $db->result();
		
		if($db->total() > 0){
			
			foreach($comments as $key=>$row){
				?>
                <div class="row" id="comment-<?php echo $row->id; ?>">
                    <div class="span2">
                        <div class="pinfo">
                            <?php 
                                echo '<img src="'.$global->baseurl.'images/users/r_thumb_'.$row->avatar.'" alt="'.$row->name.'" class="img-circle">'; 
                            ?>
                        </div>
                    </div>
                    <div class="span10">
                        <div class="comment">
                        	<span class="loading_state icon-refresh icon-spin hide"></span>
							<div class="info">
                            	<span class="left_arrow"></span>
                            	<span class="author_name"><?php echo '<a href="'.getProfileUrl($row->email).'" target="_blank">'.$row->name.'</a>'; ?></span>
                            	
                                <span class="added_time muted"><small>
									<?php 
										if($row->modified_at == '0000-00-00 00:00:00')
											echo ago(strtotime($row->created_at), true);
										else
											echo 'edited '.ago(strtotime($row->modified_at), true);
									?>
                                </small></span>
                                
                                <span class="pull-right moderation_menu">
                                	<?php if( ($userData->id == $row->commenter_id) || $userData->usergroup=='Administrator' ) { ?><a href="javascript:void(0)" class="comment_edit"><i class="icon-edit"></i> Edit</a><?php } ?>
                                    <?php if($userData->usergroup=='Administrator') { ?><a href="javascript:void(0)" class="comment_delete"><i class="icon-remove"></i> Delete</a><?php } ?>
                                </span>
                            </div>

                            <div class="commit">
								<span class="full_commit" data-id="<?php if( ($userData->id == $row->commenter_id) || $userData->usergroup=='Administrator' ) echo $row->id; else echo '0'; ?>"><?php echo html_decode($row->comment); ?></span>
                                <?php
									$jsonString = encode(json_encode(array('rep'=>$global->rep_comment_up, 'to_user_id'=>$row->commenter_id, 'for_comment_id'=>$row->id, 'from_user_id'=> $userData->id)));
									$jsonString2 = encode(json_encode(array('rep'=>$global->rep_comment_down, 'to_user_id'=>$row->commenter_id, 'for_comment_id'=>$row->id, 'from_user_id'=> $userData->id)));
								?>
                            	<span class="action_buttons">
                                	<a href="javascript:void(0)" class="up_vote<?php if($row->up_vote>0) echo ' added'; ?>" rel="<?php echo $jsonString; ?>"><i class="icon-thumbs-up"></i> <span><?php echo $row->up_vote; ?></span></a>
                                	<a href="javascript:void(0)" class="down_vote<?php if($row->down_vote>0) echo ' added'; ?>" rel="<?php echo $jsonString2; ?>@@no"><i class="icon-thumbs-down"></i> <span><?php echo $row->down_vote; ?></span></a> 
                                </span>
                                
                                <?php
									if($row->voted_users!=NULL){
										$voted_user_array = explode(",", $row->voted_users);
										echo '<ul class="vlist">';
										foreach($voted_user_array as $voted_user_details){
											$exploring_voted_user = explode("::", $voted_user_details);
											echo '<li>';
											if($exploring_voted_user[2]=='up') echo '<i class="icon-long-arrow-up"></i> ';
											else echo '<i class="icon-long-arrow-down"></i> ';
											echo '<a href="'.getProfileUrl($exploring_voted_user[0]).'" target="_blank">'.$exploring_voted_user[1].'</a>';
											echo '</li>';
										}
										echo '</ul>';
									}
								?>
                            </div>
                            
                        </div>
                       	
                    </div>
                </div>
                <?php
			} // foreach loop
		} // if total condition
	?>
    </div>
	
    <div class="row" id="comment-add">
    	<div class="span2">
        	<div class="pinfo">
				<?php 
                    echo '<img src="'.$global->baseurl.'images/users/r_thumb_'.$userData->avatar.'" alt="'.$userData->name.'" class="img-circle">'; 
                    echo '<br><a href="'.getProfileUrl($userData->email).'" target="_blank" class="muted"><strong>'.$userData->name.'</strong></a>';
                ?>
            </div>
        </div>
        <div class="span10">
        	<form id="comment_form" method="post">
            	<textarea rows="4" class="input-block-level" name="comment"></textarea>
                <button class="btn" type="submit" name="post_comment" id="post_comment"><span class="loading_state icon-refresh icon-spin hide"></span><span class="calloutTitle">Post comment</span></button>
                <input type="hidden" name="to_id" value="<?php echo $single->id; ?>">
                <span class="error_state"></span>
            </form>
        </div>
    </div>
    
</div>

<script>
	
	$(document).ready(function(){
		
		// Commnet in edit mode
		var old_comment;
		$('#comment-panel').on('click', '.comment_edit', function(){
			var cDiv = $(this).parent().parent().parent().children('.commit');
			cDiv.addClass('edit_mode');
			var comment = cDiv.children('.full_commit').html();
			old_comment = comment;
			cDiv.children('.full_commit').html('<textarea rows="4" class="input-block-level" id="user_comment">'+comment+'</textarea><button class="btn btn-info" type="submit" id="update_comment">Update</button> <button class="btn pull-right" type="submit" id="cancle_update_comment">Cancle</button></span>');
			$(this).hide();
			return false;	
		});
		
		// Cancle comment updating
		$('#comment-panel').on('click', '#cancle_update_comment', function(){
			var comment = $(this).parent().children('#user_comment').val();
			$(this).parent().parent().removeClass('edit_mode');
			$(this).parent().parent().parent().children('.info').children('.moderation_menu').children('.comment_edit').show();
			$(this).parent().html( (old_comment!=null)?old_comment:comment );
			return false;	
		});
		
		// Updating comment
		var is_changed = false;
		$('#comment-panel').on('keypress', 'textarea', function(){
			is_changed = true;
		});
		
		$('#comment-panel').on('click', '#update_comment', function(){
			if(!is_changed) {
				$('#cancle_update_comment').click();
				return false;
			}
			
			var commentData = $(this).parent().children('#user_comment').val();
			var update_on_id = $(this).parent().attr('data-id');
			
			var elem_loading = $(this).parent().parent().parent().children('.loading_state');
			elem_loading.removeClass('hide');
			
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.update.comment.php",
				type: "POST",
				data: {comment : commentData, id : update_on_id}
			});
			
			request.done(function(msg) {
				$('#cancle_update_comment').click();
				is_changed = false;
			});
			
			request.always(function(){ elem_loading.addClass('hide'); });
			
			return false;	
		});
		// Updating comment
		
		// Comment like and dislike
		$('#comment-panel').on('click', '.action_buttons a', function(){
			$(this).parent().children('a').removeClass('added');
			$(this).addClass('added');
			var counted = $(this).children('span').text();
			var elem = $(this);
			
			var jsonData = $(this).attr('rel');
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.comment.updown.php",
				type: "GET",
				data: {json : jsonData}
			});
			
			request.done(function(msg) {
				if(msg=='true'){
					elem.children('span').text(parseInt(counted)+1);	
				}
			});
			
			return false;	
		});
		// Comment like and dislike
		
		// Adding comment
		$('#post_comment').click(function(){
			var frmInput = $('#comment_form textarea');
			var frmSubmit = $('#comment_form button');
			var frm = $('#comment_form');
			
			if($.trim(frmInput.val()).length == 0){
				frm.children('.error_state').show();
				frm.children('.error_state').html('<i class="icon-bell"></i> Hi <strong><?php echo $userData->name; ?></strong>! please write something.');
				return false;	
			}
			
			var frmData = frm.serialize();
			
			frmInput.attr('disabled','');
			frmSubmit.addClass('disabled');
			frmSubmit.children('.loading_state').removeClass('hide');
			frmSubmit.children('.calloutTitle').html('Posting..');
			frm.children('.error_state').hide();
			
			var request = $.ajax({
				url: "<?php echo $global->baseurl.$comDir ?>_ajax.add.comment.php",
				type: "POST",
				data: frmData,
				dataType : 'json'
			});
			
			request.done(function(json) {
				
				if( json.affectedRow == 1 && !json.duplicate ){
				
					// APPEND DATA
					var commentData = $('#comment_form textarea').val();
					var userImage = '<?php echo '<img src="'.$global->baseurl.'images/users/r_thumb_'.$userData->avatar.'" alt="'.$userData->name.'" class="img-circle">'; ?>';
					var userName = '<?php echo '<a href="'.getProfileUrl($userData->email).'" target="_blank" class="muted"><strong>'.$userData->name.'</strong></a>'; ?>';
					var timeText = 'just now';
					var moderationTool = '<a href="javascript:void(0)" class="comment_edit"><i class="icon-edit"></i> Edit</a><?php if($userData->usergroup=='Administrator') { ?><a href="javascript:void(0)" class="comment_delete"><i class="icon-remove"></i> Delete</a><?php } ?>';
					var commentId = json.insertedId;
					
					var commnentSkeleton = '<div class="row" id="comment-'+commentId+'">'+
			
						'<div class="span2">'+
							'<div class="pinfo">'+userImage+'</div>'+
						'</div>'+
						
						'<div class="span10">'+
							'<div class="comment">'+
								'<span class="loading_state icon-refresh icon-spin hide"></span>'+
								
								'<div class="info">'+
									'<span class="left_arrow"></span>'+
									'<span class="author_name">'+userName+'</span>'+
									'<span class="added_time muted"><small>'+timeText+'</small></span>'+
									'<span class="pull-right moderation_menu">'+moderationTool+'</span>'+
								'</div>'+
					
								'<div class="commit">'+
									'<span class="full_commit" data-id="'+commentId+'">'+commentData+'</span>'+
									'<span class="action_buttons">'+
										'<a href="#" class="up_vote"><i class="icon-thumbs-up"></i> <span>0</span></a>'+
										'<a href="#" class="down_vote"><i class="icon-thumbs-down"></i> <span>0</span></a>'+
									'</span> '+ 
								'</div>'+
								
							'</div>'+
						'</div>'+
						
					'</div>';
					
					$('.user_comments').append(commnentSkeleton);
					// APPEND DATA
					
					frmInput.val('');
				}
				else if(json.duplicate){
					frm.children('.error_state').show();
					frm.children('.error_state').html('<i class="icon-exclamation-sign"></i> You have already said this!');	
				}
				else {
					frm.children('.error_state').show();
					frm.children('.error_state').html('<i class="icon-exclamation-sign"></i> An error occurred. Please try again or reload the browser.');		
				}
				
			});
			
			request.always(function(){ 
				frmInput.removeAttr('disabled');
				frmSubmit.removeClass('disabled');
				frmSubmit.children('.loading_state').addClass('hide');
				frmSubmit.children('.calloutTitle').html('Post comment');
			});
			
			return false;	
		});
		// Adding comment
	});
</script>