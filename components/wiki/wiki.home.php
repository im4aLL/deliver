<?php
/*
* Deliver wiki
* wiki/wiki.home.php
* 19.07.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/
defined("deliver") or die("Restriced Access");

if(isset($_GET['keyword']) && $_GET['keyword']!=NULL) $keyword = safe_string($_GET['keyword']);
else $keyword = NULL;
if(isset($_GET['in']) && $_GET['in']!=NULL) $in = safe_string($_GET['in']);
else $in = NULL;

if($keyword!=NULL && $in=='tags'){
	$keywordArray = genTag($keyword);
	$search_string = "b.tags = '".implode("' OR b.tags = '", $keywordArray)."'";
	$db->query("SELECT a.kw_id FROM ".$config['tbl_prefix']."tag_relate as a 
				JOIN ".$config['tbl_prefix']."tags as b ON b.id = a.tag_id
				WHERE ".$search_string);
	
	if( $db->total() > 0 ){
		foreach($db->result() as $result){
			$preQ[] = "a.id = '".$result->kw_id."'";	
		}
		$searchString = implode(' OR ',$preQ);
	}
}
elseif( $keyword!=NULL && $in!=NULL ){
	if($in=='all'){
		$searchString = "a.title LIKE '".$keyword."%'";	
	}
	else {
		$searchString = "a.title LIKE '".$keyword."%' AND a.category = '".$in."'";		
	}
}

if(!isset($searchString)) $searchString = NULL;
?>
    
        <form>
            <div class="input-append">
            	<input class="span6" id="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword; ?>">
                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown" type="button">Search in <span class="caret"></span></button>
                    <ul class="dropdown-menu text-left src_in">
                    	<!--<li class="disabled"><a href="javascript:void(0)">Category</a></li>-->
                        <li><a href="javascript:void(0)" rel="tags">Tags</a></li>
                        <?php
							$qryArray = array( 'tbl_name' => $_this->tableName, 'field' => array('category'), 'method' => PDO::FETCH_OBJ, 'groupby'=>'category', 'orderby'=>'category', 'condition' => " WHERE type='wiki'" );
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

	<?php
		if($keyword!=NULL && $searchString==NULL) echo '<div class="alert"><strong>Search result:</strong> Sorry! no wiki found with desired keyword(s).</div>';
		else 
			{
				$db->query("SELECT a.title
							FROM ".$_this->tableName." as a 
							WHERE a.type='wiki' ".(($searchString)?' AND ('.$searchString.') ':'')." ");
							
				$paginator = new hPagination();
				$paginator->page_url        = ($keyword!=NULL && $in!=NULL)?$comURL.'?keyword='.str_replace(" ", "+",$keyword).'&in='.$in:$comURL;
				$paginator->rows_per_page   = $global->rowPerPage;              
				$paginator->total_rows      = $db->total();
				$paginator->current_page    = isset($_GET['page']) ? intval($_GET['page']) : 1;
				$paginator->seourl          = false;
				$paginator->parameter       = 'page'; 
				
				$paginator->message = true;
				$paginator->class_page = 'pagination';
				$paginator->class_button = 'btn btn-small';
				$paginator->class_active = 'btn-inverse';
				
				$db->query("SELECT a.title, a.url, a.created_at, a.category, a.hit, b.name, b.email
							FROM ".$_this->tableName." as a 
							LEFT JOIN
								".$config['tbl_prefix']."users as b ON b.id = a.by_id
							WHERE a.type='wiki' ".(($searchString)?' AND ('.$searchString.') ':'')." ORDER by a.id DESC ".$paginator->limit());
				$data = $db->result();
				
				if( count($data) > 0 ){
					
					foreach($data as $kn_wiki){
						echo '<div class="row listrow">';
							
							echo '<div class="span9">';
								echo '<p class="kw_title">';
								echo '<a href="'.$comURL.'article/'.$kn_wiki->url.'/">'.$kn_wiki->title.'</a><br>';
								echo '<small class="muted category"><i class="icon-search"></i> <a href="'.$comURL.'?keyword='.$kn_wiki->category.'&in=tags" class="muted">'.ucwords($kn_wiki->category).'</a></small>';
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
					
					$paginator->display_pagination();
					
				}
				elseif($keyword!=NULL) echo '<div class="alert"><strong>Search result:</strong> Sorry! no result with desired keyword(s).</div>';
				else echo '<div class="alert"><strong>Sorry!</strong> no wiki found.</div>';	
			}
	?>
	
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
	});
</script>