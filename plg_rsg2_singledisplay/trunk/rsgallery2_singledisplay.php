<?php
/**
* @version $Id:$
* @package RSGallery2
* @copyright Copyright (C) 2007 Jonathan DeLaigle. (plugin for Joomla 1.0.x.)
* @copyright Copyright (C) 2010 Radek Kafka. (Migration of plugin to Joomla 1.5.x and addition of Highslide using Highslide JS for Joomla plugin)
* @copyright Copyright (C) 2011 RSGallery2 Team. (Addition of popup options and the popup styles: No popup, Normal popup, Joomla Modal. Code slightly re-arranged.)
* @copyright Copyright (C) 2012 RSGallery2 Team. (Code changed for Joomla 2.5 and addition of clearfloat and modal behaviour)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Usage example: {rsg2_singledisplay:9999,thumb,true,left;float:left,both}
*	 imageid: Backend > Components > RSGallery2 > Items: use the number from the ID column.
*	 size: thumb, display or original.
*	 caption: true (use the item desciption as a caption) or false (no caption).
*	 format: text-align style property
*	 clearfloat: both, left, right (clears float after image with extra div with style clear:both/left/right) or false for no added div
*/
/* ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die();

class plgContentrsgallery2_singledisplay extends JPlugin {

	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		// Simple performance check to determine whether bot should process further.
		if (JString::strpos($article->text, 'rsg2_singledisplay') === false) {
			return true;
		}

		// Define the regular expression for the bot.
		$regex = "#{rsg2_singledisplay\:*(.*?)}#s";

		// Perform the replacement.
		$article->text = preg_replace_callback($regex, array(&$this, '_replacer'), $article->text);

		return true;
	}	

	/**
	 * Replaces the matched tags.
	 *
	 * @param	array	An array of matches (see preg_match_all)
	 * @return	string
	 */
	protected function _replacer( &$matches ) {
		if( $matches ) {
			// initialize RSGallery2 
			require_once( JPATH_BASE.'/administrator/components/com_rsgallery2/init.rsgallery2.php' );

			// get attributes from matches and create array
			$attribs = explode( ',',$matches[1] );
			if ( is_array( $attribs ) ) {
				$clean_attribs = array ();
				foreach ( $attribs as $attrib ) {
					// Remove spaces (&nbsp;) from attributes and trim whith space
					$clean_attrib = $this->bot_rsg2_singledisplay_clean_data ( $attrib );
					array_push( $clean_attribs, $clean_attrib );
				}
			} else {
				return false;
			}
		
			// check if imageID is numeric
			if ( (int)$clean_attribs[0] ) {
				// Get imageID from attribs
				$image_id = $clean_attribs[0];
				// If Size is set get it from attribs
				if ( isset( $clean_attribs[1] ) ) {
					$image_size = $clean_attribs[1];
				} else {
					$image_size = NULL;
				}
				// If caption is set get it from attribs
				if ( isset( $clean_attribs[2] ) ) {
					// Make sure you get bool from (string) $clean_attribs[2]
					$image_caption = $this->bot_rsg2_singledisplay_bool($clean_attribs[2]);
				} else {
					$image_caption = NULL;
				}
				// If align is set get it from attribs
				if (isset( $clean_attribs[3] ) ) {
					$image_align = $clean_attribs[3];
				} else {
					$image_align = NULL;
				}
				if (isset( $clean_attribs[4] ) ) {
					$clearfloat = $clean_attribs[4];
				} else {
					$clearfloat = NULL;
				}
			// no (numerid) imageID
			} else {
				// if nothing is set then the User did not use bot correctly SHOW NOTHING!
				return false;
			}
		
			// obtain gallery object by the Images ID
			$gallery_object = rsgGalleryManager::getGalleryByItemID( $image_id );
			
			// check if gallery object was returned from ImageID
			if ( is_object( $gallery_object ) ) {
				// get image array from gallery object	
				$image_object = $gallery_object->getItem( $image_id );
			} else {
				// if image object is not returned from gallery object then user specified wrong imageID SHOW NOTHING!
				return false; 
			}
			
			// Check if image array was returned
			if ( is_object( ( $image_object ) ) ) {
				$output = $this->bot_rsg2_singledisplay_display( $image_object, $image_size, $image_caption, $image_align, $clearfloat);
				// start output buffer (trun output buffering on)
				ob_start(); 
					// output content
					echo $output;
					// apply buffer to var (get contents of output buffer wihtout clearing it)
					$display_output = ob_get_contents(); 
				// close buffer and clean up
				ob_end_clean();
				// return output content buffer
				return $display_output; 
			} else {
				return true;// Something messed up some how not sure but SHOW NOTHING!
			}
		}	//endif matches
	}

	/**
	 * Code that generates Image output
	 *
	 * @param object $image_object
	 * @param string $image_size
	 * @param bool $image_caption
	 * @param string $image_align 
	 * @return string
	 * Example: {rsg2_singledisplay:9999,thumb,true,left;float:left} 
	 */
	function bot_rsg2_singledisplay_display ( $image_object, $image_size ,$image_caption, $image_align, $clearfloat) {
		// Get plugin info
		$pluginName = 'rsgallery2_singledisplay';
		$plugin =& JPluginHelper::getPlugin('content', $pluginName);
		// Parameters
		$pluginParams = new JParameter( $plugin->params );
		$popupStyle = $pluginParams->get('popup_style', 'normal_popup');

		//Get parameters of item object (e.g. Link and Link Text)
		$params_obj = $image_object->parameters();
		
		//Start $output with div class with/without align style
		if ($image_align != '') {
			$output = '<div class="rsgSingleDisplay id_' . $image_object->id . '" style="text-align: '.$image_align.';">';
		} else {
			$output = '<div class="rsgSingleDisplay id_' . $image_object->id . '">';
		}

		// rsgItem Object can be audio, video (not implemented) or image (continue $output)
		// For audio objects
		if( is_a( $image_object, 'rsgItem_audio' ) ) {
			$audio = $image_object->original();
			// thumb
			if ( strtolower( $image_size ) == "thumb" ) {
				$output .= '<object type="application/x-shockwave-flash" width="17" height="17" data="' . JURI::base() . 'components/com_rsgallery2/flash/xspf/musicplayer.swf?song_title=' . $image_object->name . '&song_url=' . $audio->url() .'"><param name="movie" value="' . JURI::base() . 'components/com_rsgallery2/flash/xspf/musicplayer.swf?song_title=' . $image_object->name
					.'&song_url=' . $audio->url() . '" /></object>';
			// not thumb
			} else {
			$output .= '<object type="application/x-shockwave-flash" width="400" height="15" data="' . JURI::base() . 'components/com_rsgallery2/flash/xspf/xspf_player_slim.swf?song_title=' . $image_object->name . '&song_url=' . $audio->url() .'"><param name="movie" value="' . JURI::base() . 'components/com_rsgallery2/flash/xspf/xspf_player_slim.swf?song_title=' . $image_object->name
					.'&song_url=' . $audio->url() . '" /></object>';			
			}
			
			//Use link and link text from item parameter?
			if ( $params_obj->get( 'link_text','' ) ) {
				$parse_url = parse_url( $params_obj->get( 'link', '' ) );
				( $parse_url['scheme'] == "http" ) ? $link = $params_obj->get( 'link', '' ) : $link = 'http://' . $params_obj->get( 'link', '' );
				$output .= '<a href="' . $link . '">';
				$output .= $image_output . '<br />';
				if( $params_obj->get( 'link_text','' ) ){ $output .= $params_obj->get( 'link_text','' ); }
				$output .= '</a>';
			} else {
				$output .= $image_output;
			}

		// For image objects
		} else {
			// First set several different things... (before continueing with $output)
			// Set original object and url of original image 
			$original 	= $image_object->original();
			$original_url = $original->url();
			// Set description
			$description = $image_object->descr;
			// Set $image_url based on $image_size (thumb, display or original)
			switch (strtolower($image_size)) {
				case "thumb":		// thumbnail display
					$thumb 		= $image_object->thumb();
					$image_url 	= $thumb->url();
					break;
				case "display":		// display set by RSGallery
					$display 	= $image_object->display();
					$image_url 	= $display->url();
					break;
				case "original":	// original image 
					$image_url 	= $original->url();
					break;
				default:			// display set by RSGallery
					$display 	= $image_object->display();
					$image_url 	= $display->url();
					break;
			}
			// Item Parameters can have Link and Link Text
			if ( $params_obj->get('link','') ) {
				$parameter_Link_Available = true;
				// Parse url from link to see if 'http://' needs to be added
				$parse_url = parse_url( $params_obj->get( 'link', '' ) );
				if ( isset($parse_url['scheme']) AND $parse_url['scheme'] == "http" ) {
					$parameter_Link = $params_obj->get( 'link', '' );
				} else {
					$parameter_Link = 'http://' . $params_obj->get( 'link', '' );
				}
				//Get the link text if available
				if( $params_obj->get( 'link_text','' ) ){ 
					$parameter_LinkText = $params_obj->get( 'link_text','' ); 
				} else {
					$parameter_LinkText = '';
				}
			} else {
				$parameter_Link_Available = false;
			}

		
			// Now, continue on $output... based on parameter $popupStyle
			switch ($popupStyle) {
				case "no_popup":
					// Just show the (thumb/display/original) image without a link
					$output .= '<img src="' . $image_url . '" alt="' . $description . '" border="0" />';
					// if image caption then output the description of the image 
					if ($image_caption) {
						$output .= '<div class=rsg2-caption>' . $description . '</div>';
					}
					break;
				case "use_link":
					// Show image using item parameter(s) Link and Link Text if available
					if ( $parameter_Link_Available ) {
						$output .= '<a href="' . $parameter_Link . '">';
						$output .= '<img src="' . $image_url . '" alt="' . $description . '" border="0" />';
						$output .= '<br />';
						if( $parameter_LinkText ){
							$output .= $parameter_LinkText; 
						}
						$output .= '</a>';
					} else {
						$output .= '<img src="' . $image_url . '" alt="' . $description . '" border="0" />';
					}
					// if image caption then output the description of the image 
					if ($image_caption) {
						if ($image_align != '') {
							$output .= '<div class=rsg2-caption style="text-align: '.$image_align.';">' . $description . '</div>';
						} else {
							$output .= '<div class=rsg2-caption>' . $description . '</div>';
						}

					}
					break;
				case "highslide":
					// Show image with Highslide effect (needs Highslide JS plugin!)
					// Highslide: links to original url - part 1 of 2
					$image_output = '<a href="' . $original_url . '" class="highslide" onclick="return hs.expand(this, { fadeInOut:true,dimmingOpacity:0.75 })"> ';
					// Highslide: shows thumb/display/image url
					$image_output .= '<img src="' . $image_url . '" alt="' . $description . '" border="0" />';
					// Highslide: links to original url - part 2 of 2
					$image_output .= '</a>';

					// Add Highslide stuff to $outpout...
					$output .= $image_output;
					// ...with image caption?
					if ($image_caption) {
						$output .= '<div class=highslide-caption>' . $description . '</div>';
					}
					break;
				case "modal":
					// Show image with modal behaviour
					JHTML::_('behavior.modal');
					$output .= '<a href="'.$original_url.'" class="modal"><img class="modal" src="' . $image_url . '" alt="' . $description . '" border="0" /></a>';
					// If image caption then output the description of the image 
					if ($image_caption) {
						$output .= '<div class=rsg2-caption>' . $description . '</div>';
					}
					break;
				case "normal_popup":	//this is default as well
				default:
					// Show image with link to original image
					$output .= '<a href="' . $original_url . '" target="_blank" > ';
					$output .= '<img src="' . $image_url . '" alt="' . $description . '" border="0" />';
					$output .= '</a>';
					// if image caption then output the description of the image 
					if ($image_caption) {
						if ($image_align != '') {
							$output .= '<div class=rsg2-caption style="text-align: '.$image_align.';">' . $description . '</div>';
						} else {
							$output .= '<div class=rsg2-caption>' . $description . '</div>';
						}
					}

					break;
			}	//end switch $popupStyle 

		}	//end of image rsgItem Object
		
		
		// end of div class="rsgSingleDisplay id_.."
		$output .= '</div>';
		
		// If user set clearfloat output a div with style clear both/left/right
		if ($clearfloat) {
			$output .= '<div style="clear:'.$clearfloat.';"></div>';
		}

		// return image ouput
		return $output;
	}

	/**
	 * Converts string attribute in plugin to bool
	 *
	 * @param string $var
	 * @return bool
	 */
	function bot_rsg2_singledisplay_bool( $var ) {
		if ( $var === '1' ) {			//compare (===) to string so '1' and not 1!
			return true;
		} elseif ( $var === '0' ) {
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

	/**
	 * Remove spaces (&nbsp;) from attributes and trim whith space
	 *
	 * @param string $var
	 * @return string
	 */
	function bot_rsg2_singledisplay_clean_data ( $attrib ) {
		$attrib = str_replace( "&nbsp;", '', "$attrib" );

		return trim( $attrib );
	}
}