<?php
// 
/*
* class Handles all tag related routines
*  	August 24, 2008
*	Chef Groovy (chefgroovy@fantasyartwork.net)
*/

defined( '_VALID_MOS' ) or die( 'Access Denied' );


class tagUtils extends fileUtils {
	
/*
*		Returns array of all tags for a given image id
*/
	function getTagsForImage ($imageNumber) {
		global $database;		


		$query="SELECT * FROM #__rsgallery2_tagmatch WHERE image_id=" . $imageNumber . " GROUP BY tag_id";
		
		$query ="SELECT image_id, tag_id, name FROM #__rsgallery2_tagmatch  INNER JOIN #__rsgallery2_tags ON (#__rsgallery2_tagmatch.tag_id=#__rsgallery2_tags.id) WHERE image_id=" . $imageNumber . " GROUP BY tag_id";
 

	    $database->setQuery( $query );
		$tagsFound = $database->loadObjectList();

		return $tagsFound;
	}  // End Get Tags


/*
*	displays tags for an image
*/
	function showTagsForImage ($imageNumber) {
		$taglist = tagUtils::getTagsForImage($imageNumber);
		
		foreach ( $taglist as $tag ) {
			echo $tag->name . ", ";	
		}
		
		
	}
	
} // END CLASS 

?>