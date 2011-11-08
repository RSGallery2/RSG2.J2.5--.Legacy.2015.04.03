<?php
/**
 * @version $Id $
 * @package RSGallery2
 * @copyright (C) 2003 - 2011 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

global $rsgConfig;
$doc =& JFactory::getDocument();
JHTML::_("behavior.mootools");

//Add stylesheets and scripts to header
$css1 = JURI::base().'components/com_rsgallery2/templates/slideshow_parth/css/jd.gallery.css';
$doc->addStyleSheet($css1);
$css2 = JURI::base().'components/com_rsgallery2/templates/slideshow_parth/css/template.css';
$doc->addStyleSheet($css2);
$js2 = JURI::base().'components/com_rsgallery2/templates/slideshow_parth/js/jd.gallery.js';
$doc->addScript($js2);
?>

<!-- Override default CSS styles -->
<style>
	#myGallery, #myGallerySet, #flickrGallery {
		width: <?php echo $rsgConfig->get('image_width');?>px;
	}
	/* Background color for the slideshow element */
	.jdGallery .slideElement
	{
		background-color: #000;
	}
	/* Override personal.css */
	#main a:hover, #main a:active, #main a:focus{
		background-color: transparent;
	}
</style>

<script type="text/javascript">
	function startGallery() {
		var myGallery = new gallery($('myGallery'), {
			/* Automated slideshow */
			timed: true,
			/* Show the thumbs carousel */
			showCarousel: true,
			/* Text on carousel tab */
			textShowCarousel: 'Thumbs',
			/* Thumbnail height */
			thumbHeight: 50,
			/* Thumbnail width*/
			thumbWidth: 50,
			/* Fade duration in milliseconds (500 equals 0.5 seconds)*/
			fadeDuration: 500,
			/* Delay in milliseconds (6000 equals 6 seconds)*/
			delay: 6000,
			/* Disable the 'open image' link for the images */
			embedLinks: false
		});
	}
	window.addEvent('domready',startGallery);
</script>

<div class="content">

<?php
	//Show link only when menu-item is a direct link to the slideshow
	if (JRequest::getVar('view') !== 'slideshow') {
?>
		<div style="float: right;">
			<a href="index.php?option=com_rsgallery2&Itemid=<?php echo JRequest::getVar('Itemid');?>&gid=<?php echo $this->gid;?>">
				<?php echo JText::_('COM_RSGALLERY2_BACK_TO_GALLERY');?>
			</a>
		</div>
<?php
	}
?>
	<div class="rsg2-clr">
	</div>
	<div style="text-align:center;font-size:24px;">
		<?php echo $this->galleryname;?>
	</div>
	<div class="rsg2-clr">
	</div>
	<div id="myGallery">
		<?php echo $this->slides;?>
	</div><!-- end myGallery -->
</div><!-- End content -->