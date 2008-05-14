<?php
/**
* @version		$Id: mod_rsg2.php 10079 2008-02-28 13:39:08Z ircmaxell $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );


$newInstance = array(
	'rsgTemplate' => $params->get( 'rsgTemplate' ),
	'gid' => $params->get( 'gid' )
	);

/*
 * support xtra params in the future:
foreach( $attribs as $a ){
	$attrib = explode('=',$a);
	$newInstance[ plg_rsg2_display_replacer_clean_data( $attrib[0] )] = plg_rsg2_display_replacer_clean_data( $attrib[1] );
}
*/

rsgInstance::instance( $newInstance );
