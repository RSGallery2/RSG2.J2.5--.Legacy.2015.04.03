<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

/*
class tempDisplay extends JObject{
	var $gallery;
	var $items;
	var $item;
	var $limitstart;

	function __construct(){
		$this->gallery = rsgInstance::getGallery();
	}
	
	function mainPageX(){
		global $my;
		$page = rsgInstance::getWord( 'page', '' );
	
		switch( $page ){
			case "edit_image":
				$id = rsgInstance::getInt('id'  , null);
				$this->edit_image($id);
			break;
			case "save_image":
				$this->save_image();
			break;
			case "delete_image":
				$this->delete_image();
			break;
			case "newusercat":
				$this->userCat($my->id, 0);
			break;
			case "editusercat":
				$this->usercat();
			break;
			case "makeusercat":
				$this->makeusercat(NULL);
			break;
			case "delusercat":
				$catid = rsgInstance::getInt( 'catid'  , null);
				$this->delusercat($catid);
			break;
			default:
				// we don't handle any of these pages
				return false;
		}
		// we handled the page, let rsgDisplay know.
		return true;
	}
	
	function addVoteX() {
		global $database, $Itemid;
	
		$picid = rsgInstance::getInt( 'picid'  , null);
		$limitstart = rsgInstance::getInt( 'limitstart'  , null);
		$vote = rsgInstance::getVar( 'vote'  , null);
			
		if ($vote)
			{
			//Retrieve values
			$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$picid'");
			$rows = $database->loadObjectList();
			foreach ($rows as $row)
				{
				$votes = $row->votes + 1;
				$rating = $row->rating + $vote;
				$ordering = $row->ordering - 1;
				//Store new values
				$database->setQuery("UPDATE #__rsgallery2_files SET votes = '$votes', rating = '$rating' WHERE id = '$row->id'");
				if ($database->query())
					{
					?>
					<script type="text/javascript">
						//<![CDATA[
						alert("<?php echo _RSGALLERY_THANK_VOTING; ?>");
						location = '<?php echo JRoute::_("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart, false); ?>';
						//]]>
					</script>
					<?php
					//mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart),_RSGALLERY_THANK_VOTING);
					}
				else
					{
					?>
					<script type="text/javascript">
						//<![CDATA[
						alert("<?php echo _RSGALLERY_VOTING_FAILED; ?>");
						location = '<?php echo JRoute::_("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart, false); ?>';
						//]]>
					</script>
					<?php
					//mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart),_RSGALLERY_VOTING_FAILED);
					}
				}
			}
		else
			{
			//mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart),_RSGALLERY_RATING_NOTSELECT);
			}
		}
}// end tempDisplay
*/
class rsgDisplay{
	
	function __construct(){
		$this->gallery = rsgInstance::getGallery();
	}
	
	function mainPage(){

		$page = rsgInstance::getWord( 'page', '' );

		switch( $page ){
			case 'slideshow':
				$gallery = rsgGalleryManager::get();
				rsgInstance::instance( array( 'rsgTemplate' => 'slideshowone', 'gid' => $gallery->id ));
			break;
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

	/**
	 *  write the footer
	 */
	function showRsgFooter(){
		global $rsgConfig, $rsgVersion;
	
		$hidebranding = '';
		if( $rsgConfig->get( 'displayBranding' ) == false )
			$hidebranding ="style='display: none'";
			
		?>
		<div id='rsg2-footer' <?php echo $hidebranding; ?>>
			<br /><br /><?php echo $rsgVersion->getShortVersion(); ?>
		</div>
		<div class='rsg2-clr'>&nbsp;</div>
		<?php
	}


	function display( $file = null ){
		global $rsgConfig;
		$template = preg_replace( '#\W#', '', rsgInstance::getVar( 'rsgTemplate', $rsgConfig->get('template') ));
		$templateDir = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template . DS . 'html';
	
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);
		
		include $templateDir . DS . $file;
	}

	/**
	 * Shows the top bar for the RSGallery2 screen
	 */
	function showRsgHeader() {
		$rsgOption 	= rsgInstance::getVar( 'rsgOption'  , '');
		$gid 		= rsgInstance::getVar( 'gid'  , null);
		
		if (!$rsgOption == 'mygalleries' AND !$gid) {
			?>
			<div class="rsg2-mygalleries">
				<a class="rsg2-mygalleries_link" href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;rsgOption=myGalleries");?>">My Galleries</a>
			</div>
			<div class="clr"></div>
			<?php
		}
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
     */
	function showRSPathWay() {
		global $mainframe, $mosConfig_live_site, $Itemid, $option;

		// if rsg2 isn't the component being displayed, don't show pathway
		if( $option != 'com_rsgallery2' )
			return;

		$gallery = rsgInstance::getGallery();
		$currentGallery = $gallery->id;

		$item = rsgInstance::getItem();

		$galleries = array();
		$galleries[] = $gallery;

		while ( $gallery->parent != 0) {
			$gallery = $gallery->parent();
			$galleries[] = $gallery;
		}

		$galleries = array_reverse($galleries);

		if( defined( 'J15B_EXEC' ) ){
			// J1.0 method
			foreach( $galleries as $gallery ) {
				if ( $gallery->id == $currentGallery && empty($item) ) {
					$mainframe->appendPathWay($gallery->name);
				} else {
					$mainframe->appendPathWay('<a href="' . $mosConfig_live_site . '/index.php?option=com_rsgallery2&amp;Itemid='.$Itemid.'&amp;gid=' . $gallery->id . '">' . $gallery->name . '</a>');
				}
			}

			if (!empty($item)) {
				$mainframe->appendPathWay( $item->title );
			}
		}
		else{
			// J1.5 method
			$pathway =& $mainframe->getPathway();
			
			foreach( $galleries as $gallery ) {
				if ( $gallery->id == $currentGallery && empty($item) ) {
					$pathway->addItem( $gallery->name );
				} else {
					$link = 'index.php?option=com_rsgallery2&gid=' . $gallery->id;
					$pathway->addItem( $gallery->name, $link );
				}
			}

			if (!empty($item)) {
				$mainframe->appendPathWay( $item->title );
			}
		}
	}

	/**
		insert meta data into head
	**/
	function metadata(){
		global $mainframe, $option;

		// if rsg2 isn't the component being displayed, don not append meta data
		if( $option != 'com_rsgallery2' )
			return;

		$mainframe->setPageTitle( ' '. $this->gallery->get('name') );
		$mainframe->appendMetaTag( 'description',  htmlspecialchars(strip_tags($this->gallery->get('description')),ENT_QUOTES) );
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
            <img id="thumb1" src="<?php waterMarker::showMarkedImage($name);?>" alt="<?php echo htmlspecialchars(stripslashes($descr), ENT_QUOTES); ?>" border="0"  />
            <?php
        } else {
            ?>
            <img id="thumb1" src="<?php echo imgUtils::getImgDisplay($name); ?>" alt="<?php echo htmlspecialchars(stripslashes($descr), ENT_QUOTES); ?>" border="0"  />
            <?php
        }
    }
    /**
     * Shows the comments screen
     */
    function _showComments() {
    	global $mainframe, $mosConfig_live_site;
    	$id = rsgInstance::getVar( 'id'  , '');
    	$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/rsgcomments/rsgcomments.css\" type=\"text/css\" />";
		$mainframe->addCustomHeadTag($css);
    	
		$comment = new rsgComments();
		$comment->showComments($id);
    	$comment->editComment($id);
    }
    
    function _showVotes() {
    	global $mainframe, $mosConfig_live_site;
    	$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/rsgvoting/rsgvoting.css\" type=\"text/css\" />";
    	$mainframe->addCustomHeadTag($css);
    	$voting = new rsgVoting();
    	$voting->showVoting();
    }
    
    /**
     * Shows random images
     * @todo: Rewrite to work within template system
     */
    function showRandom2($rows, $style = "hor") {
        global $mosConfig_live_site;
        if ($style == "vert") {
            ?>
            <ul id='rsg2-galleryList'>
                <li class='rsg2-galleryList-item' >
                    <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td><?php echo _RSGALLERY_RANDOM_TITLE;?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    foreach($rows as $row) {
                        $l_start = $row->ordering - 1;
                        ?>
                        <tr>
                        <td align="center">
                            <div align="center">
                                <a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
                                <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" />
                                </a>
                            </div>
                        </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </table>
                </li>
            </ul>
            <?php
        } else {
            ?>
            <ul id='rsg2-galleryList'>
                <li class='rsg2-galleryList-item' >
                    <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td colspan="3"><?php echo _RSGALLERY_RANDOM_TITLE;?></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <?php
                        foreach($rows as $row)
                            {
                            $l_start = $row->ordering - 1;
                            ?>
                            <td align="center">
                            <div align="center">
                            <a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>"  />
                            </a>
                            </div>
                            </td>
                            <?php
                            }
                        ?>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    </table>
                </li>
            </ul>
            <?php
        }
    }
    /**
     * Shows latest images
     * @todo: Rewrite to work within template system
     */
	function showLatest2($rows, $style = "hor") {
        global $mosConfig_live_site;
        if ($style == "vert") {
            ?>
            <ul id='rsg2-galleryList'>
                <li class='rsg2-galleryList-item' >
                    <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td><?php echo _RSGALLERY_RANDOM_TITLE;?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    foreach($rows as $row) {
                        $l_start = $row->ordering - 1;
                        ?>
                        <tr>
                        <td align="center"><div align="center">
                            <a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>"  />
                            </a></div>
                        </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </table>
                </li>
            </ul>
            <?php
        } else {
            ?>
            <ul id='rsg2-galleryList'>
                <li class='rsg2-galleryList-item' >
                    <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td colspan="3"><?php echo _RSGALLERY_LATEST_TITLE;?></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <?php
                        foreach($rows as $row)
                            {
                            $l_start = $row->ordering - 1;
                            ?>
                            <td align="center"><div align="center">
                            <a href="<?php echo sefRelToAbs($mosConfig_live_site."/index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$l_start);?>">
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>"  />
                            </a></div>
                            </td>
                            <?php
                            }
                        ?>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    </table>
                </li>
            </ul>
            <?php
        }
    }
}
?>