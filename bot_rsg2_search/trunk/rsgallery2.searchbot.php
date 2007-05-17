<?php
/**
* @version $Id: weblinks.searchbot.php 1268 2005-11-30 22:55:50Z eddieajau $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$_MAMBOTS->registerFunction( 'onSearch', 'botSearchRSGallery2' );

/**
* Weblink Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, name, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
*/
function botSearchRSGallery2( $text, $phrase='', $ordering='' ) {
	global $database, $my, $mosConfig_live_site;
	
	$query = "SELECT * FROM #__rsgallery2_config WHERE `name` = 'imagepath'";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	
	$imagedir = $rows[0]->value;	
	
	$query = "SELECT id"
	. "\n FROM #__menu"
	. "\n WHERE link = 'index.php?option=com_rsgallery2'";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	$itemid = $rows[0]->id;	
	
	// load mambot params info
	$query = "SELECT id"
	. "\n FROM #__mambots"
	. "\n WHERE element = 'rsgallery2.searchbot'"
	. "\n AND folder = 'search'"
	;
	$database->setQuery( $query );
	$id 	= $database->loadResult();
	$mambot = new mosMambot( $database );
	$mambot->load( $id );
	$botParams = new mosParameters( $mambot->params );
	
	$limit = $botParams->def( 'search_limit', 50 );
	
	$text = trim( $text );
	if ($text == '') {
		return array();
	}
	
	$wheres 	= array();
	switch ($phrase) {
		case 'exact':
			$wheres2 = array();

			$wheres2[] = "LOWER(a.name) LIKE '%$text%'";
			$wheres2[] = "LOWER(a.descr) LIKE '%$text%'";
			$wheres2[] = "LOWER(a.title) LIKE '%$text%'";
			$where = '(' . implode( ') OR (', $wheres2 ) . ')';
			break;

		case 'all':
		case 'any':
		default:
			$words 	= explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$wheres2 = array();
		  		$wheres2[] 	= "LOWER(a.name) LIKE '%$word%'";
				$wheres2[] 	= "LOWER(a.descr) LIKE '%$word%'";
				$wheres2[] 	= "LOWER(a.title) LIKE '%$word%'";
				$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}

	switch ( $ordering ) {
		case 'oldest':
			$order = 'a.date ASC';
			break;

		case 'popular':
			$order = 'a.ordering ASC';
			break;

		case 'alpha':
			$order = 'a.name ASC';
			break;

		case 'category':
			$order = 'b.name ASC, a.name ASC';
			break;

		case 'newest':
		default:
			$order = 'a.date DESC';
	}
	
	$linkurl = $mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline";
	$link = $mosConfig_live_site."/".$imagedir."";
	
	$query = "SELECT CONCAT(a.title,' - Foto') AS title,"
	. "\n a.descr AS text,"
	. "\n a.date AS created,"
	. "\n '0' AS browsernav,"
	. "\n CONCAT('$linkurl','&id=',a.id,'&catid=',a.gallery_id,'&limitstart=',a.ordering-1) AS href"
	. "\n FROM #__rsgallery2_files AS a"
	. "\n INNER JOIN #__rsgallery2_galleries"
	. "\n ON a.gallery_id=#__rsgallery2_galleries.id"
	. "\n WHERE ($where)"
	. "\n AND #__rsgallery2_galleries.published = 1"
	. "\n ORDER BY $order"
	;
	$database->setQuery( $query, 0, $limit );
	
	$rows = $database->loadObjectList();
	
	return $rows;
}
?>