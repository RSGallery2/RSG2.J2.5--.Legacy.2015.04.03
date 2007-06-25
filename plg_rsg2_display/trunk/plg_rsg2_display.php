<?php
/**
* @version $Id $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
 
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
 

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botMosRSGdisplay' );
 
/**
* Link bot
*
* <b>Usage:</b>
* <code>{moslink id="the_id"}</code>
*/
function botMosRSGdisplay( $published, &$row, $mask=0, $page=0  ) {
  global $mosConfig_absolute_path;
 
  if (!$published) {
    return true;
  }
 
  // define the regular expression for the bot
  $regex = "#{rsg2_display\:*(.*?)}#s";
 
  // perform the replacement
  $row->text = preg_replace_callback( $regex, 'bot_rsg2_display_replacer', $row->text );
 
  return true;
}
/**
 * Replace {mosrsgdisplay ID,TEMPLATE} with the gallery displayed with the specified template
 *
 * @param array $matches
 * @return string
 */
function bot_rsg2_display_replacer( &$matches ) {
	if($matches) {
		global $mosConfig_absolute_path;
		global $mosConfig_lang;
		
		$attribs = explode(",",$matches[1]);
	 	
	 	$gallery_attribute =  bot_rsg2_display_clean_data( $attribs[0] );
	 	( !isset( $attribs[1] ) ) ? $template_attribute = 'photoBox' : $template_attribute = bot_rsg2_display_clean_data( $attribs[1] );
	 	
	 
	 	
	 	if ( !file_exists( $mosConfig_absolute_path . '/components/com_rsgallery2/templates/' . $template_attribute . '/index.php' ) ) $template_attribute = 'photoBox';
	 	
	 	require_once( $mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );
	 	
	 	if ((int)$gallery_attribute) {	
		 	ob_start();
				rsgInstance::instance( array( 'rsgTemplate' => $template_attribute, 'gid' => $gallery_attribute ) );
				$content_output = ob_get_contents();
			ob_end_clean();	
			
			return $content_output;
	 	} else {
	 		return '';
	 	}
	} else {
		return '';
	}
}

function bot_rsg2_display_clean_data ( $attrib ) {//remove &nbsp; and trim white space
	$attrib = str_replace( "&nbsp;", '', "$attrib" );

	return trim( $attrib );
}
?>
