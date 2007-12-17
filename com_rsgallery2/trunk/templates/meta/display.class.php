<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

class rsgDisplay extends JObject{
	
	var $params = null;
	
	function __construct(){
		global $rsgConfig;
		
		$this->gallery = rsgInstance::getGallery();

		$template = $rsgConfig->get('template');

		// load parameters
		jimport('joomla.filesystem.file');
		// Read the ini file
		$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'params.ini';
		if (JFile::exists($ini)) {
			$content = JFile::read($ini);
		} else {
			$content = null;
		}
		$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template .DS.'templateDetails.xml';
		$this->params = new JParameter($content, $xml, 'rsgTemplate');
		
	}
	
	/**
	 * Switch for the main page, when not handled by rsgOption
	 */
	function mainPage(){
		global $rsgConfig;
		$page = rsgInstance::getWord( 'page', '' );

		switch( $page ){
			case 'slideshow':
				$gallery = rsgGalleryManager::get();
				rsgInstance::instance( array( 'rsgTemplate' => $rsgConfig->get('current_slideshow'), 'gid' => $gallery->id ));
			break;
			case 'inline':
				$this->inline();
			break;
			case 'viewChangelog':
				$this->viewChangelog();
			break;
			case 'test':
				$this->test();
				break;
			default:
				$this->showMainGalleries();
				$this->showThumbs();
		}
	}
	
	/**
	 * Debug only
	 */
	function test() {
		
		echo "test code goes here!";
		$folders = JFolder::folders('components/com_rsgallery2/templates');
		foreach ($folders as $folder) {
			if (preg_match("/slideshow/i", $folder)) {
				$folderlist[] = $folder;
			}
		}
		echo "<pre>";
		print_r($folderlist);
		echo "</pre>";
		
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

	/**
	 * 
	 */
	function display( $file = null ){
		global $rsgConfig;
		$template = preg_replace( '#\W#', '', rsgInstance::getVar( 'rsgTemplate', $rsgConfig->get('template') ));
		$templateDir = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template . DS . 'html';
	
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);

		if(!defined("J15B_EXEC") ) {
			// add this for page size listbox handling in J1.5
			echo '<form action="'.JRoute::_("index.php?option=com_rsgallery2&gid=".$this->gallery->id).'" method="post">';
			include $templateDir . DS . $file;
			echo '</form>';
		} else {
			include $templateDir . DS . $file;
		}
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
				<a class="rsg2-mygalleries_link" href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries");?>">My Galleries</a>
			</div>
			<div class="rsg2-clr"></div>
			<?php
		}
	}
	
	/**
	 * set Itemid for proper pathway and linking.
	 * contributed by Jeckel
	 */
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

	/**
	 * Shows contents of changelog.php in preformatted layout
	 */
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
					$mainframe->appendPathWay('<a href="' . JRoute::_('index.php?option=com_rsgallery2&Itemid='.$Itemid.'&gid=' . $gallery->id) . '">' . $gallery->name . '</a>');
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
	 * insert meta data into head
	 */
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
    	global $mainframe, $mosConfig_live_site, $rsgConfig;
    	if ($rsgConfig->get('comment')) {
    		$id = rsgInstance::getVar( 'id'  , '');
    		$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/rsgcomments/rsgcomments.css\" type=\"text/css\" />";
			$mainframe->addCustomHeadTag($css);
		
			$comment = new rsgComments();
			$comment->showComments($id);
			$comment->editComment($id);
		} else {
			echo _RSGALLERY_COMMENTS_DISABLED;
		}
    }
    
    /**
     * Shows the voting screen
     */
    function _showVotes() {
    	global $mainframe, $mosConfig_live_site, $rsgConfig;
    	if ($rsgConfig->get('voting')) {
    		$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/rsgvoting/rsgvoting.css\" type=\"text/css\" />";
    		$mainframe->addCustomHeadTag($css);
    		$voting = new rsgVoting();
    		$voting->showVoting();
    	} else {
    		echo _RSGALLERY_VOTING_DISABLED;
    	}
    }
    
    /**
     * Shows either random or latest images, depending on parameter
     * @param String Type of images. Options are 'latest' or 'random'
     * @param Int Number of images to show. Defaults to 3
     * @param String Style, options are 'vert' or 'hor'.(Vertical or horizontal)
     * @return HTML representation of image block.
     * @todo Rewrite to make use of RSGallery2 framework instead of straight SQL
     */
    function showImages($type="latest", $number = 3, $style = "hor") {
    	global $database, $mosConfig_live_site, $Itemid, $rsgConfig;
		
		//Check if backend permits showing these images
		if ( $type == "latest" AND !$rsgConfig->get('displayLatest') ) {
			return;
		} elseif ( $type == "random" AND !$rsgConfig->get('displayRandom') ) {
			return;
		}
		
    	switch ($type) {
    		case 'random':
    			$database->setQuery("SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.descr".
                        " FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal".
                        " WHERE file.gallery_id=gal.id and gal.published=1 AND file.published=1".
                        " ORDER BY rand() limit $number");
    			$rows = $database->loadObjectList();
    			$title = _RSGALLERY_RANDOM_TITLE;
    			break;
    		case 'latest':
    			$database->setQuery("SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.descr".
                        " FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal".
                        " WHERE file.gallery_id=gal.id AND gal.published=1 AND file.published=1".
                        " ORDER BY file.date DESC LIMIT $number");
    			$rows = $database->loadObjectList();
    			$title = _RSGALLERY_LATEST_TITLE;
    			break;
    	}
    	
    	if ($style == "vert") {
    	?>
    	     <ul id='rsg2-galleryList'>
                <li class='rsg2-galleryList-item' >
                    <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td><?php echo $title;?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    foreach($rows as $row) {
                        $l_start = $row->ordering - 1;
						$url = JRoute::_("index.php?option=com_rsgallery2&page=inline&id=".$row->id);
                        ?>
                        <tr>
                        <td align="center">
                            <div class="shadow-box">
                            	<div class="img-shadow">
                            	<a href="<?php echo $url;?>">
                                <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" width="<?php echo $rsgConfig->get('thumb_width');?>" />
                                </a>
                            	</div>
                                <div class="rsg2-clr"></div>
                                <div class="rsg2_details"><?php echo mosFormatDate($row->date);?></div>
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
                        <td colspan="3"><?php echo $title;?></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <?php
                        foreach($rows as $row)
                            {
                            $l_start = $row->ordering - 1;
							$url = JRoute::_("index.php?option=com_rsgallery2&page=inline&id=".$row->id);
                            ?>
                            <td align="center">
                            <div class="shadow-box">
                            	<div class="img-shadow">
                            	<a href="<?php echo $url;?>">
                            	<img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" width="<?php echo $rsgConfig->get('thumb_width');?>"  />
                            	</a>
                            	</div>
                            	<div class="rsg2-clr"></div>
								<div class="rsg2_details"><?php echo _RSGALLERY_TMPL_GAL_UPLOADED ?>&nbsp;<?php echo mosFormatDate($row->date, "%d-%m-%Y");?></div>
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
	 * Write downloadlink for image
	 * @param int image ID
	 * @param string Text below button
	 * @param string Button or HTML link (button/link)
	 * @return HTML for downloadlink
	 */
	function _writeDownloadLink($id, $showtext = true, $type = 'button') {
		global $rsgConfig, $mosConfig_live_site;
		if ( $rsgConfig->get('displayDownload') ) {
			echo "<div class=\"rsg2-toolbar\">";
			if ($type == 'button') {
				?>
				<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>">
				<img height="20" width="20" src="<?php echo $mosConfig_live_site;?>/administrator/images/download_f2.png" alt="<?php echo _RSGALLERY_DOWNLOAD;?>">
				<?php
				if ($showtext == true) {
					?>
					<br /><span style="font-size:smaller;"><?php echo _RSGALLERY_DOWNLOAD?></span>
					<?php
				}
				?>
				</a>
				<?php
			} else {
				?>
				<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>"><?php echo _RSGALLERY_DOWNLOAD;?></a>
				<?php
			}
			echo "</div><div class=\"rsg2-clr\">&nbsp;</div>";
		}
	}
	
	/**
	 * Provides unformatted EXIF data for the current item
	 * @result Array with EXIF values
	 */
	function _showEXIF() {
		require_once(JPATH_ROOT . DS . "components" . DS . "com_rsgallery2" . DS . "lib" . DS . "exifreader" . DS . "exifReader.php");
		$image = rsgInstance::getItem();
		$filename = JPATH_ROOT . $image->original->name;
		
		$exif = new phpExifReader($filename);
		$exif->showFormattedEXIF();
 	}    
    
    function showSearchBox() {
    	global $Itemid, $mainframe, $mosConfig_live_site;
    	$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css\" type=\"text/css\" />";
    	$mainframe->addCustomHeadTag($css);
    	?>

    	<div align="right">
    	<form name="rsg2_search" method="post" action="index.php">
    		<?php echo _RSGALLERY_SEARCH_LABEL;?>
    		<input type="text" name="searchtext" class="searchbox" onblur="if(this.value=='') this.value='<?php echo _RSGALLERY_SEARCH_KEYWORDS;?>';" onfocus="if(this.value=='<?php echo _RSGALLERY_SEARCH_KEYWORDS;?>') this.value='';" value='<?php echo _RSGALLERY_SEARCH_KEYWORDS;?>' />
			<input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="search" />
			<input type="hidden" name="task" value="showResults" />
			<input type="hidden" name="Itemid" value="<?php echo $Itemid ;?>" />
    	</form>
    	</div>
    	<?php
    }
    /*
    function showRSTopBar() {
        global $my, $mosConfig_live_site, $rsgConfig, $Itemid;
        $catid =rsgInstance::getInt( 'catid', 0 );
        $Itemid = rsgInstance::getInt( 'Itemid', 0 );
        $page = rsgInstance::getVar( 'page'  , null);
        ?>
        <div style="float:right; text-align:right;">
        <ul id='rsg2-navigation'>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid); ?>">
                <?php echo _RSGALLERY_MAIN_GALLERY_PAGE; ?>
                </a>
            </li>
            <?php 
            if ( !$my->id == "" && $page != "my_galleries" && $rsgConfig->get('show_mygalleries') == 1):
            ?>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid."&amp;rsgOption=myGalleries");?>">
                <?php echo _RSGALLERY_MY_GALLERIES; ?>
                </a>
            </li>
            <?php
            elseif( $page == "slideshow" ): 
            ?>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&catid=".$catid."&id=".$_GET['id']);?>">
                <?php echo _RSGALLERY_SLIDESHOW_EXIT; ?>
                </a>
            </li>
        <?php endif; ?>
        </ul>
        </div>
        <div style="float:left;">
        <?php if( isset( $catid )): ?>
            <h2 id='rsg2-galleryTitle'><?php htmlspecialchars(stripslashes(galleryUtils::getCatNameFromId($catid)), ENT_QUOTES) ?></h2>
        <?php elseif( $page != "my_galleries" ): ?>
            <h2 id='rsg2-galleryTitle'><?php echo _RSGALLERY_COMPONENT_TITLE ?></h2>
        <?php endif; ?>
        </div>
        <?php
    }
	*/
}
?>