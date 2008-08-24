<?php
// Handles tag lookups and functins


defined( '_VALID_MOS' ) or die( 'Access Denied' );


class tagUtils extends fileUtils {
	
	function test ($imageNumber) {
				
		echo "TagUtils: " . $imageNumber;
	
	}
	
	/*
		Returns array of all tags for a given image id
	*/
	
	function getTagsForImage ($imageNumber) {
		global $database;
		

echo $imageNumber . "<br/>";

		$query="SELECT * FROM #__rsgallery2_tagmatch WHERE image_id=" . $imageNumber . " GROUP BY tag_id";
		
		$query ="SELECT image_id, tag_id, name FROM #__rsgallery2_tagmatch  INNER JOIN #__rsgallery2_tags ON (#__rsgallery2_tagmatch.tag_id=#__rsgallery2_tags.id) WHERE image_id=" . $imageNumber . " GROUP BY tag_id";
 

	    $database->setQuery( $query );
		$tagsFound = $database->loadObjectList();

       
	   if( $tagsFound==null ){
            echo "No tags for image"  . "<br/>";
         
        } else {
			echo "tags found: " . count($tagsFound) . "<br/>";
		}

	


//		foreach ( $tagsFound as $tag ) {
//			echo $tag->tag_id . "<br/>";
//
//		}
				 
		return $tagsFound;
		
	}
	

	
} // END CLASS 

?>