<?php
/**
 * @version		$Id$
 * @package		RSGallery2
 * @subpackage	Content
 * @copyright	Copyright (C) 2008 RSGallery2
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die('Restricted');

$mainframe->registerEvent( 'onPrepareContent', 'plg_rsg2_display' );

function plg_rsg2_display( &$row, &$params, $page=0 ) {
    // define the regular expression for the bot
    $regex = "#{rsg2_display\:*(.*?)}#s";
    
    // perform the replacement
    $row->text = preg_replace_callback( $regex, 'plg_rsg2_display_replacer', $row->text );
    
    return true;
}   

function plg_rsg2_display_replacer ( $matches ) {
	if( !$matches )
		return '';

	function plg_rsg2_display_replacer_clean_data ( $attrib ) {//remove &nbsp; and trim white space
		$attrib = str_replace( "&nbsp;", '', "$attrib" );

		return trim( $attrib );
	}
	
	require_once( JPATH_SITE. DS . "administrator" . DS . "components" . DS . "com_rsgallery2" . DS . "init.rsgallery2.php" );
	
	$attribs = explode(",",$matches[1]);
	
	$newInstance = array(
		'rsgTemplate' => plg_rsg2_display_replacer_clean_data( strtolower( array_shift( $attribs ))),
		'gid' => (int) plg_rsg2_display_replacer_clean_data( array_shift( $attribs ))
		);
	
	foreach( $attribs as $a ){
		$attrib = explode('=',$a);
		$newInstance[ plg_rsg2_display_replacer_clean_data( $attrib[0] )] = plg_rsg2_display_replacer_clean_data( $attrib[1] );
	}
	ob_start();
	rsgInstance::instance( $newInstance );
	$content_output = ob_get_contents();
	ob_end_clean();	
		
	return $content_output;
}
