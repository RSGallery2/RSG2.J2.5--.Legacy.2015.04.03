<?php
/**
* This file handles image manipulation functions RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2005 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

defined( '_JEXEC' ) or die( 'Access Denied' );

/**
* Image utilities class
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class videoUtils extends fileUtils{
    function allowedFileTypes(){
        return array('flv');
    }
    
    /**
      * thumb and display are resized into jpeg of first frame of video
      * @param string name of original image
      * @return filename of image
      */
    function getImgNameThumb($name){
        return $name . '.jpg';
    }
    
    /**
      * thumb and display are resized into jpeg of first frame of video
      * @param string name of original image
      * @return filename of image
      */
    function getImgNameDisplay($name){
        return $name . '.jpg';
    }
    
    function getVideoName($name){
        return $name . '.flv';
    }
    
    function getImgPreviewName($name){
        return $name . '.png';
    }
    
    /**
     * Takes an image file, moves the file and adds database entry
     * @param the verified REAL name of the local file including path
     * @param name of file according to user/browser or just the name excluding path
     * @param desired category
     * @param title of image, if empty will be created from $name
     * @param description of image, if empty will remain empty
     * @todo deleteImage (video)
     * @return returns true if successfull otherwise returns an ImageUploadError
     */
    function importImage($tmpName, $name, $cat, $title='', $desc='') {
        global $rsgConfig;
		$my =& JFactory::getUser();
		$database =& JFactory::getDBO();

        $destination = fileUtils::move_uploadedFile_to_orignalDir( $tmpName, $name );
        
        if( is_a( $destination, imageUploadError ) )
            return $destination;

		$parts = pathinfo( $destination );
        // fill $imgTitle if empty
        if( $imgTitle == '' ) 
            $imgTitle = substr( $parts['basename'], 0, -( strlen( $parts['extension'] ) + ( $parts['extension'] == '' ? 0 : 1 )));

        // replace names with the new name we will actually use
        $parts = pathinfo( $destination );
        $newName = $parts['basename'];
        $imgName = $parts['basename'];
        
        //Destination becomes original video, just for readability
        $original_video = $destination;
        
        // New video vill be locate to same directory
        $newVideo = JPATH_ORIGINAL . DS . $newName . ".flv";
        $result = videoUtils::convertVideo( $original_video, $newVideo );
        if( PEAR::isError( $result )){
        	//videoUtils::deleteImage( $newName );
            return new imageUploadError( $imgName, "error converting video: <pre>" . print_r( $result->getMessage(), true) ."</pre>" );
		}
        
        // First frame of video
        $videoPreviewImage =  JPATH_ORIGINAL . DS . $newName . ".png";
		$result = videoUtils::capturePreviewImage( $original_video, $videoPreviewImage );
        if( PEAR::isError( $result )){
        	//videoUtils::deleteImage( $newName );
            return new imageUploadError( $imgName, "error capturing preview image: <pre>" . print_r( $result->getMessage(), true) ."</pre>" );
		}
		
		//Get details of the original image.
        $width = getimagesize( $videoPreviewImage );
        if( !$width ){
            imgUtils::deleteImage( $newName );
            return new imageUploadError( $videoPreviewImage, "not an image OR can't read $videoPreviewImage" );
        } else {
            //the actual image width
            $width = $width[0];
        }
            // if original is wider than display, create a display image
        if( $width > $rsgConfig->get('image_width') ) {
            $result = imgUtils::makeDisplayImage( $videoPreviewImage, $newName, $rsgConfig->get('image_width') );
            if( PEAR::isError( $result )){
                imgUtils::deleteImage( $newName );
                return new imageUploadError( $imgName, "error creating display image: <pre>" . print_r( $result->getMessage(), true) ."</pre>" );
            }
        } else {
            $result = imgUtils::makeDisplayImage( $videoPreviewImage, $newName, $width );
            if( PEAR::isError( $result )){
                imgUtils::deleteImage( $newName );
                return new imageUploadError( $imgName, "error creating display image: <pre>" . print_r( $result->getMessage(), true)  ."</pre>");
                }
        }
           
        // if original is wider than thumb, create a thumb image
        if( $width > $rsgConfig->get('thumb_width') ){
            $result = imgUtils::makeThumbImage( $videoPreviewImage, $newName );
            if( PEAR::isError( $result )){
                imgUtils::deleteImage( $newName );
                return new imageUploadError( $imgName, "error creating thumb image: " . $result->getMessage() );
            }
        }


        // determine ordering
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id = '$cat'");
        $ordering = $database->loadResult() + 1;
        
        //Store image details in database
        $desc = mysql_real_escape_string($desc);
        $title = mysql_real_escape_string($title);
        $database->setQuery("INSERT INTO #__rsgallery2_files".
                " (title, name, descr, gallery_id, date, ordering, userid) VALUES".
                " ('$title', '$name', '$desc', '$cat', now(), '$ordering', '$my->id')");
        
        if (!$database->query()){
            imgUtils::deleteImage( $parts['basename'] );
            return new imageUploadError( $parts['basename'], $database->stderr(true) );
        }

        return true;
    }

}
/**
  * abstract image library class
  * @package RSGallery2
  */
class genericVideoLib{
    /**
     * video conversion to flv function
     * @param string full path of source video
     * @param string full path of target video (FLV)
     * @return true if successfull, PEAR_Error if error
     * @todo not final yet
     */
    function convertVideo($source, $target){	
        return new PEAR_Error( 'this is the abstract image library class, no resize available' );
    }

	/**
     * preview image capture function
     * @param string full path of source video
     * @param string full path of target image (PNG)
     * @return true if successfull, PEAR_Error if error
     * @todo not final yet
     */
    function capturePreviewImage($source, $target){
		return new PEAR_Error( 'this is the abstract image library class, no resize available' );
    }    
    /**
      * detects if image library is available
      * @return false if not detected, user friendly string of library name and version if detected
      */
    function detect(){
        return false;
    }
}
/**
 * FFMPEG handler class
 * @package RSGallery2
 */
class Ffmpeg extends genericVideoLib{
    /**
     * video conversion to flv function
     * @param string full path of source video
     * @param string full path of target video (FLV)
     * @return true if successfull, PEAR_Error if error
     * @todo not final yet
     */
    function convertVideo($source, $target){
        global $rsgConfig;
        
        // if path exists add the final /
        $ffmpeg_path = $rsgConfig->get( "ffmpeg_path" );
        $ffmpeg_path = $ffmpeg_path==''? '' : $ffmpeg_path.'/';
        //ffmpeg -i 03022008011.mp4 -deinterlace -ar 22050 -ab 56 -acodec mp3 -r 25 -f flv -b 400 -s 320x240 output.flv
        
        $ffmpeg_params = " -deinterlace -ar 22050 -ab 56 -acodec mp3 -r 25 -f flv -b 400 -s 320x240 ";
        
        $cmd = $ffmpeg_path . "ffmpeg -i " . $source . $ffmpeg_params . " " . $target;
        @exec($cmd);
    }
    
    /**
     * preview image capture function
     * @param string full path of source video
     * @param string full path of target image (PNG)
     * @return true if successfull, PEAR_Error if error
     * @todo not final yet
     */
    function capturePreviewImage($source, $target){
        global $rsgConfig;
        
        // if path exists add the final /
        $ffmpeg_path = $rsgConfig->get( "ffmpeg_path" );
        $ffmpeg_path = $ffmpeg_path==''? '' : $ffmpeg_path.'/';
        //ffmpeg -vframes 1 -i 19072007013.mp4 -vcodec png -an -f rawvideo test.png
        
        $ffmpeg_params = " -vframes 1 -vcodec png -an -f rawvideo ";
        
        $cmd = $ffmpeg_path . "ffmpeg -i " . $source . $ffmpeg_params . " " . $target;
        @exec($cmd);
    }

    /**
      * detects if image library is available
      * @return false if not detected, user friendly string of library name and version if detected
      */
    function detect($shell_cmd = '', $output = '', $status = ''){
        @exec($shell_cmd. 'jpegtopnm -version 2>&1',  $output, $status);
        if(!$status){
            if(preg_match("/netpbm[ \t]+([0-9\.]+)/i",$output[0],$matches)){
                return $matches[0];
            }
            else
            	return false;
        }
    }
} // END CLASS FFMPEG
?>