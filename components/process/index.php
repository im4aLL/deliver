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

<script src="<?php echo $global->baseurl ?>lib/drag/drag.js"></script>
<script src="<?php echo $global->baseurl ?>lib/mousewheel/jquery.mousewheel.js"></script>
<script>
	function ZoomLevel(elem, zoomVal){
		elem.css({
			'-moz-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-webkit-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-o-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'-ms-transform' : 'scale('+zoomVal+') rotate(0.1deg)',
			'filter': 'progid:DXImageTransform.Microsoft.Matrix(sizingMethod="auto expand",M11='+zoomVal+', M12=-0, M21=0, M22='+zoomVal+')'	
		});	
	}
	
	$(document).ready(function(){
		$('body').addClass('over-hide');
		
		$('.dragme').drags();
		
		var zoomVal = 1;
		$('.dragme').bind('mousewheel', function(event, delta, deltaX, deltaY) {
			zoomVal = ((delta>0)?.1:-.1) + zoomVal;
			if(zoomVal<=.2) zoomVal = .2;
			ZoomLevel($(this), zoomVal);
		});
		
		$('.process').css({ 'min-height' : $(window).height() - 200 + 'px' });
			
	});
</script>

<div class="<?php echo $route['component']; ?>">
	<img src="<?php echo $global->baseurl.$comDir ?>img/occ.png" alt="occ" class="dragme smooth">
</div>

<div class="fixed_at_bottom">
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>