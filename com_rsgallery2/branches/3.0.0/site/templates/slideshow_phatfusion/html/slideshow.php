<?php
defined('_JEXEC') or die('Restricted access');

global $rsgConfig;
$doc =& JFactory::getDocument();
JHTML::_("behavior.mootools");

//Add stylesheets and scripts to header
$css1 = JURI::base().'components/com_rsgallery2/templates/slideshow_phatfusion/css/slideshow.css';
$doc->addStyleSheet($css1);
$js1 = JURI::base().'components/com_rsgallery2/templates/slideshow_phatfusion/js/backgroundSlider.js';
$doc->addScript($js1);
$js2 = JURI::base().'components/com_rsgallery2/templates/slideshow_phatfusion/js/slideshow.js';
$doc->addScript($js2);
?>

<!-- Override default CSS styles -->
<style>
.slideshowContainer {
	border: 1px solid #ccc;
	width: 400px;
	height: 300px;
	margin-bottom: 5px;
}
</style>
<!-- show main slideshow screen -->
<div id="container">
	<h3>
		<?php echo $this->galleryname;?>
	</h3>
	<div id="slideshowContainer" class="slideshowContainer">
	</div>
	<div id="thumbnails">
		<?php echo $this->slides;?>
  		<p>
  			<a href="#" onclick="show.previous(); return false;">&lt;&lt; Previous</a> |
  			<a href="#" onclick="show.play(); return false;">Play</a> | 
  			<a href="#" onclick="show.stop(); return false;">Stop</a> | 
  			<a href="#" onclick="show.next(); return false;">Next &gt;&gt;</a>
  		</p>
  	</div>
  	<!-- Set parameters for slideshow -->
	<script type="text/javascript">
  	window.addEvent('domready',function(){
		var obj = {
			wait: 3000, 
			effect: 'fade',
			duration: 1000, 
			loop: true, 
			thumbnails: true,
			backgroundSlider: true,
			onClick: function(i){alert(i)}
		}
		show = new SlideShow('slideshowContainer','slideshowThumbnail',obj);
		show.play();
	});
	</script>
</div><!-- end container -->