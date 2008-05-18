<?php
/**
 * @version		$Id: rsgallery.php 9875 john caprez $
 * @package		RSGallery2
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * See COPYRIGHT.php for copyright notices and details.
 */


// To have thumbnails displayed in the search result, change in file : 
// \administrator\components\com_search\helpers\search.php
// line 130 :		
// return SearchHelper::_smartSubstr( strip_tags( $text ), $length, $searchword );
// to :
// return SearchHelper::_smartSubstr( $text , $length, $searchword );

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchRSGallery2' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchRSGallery2Areas' );

JPlugin::loadLanguage( 'plg_search_rsgallery2' );

/**
 * @return array An array of search areas
 */
function &plgSearchRSGallery2Areas() {
	static $areas = array();
	$areas['gallery'] = 'Gallery';
	return $areas;
}

/**
* RSGallery2 Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
 */
function plgSearchRSGallery2( $text, $phrase='', $ordering='', $areas=null )
{

	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();
	
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchRSGallery2Areas() ) )) {
			return array();
		}
	}
	
	// load plugin params info
	$plugin =& JPluginHelper::getPlugin('search', 'rsgallery2');
	$pluginParams = new JParameter( $plugin->params );
	
	$limit = $pluginParams->def( 'search_limit', 50 );
	$showImage = $pluginParams->def( 'show_image', 1 );
	
	$text = trim( $text );
	if ($text == '') {
		return array();
	}
	
	$wheres 	= array();
	switch ($phrase)
	{
		case 'exact':
			$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
			$wheres2 	= array();
			$wheres2[] 	= 'LOWER(f.descr) LIKE '.$text;
			$wheres2[] 	= 'LOWER(f.title) LIKE '.$text;
			$where 		= '(' . implode( ') OR (', $wheres2 ) . ')';
			break;
		
		case 'all':
		case 'any':
		default:
			$words 	= explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word)
			{
				$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
				$wheres2 	= array();
				$wheres2[] 	= 'LOWER(f.descr) LIKE '.$word;
				$wheres2[] 	= 'LOWER(f.title) LIKE '.$word;
				$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}
	
	switch ( $ordering )
	{
		case 'oldest':
			$order = 'f.date ASC';
			break;
		
		case 'popular':
			$order = 'f.hits DESC';
			break;
		
		case 'alpha':
			$order = 'f.title ASC';
			break;
		
		case 'category':
			$order = 'g.title ASC, f.title ASC';
			break;
		
		case 'newest':
		default:
			$order = 'f.date DESC';
	}


	$db->setQuery( 'SELECT value from #__rsgallery2_config WHERE name="acl_enabled";');
	
	if($db->loadResult() == "1")
	{
		$aclWHERE = ' AND f.gallery_id = a.gallery_id ';
		if($user->gid == 0){
			$aclWHERE .= ' AND a.public_view = 1 ';
		}
		else{
			$aclWHERE .= ' AND (a.public_view = 1 OR a.registered_view = 1) ';
		}
		$aclFROM = ', #__rsgallery2_acl as a ';
	}
		
	$sql = 'SELECT f.title as title, ' .
		'f.id as id, ' .
		'f.descr as text, ' .
		'f.date as created, ' .
		'f.name as imageName, ' .
		'g.name as galleryName ' .
		'FROM #__rsgallery2_files as f, #__rsgallery2_galleries as g ' . $aclFROM .
		'WHERE f.gallery_id = g.id ' .
		$aclWHERE .
		'AND ' . $where .
		'AND f.published = 1 ' .
		'AND g.published = 1 ' .
		'ORDER BY '. $order ;
	
	
	$db->setQuery( $sql, 0, $limit );
	$rows = $db->loadObjectList();
	
	foreach($rows as $key => $row) {
		$rows[$key]->href = JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$rows[$key]->id);
		$rows[$key]->section = JText::_("Gallery") ."/" . $rows[$key]->galleryName;
		$rows[$key]->browsernav = '2';
		
		if($showImage == 1)
			$text = $rows[$key]->text;
			$rows[$key]->text = "<img src=\"" . 
								JURI::base() . "images/rsgallery/thumb/" . $rows[$key]->imageName .".jpg\" alt=\"\" style=\"float:left;padding:0 0.5em 0.5em 0;\" />" . 
								$text . 
								"<p style=\"clear:left;\" ></p>";
	}
	
	return $rows;
}