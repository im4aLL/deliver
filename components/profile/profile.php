<?php
/*
* Deliver wiki
* profile/profile.php
* 20.06.2013
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

<?php
	//$qryArray = array( 'tbl_name' => $_this->tableName, 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE email LIKE '".$route['view']."@%'" );
	//$db->select($qryArray);
	
	$db->query("SELECT * FROM ".$_this->tableName." WHERE email LIKE '".$route['view']."@%'");
	$db->result();
	
	$profileResult = $db->result();
	$profileInfo = $profileResult[0];
	
	$s_p_username = explode("@", $userData->email);
	$profileInfo->username = strtolower($s_p_username[0]);
?>

<!--container-->
<div class="container <?php echo $route['component']; ?>">

	<div class="page-header">
    	<h1>Profile <small>information and activity log</small></h1>
    </div>
    
    <div class="row profile_info">
    	<div class="span3">
        	<div class="thumbnail"><img src="<?php echo $global->baseurl ?>images/users/<?php if($profileInfo->avatar!=NULL) echo 'r_'.$profileInfo->avatar; else echo 'default.jpg'; ?>" alt="<?php echo $profileInfo->name; ?>"></div>
        </div>
        <div class="span9">
            <dl class="dl-horizontal">
                <dt>name</dt>
                <dd><a href="<?php echo $global->baseurl ?>profile/<?php echo $profileInfo->username ?>/"><?php echo $profileInfo->name; ?></a></dd>
                
                <dt>email</dt>
                <dd><?php echo $profileInfo->email; ?></dd>
                
                <dt>employee ID</dt>
                <dd><?php echo $profileInfo->emp_id; ?></dd>
                
                <dt>cell</dt>
                <dd><?php echo $profileInfo->cell_no; ?></dd>
                
                <dt>skype</dt>
                <dd><?php echo $profileInfo->skype; ?></dd>
                
                <dt>designation</dt>
                <dd><?php echo ucwords(str_replace('-', ' ', $profileInfo->designation)); ?></dd>
                
                <dt>member since</dt>
                <dd><?php echo ago( strtotime($profileInfo->created_at) ); ?></dd>
                
                <dt>reputation</dt>
                <dd><?php echo get_rep($profileInfo->id); ?></dd>
                
                <dt>&nbsp;</dt>
                <dd>&nbsp;</dd>

                <?php
					if($profileInfo->id == $userData->id){
						echo '<dt>&nbsp;</dt>';
						echo '<dd><a class="btn" href="'.$comURL.'edit/'.$profileInfo->username.'/">Update profile information</a></dd>';	
					}
				?>
                
            </dl>
        </div>
    </div>
    
    <hr>
    <h3>Activity <small class="muted">past 30 records</small></h3>
    
    <ul class="nav nav-tabs" id="activity">
        <li class="active"><a href="#reputation">Reputation</a></li>
        <li><a href="#comment">Comment</a></li>
        <li><a href="#kbasenwiki">Knowledge base and wiki</a></li>
    </ul>
    
    <div class="tab-content">
    	<div class="tab-pane active" id="reputation">
        	<?php
				$db->query("SELECT a.*, b.title, b.url, b.type, c.id as comment_id, c.comment, d.title as ctitle, d.url as curl, d.type as ctype
							FROM ".$config['tbl_prefix']."reputation as a
							LEFT JOIN ".$config['tbl_prefix']."kn_wiki as b ON b.id = a.for_kn_id
							LEFT JOIN ".$config['tbl_prefix']."comments as c ON c.id = a.for_comment_id
							LEFT JOIN ".$config['tbl_prefix']."kn_wiki as d ON d.id = c.to_id
								WHERE a.to_user_id = '$profileInfo->id' 
							ORDER by a.id DESC LIMIT 0,30");
				if( $db->total() > 0 ){
					echo '<ul>';
					foreach( $db->result() as $rep_row ){
						if($rep_row->rep > 0) echo '<li class="green"><i class="icon-chevron-up"></i> <strong>'.$rep_row->rep.'</strong>';
						else echo '<li class="red"><i class="icon-chevron-down"></i> <strong>'.$rep_row->rep.'</strong>';
						
						if($rep_row->for_kn_id > 0) echo ' <a href="'.(($rep_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$rep_row->url.'/" target="_blank">'.$rep_row->title.'</a>';
						else echo ' <span class="comment_row"><i class="icon-comments-alt"></i> '.html_decode($rep_row->comment).' <em class="muted">on</em> <a href="'.(($rep_row->ctype=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$rep_row->curl.'/#comment-'.$rep_row->comment_id.'" target="_blank">'.$rep_row->ctitle.'</a></span>';
						
						echo '</li>';
					}
					echo '</ul>';
				}
				else echo 'No activity.';
			?>
        </div>
        <div class="tab-pane" id="comment">
        	<?php
				$db->query("SELECT a.*, b.title, b.url, b.type 
							FROM ".$config['tbl_prefix']."comments as a
							LEFT JOIN ".$config['tbl_prefix']."kn_wiki as b ON b.id = a.to_id
								WHERE a.by_user_id = '$profileInfo->id'
							ORDER by a.id DESC LIMIT 0,30");
				if( $db->total() > 0 ){
					echo '<ul>';
					foreach( $db->result() as $comment_row ){
						echo '<li><small class="muted">'.ago(strtotime($comment_row->created_at)).'</small> '.html_decode($comment_row->comment).' <em class="muted">on</em> <a href="'.(($comment_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$comment_row->url.'/" target="_blank">'.$comment_row->title.'</a></li>';
					}
					echo '</ul>';
				}
			?>
        </div>
        <div class="tab-pane" id="kbasenwiki">
        	<?php
				$db->query("SELECT * 
							FROM ".$config['tbl_prefix']."kn_wiki
								WHERE by_id = '$profileInfo->id'
							ORDER by id DESC LIMIT 0,30");
				if( $db->total() > 0 ){
					echo '<ul>';
					foreach( $db->result() as $post_row ){
						echo '<li><small class="muted">'.ago(strtotime($post_row->created_at)).'</small> <a href="'.(($post_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$post_row->url.'/" target="_blank"><i class="'.(($post_row->type=='wiki')? $globalMenu['wiki']['icon']: $globalMenu['knowledgebase']['icon']).'"></i> '.$post_row->title.'</a></li>';
					}
					echo '</ul>';
				}
			?>
        </div>
    </div>
    
</div>
<!--container-->

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>