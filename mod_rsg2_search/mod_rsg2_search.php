<?php
/**************************************
 * File: mod_rsg2_search.php
 * RSGallery2 Search Module
 **************************************
 * @ Released under GNU/ GPL license
 * @ Author RSGallery2 Team
 * @ http://www.rsgallery2.nl
 * @ version 2.0.0
 **************************************/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.(mod_rsg2scroller)' );

$database = &JFactory::getDBO();

//Get an Itemid for an RSGallery2 link
$query = "SELECT id"
	. "\n FROM #__menu"
	. "\n WHERE published = 1"
	. "\n AND link = 'index.php?option=com_rsgallery2'"
	. "\n ORDER BY link"
	;
$database->setQuery( $query );
$RSG2Itemidobj = $database->loadObjectList();
if (count($RSG2Itemidobj) > 0)
	$RSG2Itemid = $RSG2Itemidobj[0]->id;
//echo 'RSG2Itemid: '.$RSG2Itemid.'<p>';

//initialise init file
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_rsgallery2'.DS.'init.rsgallery2.php');

//initialise search and display searchbox
require_once(JPATH_ROOT . DS . "components" . DS . "com_rsgallery2" . DS . "lib" . DS . "rsgsearch" . DS . "search.html.php");
html_rsg2_search::showSearchBox();