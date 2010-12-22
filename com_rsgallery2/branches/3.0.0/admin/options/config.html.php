<?php
/**
* Galleries option for RSGallery2 - HTML display code
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2010 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Restricted Access' );

/**
 * Explain what this class does
 * @package RSGallery2
 */
class html_rsg2_config{
    
    
    /**
     * raw configuration editor, debug only
     */
    function config_rawEdit(){
        global $rsgConfig, $option;
        $config = get_object_vars( $rsgConfig );

        ?>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table id='rsg2-config_rawEdit' align='left'>
        <?php foreach( $config as $name => $value ): ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td><input type='text' name='<?php echo $name; ?>' value='<?php echo $value; ?>'></td>
            </tr>
            
        <?php endforeach; ?>
        </table>
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="task" value="config_rawEdit_save" />
        </form>
        <?php
    }
    
    /**
     * Shows the configuration page.
     * @todo get rid of patTemplate!!!
    **/
	function showconfig( &$lists ){
		global $rsgConfig, $mainframe;

		$config = $rsgConfig;
		
		//Exif tags
		$exifTagsArray = array(
				"resolutionUnit" 		=> "Resolution unit",
			    "FileName" 				=> "Filename",
			    "FileSize" 				=> "Filesize",
			    "FileDateTime" 			=> "File Date",
			    "FlashUsed" 			=> "Flash used",
			    "imageDesc" 			=> "Image description",                              
			    "make" 					=> "Camera make",
			    "model" 				=> "Camera model",
			    "xResolution" 			=> "X Resolution",
			    "yResolution" 			=> "Y Resolution",
			    "software" 				=> "Software used",
			    "fileModifiedDate" 		=> "File modified date",
			    "YCbCrPositioning" 		=> "YCbCrPositioning",
			    "exposureTime" 			=> "Exposure time",
			    "fnumber" 				=> "f-Number",
			    "exposure" 				=> "Exposure",
			    "isoEquiv" 				=> "ISO equivalent",
			    "exifVersion" 			=> "EXIF version",
			    "DateTime" 				=> "Date & time",
			    "dateTimeDigitized" 	=> "Original date",
			    "componentConfig" 		=> "Component config",
			    "jpegQuality" 			=> "Jpeg quality",
			    "exposureBias" 			=> "Exposure bias",
			    "aperture" 				=> "Aperture",
			    "meteringMode" 			=> "Metering Mode",
			    "whiteBalance" 			=> "White balance",
			    "flashUsed" 			=> "Flash used",
			    "focalLength" 			=> "Focal lenght",
			    "makerNote" 			=> "Maker note",
			    "subSectionTime" 		=> "Subsection time",
			    "flashpixVersion" 		=> "Flashpix version",
			    "colorSpace" 			=> "Color Space",
			    "Width" 				=> "Width",
			    "Height" 				=> "Height",
			    "GPSLatitudeRef" 		=> "GPS Latitude reference",
			    "Thumbnail" 			=> "Thumbnail",
			    "ThumbnailSize" 		=> "Thumbnail size",
			    "sourceType" 			=> "Source type",
			    "sceneType" 			=> "Scene type",
			    "compressScheme" 		=> "Compress scheme",
			    "IsColor" 				=> "Color or B&W",
			    "Process" 				=> "Process",
			    "resolution" 			=> "Resolution",
			    "color" 				=> "Color",
			    "jpegProcess" 			=> "Jpeg process"
		);
		//Format selected items
		$exifSelected = explode("|", $config->exifTags);
		foreach ($exifSelected as $select) {
			$exifSelect[] = JHTML::_("select.option",$select,$select);
		}
		//Format values for dropdownbox
		foreach ($exifTagsArray as $key=>$value) {
			$exif[] = JHTML::_("select.option",$key,$key);
		}
		
		//Format values for slideshow dropdownbox
		$folders = JFolder::folders(JPATH_RSGALLERY2_SITE. DS . '/templates');
		foreach ($folders as $folder) {
			if (preg_match("/slideshow/i", $folder)) {
				$current_slideshow[] = JHTML::_("select.option",$folder,$folder);
			}
		}
		
		// front display
		$display_thumbs_style[] = JHTML::_("select.option",'table',JText::_('COM_RSGALLERY2_TABLE'));
		$display_thumbs_style[] = JHTML::_("select.option",'float',JText::_('COM_RSGALLERY2_FLOAT'));
		$display_thumbs_style[] = JHTML::_("select.option",'magic',JText::_('COM_RSGALLERY2_MAGIC_NOT_SUPPORTED_YET'));
		
		$display_thumbs_floatDirection[] = JHTML::_("select.option",'left',JText::_('COM_RSGALLERY2_LEFT_TO_RIGHT'));
		$display_thumbs_floatDirection[] = JHTML::_("select.option",'right',JText::_('COM_RSGALLERY2_RIGHT_TO_LEFT'));
		
		$thumb_style[] = JHTML::_("select.option",'0',JText::_('COM_RSGALLERY2_PROPORTIONAL'));
		$thumb_style[] = JHTML::_("select.option",'1',JText::_('COM_RSGALLERY2_SQUARE'));
		
		$thum_order[] = JHTML::_("select.option",'ordering',JText::_('COM_RSGALLERY2_DEFAULT'));
		$thum_order[] = JHTML::_("select.option",'date',JText::_('COM_RSGALLERY2_DATE'));
		$thum_order[] = JHTML::_("select.option",'name',JText::_('COM_RSGALLERY2_NAME'));
		$thum_order[] = JHTML::_("select.option",'rating',JText::_('COM_RSGALLERY2_RATING'));
		$thum_order[] = JHTML::_("select.option",'hits',JText::_('COM_RSGALLERY2_HITS'));
		
		$thum_order_direction[] = JHTML::_("select.option",'ASC',JText::_('COM_RSGALLERY2_ASCENDING'));
		$thum_order_direction[] = JHTML::_("select.option",'DESC',JText::_('COM_RSGALLERY2_DESCENDING'));
		
		$resizeOptions[] = JHTML::_("select.option",'0',JText::_('COM_RSGALLERY2_DEFAULT_SIZE'));
		$resizeOptions[] = JHTML::_("select.option",'1',JText::_('COM_RSGALLERY2_RESIZE_LARGER_PICS'));
		$resizeOptions[] = JHTML::_("select.option",'2',JText::_('COM_RSGALLERY2_RESIZE_SMALLER_PICS'));
		$resizeOptions[] = JHTML::_("select.option",'3',JText::_('COM_RSGALLERY2_RESIZE_PICS_TO_FIT'));
		
		$displayPopup[] = JHTML::_("select.option",'0',JText::_('COM_RSGALLERY2_NO_POPUP'));
		$displayPopup[] = JHTML::_("select.option",'1',JText::_('COM_RSGALLERY2_NORMAL_POPUP'));
		$displayPopup[] = JHTML::_("select.option",'2',JText::_('COM_RSGALLERY2_JOOMLA_MODAL'));
		
		//Number of galleries dropdown field
		$dispLimitbox[] = JHTML::_("select.option",'0',JText::_('COM_RSGALLERY2_NEVER'));
		$dispLimitbox[] = JHTML::_("select.option",'1',JText::_('COM_RSGALLERY2_IF_MORE_GALLERIES_THAN_LIMIT'));
		$dispLimitbox[] = JHTML::_("select.option",'2',JText::_('COM_RSGALLERY2_ALWAYS'));
		
		$galcountNrs[] = JHTML::_("select.option",'5','5');
		$galcountNrs[] = JHTML::_("select.option",'10','10');
		$galcountNrs[] = JHTML::_("select.option",'15','15');
		$galcountNrs[] = JHTML::_("select.option",'20','20');
		$galcountNrs[] = JHTML::_("select.option",'25','25');
		$galcountNrs[] = JHTML::_("select.option",'30','30');
		$galcountNrs[] = JHTML::_("select.option",'50','50');
		
		// watermark
		$watermarkAngles[] = JHTML::_("select.option",'0','0');
		$watermarkAngles[] = JHTML::_("select.option",'45','45');
		$watermarkAngles[] = JHTML::_("select.option",'90','90');
		$watermarkAngles[] = JHTML::_("select.option",'135','135');
		$watermarkAngles[] = JHTML::_("select.option",'180','180');
		
		$watermarkPosition[] = JHTML::_("select.option",'1',JText::_('COM_RSGALLERY2_TOP_LEFT'));
		$watermarkPosition[] = JHTML::_("select.option",'2',JText::_('COM_RSGALLERY2_TOP_CENTER'));
		$watermarkPosition[] = JHTML::_("select.option",'3',JText::_('COM_RSGALLERY2_TOP_RIGHT'));
		$watermarkPosition[] = JHTML::_("select.option",'4',JText::_('COM_RSGALLERY2_LEFT'));
		$watermarkPosition[] = JHTML::_("select.option",'5',JText::_('COM_RSGALLERY2_CENTER'));
		$watermarkPosition[] = JHTML::_("select.option",'6',JText::_('COM_RSGALLERY2_RIGHT'));
		$watermarkPosition[] = JHTML::_("select.option",'7',JText::_('COM_RSGALLERY2_BOTTOM_LEFT'));
		$watermarkPosition[] = JHTML::_("select.option",'8',JText::_('COM_RSGALLERY2_BOTTOM_CENTER'));
		$watermarkPosition[] = JHTML::_("select.option",'9',JText::_('COM_RSGALLERY2_BOTTOM_RIGHT'));
		
		$watermarkFontSize[] = JHTML::_("select.option",'5','5');
		$watermarkFontSize[] = JHTML::_("select.option",'6','6');
		$watermarkFontSize[] = JHTML::_("select.option",'7','7');
		$watermarkFontSize[] = JHTML::_("select.option",'8','8');
		$watermarkFontSize[] = JHTML::_("select.option",'9','9');
		$watermarkFontSize[] = JHTML::_("select.option",'10','10');
		$watermarkFontSize[] = JHTML::_("select.option",'11','11');
		$watermarkFontSize[] = JHTML::_("select.option",'12','12');
		$watermarkFontSize[] = JHTML::_("select.option",'13','13');
		$watermarkFontSize[] = JHTML::_("select.option",'14','14');
		$watermarkFontSize[] = JHTML::_("select.option",'15','15');
		$watermarkFontSize[] = JHTML::_("select.option",'16','16');
		$watermarkFontSize[] = JHTML::_("select.option",'17','17');
		$watermarkFontSize[] = JHTML::_("select.option",'18','18');
		$watermarkFontSize[] = JHTML::_("select.option",'19','19');
		$watermarkFontSize[] = JHTML::_("select.option",'20','20');
		$watermarkFontSize[] = JHTML::_("select.option",'22','22');
		$watermarkFontSize[] = JHTML::_("select.option",'24','24');
		$watermarkFontSize[] = JHTML::_("select.option",'26','26');
		$watermarkFontSize[] = JHTML::_("select.option",'28','28');
		$watermarkFontSize[] = JHTML::_("select.option",'30','30');
		$watermarkFontSize[] = JHTML::_("select.option",'36','36');
		$watermarkFontSize[] = JHTML::_("select.option",'40','40');
	
		$watermarkTransparency[] = JHTML::_("select.option",'0','0');
		$watermarkTransparency[] = JHTML::_("select.option",'10','10');
		$watermarkTransparency[] = JHTML::_("select.option",'20','20');
		$watermarkTransparency[] = JHTML::_("select.option",'30','30');
		$watermarkTransparency[] = JHTML::_("select.option",'40','40');
		$watermarkTransparency[] = JHTML::_("select.option",'50','50');
		$watermarkTransparency[] = JHTML::_("select.option",'60','60');
		$watermarkTransparency[] = JHTML::_("select.option",'70','70');
		$watermarkTransparency[] = JHTML::_("select.option",'80','80');
		$watermarkTransparency[] = JHTML::_("select.option",'90','90');
		$watermarkTransparency[] = JHTML::_("select.option",'100','100');
	
		$watermarkType[] = JHTML::_("select.option",'image','Image');
		$watermarkType[] = JHTML::_("select.option",'text','Text');
		
		//Commenting options
		if ( galleryUtils::isComponentInstalled('com_securityimages') == 1 ) {
			$security_notice = "<span style=\"color:#009933;font-weight:bold;\">" . JText::_('COM_RSGALLERY2_SECURITYIMAGES_COMPONENT_DETECTED')." </span>";
		} else {
			$security_notice = "<span style=\"color:#FF0000;font-weight:bold;\">".JText::_('COM_RSGALLERY2_SECURITYIMAGES_COMPONENT_NOT_INSTALLED')." </span>";
		}

		/**
			* Routine checks if Freetype library is compiled with GD2
			* @return boolean True or False
			*/
		if (function_exists('gd_info'))
			{
			$gd_info = gd_info();
			$freetype = $gd_info['FreeType Support'];
			if ($freetype == 1)
				$freeTypeSupport = "<div style=\"color:#009933;\">". JText::_('COM_RSGALLERY2_FREETYPE_LIBRARY_INSTALLED_WATERMARK_IS_POSSIBLE'). "</div>";
			else
				$freeTypeSupport = "<div style=\"color:#FF0000;\">". JText::_('COM_RSGALLERY2_FREETYPE_LIBRARY_NOT_INSTALLED_WATERMARK_DOES_NOT_WORK')."</div>";
			}
		
		// in page.html joomla has a template for $tabs->func*()
		// couldn't figure out how to use it effectively however.
		// this is why the templates were broken up, so i could call echo $tabs->func*() between them
		jimport("joomla.html.pane");
		$editor =& JFactory::getEditor();
		$tabs =& JPane::getInstance("Tabs");
		?>
		<script  type="text/javascript">
			function submitbutton(pressbutton) {
				<?php echo $editor->save('intro_text') ; ?>
				submitform( pressbutton );
			}
		</script>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<?php
		echo $tabs->startPane( 'rsgConfig','rsgConfig' );
		echo $tabs->startPanel( JText::_('COM_RSGALLERY2_GENERAL'), 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_GENERAL_SETTINGS') ?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_VERSION')?></td>
								<td><?php echo $config->version?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_INTRODUCTION_TEXT')?></td>
								<td>
									<?php echo $editor->display( 'intro_text',  $config->intro_text , '100%', '200', '10', '20', false ) ; ?>
								</td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_DEBUG') ?></td>
								<td>
								<fieldset id="jform_block" class="radio">
								<?php echo JHTML::_("select.booleanlist",'debug', '', $config->debug); ?>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_ADVANCED_SEF_ALL_CATEGORY_NAMES_AND_ITEM_TITLES_MUST_BE_UNIQUE'); ?></td>
								<td>
								<fieldset id="jform_block" class="radio">
								<?php echo JHTML::_("select.booleanlist",'advancedSef', '', $config->advancedSef); ?>
								</fieldset>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableGeneral' );
		echo $tabs->endPanel();
	
		echo $tabs->startPanel( JText::_('COM_RSGALLERY2_IMAGES'), 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_MANIPULATION') ?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_DISPLAY_PICTURE_WIDTH') ?></td>
								<td><input class="text_area" type="text" name="image_width" size="10" value="<?php echo $config->image_width;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_RESIZE_PORTRAIT_IMAGES_BY_HEIGHT_USING_DISPLAY_PICTURE_WIDTH') ; ?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'resize_portrait_by_height', '', $config->resize_portrait_by_height);?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_THUMBNAIL_WIDTH') ?></td>
								<td><input class="text_area" type="text" name="thumb_width" size="10" value="<?php echo $config->thumb_width;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_THUMBNAIL_STYLE') ?></td>
								<td><?php echo JHTML::_("select.genericlist", $thumb_style, 'thumb_style', '', 'value', 'text', $config->thumb_style ) ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_JPEG_QUALITY_PERCENTAGE') ?></td>
								<td><input class="text_area" type="text" name="jpegQuality" size="10" value="<?php echo $config->jpegQuality;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_ALLOWED_FILETYPES') ?></td>
								<td><input class="text_area" type="text" name="allowedFileTypes" size="30" value="<?php echo $config->allowedFileTypes;?>"/></td>
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_UPLOAD') ?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JHTML::tooltip(JText::_('COM_RSGALLERY2_RSG2_IPTC_TOOLTIP'), JText::_('COM_RSGALLERY2_RSG2_IPTC_TOOLTIP_TITLE'), 
                    '', JText::_('COM_RSGALLERY2_RSG2_USE_IPTC')); ?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'useIPTCinformation', '', $config->useIPTCinformation);?></fieldset></td>
							</tr>
						</table>
					</fieldset>
<!--end of addition-->					
				</td>
			</tr>
			<tr>
				<td width="50%" valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_GRAPHICS_LIBRARY') ?></legend>
						<table width="100%">
							<tr>
								<td width=200><?php echo JText::_('COM_RSGALLERY2_GRAPHICS_LIBRARY') ?>:</td>
								<td><?php echo $lists['graphicsLib'] ?></td>
							</tr>
							<tr>
								<td colspan=2 ><span style="color:red;"><?php echo JText::_('COM_RSGALLERY2_NOTE');?></span><?php echo JText::_('COM_RSGALLERY2_LEAVE_THE_FOLLOWING_FIELDS_EMPTY_UNLESS_YOU_HAVE_PROBLEMS'); ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_IMAGEMAGICK_PATH') ?></td>
								<td><input class="text_area" type="text" name="imageMagick_path" size="50" value="<?php echo $config->imageMagick_path ?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_NETPBM_PATH') ?></td>
								<td><input class="text_area" type="text" name="netpbm_path" size="50" value="<?php echo $config->netpbm_path;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_FTP_PATH') ?></td>
								<td><input class="text_area" type="text" name="ftp_path" size="50" value="<?php echo $config->ftp_path?>"/>(<?php echo JText::_('COM_RSGALLERY2_HTML-ROOT_IS')?>: <?php  print $_SERVER['DOCUMENT_ROOT']?>)</td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_VIDEO_CONVERTER_PATH') ?></td>
								<td><input class="text_area" type="text" name="videoConverter_path" size="50" value="<?php echo $config->videoConverter_path;?>"/>(ex. "C:\ffmpeg\ffmpeg.exe")</td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_VIDEO_CONVERTER_PARAMETERS') ?></td>
								<td><input class="text_area" type="text" name="videoConverter_param" size="100" value="<?php echo $config->videoConverter_param;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_THUMBNAIL_EXTRACTION_PARAMETERS') ?></td>
								<td><input class="text_area" type="text" name="videoConverter_thumbParam" size="100" value="<?php echo $config->videoConverter_thumbParam;?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_VIDEO_OUTPUT_TYPE') ?></td>
								<td><input class="text_area" type="text" name="videoConverter_extension" size="50" value="<?php echo $config->videoConverter_extension;?>"/></td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_STORAGE') ?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_KEEP_ORIGINAL_IMAGE') ?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'keepOriginalImage', '', $config->keepOriginalImage)?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_ORIGINAL_IMAGE_PATH') ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_original" size="10" value="<?php echo $config->imgPath_original?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_IMAGE_PATH') ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_display" size="10" value="<?php echo $config->imgPath_display?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_THUMB_PATH') ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_thumb" size="10" value="<?php echo $config->imgPath_thumb?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_("COM_RSGALLERY2_CREATE_DIRECTORIES_IF_THEY_DONT_EXIST") ?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'createImgDirs', '', $config->createImgDirs)?></fieldset></td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="50%" valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_COMMENTS');?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_COMMENTING_ENABLED');?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'comment', '', $config->comment);?></fieldset></td>
							</tr>
							<tr>
								<td>Use  <a href="http://www.waltercedric.com" target="_blank"><?php echo JText::_('COM_RSGALLERY2_SECURITYIMAGES_COMPONENT')?></a> <?php echo $security_notice;?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'comment_security', '', $config->comment_security && galleryUtils::isComponentInstalled('com_securityimages'))?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_ALLOW_PUBLIC_USERS_TO_COMMENT');?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'comment_allowed_public', '', $config->comment_allowed_public)?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_USER_CAN_ONLY_COMMENT_ONCE');?>(Not working yet!)</td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'comment_once', '', $config->comment_once)?></fieldset></td>
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_VOTING');?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_VOTING_ENABLED');?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'voting', '', $config->voting);?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_USER_CAN_ONLY_VOTE_ONCE_COOKIE_BASED');?></td>
								<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'voting_once', '', $config->voting_once)?></fieldset></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_COOKIE_PREFIX');?></td>
								<td><input type="text" name="cookie_prefix" value="<?php echo $config->cookie_prefix;?>" /></td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableImages' );
		echo $tabs->endPanel();
	
		echo $tabs->startPanel( JText::_('COM_RSGALLERY2_DISPLAY'), 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="100%" valign="top">
					<fieldset>
					<legend><?php echo JText::_('COM_RSGALLERY2_FRONT_PAGE')?></legend>
					<table width="100%">
						<tr>
							<td width="200"><?php echo JText::_('COM_RSGALLERY2_DISPLAY_SEARCH')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist", 'displaySearch', '', $config->displaySearch)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_RANDOM')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayRandom', '', $config->displayRandom)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_LATEST')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayLatest', '', $config->displayLatest)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_BRANDING')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayBranding','', $config->displayBranding)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_DOWNLOADLINK')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayDownload','', $config->displayDownload)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_STATUS_ICONS')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayStatus', '', $config->displayStatus)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_GALLERY_LIMITBOX')?></td>
							<td><?php echo JHTML::_("select.genericlist",$dispLimitbox, 'dispLimitbox','','value', 'text', $config->dispLimitbox)?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DEFAULT_NUMBER_OF_GALLERIES_ON_FRONTPAGE')?></td>
							<td><?php echo JHTML::_("select.genericlist",$galcountNrs, 'galcountNrs','','value', 'text', $config->galcountNrs)?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_SLIDESHOW')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displaySlideshow', '', $config->displaySlideshow)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_SELECT_SLIDESHOW')?></td>
							<td><?php echo JHTML::_("select.genericlist",$current_slideshow, 'current_slideshow','','value', 'text', $config->current_slideshow);?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_OWNER_INFORMATION'); ?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'showGalleryOwner', '', $config->showGalleryOwner)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_NUMBER_OF_ITEMS_IN_GALLERY');?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'showGallerySize', '', $config->showGallerySize)?></fieldset></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_CREATION_DATE');?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'showGalleryDate', '', $config->showGalleryDate)?></fieldset>
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<fieldset>
					<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_DISPLAY')?></legend>
					<table width="100%">
						<tr>
							<td width="200"><?php echo JText::_('COM_RSGALLERY2_POPUP_STYLE')?></td>
							<td><?php echo JHTML::_("select.genericlist", $displayPopup, 'displayPopup', '', 'value', 'text', $config->displayPopup )?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_RESIZE_OPTION')?></td>
							<td><?php echo JHTML::_("select.genericlist", $resizeOptions, 'display_img_dynamicResize', '', 'value', 'text', $config->display_img_dynamicResize )?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_DESCRIPTION')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayDesc', '', $config->displayDesc)?></fieldset>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_HITS')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayHits', '', $config->displayHits)?></fieldset>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_VOTING')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayVoting', '', $config->displayVoting)?></fieldset>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_DISPLAY_COMMENTS')?></td>
							<td><fieldset id="jform_block" class="radio">
							<?php echo JHTML::_("select.booleanlist",'displayComments', '', $config->displayComments)?></fieldset>
							</td>
						</tr>
					</table>
					</fieldset>

					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_ORDER')?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_ORDER_IMAGES_BY')?></td>
								<td><?php echo JHTML::_("select.genericlist",$thum_order, 'filter_order','','value', 'text', $config->filter_order)?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_ORDER_DIRECTION')?></td>
								<td><?php echo JHTML::_("select.genericlist",$thum_order_direction, 'filter_order_Dir','','value', 'text', $config->filter_order_Dir)?></td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_EXIF_SETTINGS')?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_DISPLAY_EXIF_DATA')?></td>
								<td><fieldset id="jform_block" class="radio">
								<?php echo JHTML::_("select.booleanlist",'displayEXIF', '', $config->displayEXIF)?></fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top"><?php echo JText::_('COM_RSGALLERY2_SELECT_EXIF_TAGS_TO_DISPLAY')?></td>
								<td valign="top">
									<label class="examples"></label><br>
									<?php echo JHTML::_("select.genericlist", $exif, 'exifTags[]', 'MULTIPLE size="15"', 'value', 'text', $exifSelect );?>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<fieldset>
						<legend><?php echo JText::_('COM_RSGALLERY2_GALLERY_VIEW')?></legend>
						<table width="100%">
							<tr>
								<td width="200"><?php echo JText::_('COM_RSGALLERY2_THUMBNAIL_STYLE_USE_FLOAT_FOR_VARIABLE_WIDTH_TEMPLATES')?></td>
								<td><?php echo JHTML::_("select.genericlist", $display_thumbs_style, 'display_thumbs_style', '', 'value', 'text', $config->display_thumbs_style );?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_DIRECTION_ONLY_WORKS_FOR_FLOAT')?></td>
								<td><?php echo JHTML::_("select.genericlist", $display_thumbs_floatDirection, 'display_thumbs_floatDirection', '', 'value', 'text', $config->display_thumbs_floatDirection )?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_NUMBER_OF_THUMBNAIL_COLUMNS_ONLY_FOR_TABLE')?></td>
								<td><?php echo JHTML::_("select.integerlist",1, 19, 1, 'display_thumbs_colsPerPage', '', $config->display_thumbs_colsPerPage)?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_THUMBNAILS_PER_PAGE')?></td>
								<td><input class="text_area" type="text" name="display_thumbs_maxPerPage" size="10" value="<?php echo $config->display_thumbs_maxPerPage?>"/></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_RSGALLERY2_SHOW_IMAGE_NAME_BELOW_THUMBNAIL')?></td>
								<td><fieldset id="jform_block" class="radio">
								<?php echo JHTML::_("select.booleanlist", 'display_thumbs_showImgName','', $config->display_thumbs_showImgName )?></fieldset>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<fieldset>
					<legend><?php echo JText::_('COM_RSGALLERY2_IMAGE_WATERMARK')?></legend>
					<table width="100%">
						<tr>
							<td colspan="2">
								<strong><?php echo $freeTypeSupport?></strong>
							</td>
						</tr>
						<tr>
							<td width="200">
								<?php echo JText::_('COM_RSGALLERY2_DISPLAY_WATERMARK')?>
							</td>
							<td>
								<fieldset id="jform_block" class="radio">
								<?php echo JHTML::_("select.booleanlist",'watermark','', $config->watermark)?>
								</fieldset>
							</td>
						</tr>
						<!--
						<tr>
							<td width="40%">* Watermark type *</td>
							<td><?php // echo JHTML::_("select.genericlist",$watermarkType, 'watermark_type','','value', 'text', $config->watermark_type)?></td>
						</tr>
						<tr>
							<td valign="top" width="40%">* Watermark upload *</td>
							<td></td>
						</tr>
						-->
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_FONT')?></td>
							<td><?php echo galleryUtils::showFontList();?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARK_TEXT')?></td>
							<td><input class="text_area" type="text" name="watermark_text" size="50" value="<?php echo $config->watermark_text?>"/></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARK_FONT_SIZE')." (points)";?></td>
							<td>
								<?php echo JHTML::_("select.genericlist",$watermarkFontSize, 'watermark_font_size','','value', 'text', $config->watermark_font_size)?>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARK_TEXT_ANGLE')?></td>
							<td><?php echo JHTML::_("select.genericlist",$watermarkAngles, 'watermark_angle','','value', 'text', $config->watermark_angle)?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARK_POSITION')?></td>
							<td><?php echo JHTML::_("select.genericlist",$watermarkPosition, 'watermark_position','','value', 'text', $config->watermark_position)?></td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARK_TRANSPARENCY') . " (%)";?></td>
							<td>
								<?php echo JHTML::_("select.genericlist",$watermarkTransparency, 'watermark_transparency','','value', 'text', $config->watermark_transparency)?>
							</td>
						</tr>
						<tr>
							<td><?php echo JText::_('COM_RSGALLERY2_WATERMARKED_IMAGE_PATH') ?></td>
							<td><input class="text_area" style="width:300px;" type="text" name="imgPath_watermarked" size="10" value="<?php echo $config->imgPath_watermarked?>"/></td>
						</tr>
					</table>
					</fieldset>

				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableFrontDisplay' );
		echo $tabs->endPanel();
	
		echo $tabs->startPanel( JText::_('COM_RSGALLERY2_PERMISSIONS'), 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td>
					<fieldset>
					<legend><?php echo JText::_('COM_RSGALLERY2_ACCESS_CONTROL_SETTINGS')?></legend>
					<table width="100%">
					<tr>
						<td width="200"><?php echo JText::_('COM_RSGALLERY2_ENABLE_ACCESS_CONTROL')?></td>
						<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'acl_enabled', '', $config->acl_enabled)?></fieldset>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_RSGALLERY2_SHOW_MY_GALLERIES')?></td>
						<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'show_mygalleries', '', $config->show_mygalleries)?></fieldset></td>
					</tr>	
					<tr>
						<td><?php echo JText::_('COM_RSGALLERY2_CAN_USER_CREATE_GALLERIES')?></td>
						<td><fieldset id="jform_block" class="radio">
						<?php echo JHTML::_("select.booleanlist",'uu_createCat', '', $config->uu_createCat)?>
						</fieldset></td>
					</tr>	
					</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<fieldset>
					<legend><?php echo JText::_('COM_RSGALLERY2_USER_SPECIFIC_SETTINGS')?></legend>
					<table width="100%">
					<tr>
						<td  width="200"><?php echo JText::_('COM_RSGALLERY2_MAXIMUM_NUMBER_OF_GALLERIES_A_USER_CAN_HAVE')?></td>
						<td><input class="text_area" type="text" name="uu_maxCat" size="10" value="<?php echo $config->uu_maxCat?>"/></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_RSGALLERY2_MAX_NUMBERS_OF_PICTURES_A_USER_CAN_HAVE')?></td>
						<td><input class="text_area" type="text" name="uu_maxImages" size="10" value="<?php echo $config->uu_maxImages?>"/></td>
					</tr>
					</table>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableUsers' );
		echo $tabs->endPanel();
		?>
		<input type="hidden" name="option" value="com_rsgallery2" />
		<input type="hidden" name="rsgOption" value="config" />
		<input type="hidden" name="task" value="" />
		</form>
		<!-- Fix for Firefox browser -->
		<div style='clear:both;line-height:0px;'>&nbsp;</div>
		<?php
		echo $tabs->endPane();
		//$tmpl->displayParsedTemplate( 'configFinish' );
	}
}
