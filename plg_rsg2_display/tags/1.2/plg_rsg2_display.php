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
	if( !$matches )
		return '';

	global $mosConfig_absolute_path;
	require_once( $mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );
	
	$attribs = explode(",",$matches[1]);
	
	$newInstance = array(
		'rsgTemplate' => bot_rsg2_display_clean_data( strtolower( array_shift( $attribs ))),
		'gid' => (int) bot_rsg2_display_clean_data( array_shift( $attribs ))
		);
	
	foreach( $attribs as $a ){
		$attrib = explode('=',$a);
		$newInstance[ bot_rsg2_display_clean_data( $attrib[0] )] = bot_rsg2_display_clean_data( $attrib[1] );
	}
	ob_start();
	rsgInstance::instance( $newInstance );
	$content_output = ob_get_contents();
	ob_end_clean();	
		
	return $content_output;
}

function bot_rsg2_display_clean_data ( $attrib ) {//remove &nbsp; and trim white space
	$attrib = str_replace( "&nbsp;", '', "$attrib" );

	return trim( $attrib );
}
?>
