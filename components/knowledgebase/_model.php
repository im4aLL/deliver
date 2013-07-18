<?php
/*
* Deliver wiki
* components/authentication/_model.php
* 14.06.2013
*
* ===========================================
* @package		1.0
* @author 		Al.Hadi@adpeople.com / me@habibhadi.com
* @copyright	GraphicPeople Deliver Team
* @version    	1.0.0 beta
* ===========================================
*/

$array = array();
$array = $_POST;

// if hit kn_wiki button
if( isset($array['kn_wiki']) ){
	
	unset($array['kn_wiki']);
	if(isset($array['el-select'])) unset($array['el-select']);
	
	if( $array['new_category'] != NULL ) $array['category'] = $array['new_category'];
	unset($array['new_category']);
	$array = sanitize($array, array(), array('description'));
	$array['description'] = strip_unsafe($array['description']);
	$tags = trim($array['tags']);
	unset($array['tags']);
	
	// checking for error
	$errorList = array();
	
	if($array['category']=='flagged') $errorList[] = 'Invalid category name!';
	if($array['title']=='flagged') $errorList[] = 'Please enter a valid title';	
	if(trim($array['description'])==NULL) $errorList[] = 'Please write something in description';	
	// checking for error
	
	// if there is an error
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		$_SESSION['msg']['timeout'] = 20;
		include($global->comFolder.'/redirect/error.php');
	}
	// if there is an error
	// if there is no error
	else {
		$array['category'] = strtolower($array['category']);
		$array['url'] = genUrl($array['title']);
		$array['created_at'] = date("Y-m-d");
		$array['state'] = 0;
		$array['by_id'] = $userData->id;
		$array['type'] = 'kbase';
		
		$inserted = $db->insert($_this->tableName, $array, array('title', 'url'));
		
		// if data inserted
		if( $inserted['affectedRow'] == 1 ){
			
			// tags
			if( $tags != NULL ){
				$generatedTagArray = genTag($tags, true);
				if( count($generatedTagArray) == 0 ) $generatedTagArray = genTag($array['title']);
			}
			else {
				$generatedTagArray = genTag($array['title']);	
			}
			
			if( count($generatedTagArray) > 0 ){
				foreach($generatedTagArray as $tag){
					$t_array = array();
					$t_array['tags'] = $tag;
					$t_inserted = $db->insert($config['tbl_prefix'].'tags', $t_array, array('tags'));
					
					if($t_inserted['duplicate']==true){
						$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'tags', 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE tags = '".$tag."'");
						$db->select($qryArray);
						$getted_tag_attribute = $db->result();
						$inserted_tag_id = $getted_tag_attribute[0]->id;
					}
					else $inserted_tag_id = $t_inserted['insertedId'];
					
					$relate_q_array = array();
					$relate_q_array['tag_id'] = $inserted_tag_id;
					$relate_q_array['kw_id'] = $inserted['insertedId'];
					$db->insert($config['tbl_prefix'].'tag_relate', $relate_q_array, array('tag_id', 'kw_id'));
				}
			}
			// tags
			
			$_SESSION['msg']['main'] = 'Thank you for submit article into knowledge base!';
			$_SESSION['msg']['more'] = 'An administrator must approve this article, Before appears to knowledge base. It may take up to 1-2 business day(s).';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/success.php');
			
			//SendMail( $global->nofication_email, 'Wiki: Action required!', "A new article has been submitted to wiki waiting for moderation.<br /><br /><strong>Title:</strong> ".$array['title']."<br /><br />Please go to your account dashboard and approve/disapprove pending submission(s)." );
		}
		
		// if email already exists
		elseif( $inserted['duplicate'] == true ){
			$_SESSION['msg']['main'] = 'Sorry, similar type of article is already exists!';
			$_SESSION['msg']['rurl'] = $comURL.'new/';
			include($global->comFolder.'/redirect/error.php');	
		}
		// if email already exists
		
		// if nothing happens
		else {
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/error.php');	
		}
		// if nothing happens
			
	}
	// if there is no error
}
// if hit kn_wiki button

// If update wiki
elseif(isset($_POST['update_kn_wiki'])){	
	unset($array['update_kn_wiki']);
	if(isset($array['el-select'])) unset($array['el-select']);
	
	if( $array['new_category'] != NULL ) $array['category'] = $array['new_category'];
	unset($array['new_category']);
	$array = sanitize($array, array(), array('description'));
	$array['description'] = strip_unsafe($array['description']);
	$tags = trim($array['tags']);
	unset($array['tags']);
	
	// checking for error
	$errorList = array();
	
	if($array['category']=='flagged') $errorList[] = 'Invalid category name!';
	if($array['title']=='flagged') $errorList[] = 'Please enter a valid title';	
	if($array['modify_reason']=='flagged') $errorList[] = 'You have forget to commit the changes';	
	if(trim($array['description'])==NULL) $errorList[] = 'Please write something in description';
	// checking for error
	
	// if there is an error
	if( count($errorList) > 0 ){
		$_SESSION['msg']['main'] = 'Following error(s) has been occurred - ';
		$_SESSION['msg']['more'] = genList($errorList);
		$_SESSION['msg']['rurl'] = currentURL();
		$_SESSION['msg']['timeout'] = 20;
		include($global->comFolder.'/redirect/error.php');
	}
	// if there is an error
	// if there is no error
	else {
		$array['category'] = strtolower($array['category']);
		$array['modified_at'] = date("Y-m-d");
		
		$updated = $db->update($_this->tableName, $array, array('url'=>$comRoute[0]));
		
		// getting current kn_w ID
		$fetchArray = array( 'tbl_name' => $_this->tableName, 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition'=>" WHERE url = '".$comRoute[0]."'" );
		$db->select($fetchArray);
		$origDataResult = $db->result();
		$origData = $origDataResult[0];
		
		// if data inserted
		if( $updated['affectedRow'] == 1 ){
			
			// tags
			if( $tags != NULL ){
				$generatedTagArray = genTag($tags, true);
				if( count($generatedTagArray) == 0 ) $generatedTagArray = genTag($array['title']);
			}
			else {
				$generatedTagArray = genTag($array['title']);	
			}
			
			if( count($generatedTagArray) > 0 ){
				foreach($generatedTagArray as $tag){
					$t_array = array();
					$t_array['tags'] = $tag;
					$t_inserted = $db->insert($config['tbl_prefix'].'tags', $t_array, array('tags'));
					
					if($t_inserted['duplicate']==true){
						$qryArray = array( 'tbl_name' => $config['tbl_prefix'].'tags', 'field' => array('id'), 'method' => PDO::FETCH_OBJ, 'condition' => " WHERE tags = '".$tag."'");
						$db->select($qryArray);
						$getted_tag_attribute = $db->result();
						$inserted_tag_id = $getted_tag_attribute[0]->id;
					}
					else $inserted_tag_id = $t_inserted['insertedId'];
					
					$relate_q_array = array();
					$relate_q_array['tag_id'] = $inserted_tag_id;
					$relate_q_array['kw_id'] = $origData->id;
					$db->insert($config['tbl_prefix'].'tag_relate', $relate_q_array, array('tag_id', 'kw_id'));
				}
			}
			// tags
			
			$_SESSION['msg']['main'] = 'knowledge base has been updated!';
			$_SESSION['msg']['rurl'] = $comURL.'article/'.$comRoute[0].'/';
			include($global->comFolder.'/redirect/success.php');
		}
		
		// if email already exists
		elseif( $updated['duplicate'] == true ){
			$_SESSION['msg']['main'] = 'Sorry, similar type of article is already exists!';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/error.php');	
		}
		// if email already exists
		
		// if nothing happens
		else {
			$_SESSION['msg']['main'] = 'Sorry, an unknown error occurred';
			$_SESSION['msg']['more'] = 'Please contact to your team leader to investigate and send a bug report <a href="mailto:'.$global->bug_report.'">here</a>.';
			$_SESSION['msg']['rurl'] = $comURL;
			include($global->comFolder.'/redirect/error.php');	
		}
		// if nothing happens
			
	}
	// if there is no error
		
}
// If update wiki
?>