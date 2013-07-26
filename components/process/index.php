<?php
/*
* Deliver wiki
* process/index.php
* 25.07.2013
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

<div class="<?php echo $route['component']; ?>">
	<img src="" alt="" class="dragme smooth hide">
</div>

<div class="loading fixed_centered hide"><img src="" alt="loading"></div>

<div class="process_menu">
	<div class="btn-group menugrp">
        <a href="javascript:void(0)" class="btn btn-success" rel="deliver">Deliver</a>
        <a href="javascript:void(0)" class="btn" rel="occ">OCC</a>
    </div>
    <div class="btn-group btn-group-vertical controlgrp">
        <a href="javascript:void(0)" class="btn" rel="zoomin"><i class="icon-zoom-in"></i></a>
        <a href="javascript:void(0)" class="btn" rel="zoomout"><i class="icon-zoom-out"></i></a>
        <a href="javascript:void(0)" class="btn" rel="reset"><i class="icon-refresh"></i></a>
    </div>
</div>

<script src="<?php echo $global->baseurl ?>lib/browser/browser.js"></script>
<script src="<?php echo $global->baseurl ?>lib/drag/drag.js"></script>
<script src="<?php echo $global->baseurl ?>lib/mousewheel/jquery.mousewheel.js"></script>
<script>
	var zoomVal = 1;
	var main_elem = '.dragme';
	
	function ZoomLevel(zoomVal){
		$(main_elem).css({
			'-moz-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-webkit-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-o-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-ms-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'filter': 'progid:DXImageTransform.Microsoft.Matrix(sizingMethod="auto expand",M11='+zoomVal+', M12=-0, M21=0, M22='+zoomVal+')'	
		});	
	}
	
	function trigger_loading(){
		if( BrowserDetect.browser == 'Explorer' ){
			$('.loading').addClass('hide');
			$('img.dragme').removeClass('hide');
		}
		else {
			$('.loading').removeClass('hide');
			$('img.dragme').addClass('hide');
			
			$('img.dragme').bind('load', function(){
				$(this).removeClass('hide');
				$('.loading').addClass('hide');	
			});	
		}
	}
	
	function trigger_reset(){
		$(main_elem).css({ 'top' : '43px', 'left' : '0px' });
		zoomVal = 1;
		ZoomLevel(zoomVal);	
	}
	
	function trigger_zoomin(){
		zoomVal = .07 + zoomVal;
		ZoomLevel(zoomVal);		
	}
	
	function trigger_zoomout(){
		zoomVal = zoomVal - .07;
		ZoomLevel(zoomVal);		
	}
	
	$(document).ready(function(){
		var loading_image = '<?php echo $global->baseurl.$comDir ?>img/loading.gif';
		var images = {
				'deliver' : '<?php echo $global->baseurl.$comDir ?>img/deliver_banner.png',
				'occ' : '<?php echo $global->baseurl.$comDir ?>img/occ.png'
			};
		
		// preloading image
		/*for (var key in images) {
			if (images.hasOwnProperty(key)) {
				$('<img/>')[0].src = images[key];
			}
		}*/
		
		$('.loading img').attr('src', loading_image);
		$('.dragme').attr('src', images.deliver);
		
		trigger_loading();
		
		$('.process_menu .menugrp a').click(function(){
			var hash = $(this).attr('rel');
			window.location.hash = '!'+hash;
			
			$('.process_menu .menugrp a').removeClass('btn-success');
			$(this).addClass('btn-success');
			
			$('.dragme').attr('src', images[hash]);
			trigger_loading();
			
			return false;	
		});
		
		$('.process_menu .controlgrp a').click(function(){
			var action = $(this).attr('rel');
			switch (action){
				case 'reset':
				trigger_reset();
				break;
				
				case 'zoomin':
				trigger_zoomin();
				break;
				
				case 'zoomout':
				trigger_zoomout();
				break;	
			}
			return false;
		});
		
		
		$('body').addClass('over-hide');
		$(main_elem).drags();
		
		$(main_elem).bind('mousewheel', function(event, delta, deltaX, deltaY) {
			zoomVal = ((delta>0)?.07:-.07) + zoomVal;
			if(zoomVal<=.2) zoomVal = .2;
			ZoomLevel(zoomVal);
		});
		
		$('.process').css({ 'min-height' : $(window).height() - 200 + 'px' });
			
	});
</script>

<div class="fixed_at_bottom">
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>