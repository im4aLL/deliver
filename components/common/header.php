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
	'wiki' => array('name'=>'Wiki', 'icon'=>'icon-book', 'link' => 'javascript:void(0)',
						'dropdown' => array(
							'listwiki' => array('name'=>'Browse All', 'icon'=>'icon-tasks', 'link' => $global->baseurl.'wiki/' ),
							'addwiki' => array('name'=>'Add new wiki', 'icon'=>'icon-plus-sign-alt', 'link' => $global->baseurl.'wiki/new/' )
						) 
					),
	'kbase' => array('name'=>'Knowledge base', 'icon'=>'icon-beaker', 'link' => '#' ),
	'process' => array('name'=>'Process', 'icon'=>'icon-random', 'link' => '#' ),
	'team' => array('name'=>'Team', 'icon'=>'icon-group', 'link' => '#' ),
	'profile' => array('name'=>'Account', 'icon'=>'icon-cogs', 'link' => 'javascript:void(0)', 
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