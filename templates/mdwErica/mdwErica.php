<?php
/**
* Morris DigitalWorks flash player by Erica
* @package RSGallery2
* @author Jonah Braun <Jonah@t.jonah.braun@morris.com>
*/
class rsgXmlGalleryTemplate_mdwErica extends rsgXmlGalleryTemplate_generic{
	function getName(){
		return 'MDW Erica';
	}

	/**
		Prepare XML first.  Then if there are errors we print an error before changing Content-Type to xml.
	**/
	function prepare( ){
		$showtitle = rsgInstance::getBool( 'showTitle', true );
		$imgSize = rsgInstance::getWord( 'imgSize', 'display' );
		
		$this->output = '<panels>';
	
		foreach( $this->gallery->items() as $img ){
			$this->output .= '  <panel loc="';
			
			switch( $imgSize ){
				case 'original':
					$resource = $img->getOriginal();
					$this->output .= $resource->url();
				break;
				case 'display':
					$resource = $img->getDisplay();
					$this->output .= $resource->url();
				break;
			}
			
			$this->output .= '" link="'. $img->descr .'" title="';
			
			if( $showtitle )
				$this->output .= $img->title;
				
			$this->output .= '" />'."\n";
		}
		
		$this->output .= '</panels>';
	}
}
?>