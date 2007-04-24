<?php

/**
* xml file for Todd Dominey's free flash slideshow http://www.whatdoiknow.org/archives/001629.shtml
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgXmlGalleryTemplate_todd_dominey extends rsgXmlGalleryTemplate_generic{

    function getName(){
        return 'Todd Dominey free slideshow';
    }
    
    /**
        Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
        
        XML gallery paremeters:
'timer' :: number of seconds between each image transition
'order' :: how you want your images displayed. choose either 'sequential' or 'random'
'looping' :: if the slide show is in sequential mode, this stops the show at the last image (use 'yes' for looping, 'no' for not)
'fadeTime' :: velocity of image crossfade. Increment for faster fades, decrement for slower. Approximately equal to seconds.
'xpos' :: _x position of all loaded clips (0 is default)
'ypos' :: _y position of all loaded clips (0 is default)
    **/
    function prepare(){
        $this->output = '';
        
        $timer = (int)mosGetParam ( $_REQUEST, 'timer', 5 );
        $order = mosGetParam ( $_REQUEST, 'order', 'sequential' ) == 'sequential'? 'sequential' : 'random';
        $fadetime = (int)mosGetParam ( $_REQUEST, 'fadetime', 2 );
        $looping = mosGetParam ( $_REQUEST, 'looping', 'yes' ) == 'yes'? 'yes' : 'no';
        $xpos = (int)mosGetParam ( $_REQUEST, 'xpos', 0 );
        $ypos = (int)mosGetParam ( $_REQUEST, 'ypos', 0 );
        
        $this->output = '';
        $this->output .= <<<EOD

<gallery timer="$timer" order="$order" fadetime="$fadetime" looping="$looping" xpos="$xpos" ypos="ypos">

EOD;
//         print_r($this->gallery);die();
        foreach( $this->gallery->items() as $img ){
            $this->output .= '  <image path="';
            $this->output .= imgUtils::getImgDisplay( $img['name'] );
            $this->output .= "\" />\n";
        }
        
        $this->output .= '</gallery>';
    }
}
?>