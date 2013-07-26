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


<?php
	$teamArray = array(
		
		'samir' => array(
					'name'			=> 'Samir Chandra Mridha',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/samir.mridha.7',
					'image'			=> 'samir.jpg',
					'email'			=> 'Samir.Chandra@adpeople.com'
				),
				
		'shovan' => array(
					'name'			=> 'Shovan Shamaddar',
					'designation'	=> 'Project Manager',
					'fb'			=> 'https://www.facebook.com/shovan.samaddar',
					'image'			=> 'shovan.jpg',
					'email'			=> 'Shovan.Samaddar@adpeople.com'
					),
					
		'saha' => array(
					'name'			=> 'Suman Chandra',
					'designation'	=> 'QA Engineer',
					'fb'			=> 'https://www.facebook.com/suman.cse25',
					'image'			=> 'saha.jpg',
					'email'			=> 'Suman.Chandra@adpeople.com'
					),
		
		'kabir' => array(
					'name'			=> 'Zakareir Kabir',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/zakareir.shishir',
					'image'			=> 'kabir.jpg',
					'email'			=> 'Zakareir.Kabir@adpeople.com'
					),
					
		'razon' => array(
					'name'			=> 'Raihan Shikdar',
					'designation'	=> 'Sr. Production manager',
					'fb'			=> 'https://www.facebook.com/raihan.razon',
					'image'			=> 'razon.jpg',
					'email'			=> 'Raihan.Sikder@adpeople.com'
					),
		
		'miraz' => array(
					'name'			=> 'Miraz Hossain',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/aalien',
					'image'			=> 'miraz.jpg',
					'email'			=> 'Miraz.Hossain@adpeople.com'
					),
					
		'anam' => array(
					'name'			=> 'Mahmudul Anam',
					'designation'	=> 'QA Engineer',
					'fb'			=> 'https://www.facebook.com/mahmudul.anam',
					'image'			=> 'anam.jpg',
					'email'			=> 'Mahmudul.Anam@adpeople.com'
					),
					
		'nahar' => array(
					'name'			=> 'Luthfunnahar Hussain',
					'designation'	=> 'Sr. Web Developer',
					'fb'			=> 'https://www.facebook.com/luthfunnahar.hussain',
					'image'			=> 'nahar.jpg',
					'email'			=> 'Luthfunnahar.Hussain@adpeople.com'
					),
					
		'hadi' => array(
					'name'			=> 'Habibullah Al Hadi',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/habib.hadi',
					'image'			=> 'hadi.jpg',
					'email'			=> 'Al.Hadi@adpeople.com'
					),
					
		'bellal' => array(
					'name'			=> 'Bellal Hossen',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/milon.400',
					'image'			=> 'bellal.jpg',
					'email'			=> 'Bellal.Hossen@adpeople.com'
					),
					
		'arafat' => array(
					'name'			=> 'Arafat Hossain',
					'designation'	=> 'Web Developer',
					'fb'			=> 'https://www.facebook.com/thehossain2',
					'image'			=> 'arafat.jpg',
					'email'			=> 'Bellal.Hossen@adpeople.com'
					),
					
		'jakir' => array(
					'name'			=> 'Jakir Hossain Suman',
					'designation'	=> 'Team leader',
					'fb'			=> 'https://www.facebook.com/JakirSuman',
					'image'			=> 'jakir.jpg',
					'email'			=> 'Jakir.Hossain@adpeople.com'
					),
					
		'irfan' => array(
					'name'			=> 'Irfan Ahmed',
					'designation'	=> 'Web developer',
					'fb'			=> 'https://www.facebook.com/irfan.anwar.1675',
					'image'			=> 'irfan.jpg',
					'email'			=> 'Irfan.Ahmed@adpeople.com'
					)
		
	);
?>

<div class="container <?php echo $route['component']; ?>">
    
    <div class="row">
    	<?php
			shuffle($teamArray);
			foreach($teamArray as $personKey=>$emp){
				
				echo '<div class="span3">';
					echo '<div class="item">';
						echo '<img src="'.$global->baseurl.$comDir.'img/'.$emp['image'].'">';
						echo '<div class="info">';
							echo '<h3>'.$emp['name'].'</h3>';
							echo '<p>'.$emp['designation'].' <br> 
										<a href="'.$emp['fb'].'"><i class="icon-facebook-sign"></i></a> 
										<a href="#"><i class="icon-skype"></i></a> 
										<a href="#"><i class="icon-google-plus-sign"></i></a> 
										<a href="#"><i class="icon-linkedin-sign"></i></a> 
										<a href="mailto:'.$emp['email'].'"><i class="icon-envelope"></i></a> 
								</p>';
						echo '</div>';
					echo '</div>';	
				echo '</div>';					
			}
		?>
        
    </div>
    
    
</div>

<div class="container">
	<hr>
	<?php require_once($global->comFolder.'/common/footer.php'); ?>
</div>

<script>
	$(document).ready(function(){
		$('body').css({ 'overflow-x' : 'hidden' });
		$('.item').each(function(){
			var first_img = $('img:first', this);
			var bg_img = first_img.attr('src');
			
			first_img.hide();
			$(this).css({ 'background-image' : 'url('+bg_img+')' });
		});	
	});
</script>