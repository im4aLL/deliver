<?php
/*
* Deliver wiki
* home/home.php
* 19.06.2013
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

<div class="container <?php echo $route['component']; ?>">
    
    <div class="row">
    	<div class="span6">
        	<h2>Recent Activity</h2>
        	<!--activity-->
            <ul class="nav nav-tabs" id="activity">
                <li class="active"><a href="#reputation">Reputation</a></li>
                <li><a href="#comment">Comment</a></li>
                <li><a href="#kbasenwiki">Knowledge base and wiki</a></li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="reputation">
                    <?php
                        $db->query("SELECT a.*, b.title, b.url, b.type, c.id as comment_id, c.comment, d.title as ctitle, d.url as curl, d.type as ctype, e.name
                                    FROM ".$config['tbl_prefix']."reputation as a
                                    LEFT JOIN ".$config['tbl_prefix']."kn_wiki as b ON b.id = a.for_kn_id
                                    LEFT JOIN ".$config['tbl_prefix']."comments as c ON c.id = a.for_comment_id
                                    LEFT JOIN ".$config['tbl_prefix']."kn_wiki as d ON d.id = c.to_id 
									LEFT JOIN ".$config['tbl_prefix']."users as e ON e.id = a.from_user_id 
                                    ORDER by a.id DESC LIMIT 0,15");
                        if( $db->total() > 0 ){
                            echo '<ul>';
                            foreach( $db->result() as $rep_row ){
                                if($rep_row->rep > 0) echo '<li class="green"><i class="icon-chevron-up"></i> <strong>'.$rep_row->rep.'</strong>';
                                else echo '<li class="red"><i class="icon-chevron-down"></i> <strong>'.$rep_row->rep.'</strong>';
                                
                                if($rep_row->for_kn_id > 0) echo ' <a href="'.(($rep_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$rep_row->url.'/" target="_blank">'.$rep_row->title.'</a>';
                                else echo ' <span class="comment_row"><i class="icon-comments-alt"></i> '.html_decode($rep_row->comment).' <em class="muted">on</em> <a href="'.(($rep_row->ctype=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$rep_row->curl.'/#comment-'.$rep_row->comment_id.'" target="_blank">'.$rep_row->ctitle.'</a></span>';
								
								echo ' <small class="muted">by '.(($rep_row->name==NULL)?'System':$rep_row->name).'</small>';
                                
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        else echo 'No activity.';
                    ?>
                </div>
                <div class="tab-pane" id="comment">
                    <?php
                        $db->query("SELECT a.*, b.title, b.url, b.type, e.name
                                    FROM ".$config['tbl_prefix']."comments as a
                                    LEFT JOIN ".$config['tbl_prefix']."kn_wiki as b ON b.id = a.to_id
									LEFT JOIN ".$config['tbl_prefix']."users as e ON e.id = a.by_user_id 
                                    ORDER by a.id DESC LIMIT 0,15");
                        if( $db->total() > 0 ){
                            echo '<ul>';
                            foreach( $db->result() as $comment_row ){
                                echo '<li><small class="muted">'.ago(strtotime($comment_row->created_at)).'</small> '.html_decode($comment_row->comment).' <em class="muted">on</em> <a href="'.(($comment_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$comment_row->url.'/" target="_blank">'.$comment_row->title.'</a> <small class="muted">by '.(($comment_row->name==NULL)?'System':$comment_row->name).'</small></li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </div>
                <div class="tab-pane" id="kbasenwiki">
                    <?php
                        $db->query("SELECT a.* , e.name 
                                    FROM ".$config['tbl_prefix']."kn_wiki as a
									LEFT JOIN ".$config['tbl_prefix']."users as e ON e.id = a.by_id 
                                    ORDER by a.id DESC LIMIT 0,15");
                        if( $db->total() > 0 ){
                            echo '<ul>';
                            foreach( $db->result() as $post_row ){
                                echo '<li><small class="muted">'.ago(strtotime($post_row->created_at)).'</small> <a href="'.(($post_row->type=='wiki')? $globalMenu['wiki']['link']: $globalMenu['knowledgebase']['link']).'article/'.$post_row->url.'/" target="_blank"><i class="'.(($post_row->type=='wiki')? $globalMenu['wiki']['icon']: $globalMenu['knowledgebase']['icon']).'"></i> '.$post_row->title.'</a> <small class="muted">by '.(($post_row->name==NULL)?'System':$post_row->name).'</small></li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </div>
            </div>
            <!--activity-->
        </div>
        
        <div class="span6">
        	<!--latest wiki-->
            <h2>Latest wiki</h2>
            
            <?php
                $wiki_comURL = $globalMenu['wiki']['link'];
                $db->query("SELECT a.title, a.url, a.created_at, a.category, a.hit, b.name, b.email
                            FROM ".$config['tbl_prefix']."kn_wiki as a 
                            LEFT JOIN
                                ".$config['tbl_prefix']."users as b ON b.id = a.by_id
                            WHERE a.type='wiki' ORDER by a.id DESC LIMIT 0,5");
                $data = $db->result();
                
                if( count($data) > 0 ){
                    
                    foreach($data as $kn_wiki){
                        echo '<div class="row listrow">';
                            
                            echo '<div class="span6">';
                                echo '<p class="kw_title">';
                                echo '<a href="'.$wiki_comURL.'article/'.$kn_wiki->url.'/">'.$kn_wiki->title.'</a> ';
                                echo '<small class="muted category"><i class="icon-search"></i> <a href="'.$wiki_comURL.'?keyword='.$kn_wiki->category.'&in=tags" class="muted">'.ucwords($kn_wiki->category).'</a></small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted created_at"><i class="icon-calendar"></i> '.date("jS F, Y", strtotime($kn_wiki->created_at)).'</small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted"> <i class="icon-user"></i> <a href="'.getProfileUrl($kn_wiki->email).'" target="_blank" class="muted">'.$kn_wiki->name.'</a></small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted"> <i class="icon-eye-open"></i> '.$kn_wiki->hit.' time'.(($kn_wiki->hit<=1)?'':'s').'</small>';
                                echo '</p>';
                            echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '<a href="'.$wiki_comURL.'" class="btn">Browse all wiki</a>';
                }
            ?>
            <!--latest wiki-->
            <br><br>
            <!--kbase-->
            <h2>Latest Knowledge base</h2>
        
			<?php
                $wiki_comURL = $globalMenu['knowledgebase']['link'];
                $db->query("SELECT a.title, a.url, a.created_at, a.category, a.hit, b.name, b.email
                            FROM ".$config['tbl_prefix']."kn_wiki as a 
                            LEFT JOIN
                                ".$config['tbl_prefix']."users as b ON b.id = a.by_id
                            WHERE a.type='kbase' ORDER by a.id DESC LIMIT 0,5");
                $data = $db->result();
                
                if( count($data) > 0 ){
                    
                    foreach($data as $kn_wiki){
                        echo '<div class="row listrow">';
                            
                            echo '<div class="span6">';
                                echo '<p class="kw_title">';
                                echo '<a href="'.$wiki_comURL.'article/'.$kn_wiki->url.'/">'.$kn_wiki->title.'</a> ';
                                echo '<small class="muted category"><i class="icon-search"></i> <a href="'.$wiki_comURL.'?keyword='.$kn_wiki->category.'&in=tags" class="muted">'.ucwords($kn_wiki->category).'</a></small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted created_at"><i class="icon-calendar"></i> '.date("jS F, Y", strtotime($kn_wiki->created_at)).'</small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted"> <i class="icon-user"></i> <a href="'.getProfileUrl($kn_wiki->email).'" target="_blank" class="muted">'.$kn_wiki->name.'</a></small>';
                                echo '&nbsp;&nbsp;';
                                echo '<small class="muted"> <i class="icon-eye-open"></i> '.$kn_wiki->hit.' time'.(($kn_wiki->hit<=1)?'':'s').'</small>';
                                echo '</p>';
                            echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '<a href="'.$wiki_comURL.'" class="btn">Browse all knowledge base</a>';
                }
            ?>    
            <!--kbase-->
            
        </div>
        
    </div>
   
    <hr>
    
    <div class="row">
    	<?php
			foreach($globalMenu as $menuKey=>$field){
				if($menuKey=='wiki' || $menuKey=='knowledgebase' || $menuKey=='process' || $menuKey=='team'){
					echo '<div class="span3 grids">';
					echo '<a class="main" href="'.$field['link'].'"><i class="big '.$field['icon'].'"></i>';
					echo '<span>'.$field['name'].'</span></a>';
					echo '</div>';	
				}
			}
		?>
    </div>
    
</div>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>