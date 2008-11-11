<?php 
// no direct access
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function getLabel($labeltext, $row)  {
		
		$dateformat = "M j, Y";
		
	switch ($labeltext) {
		
		case "DateAdded":
			return date($dateformat ,strtotime($row->filedate));
			break;
		case "Hits":
			return _RSGALLERY_IMAGEHITS . ": " . $row->imghits;
			break;
		default:
			return "";
			break;
	}
	
	
	

}


?>
