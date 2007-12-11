<?php
/**
* This file contains xxxxxxxxxxxxxxxxxxxxxxxxxxx.
* @version xxx
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $rsgOptions_path . 'search.html.php' );
//require_once( $rsgOptions_path . 'search.class.php' );

$cid = josGetArrayInts( 'cid' );
$task = rsgInstance::getVar( 'task', null);

switch ($task) {
	case 'showResults':
		showResults();
		break;
}

	function showResults() {
		global $database, $mosConfig_live_site, $rsgConfig;
		//Retrieve search string
		$searchtext 	= rsgInstance::getVar( 'searchtext'  , '');
		
		//Check searchtext against database
		$sql = "SELECT *, a.name as itemname, a.id as item_id FROM #__rsgallery2_files as a, #__rsgallery2_galleries as b " .
				"WHERE a.gallery_id = b.id " .
				"AND (" .
				"a.title LIKE '%$searchtext%' OR " .
				"a.descr LIKE '%$searchtext%'" .
				") " .
				"AND a.published = 1 " .
				"AND b.published = 1 " .
				"GROUP BY a.id " .
				"ORDER BY a.id DESC";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		
		//show results
		html_rsg2_search::showResults($result, $searchtext);		
	}


function showExtendedSearch() {
	echo "Extended search possibilities later!";
}
?>