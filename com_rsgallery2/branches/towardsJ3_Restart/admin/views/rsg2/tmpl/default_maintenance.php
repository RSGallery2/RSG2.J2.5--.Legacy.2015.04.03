<?php 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// http://www.techrepublic.com/article/handling-multiple-submits-in-a-single-form-with-php/


?>

	<p>maintenance</p>
	<br />
	<!-- p>
		<button onclick="Joomla.submitbutton('rsg2.ClearRsg2DbItems')">Clear image database complete</button>
	</p --> 
	
	<p>
		<button onclick="Joomla.submitbutton('rsg2.ClearRsg2DbItems')">
			<img src="<?php echo JUri::base();?>/components/com_rsg2/images/db_DelItems.png" width="48" height="48" border="0" alt="Home">
			<strong><!-- ?php echo JText::_('COM_RSG2_MAINT_CONSOLDB') ?-->Clear image database complete</strong>
			<!--?php echo JText::_('COM_RSG2_MAINT_CONSOLDB_TXT') ?--> Delete all items in RSG2 database.  Attention: It will delete each gallery and each image entry in the database.
		</button>
	</p> 
	
	<!--p>
		Delete all RSG2 image files (Images, thumbs ...) <button onclick="Joomla.submitbutton('rsg2.DeleteAllRsg2Images')">Delete all RSG2 images</button>
	</p --> 
	
	<p>
		<div style="float:left;">
		<button onclick="Joomla.submitbutton('rsg2.DeleteAllRsg2Images')">
			<img src="<?php echo JUri::base();?>/components/com_rsg2/images/media_DelItems.png" width="48" height="48" border="0" alt="Home">
			<strong><!-- ?php echo JText::_('COM_RSG2_MAINT_CONSOLDB') ?-->Delete all RSG2 images</strong>
			<!--?php echo JText::_('COM_RSG2_MAINT_CONSOLDB_TXT') ?--> Delete all uploaded images in RSG2. Attention: It will delete each image together with thumbs and watermarked files.
		</button>
	</p> 
	
	
    <!-- div style="float:left;">
	    <div class="icon-bar">
	        <a href="index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=consolidateDB">
	            <div class="iconimage">
	                <div class="rsg2-icon"><img src="http://127.0.0.1/Joomla3x//administrator/components/com_rsgallery2/images/blockdevice.png" alt="alternate text" /></div>
					<div class="rsg2-text">
						<span class="maint-title">**Consolidate Database**</span>
						<span class="maint-text">**This option will perform a complete check on the database and filesystem, to see if there are any discrepancies.**</span></div>
	            </div>
	        </a>
	    </div -->
	
	
	
	<!-- ?php
	$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=consolidateDB';
	html_rsg2_maintenance::quickiconBar( $link, 'blockdevice.png', 
		JText::_('COM_RSGALLERY2_MAINT_CONSOLDB'), JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT') );
	? -->
	<br>
	<!--p>
		<div class="icon-bar">
			<button onclick="Joomla.submitbutton('rsg2.ConsolidateDatabase')">
	            <div class="iconimage">
					<p class="rsg2-icon">
						<img src="<?php echo JUri::base();?>/components/com_rsgallery2/images/blockdevice.png" width="48" height="48" border="0" alt="Home">
					</p>
					<div style="float:left;">
					<div class="rsg2-text">
						<span class="maint-title"><strong><?php echo JText::_('COM_RSG2_MAINT_CONSOLDB') ?></strong></span>
						<span class="maint-text"><?php echo JText::_('COM_RSG2_MAINT_CONSOLDB_TXT') ?></span>
					</div>
	            </div>
			</button>
        </div>
	</p --> 
	
	<p>
		<div style="float:left;">
		<button onclick="Joomla.submitbutton('rsg2.ConsolidateDatabase')">
			<img src="<?php echo JUri::base();?>/components/com_rsgallery2/images/blockdevice.png" width="48" height="48" border="0" alt="Home">
			<strong><?php echo JText::_('COM_RSG2_MAINT_CONSOLDB') ?></strong>
			<?php echo JText::_('COM_RSG2_MAINT_CONSOLDB_TXT') ?> 
		</button>
	</p> 
	<br>
	<!-- ?php
	$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=regenerateThumbs';
	html_rsg2_maintenance::quickiconBar( $link, 'menu.png', 
		JText::_('COM_RSGALLERY2_MAINT_REGEN'), JText::_('COM_RSGALLERY2_MAINT_REGEN_TXT') );
	? -->

	<br>
	<p>
		<div style="float:left;">
		<button onclick="Joomla.submitbutton('rsg2.RegenerateFromImages')">
			<img src="<?php echo JUri::base();?>/components/com_rsgallery2/images/menu.png" width="48" height="48" border="0" alt="Home">
			<strong><?php echo JText::_('COM_RSG2_MAINT_REGEN') ?></strong>
			<?php echo JText::_('COM_RSG2_MAINT_REGEN_TXT') ?> 
		</button>
	</p> 
	
	<br>
				<p>
	<!-- ?php
	$link = 'index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=optimizeDB';
	html_rsg2_maintenance::quickiconBar( $link, 'db_optimize.png', JText::_('COM_RSGALLERY2_MAINT_OPTDB'), 
		JText::_('COM_RSGALLERY2_MAINT_OPTDB_TXT') );
	? -->
	<br>
	<p>
		<div style="float:left;">
		<button onclick="Joomla.submitbutton('rsg2.OptimizeDatabase')">
			<img src="<?php echo JUri::base();?>/components/com_rsgallery2/images/db_optimize.png" width="48" height="48" border="0" alt="Home">
			<strong><?php echo JText::_('COM_RSG2_MAINT_OPTDB') ?></strong>
			<?php echo JText::_('COM_RSG2_MAINT_OPTDB_TXT') ?> 
		</button>
	</p> 
	<br>
	

	
	
	
