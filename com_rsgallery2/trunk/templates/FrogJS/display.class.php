<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2007 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @FrogJS: http://www.dynamicdrive.com/dynamicindex4/frogjs/index.htm
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay_FrogJS extends rsgDisplay_semantic{
	if (isset($_REQUEST['catid'])) $catid= mosGetParam ( $_REQUEST, 'catid'  , '');
	if($catid){
		function showThumbs(){
			global $database, $rsgConfig, $rsgImagesItem;
		 	echo "<div id='FrogJS'>";
			foreach( $this->gallery->items() as $img ):
				$thumb = imgUtils::getImgThumb( $img['name'] );
				$display = imgUtils::getImgDisplay( $img['name'] );
				$original = imgUtils::getImgOriginal( $img['name'] );
				$title = $img['title'];
				$descr = $img['descr'];
				echo <<<EOD
					<a href="$display" title='$title' rel="$original">
						<img src='$thumb' alt='$descr'/>
					</a>
				EOD;
			endforeach;
			echo"</div>";
		}
	}
}