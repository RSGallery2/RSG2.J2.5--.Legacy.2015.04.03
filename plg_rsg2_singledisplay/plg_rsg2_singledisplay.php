<?php
/**
* @version $Id:$
* @package RSGallery2
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
 
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
 

$_MAMBOTS->registerFunction( 'onPrepareContent', 'bot_rsg2_singledisplay' );
 
/**
* RSGallery Single Image Display Bot
*
* <b>Usage:</b>
* <code>{rsg2_singledisplay: imageid, size, caption}</code>
*/
function bot_rsg2_singledisplay( $published, &$row, $mask=0, $page=0  ) {
  global $mosConfig_absolute_path;
 
  if (!$published) {
    return true;
  }
 
  // define the regular expression for the bot
  $regex = "#{rsg2_singledisplay\:*(.*?)}#s";
 
  // perform the replacement
  $row->text = preg_replace_callback( $regex, 'bot_rsg2_singledisplay_replacer', $row->text );
}

/**
 * Code to replace {rsg2_singledisplay: imageid, size, caption}
 *
 * @param object $matches
 * @return output
 */
function bot_rsg2_singledisplay_replacer( &$matches ) {
	if( $matches ) {
		global $mosConfig_absolute_path;
		global $mosConfig_lang;
		
		// initialize RSGallery 2 
		require_once( $mosConfig_absolute_path.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );
		
		// get attributes from matches and create array
		$attribs = split( ',',$matches[1] );
		if ( is_array( $attribs ) ) {
			$clean_attribs = array ();
			foreach ( $attribs as $attrib ) {
				$clean_attrib = bot_rsg2_singledisplay_clean_data ( $attrib );
				array_push( $clean_attribs, $clean_attrib );
			}
		} else {
			return true;
		}
		
		
		if ( (int)$clean_attribs[0] ) {// check if imageID is numeric
			$image_attribute = $clean_attribs[0];// imageID
			if ( isset( $clean_attribs[1] ) ) {// check if Size is set
				$image_size = $clean_attribs[1];
			} else {
				$image_size = NULL;
			}
			if ( isset( $clean_attribs[2] ) ) {// check if caption is set
				$image_caption = bot_rsg2_singledisplay_bool($clean_attribs[2]);//make sure you get bool 
			} else {
				$image_caption = NULL;
			}

		} else {
			return true;// if nothing is set then User is did not use bot correctly SHOW NOTHING!
		}
		
		// obtain gallery object by the Images ID
		$gallery_object = rsgGalleryManager::getGalleryByItemID( $image_attribute );
		
		if ( is_object( $gallery_object ) ) {// check if gallery object was returned from ImageID
			$image_array = $gallery_object->getItem( $image_attribute );// get image array from gallery object	
		} else {
			return true; // if image array is not returned from gallery object then user specified wrong imageID SHOW NOTHING!
		}
		
		if ( is_array( ( $image_array ) ) ) {// Check if image array was returned
			$output = bot_rsg2_singledisplay_display( $image_array, $image_size, $image_caption);
			ob_start();// start output buffer
				echo $output;// output content
				$display_output = ob_get_contents(); // apply buffer to var
			ob_end_clean();// close buffer and clean up
			return $display_output; // return output content buffer
		} else {
			return true;// Something messed up some how not sure but SHOW NOTHING!
		}
	}
}

/**
 * Code that generates Image output
 *
 * @param array $image_array
 * @param string $image_size
 * @param bool $image_caption
 * @return string
 */
function bot_rsg2_singledisplay_display ( $image_array, $image_size ,$image_caption) {
	$output = '<div class="rsgSingleDisplayImage id_' . $image_array['id'] . '">';
	switch ( strtolower( $image_size ) ) {
		case "thumb":// thumbnail display
			$output .= '<img src="' . imgUtils::getImgThumb( $image_array['name'] ) . '" alt="' . $image_array['descr'] . '" border="0" />';
			break;
		
		case "display":// display set by RSGallery
			$output .= '<img src="' . imgUtils::getImgDisplay( $image_array['name'] ) . '" alt="' . $image_array['descr'] . '" border="0" />';
			break;
						
		case "original":// original image 
			$output .= '<img src="' . imgUtils::getImgOriginal( $image_array['name'] ) . '" alt="' . $image_array['descr'] . '" border="0" />';
			break;
			
		default:// display set by RSGallery
			$output .= '<img src="' . imgUtils::getImgDisplay( $image_array['name'] ) . '" alt="' . $RSGDisplay_image_array['descr'] . '" border="0" />';
			break;
	}
	
	// if image caption then output the description of the image 
	$image_caption ? $output .= '<div class=caption>' . $image_array['descr'] . '</div>' : $output .= "";
	$output .= '</div>';
	
	// return image ouput
	return $output;
}

/**
 * Converts string to bool
 *
 * @param string $var
 * @return bool
 */
function bot_rsg2_singledisplay_bool( $var ) {
	if ( $var === 1 ) {
		return true;
	} elseif ( $var === 0 ) {
		return false;
	}
	
    switch (strtolower($var)) {
        case ("true"):
            return true;
            break;
        case ("false"):
            return false;
            break;
        default:
            return false;
    }
}

function bot_rsg2_singledisplay_clean_data ( $attrib ) {//remove &nbsp; and trim white space
	$attrib = str_replace( "&nbsp;", '', "$attrib" );

	return trim( $attrib );
}
?>
