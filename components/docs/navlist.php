<?php
/*
* Deliver wiki
* wiki/navlist.php
* 27.06.2013
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

<?php
	$db->query("SELECT category, project, title, url
				FROM ".$_this->tableName." 
					WHERE type = 'wiki'");
	$leftNav = $db->result();
	
	$list = array();
	if( count($leftNav)>0 ){
		foreach($leftNav as $nav){
			$list[$nav->project][$nav->category][$nav->url] = $nav->title;	
		}
	}
	
	
	function leftNavList($array, $recursive = false){
		global $comURL;
		global $comRoute;
		
		if(count($array)>0){
			foreach($array as $key=>$val){
				if(is_array($val)) {
					if($recursive) echo '<li class="nav-header">'.$key.'</li>';
					else echo '<li class="nav-header colored">'.$key.'</li><li class="divider"></li>';
					leftNavList($val, true);
				}
				else echo '<li'.(($comRoute[0] == $key)?' class="active"':'').'><a href="'.$comURL.'article/'.$key.'/">'.$val.'</a></li>';
			}
		}
	}
?>

<ul class="nav nav-list">
	<?php leftNavList($list); ?>
</ul>