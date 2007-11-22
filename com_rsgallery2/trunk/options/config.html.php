<?php
/**
* Galleries option for RSGallery2 - HTML display code
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

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
        <form action="index2.php" method="post" name="adminForm" id="adminForm">
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
		global $rsgConfig, $mainframe, $mosConfig_live_site;

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
			$exifSelect[] = mosHTML::makeOption($select,$select);
		}
		//Format values for dropdownbox
		foreach ($exifTagsArray as $key=>$value) {
			$exif[] = mosHTML::makeOption($key,$key);
		}
		/*
		echo "<pre>";
		print_r($exifSelected);
		print_r($exifTagsArray);
		echo "</pre>";
		*/
		// front display
		$display_thumbs_style[] = mosHTML::makeOption('table',_RSGALLERY_CONF_OPTION_TABLE);
		$display_thumbs_style[] = mosHTML::makeOption('float',_RSGALLERY_CONF_OPTION_FLOAT);
		$display_thumbs_style[] = mosHTML::makeOption('magic',_RSGALLERY_CONF_OPTION_MAGIC);
		
		$display_thumbs_floatDirection[] = mosHTML::makeOption('left',_RSGALLERY_CONF_OPTION_L2R);
		$display_thumbs_floatDirection[] = mosHTML::makeOption('right',_RSGALLERY_CONF_OPTION_R2L);
		
		$thumb_style[] = mosHTML::makeOption('0',_RSGALLERY_CONF_OPTION_PROP);
		$thumb_style[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_SQUARE);
		
		$thum_order[] = mosHTML::makeOption('ordering',_RSGALLERY_CONF_OPTION_ORDER_DEFAULT);
		$thum_order[] = mosHTML::makeOption('date',_RSGALLERY_CONF_OPTION_ORDER_DATE);
		$thum_order[] = mosHTML::makeOption('name',_RSGALLERY_CONF_OPTION_ORDER_NAME);
		$thum_order[] = mosHTML::makeOption('rating',_RSGALLERY_CONF_OPTION_ORDER_RATING);
		$thum_order[] = mosHTML::makeOption('hits',_RSGALLERY_CONF_OPTION_ORDER_HITS);
		
		$thum_order_direction[] = mosHTML::makeOption('ASC',_RSGALLERY_CONF_OPTION_ORDER_DIRECTION_ASCENDING);
		$thum_order_direction[] = mosHTML::makeOption('DESC',_RSGALLERY_CONF_OPTION_ORDER_DIRECTION_DESCENDING);
		
		$resizeOptions[] = mosHTML::makeOption('0',_RSGALLERY_CONF_OPTION_DEFAULT_SIZE);
		$resizeOptions[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_REZ_LARGE);
		$resizeOptions[] = mosHTML::makeOption('2',_RSGALLERY_CONF_OPTION_REZ_SMALL);
		$resizeOptions[] = mosHTML::makeOption('3',_RSGALLERY_CONF_OPTION_REZ_2FIT);
		
		$displayPopup[] = mosHTML::makeOption('0',_RSGALLERY_CONF_POPUP_NO);
		$displayPopup[] = mosHTML::makeOption('1',_RSGALLERY_CONF_POPUP_NORMAL);
		$displayPopup[] = mosHTML::makeOption('2',_RSGALLERY_CONF_POPUP_FANCY);
		
		//Number of galleries dropdown field
		$dispLimitbox[] = mosHTML::makeOption('0',_RSGALLERY_CONF_LIMIT_NEV);
		$dispLimitbox[] = mosHTML::makeOption('1',_RSGALLERY_CONF_LIMIT_LIMIT);
		$dispLimitbox[] = mosHTML::makeOption('2',_RSGALLERY_CONF_LIMIT_ALW);
		
		$galcountNrs[] = mosHTML::makeOption('5','5');
		$galcountNrs[] = mosHTML::makeOption('10','10');
		$galcountNrs[] = mosHTML::makeOption('15','15');
		$galcountNrs[] = mosHTML::makeOption('20','20');
		$galcountNrs[] = mosHTML::makeOption('25','25');
		$galcountNrs[] = mosHTML::makeOption('30','30');
		$galcountNrs[] = mosHTML::makeOption('50','50');
		
		// watermark
		$watermarkAngles[] = mosHTML::makeOption('0','0');
		$watermarkAngles[] = mosHTML::makeOption('45','45');
		$watermarkAngles[] = mosHTML::makeOption('90','90');
		$watermarkAngles[] = mosHTML::makeOption('135','135');
		$watermarkAngles[] = mosHTML::makeOption('180','180');
		
		$watermarkPosition[] = mosHTML::makeOption('1',_RSGALLERY_CONF_OPTION_TL);
		$watermarkPosition[] = mosHTML::makeOption('2',_RSGALLERY_CONF_OPTION_TC);
		$watermarkPosition[] = mosHTML::makeOption('3',_RSGALLERY_CONF_OPTION_TR);
		$watermarkPosition[] = mosHTML::makeOption('4',_RSGALLERY_CONF_OPTION_L);
		$watermarkPosition[] = mosHTML::makeOption('5',_RSGALLERY_CONF_OPTION_C);
		$watermarkPosition[] = mosHTML::makeOption('6',_RSGALLERY_CONF_OPTION_R);
		$watermarkPosition[] = mosHTML::makeOption('7',_RSGALLERY_CONF_OPTION_BL);
		$watermarkPosition[] = mosHTML::makeOption('8',_RSGALLERY_CONF_OPTION_BC);
		$watermarkPosition[] = mosHTML::makeOption('9',_RSGALLERY_CONF_OPTION_BR);
		
		$watermarkFontSize[] = mosHTML::makeOption('5','5');
		$watermarkFontSize[] = mosHTML::makeOption('6','6');
		$watermarkFontSize[] = mosHTML::makeOption('7','7');
		$watermarkFontSize[] = mosHTML::makeOption('8','8');
		$watermarkFontSize[] = mosHTML::makeOption('9','9');
		$watermarkFontSize[] = mosHTML::makeOption('10','10');
		$watermarkFontSize[] = mosHTML::makeOption('11','11');
		$watermarkFontSize[] = mosHTML::makeOption('12','12');
		$watermarkFontSize[] = mosHTML::makeOption('13','13');
		$watermarkFontSize[] = mosHTML::makeOption('14','14');
		$watermarkFontSize[] = mosHTML::makeOption('15','15');
		$watermarkFontSize[] = mosHTML::makeOption('16','16');
		$watermarkFontSize[] = mosHTML::makeOption('17','17');
		$watermarkFontSize[] = mosHTML::makeOption('18','18');
		$watermarkFontSize[] = mosHTML::makeOption('19','19');
		$watermarkFontSize[] = mosHTML::makeOption('20','20');
		$watermarkFontSize[] = mosHTML::makeOption('22','22');
		$watermarkFontSize[] = mosHTML::makeOption('24','24');
		$watermarkFontSize[] = mosHTML::makeOption('26','26');
		$watermarkFontSize[] = mosHTML::makeOption('28','28');
		$watermarkFontSize[] = mosHTML::makeOption('30','30');
		$watermarkFontSize[] = mosHTML::makeOption('36','36');
		$watermarkFontSize[] = mosHTML::makeOption('40','40');
	
		$watermarkTransparency[] = mosHTML::makeOption('0','0');
		$watermarkTransparency[] = mosHTML::makeOption('10','10');
		$watermarkTransparency[] = mosHTML::makeOption('20','20');
		$watermarkTransparency[] = mosHTML::makeOption('30','30');
		$watermarkTransparency[] = mosHTML::makeOption('40','40');
		$watermarkTransparency[] = mosHTML::makeOption('50','50');
		$watermarkTransparency[] = mosHTML::makeOption('60','60');
		$watermarkTransparency[] = mosHTML::makeOption('70','70');
		$watermarkTransparency[] = mosHTML::makeOption('80','80');
		$watermarkTransparency[] = mosHTML::makeOption('90','90');
		$watermarkTransparency[] = mosHTML::makeOption('100','100');
	
		$watermarkType[] = mosHTML::makeOption('image','Image');
		$watermarkType[] = mosHTML::makeOption('text','Text');
		
		//Commenting options
		if ( galleryUtils::isComponentInstalled('com_securityimages') == 1 ) {
			$security_notice = "<span style=\"color:#009933;font-weight:bold;\">( SecurityImages component detected! )</span>";
		} else {
			$security_notice = "<span style=\"color:#FF0000;font-weight:bold;\">SecurityImages component NOT installed!</span>";
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
				$freeTypeSupport = "<div style=\"color:#009933;\">". _RSGALLERY_FREETYPE_INSTALLED. "</div>";
			else
				$freeTypeSupport = "<div style=\"color:#FF0000;\">". _RSGALLERY_FREETYPE_NOTINSTALLED."</div>";
			}
		
		// in page.html joomla has a template for $tabs->func*()
		// couldn't figure out how to use it effectively however.
		// this is why the templates were broken up, so i could call $tabs->func*() between them
		$tabs = new mosTabs(1);
		?>
		<script  type="text/javascript">
				function submitbutton(pressbutton) {
					<?php getEditorContents( 'editor1', 'intro_text' ) ; ?>
					submitform( pressbutton );
				}
			</script>
		<form action="index2.php" method="post" name="adminForm">
		<?php
		$tabs->startPane( 'rsgConfig' );
		$tabs->startTab( _RSGALLERY_CONF_GENERALTAB, 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="40%" valign="top">
					<fieldset>
						<legend><?php echo _RSGALLERY_C_GEN_SET ?></legend>
						<table width="100%">
							<tr>
								<td width="400"><?php echo _RSGALLERY_C_TMPL_VERSION?></td>
								<td><?php echo $config->version?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_INTRO_TEXT?></td>
								<td>
									<?php editorArea( 'editor1',  $config->intro_text , 'intro_text', '100%;', '200', '10', '20' ) ; ?>
									<!-- <textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="intro_text"><?php // echo $config->intro_text ?></textarea> -->
								</td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_DEBUG ?></td>
								<td><?php echo mosHTML::yesnoRadioList('debug', '', $config->debug); ?></td>
							</tr>
							<tr>
								<td><?php echo 'Hide Root (create multiple independant galleries)'; ?></td>
								<td><?php echo mosHTML::yesnoRadioList('hideRoot', '', $config->hideRoot); ?></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td width="60%" valign="top">&nbsp;</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableGeneral' );
		$tabs->endTab();
	
		$tabs->startTab( _RSGALLERY_CONF_IMAGESTAB, 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="40%" valign="top">
					<fieldset>
						<legend><?php echo _RSGALLERY_C_TMPL_IMG_MANIP ?></legend>
						<table width="100%">
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_DISP_WIDTH ?></td>
								<td><input class="text_area" type="text" name="image_width" size="10" value="<?php echo $config->image_width;?>"/></td>
							</tr>
							<tr>
								<td><?php echo "Resize portrait images by height using Display Picture Width:" ?></td>
								<td><?php echo mosHTML::yesnoRadioList('resize_portrait_by_height', '', $config->resize_portrait_by_height);?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_THUMB_WIDTH ?></td>
								<td><input class="text_area" type="text" name="thumb_width" size="10" value="<?php echo $config->thumb_width;?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_THUMBNAIL_STYLE ?></td>
								<td><?php echo mosHTML::selectList( $thumb_style, 'thumb_style', '', 'value', 'text', $config->thumb_style ) ?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_JPEG_QUALITY ?></td>
								<td><input class="text_area" type="text" name="jpegQuality" size="10" value="<?php echo $config->jpegQuality;?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_ALLOWED_FILE ?></td>
								<td><input class="text_area" type="text" name="allowedFileTypes" size="30" value="<?php echo $config->allowedFileTypes;?>"/></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td width="60%" valign="top">
					<fieldset>
						<legend><?php echo _RSGALLERY_C_TMPL_GRAPH_LIB ?></legend>
						<table width="100%">
							<tr>
								<td width=185><?php echo _RSGALLERY_C_TMPL_GRAPH_LIB ?>:</td>
								<td><?php echo $lists['graphicsLib'] ?></td>
							</tr>
							<tr>
								<td colspan=2 ><span style="color:red;"><?php echo _RSGALLERY_C_TMPL_NOTE_GLIB_PATH ?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_IMGMAGICK_PATH ?></td>
								<td><input class="text_area" type="text" name="imageMagick_path" size="50" value="<?php echo $config->imageMagick_path ?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_NETPBM_PATH ?></td>
								<td><input class="text_area" type="text" name="netpbm_path" size="50" value="<?php echo $config->netpbm_path;?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_FTP_PATH ?></td>
								<td><input class="text_area" type="text" name="ftp_path" size="50" value="<?php echo $config->ftp_path?>"/>(<?php echo _RSGALLERY_C_HTML_ROOT?>: <?php  print $_SERVER['DOCUMENT_ROOT']?>)</td>
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
						<legend><?php echo _RSGALLERY_C_TMPL_IMG_STORAGE ?></legend>
						<table width="100%">
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_KEEP_ORIG ?></td>
								<td><?php echo mosHTML::yesnoRadioList('keepOriginalImage', '', $config->keepOriginalImage)?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_ORIG_PATH ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_original" size="10" value="<?php echo $config->imgPath_original?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_DISP_PATH ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_display" size="10" value="<?php echo $config->imgPath_display?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_THUMB_PATH ?></td>
								<td><input class="text_area" style="width:300px;" type="text" name="imgPath_thumb" size="10" value="<?php echo $config->imgPath_thumb?>"/></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_C_TMPL_CREATE_DIR ?></td>
								<td><?php echo mosHTML::yesnoRadioList('createImgDirs', '', $config->createImgDirs)?></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td width="50%" valign="top">
					<fieldset>
						<legend><?php echo _RSGALLERY_COMMENTS_LABEL;?></legend>
						<table width="100%">
							<tr>
								<td><?php echo _RSGALLERY_COMMENTS_ENABLED;?></td>
								<td><?php echo mosHTML::yesnoRadioList('comment', '', $config->comment);?></td>
							</tr>
							<tr>
								<td>Use <a href="http://www.waltercedric.com" target="_blank">SecurityImages component</a> <?php echo $security_notice;?></td>
								<td><?php echo mosHTML::yesnoRadioList('comment_security', '', $config->comment_security)?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_COMMENTS_ALLOW_PUBLIC;?></td>
								<td><?php echo mosHTML::yesnoRadioList('comment_allowed_public', '', $config->comment_allowed_public)?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_COMMENTS_ONLY_ONCE;?>(Not working yet!)</td>
								<td><?php echo mosHTML::yesnoRadioList('comment_once', '', $config->comment_once)?></td>
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend><?php echo _RSGALLERY_VOTE_LABEL;?></legend>
						<table width="100%">
							<tr>
								<td><?php echo _RSGALLERY_VOTE_ENABLED;?></td>
								<td><?php echo mosHTML::yesnoRadioList('voting', '', $config->voting);?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_VOTE_REGISTERED_ONLY;?></td>
								<td><?php echo mosHTML::yesnoRadioList('voting_registered_only', '', $config->voting_registered_only)?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_VOTE_ONLY_ONCE;?></td>
								<td><?php echo mosHTML::yesnoRadioList('voting_once', '', $config->voting_once)?></td>
							</tr>
							<tr>
								<td><?php echo _RSGALLERY_VOTE_COOKIE_PREFIX;?></td>
								<td><input type="text" name="cookie_prefix" value="<?php echo $config->cookie_prefix;?>"</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableImages' );
		$tabs->endTab();
	
		$tabs->startTab( _RSGALLERY_CONF_DISPLAY, 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="40%" valign="top">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_FRONT_PAGE?></legend>
					<table width="100%">
					<tr>
						<td width="40%"><?php echo _RSGALLERY_C_TMPL_DISP_RAND?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayRandom', '', $config->displayRandom)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_LATEST?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayLatest', '', $config->displayLatest)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_BRAND?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayBranding','', $config->displayBranding)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_DOWN?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayDownload','', $config->displayDownload)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_DISP_STATUS_ICON?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayStatus', '', $config->displayStatus)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_DISP_LIMIB?></td>
						<td><?php echo mosHTML::selectList($dispLimitbox, 'dispLimitbox','','value', 'text', $config->dispLimitbox)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_NUMB_GAL_FRONT?></td>
						<td><?php echo mosHTML::selectList($galcountNrs, 'galcountNrs','','value', 'text', $config->galcountNrs)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_SLIDE?></td>
						<td><?php echo mosHTML::yesnoRadioList('displaySlideshow', '', $config->displaySlideshow)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_OWNER; ?></td>
						<td><?php echo mosHTML::yesnoRadioList('showGalleryOwner', '', $config->showGalleryOwner)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_ITEMS;?></td>
						<td><?php echo mosHTML::yesnoRadioList('showGallerySize', '', $config->showGallerySize)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_DATE;?></td>
						<td><?php echo mosHTML::yesnoRadioList('showGalleryDate', '', $config->showGalleryDate)?></td>
					</tr>
					</table>
					</fieldset>
				</td>
				<td width="30%" valign="top">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_IMG_DISP?></legend>
					<table width="100%">
					<tr>
						<td width="40%"><?php echo _RSGALLERY_CONF_POPUP_STYLE?></td>
						<td><?php echo mosHTML::selectList( $displayPopup, 'displayPopup', '', 'value', 'text', $config->displayPopup )?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_RESIZE_OPT?></td>
						<td><?php echo mosHTML::selectList( $resizeOptions, 'display_img_dynamicResize', '', 'value', 'text', $config->display_img_dynamicResize )?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_DESCR?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayDesc', '', $config->displayDesc)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_HITS?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayHits', '', $config->displayHits)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_VOTE?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayVoting', '', $config->displayVoting)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_COMM?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayComments', '', $config->displayComments)?></td>
					</tr>
					</table>
					</fieldset>

					<fieldset>
					<legend><?php echo _RSGALLERY_C_DISP_IMG_ORDER?></legend>
					<table width="100%">
					<tr>
						<td><?php echo _RSGALLERY_C_DISP_IMG_ORDER_BY?></td>
						<td><?php echo mosHTML::selectList($thum_order, 'filter_order','','value', 'text', $config->filter_order)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_DISP_IMG_ORDER_DIRECTION?></td>
						<td><?php echo mosHTML::selectList($thum_order_direction, 'filter_order_Dir','','value', 'text', $config->filter_order_Dir)?></td>
					</tr>
					</table>
					</filedset>
				</td>
				<td width="30%" valign="top">
					<fieldset>
					<legend><?php echo "** EXIF settings **";?></legend>
					<table width="100%">
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_DISP_EXIF?></td>
						<td><?php echo mosHTML::yesnoRadioList('displayEXIF', '', $config->displayEXIF)?></td>
					</tr>
					<tr>
						<td valign="top"><?php echo "** Select EXIF tags to display **";?></td>
						<td valign="top">
							<label class="examples"></label>
							<?php echo mosHTML::selectList( $exif, 'exifTags[]', 'MULTIPLE size="15"', 'value', 'text', $exifSelect );?>
						</td>
					</tr>
					</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="40%" valign="top">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_GAL_VIEW?></legend>
					<table width="100%">
					<tr>
						<td width="40%"><?php echo _RSGALLERY_C_TMPL_THUMB_STYLE?></td>
						<td><?php echo mosHTML::selectList( $display_thumbs_style, 'display_thumbs_style', '', 'value', 'text', $config->display_thumbs_style );?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_FLOATDIRECTION?></td>
						<td><?php echo mosHTML::selectList( $display_thumbs_floatDirection, 'display_thumbs_floatDirection', '', 'value', 'text', $config->display_thumbs_floatDirection )?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_COLS_PERPAGE?></td>
						<td><?php echo mosHTML::integerSelectList(1, 19, 1, 'display_thumbs_colsPerPage', '', $config->display_thumbs_colsPerPage)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_THUMBS_PERPAGE?></td>
						<td><input class="text_area" type="text" name="display_thumbs_maxPerPage" size="10" value="<?php echo $config->display_thumbs_maxPerPage?>"/></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_SHOW_IMGNAME?></td>
						<td><?php echo mosHTML::yesnoRadioList( 'display_thumbs_showImgName','', $config->display_thumbs_showImgName )?></td>
					</tr>
					
					</table>
					</fieldset>
				</td>
				<td colspan="2" valign="top">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_WATERMARK?></legend>
					<table width="100%">
					<tr>
						<td colspan="2">
						<strong><?php echo $freeTypeSupport?></strong>
						</td>
					</tr>
					<tr>
						<td width="40%"><?php echo _RSGALLERY_C_TMPL_DISP_WTRMRK?></td>
						<td><?php echo mosHTML::yesnoRadioList('watermark','', $config->watermark)?></td>
					</tr>
					<!--
					<tr>
						<td width="40%">* Watermark type *</td>
						<td><?php // echo mosHTML::selectList($watermarkType, 'watermark_type','','value', 'text', $config->watermark_type)?></td>
					</tr>
					<tr>
						<td valign="top" width="40%">* Watermark upload *</td>
						<td></td>
					</tr>
					-->
					<tr>
						<td width="40%"><?php echo _RSGALLERY_C_FONT?></td>
						<td><?php echo galleryUtils::showFontList();?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_WTRMRK_TEXT?></td>
						<td><input class="text_area" type="text" name="watermark_text" size="50" value="<?php echo $config->watermark_text?>"/></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_WTRMRK_FONTSIZE?></td>
						<td><?php echo mosHTML::selectList($watermarkFontSize, 'watermark_font_size','','value', 'text', $config->watermark_font_size)?>&nbsp;&nbsp;pts</td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_WTRMRK_ANGLE?></td>
						<td><?php echo mosHTML::selectList($watermarkAngles, 'watermark_angle','','value', 'text', $config->watermark_angle)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_WTRMRK_POS?></td>
						<td><?php echo mosHTML::selectList($watermarkPosition, 'watermark_position','','value', 'text', $config->watermark_position)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_WATER_TRANS?></td>
						<td><?php echo mosHTML::selectList($watermarkTransparency, 'watermark_transparency','','value', 'text', $config->watermark_transparency)?><strong>%<strong></td>
					</tr>
					</table>
					</fieldset>

				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableFrontDisplay' );
		$tabs->endTab();
	
		$tabs->startTab( _RSGALLERY_CONF_USERS, 'rsgConfig' );
		?>
		<table border="0" width="100%">
			<tr>
				<td width="40%">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_ACL_SETINGS?></legend>
					<table width="100%">
					<tr>
						<td width="60%"><?php echo _RSGALLERY_C_TMPL_ACL_ENABLE?></td>
						<td><?php echo mosHTML::yesnoRadioList('acl_enabled', '', $config->acl_enabled)?></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_SHOW_MYGAL?></td>
						<td><?php echo mosHTML::yesnoRadioList('show_mygalleries', '', $config->show_mygalleries)?></td>
					</tr>	
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_U_CREATE_GAL?></td>
						<td><?php echo mosHTML::yesnoRadioList('uu_createCat', '', $config->uu_createCat)?></td>
					</tr>	
					</table>
					</fieldset>
				</td>
				<td width="60%">&nbsp;
				
				</td>
			</tr>
			<tr>
				<td width="40%">
					<fieldset>
					<legend><?php echo _RSGALLERY_C_TMPL_USER_SET?></legend>
					<table width="100%">
					<tr>
						<td width="60%"><?php echo _RSGALLERY_C_TMPL_U_MAX_GAL?></td>
						<td><input class="text_area" type="text" name="uu_maxCat" size="10" value="<?php echo $config->uu_maxCat?>"/></td>
					</tr>
					<tr>
						<td><?php echo _RSGALLERY_C_TMPL_U_MAX_IMG?></td>
						<td><input class="text_area" type="text" name="uu_maxImages" size="10" value="<?php echo $config->uu_maxImages?>"/></td>
					</tr>
					</table>
					</fieldset>
				</td>
				<td width="60%">&nbsp;
				
				</td>
			</tr>
		</table>
		<?php
		//$tmpl->displayParsedTemplate( 'configTableUsers' );
		$tabs->endTab();
		?>
		<input type="hidden" name="option" value="com_rsgallery2" />
		<input type="hidden" name="task" value="" />
		</form>
		<!-- Fix for Firefox browser -->
		<div style='clear:both;line-height:0px;'>&nbsp;</div>
		<?php
		$tabs->endPane();
		//$tmpl->displayParsedTemplate( 'configFinish' );
	}
}
