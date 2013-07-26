<?php
/*
* Deliver wiki
* common/header.php
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

$globalMenu = array(
	'wiki' => array('name'=>'Wiki', 'icon'=>'icon-book', 'link' => $global->baseurl.'wiki/',
						'dropdown' => array(
							'listwiki' => array('name'=>'Browse All', 'icon'=>'icon-tasks', 'link' => $global->baseurl.'wiki/' ),
							'addwiki' => array('name'=>'Add new wiki', 'icon'=>'icon-plus-sign-alt', 'link' => $global->baseurl.'wiki/new/' )
						) 
					),
	'knowledgebase' => array('name'=>'Knowledge base', 'icon'=>'icon-beaker', 'link' => $global->baseurl.'knowledgebase/',
						'dropdown' => array(
							'listwiki' => array('name'=>'Browse All', 'icon'=>'icon-tasks', 'link' => $global->baseurl.'knowledgebase/' ),
							'addwiki' => array('name'=>'New post', 'icon'=>'icon-plus-sign-alt', 'link' => $global->baseurl.'knowledgebase/new/' )
						) 
					),
	'process' => array('name'=>'Process', 'icon'=>'icon-random', 'link' => $global->baseurl.'process/' ),
	'team' => array('name'=>'Team', 'icon'=>'icon-group', 'link' => $global->baseurl.'team/' ),
	'profile' => array('name'=>'Account', 'icon'=>'icon-cogs', 'link' => $global->baseurl.'profile/'.$userData->username.'/', 
						'dropdown' => array(
							'changepass' => array('name'=>'Change password', 'icon'=>'icon-edit-sign', 'link' => $global->baseurl.'profile/change-password/' ),
							'profile' => array('name'=>'Profile', 'icon'=>'icon-user', 'link' => $global->baseurl.'profile/'.$userData->username.'/' ),
							'logout' => array('name'=>'Logout', 'icon'=>'icon-power-off', 'link' => $global->baseurl.'authentication/logout/' )
						)
					)
);
?>
<header>
	<div class="navbar navbar-inverse navbar-fixed-top">
        
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="<?php echo $global->baseurl ?>home/"><?php echo $global->teamName ?> team</a>
                <?php echo genMenu($globalMenu, 'nav pull-right', $route['component']); ?>
            </div>
        </div>
    
    </div>
</header>