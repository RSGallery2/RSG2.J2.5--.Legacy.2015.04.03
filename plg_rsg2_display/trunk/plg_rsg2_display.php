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
  $regex = "#{mosrsgdisplay\:*(.*?)}#s";
 
  // perform the replacement
  $row->text = preg_replace_callback( $regex, 'botMosRSGdisplay_replacer', $row->text );
 
  return true;
}
/**
 * Replace {mosrsgdisplay ID,TEMPLATE} with the gallery displayed with the specified template
 *
 * @param array $matches
 * @return string
 */
function botMosRSGdisplay_replacer( &$matches ) {
	if($matches) {
		global $mosConfig_absolute_path;
		global $mosConfig_lang;
		
		$attribs = explode(",",$matches[1]);
	 	
	 	$RSGDislay_gallery_attribute = $attribs[0];
	 	$RSGDislay_template_attribute = $attribs[1];
	 	
	 	require_once( $mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );
	 	require_once( JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . 'meta' . DS . 'display.class.php' );
	 	
	 	$_REQUEST['gid'] = $RSGDislay_gallery_attribute;
	 	
	 	ob_start();
	 		RSGDisplay_template($RSGDislay_template_attribute);
	 		$RSGDislay_content_output = ob_get_contents();
	 	ob_end_clean();
	 	
	 	
	 	return $RSGDislay_content_output;
	} else {
		return '';
	}
}

function RSGDisplay_template($template){
	global $rsgConfig;
	
	//Set template selection
	$template = preg_replace( '#\W#', '', $template);
	
	define( 'JPATH_RSGALLERY2_TEMPLATE', JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template );
	require_once( JPATH_RSGALLERY2_TEMPLATE . DS . 'index.php');
}
?>