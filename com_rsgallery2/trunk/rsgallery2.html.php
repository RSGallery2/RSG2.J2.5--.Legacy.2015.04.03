<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay extends tempDisplay{
	
	
	function mainPage(){
		 $this->metadata();

		// if tempDisplay handles this function let it, otherwise continue as regularily scheduled.
		if( parent::mainPage() )
			return;
	
		$page = mosGetParam ( $_REQUEST, 'page', '' );

		switch( $page ){
			
			case 'inline':
				$this->inline();
			break;
			case 'viewChangelog':
				$this->viewChangelog();
			break;
			default:
				$this->showMainGalleries();
				$this->showThumbs();
		}
		
	}

	function showRSTopBar(){
	
	}
	function showRandom(){
	
	}
	function showLatest(){
	
	}
	
	/**
		set Itemid for proper pathway and linking.
		contributed by Jeckel
	**/
	function setItemid(){
		global $Itemid;
		
		if (! isset($Itemid) || empty($Itemid) || $Itemid == '99999999') {
			$query = "SELECT id"
				. "\n FROM #__menu"
				. "\n WHERE published = 1"
				. "\n AND access <= ".$GLOBALS['my']->gid
				. "\n AND link = 'index.php?option=".$_REQUEST['option']."'"
				. "\n ORDER BY link"
				;
			$GLOBALS['database']->setQuery( $query );
			$mitems = $GLOBALS['database']->loadObjectList();
			if (count($mitems) > 0)
				$Itemid = $mitems[0]->id;
		}
	}

	function viewChangelog() {
		global $mosConfig_absolute_path, $rsgConfig;
	
		if( !$rsgConfig->get('debug')){
			echo _RSGALLERY_FEAT_INDEBUG;
			return;
		}
		
		echo '<pre style="text-align: left;">';
		readfile( $mosConfig_absolute_path . '/administrator/components/com_rsgallery2/changelog.php' );
		echo '</pre>';
	}
	
	
    /**
     * shows proper Joomla path
     * contributed by Jeckel
     */
    function showRSPathWay() {
        global $mainframe, $database, $mosConfig_live_site, $Itemid, $gid, $imgid;
        
        $gid        = mosGetParam ( $_REQUEST, 'catid', 0 );
        $imgid      = mosGetParam ( $_REQUEST, 'id', 0 );

        if ($gid != 0) {
            $database->setQuery('SELECT * FROM #__rsgallery2_galleries WHERE id = "'. $gid . '"');
            $rows = $database->loadObjectList();

            $cat = $rows[0];
            $cats = array();
            array_push($cats, $cat);
            
            while ($cat->parent != 0) {
                $database->setQuery('SELECT * FROM #__rsgallery2_galleries WHERE id = "' . $cat->parent . '"');
                $rows = $database->loadObjectList();
                $cat = $rows[0];
                array_unshift($cats, $cat);
            }    // while
            
            reset($cats);
            foreach($cats as $cat) {
                if ($cat->id == $gid && empty($imgid)) {
                    $mainframe->appendPathWay($cat->name);
                } else {
                    $mainframe->appendPathWay('<a href="' . $mosConfig_live_site . '/index.php?option=com_rsgallery2&amp;Itemid='.$Itemid.'&amp;catid=' . $cat->id . '">' . $cat->name . '</a>');
                }    // if
            }    // foreach
        }    // if
        
        if (!empty($imgid)) {
            $database->setQuery('SELECT title FROM #__rsgallery2_files WHERE id = "'. $imgid . '"');
            $imgTitle = $database->loadResult();
            $mainframe->appendPathWay($imgTitle);
        }    // if
    }

	/**
		insert meta data into head
	**/
	function metadata(){
		global $mainframe;
		$mainframe->setPageTitle( ' '. $this->gallery->get('name') );
		$mainframe->appendMetaTag( 'description', $this->gallery->get('description') );
	}

	/***************************
		private functions
	***************************/
	
    /**
     * shows the image
     */
    function _showImageBox($name, $descr) {
        global $rsgConfig ;

        if ($rsgConfig->get('watermark') == true) {
            ?>
            <img id="thumb1" src="<?php waterMarker::showMarkedImage($name);?>" alt="<?php echo htmlspecialchars(stripslashes($descr), ENT_QUOTES); ?>" border="0" width="<?php echo $rsgConfig->get('image_width');?>" />
            <?php
        } else {
            ?>
            <img id="thumb1" src="<?php echo imgUtils::getImgDisplay($name); ?>" alt="<?php echo htmlspecialchars(stripslashes($descr), ENT_QUOTES); ?>" border="0" width="<?php echo $rsgConfig->get('image_width');?>" />
            <?php
        }
    }
}


?>