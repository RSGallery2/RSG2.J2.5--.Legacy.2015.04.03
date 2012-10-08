<?php
/**
* RSGallery2 latest galleries module: shows latest galleries from the Joomla extension RSGallery2 (www.rsgallery2.nl).
* @copyright (C) 2012 RSGallery2 Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @version 3.0.0
**/

defined('_JEXEC') or die('Restricted access');

// Initialise RSGallery2 and other variables
require_once(JPATH_BASE.'/administrator/components/com_rsgallery2/init.rsgallery2.php');
$database = &JFactory::getDbo();

// Add styling
$document =& JFactory::getDocument();
$url = JURI::base().'modules/mod_rsgallery2_latest_galleries/css/mod_rsgallery2_latest_galleries.css';
$document->addStyleSheet($url);

global $rsgConfig;

// ****************** Parameters *****************************************
// Number of  latest galleries to display = number of rows times the number of columns
$countrows			= (int) $params->get('countrows', 		'1');
$countcolumns		= (int) $params->get('countcolumns',	'1');
$count				= $countrows * $countcolumns;
// Select one or more galleries and set if their subgalleries (children) should be included
$galleryids			= $params->get('galleryids', 			'0'); //string, e.g. 3,8,42
$includeChildren	= $params->get('includechildren', 		'0');
// Display type of image to show: thumb (0), display (1), original (2)
$displaytype 		= (int) $params->get('displaytype', 	'0');
// CSS height and/or width attribute for the img and the div element (0=no attribute)
$imageheight 		= (int) $params->get('imageheight', 	'0');
$imagewidth 		= (int) $params->get('imagewidth', 		'0');
$divheight 			= (int) $params->get('divheight', 		'0');
$divwidth 			= (int) $params->get('divwidth', 		'0');
// ... for the div with class mod_rsgallery2_latest_galleries_name
$divNameHeight		= (int) $params->get('divnameheight', 	'0');
//$divNameWidth		= (int) $params->get('divnamewidth', 	'0');	// The width setting of the class mod_rsgallery2_latest_galleries_attibute would overrule this, so makes no sense to do this now?
// Display the gallery name
$displayname 		= $params->get('displayname', 			'0');
// Display the date and its format
$displaydate 		= $params->get('displaydate', 			'0');
$dateformat 		= $params->get('dateformat', 			'd-m-Y');
// ****************** Parameters - end ***********************************
// ****************** Collect CSS styling from parameters ****************
// Get CSS image heigth/width attributes
$imgAttributes="";
if ($imageheight > 0) $imgAttributes .= ' height="'.$imageheight.'px"';
if ($imagewidth > 0)  $imgAttributes .= ' width="'.$imagewidth.'px"';
// Get CSS image heigth/width attributes
$divAttributes="";
if (($divheight) or ($divwidth)) {
	$divAttributes .= 'style=overflow:hidden;';
	if ($divheight > 0) $divAttributes .= 'height:'.$divheight.'px;';
	if ($divwidth > 0)  $divAttributes .= 'width:'.$divwidth.'px;';
	$divAttributes .= '"';
}
$divNameAttributes="";
if (($divNameHeight)) {
	$divNameAttributes .= 'style=overflow:hidden;';
	if ($divNameHeight > 0) $divNameAttributes .= 'height:'.$divNameHeight.'px;';
	//if ($divNameWidth > 0)  $divNameAttributes .= 'width:'.$divNameWidth.'px;';// The width setting of the class mod_rsgallery2_latest_galleries_attibute would overrule this, so makes no sense to do this now?
	$divAttributes .= '"';
}
// ****************** Collect CSS styling from parameters - end **********

// Get RSGallery2 Itemid from first component menu item for use in links
$RSG2Itemid = Null;
$query = $database->getQuery(true);
$query->select('id');
$query->from('#__menu');
$query->where('published = 1');
$query->where("link like 'index.php?option=com_rsgallery2%'");
$query->order('link');
$database->setQuery($query);
$RSG2ItemidObj = $database->loadObjectList();
if (count($RSG2ItemidObj) > 0) {
	$RSG2Itemid = $RSG2ItemidObj[0]->id;
}

// ****************** Take View Access into account **********************
$user 		= JFactory::getUser();
$groups		= $user->getAuthorisedViewLevels();
$groupsIN 	= implode(", ",array_unique ($groups));
$superAdmin = $user->authorise('core.admin');
// ****************** Take View Access into account - end ****************

// ****************** Select specific galleries and possibly subs ********
if ($galleryids) {
	$galleryarray = explode(',', $galleryids);
	// Include children?
	if ($includeChildren) {
		//Function to help out
		function treerecurse($id,  $list, &$children, $maxlevel=20, $level=0) {
			//if there are children for this id and the max.level isn't reached
			if (@$children[$id] && $level <= $maxlevel) {
				//add each child to the $list and ask for its children
				foreach ($children[$id] as $v) {
					$id = $v->id;	//gallery id
					$list[$id] = $v;
					$list[$id]->level = $level;
					//$list[$id]->children = count(@$children[$id]);
					$list = treerecurse($id,  $list, $children, $maxlevel, $level+1);
				}
			}
			return $list;
		}
		// Get a list of all galleries (id/parent) ordered by parent/ordering
		$database =& JFactory::getDBO();
		$query = $database->getQuery(true);
		$query->select('id,parent');
		$query->from('#__rsgallery2_galleries');
		$query->order('parent, ordering');
		$database->setQuery( $query );
		$allGalleries = $database->loadObjectList();
		// Establish the hierarchy by first getting the children: 2dim. array $children[parentid][]
		$children = array();
		if ( $allGalleries ) {
			foreach ( $allGalleries as $v ) {
				$pt     = $v->parent;
				$list   = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		// Get the children of the userselected galleries
		$selectedgalleries = $galleryarray;
		foreach ($galleryarray as $gal) {
			$galleryselection = 
			// Get list of galleries with (grand)children in the right order and with level info
			$recursiveGalleriesList = treerecurse( $gal, array(), $children, 20, 0 );
			foreach ($recursiveGalleriesList as $gal) {
				array_push($selectedgalleries, $gal->id);
			}
		}
		$galleryselection = implode(", ",array_unique ($selectedgalleries));
	} else {	// Don't include children
		$galleryselection = implode(", ",array_unique ($galleryarray));
	}
} else {
	// No 'where' clause needed to limit the search of galleries from
	$galleryselection = 0;
} 
// ****************** Select specific galleries and possibly subs - end **


// ****************** Query **********************************************
// Query to get limited ($count) number of latest galleries
//$query = "SELECT * FROM #__rsgallery2_files $list ORDER BY date DESC LIMIT $count";
$result = Null;
$query = $database->getQuery(true);
$query->select('*');
$query->from('#__rsgallery2_galleries');
$query->where('published = 1');
// If user is not a Super Admin then use View Access Levels
if (!$superAdmin) { // No View Access check for Super Administrators
	$query->where('access IN ('.$groupsIN.')'); //@todo use trash state: published=-2
}
// Select only galleries from what user wants
if ($galleryselection) {
	$query->where('id IN ('.$galleryselection.')');
}
$query->order('date DESC');
$database->setQuery($query,0,$count);	//$count is the number of results to return
//$latestGalleries = $database->loadObjectList();
$latestGalleries = $database->loadAssocList();
if(!$latestGalleries){
	// Error handling
}
// ****************** Query - end ***************************************

// ****************** Output *********************************************
// Let's diplay what we've gathered: get the layout
require JModuleHelper::getLayoutPath('mod_rsgallery2_latest_galleries', $params->get('layout', 'default'));
// ****************** Output - end ***************************************