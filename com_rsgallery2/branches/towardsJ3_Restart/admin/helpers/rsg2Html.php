<?php
defined('_JEXEC') or die;

// For views
class Rsg2Html
{

	 /**
      * Used by showCP to generate buttons
      * @param string URL for button link
      * @param string Image name for button image
      * @param string Text to show in button
      */
	static function quickiconBar( $link, $image, $title, $text = "" ) {
	    ?>
	    <div style="float:left;">
	    <div class="icon-bar">
	        <a href="<?php echo $link; ?>">
	            <div class="iconimage">
	                <div class="rsg2-icon">
						<img src="<?php echo JUri::base();?>/administrator/components/com_rsgallery2/images/<?php echo $image;?>" alt="alternate text" />
					</div>
					<div class="rsg2-text">
						<span class="maint-title"><?php echo $title;?></span>
						<span class="maint-text"><?php echo $text;?></span>
					</div>
	            </div>
	        </a>
	    </div>
	    </div>
	    <div class='rsg2-clr'>&nbsp;</div>
	    <?php
	}
	
	
}