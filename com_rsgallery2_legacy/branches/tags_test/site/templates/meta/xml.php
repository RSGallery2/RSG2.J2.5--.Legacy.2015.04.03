<?php

/**
* abstract parent class for xml templates
* @package RSGallery2
* @author Jonah Braun <Jonah@WhaleHosting.ca>
*/
class rsgXmlGalleryTemplate_generic{
    var $gallery;

	// deprecated
    var $output;

    /**
        class constructor
        @param rsgGallery object
    **/
    function rsgXmlGalleryTemplate_generic( $gallery ){
        $this->gallery = $gallery;
    }

    /**
        Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
    **/
    function prepare(){
        ?>
<gallery name="<?php echo $this->gallery->name; ?>">
        
<?php foreach( $this->gallery->itemRows() as $img ): ?>
	<image name="<?php echo $img['name']; ?>" />
<?php endforeach; ?>

</gallery>
		<?php
    }
    
    /**
        print xml headers
    **/
    function printHead(){
        header('Content-Type: application/xml');
        echo '<?xml version="1.0" encoding="iso-8859-1"?>';
    }
    
    /**
        outputs XML
		@deprecated this function is only to support older xml templates
    **/
    function printGallery(){
        if( $this->output )
			echo $this->output;
    }
}
?>
