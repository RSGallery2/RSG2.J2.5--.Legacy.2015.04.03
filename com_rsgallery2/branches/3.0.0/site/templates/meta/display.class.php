<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted Access' );

class rsgDisplay extends JObject{
	
	var $params = null;
	
	var $currentItem = null;
	function __construct(){
		global $rsgConfig;
		
		$this->gallery = rsgInstance::getGallery();

		$template = $rsgConfig->get('template');

		// load template parameters
		jimport('joomla.filesystem.file');
		// Read the ini file
		$ini	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template.DS.'params.ini';
		if (JFile::exists($ini)) {
			$content = JFile::read($ini);
		} else {
			$content = null;
		}
		$xml	= JPATH_RSGALLERY2_SITE .DS. 'templates'.DS.$template .DS.'templateDetails.xml';
		$this->params = new JParameter($content, $xml, 'template');
		
	}
	
	/**
	 * Switch for the main page, when not handled by rsgOption
	 */
	function mainPage(){
		global $rsgConfig;
		$page = JRequest::getWord( 'page', '' );

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
		$template = preg_replace( '#\W#', '', JRequest::getVar( 'rsgTemplate', $rsgConfig->get('template') ));
		$templateDir = JPATH_RSGALLERY2_SITE . DS . 'templates' . DS . $template . DS . 'html';
	
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);

		include $templateDir . DS . $file;
	}

	/**
	 * Shows the top bar for the RSGallery2 screen
	 */
	function showRsgHeader() {
		$rsgOption 	= JRequest::getVar( 'rsgOption'  , '');
		$gid 		= JRequest::getVar( 'gid'  , null);
		
		if (!$rsgOption == 'mygalleries' AND !$gid) {
			?>
			<div class="rsg2-mygalleries">
			<a class="rsg2-mygalleries_link" href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries");?>"><?php echo JText::_('COM_RSGALLERY2_MY_GALLERIES') ?></a>
			</div>
			<div class="rsg2-clr"></div>
			<?php
		}
	}
	
	/**
	 * Shows contents of changelog.php in preformatted layout
	 */
	function viewChangelog() {
		global $rsgConfig;
	
		if( !$rsgConfig->get('debug')){
			echo JText::_('COM_RSGALLERY2_FEATURE_ONLY_AVAILABLE_IN_DEBUG_MODE');
			return;
		}
		
		echo '<pre style="text-align: left;">';
		readfile( JPATH_SITE . '/administrator/components/com_rsgallery2/changelog.php' );
		echo '</pre>';
	}
	
    /**
     * Shows the proper Joomla path
     */
	function showRSPathWay() {
		$mainframe =& JFactory::getApplication();
		$pathway =& $mainframe->getPathway();
		
		// Only show pathway if rsg2 is the component
		$option = JRequest::getCmd('option');
		if( $option != 'com_rsgallery2' )
			return;

		//Check from where the path should be taken: if there is no gid in the 
		// menu-link, it is the root, e.g. gid=0, if there is a gid that's the 
		// start for this pathway
		$theMenu = JSite::getMenu();
		$theActiveMenuItem = $theMenu->getActive();
		if (isset($theActiveMenuItem->query['gid'])) { 
			$gidInActiveMenutItem = $theActiveMenuItem->query['gid'];
			} else {
			$gidInActiveMenutItem =  '0';
			}			
			
		//Get the gallery id (gid) of the currently gallery shown
		$gallery = rsgInstance::getGallery();
		$currentGallery = $gallery->id;

		//Get the current item shown
		$item = rsgInstance::getItem();

		//If the current gallery id (gid) is the one in the menu, no parent 
		// galleries are needed, if not, get the parent galleries up until 
		// the active one. 
		if (!($currentGallery == $gidInActiveMenutItem)){
			$galleries = array();
			$galleries[] = $gallery;
			//stop at the active one
			while ( $gallery->parent != $gidInActiveMenutItem) {
				$gallery = $gallery->parent();
				$galleries[] = $gallery;
			}
			$galleries = array_reverse($galleries);
			foreach( $galleries as $gallery ) {
				if ( $gallery->id == $currentGallery && empty($item) ) {
					$pathway->addItem( $gallery->name );
				} else {
					if($gallery->id != 0)
					{
						$link = 'index.php?option=com_rsgallery2&gid=' . $gallery->id;
						$pathway->addItem( $gallery->name, $link );
					}
				}
			}
		}

		// check if an image is displayed
		$isImage = JRequest::getInt( 'id', 0 );
		$isImage = JRequest::getInt( 'limit', $isImage );
		if ($isImage) {
			$pathway->addItem( $item->title );
		}
	}

	/**
		insert meta data into head
	**/
	function metadata(){
		global $option;
		$mainframe =& JFactory::getApplication();
		
		// if rsg2 isn't the component being displayed, do not append meta data
		if( $option != 'com_rsgallery2' )
			return;

		// check if an image is displayed
		$isImage = JRequest::getInt( 'id', 0 );
		$isImage = JRequest::getInt( 'limit', $isImage );
		
		if($isImage)
		{
			$item = rsgInstance::getItem();
			$title = $item->title;
			$description = htmlspecialchars(stripslashes(strip_tags($item->descr)), ENT_QUOTES );
		}
		else
		{

			if($this->gallery->id == 0)
				$title = $mainframe->getPageTitle();
			else
				$title = $this->gallery->get('name');
			$description = htmlspecialchars(stripslashes(strip_tags($this->gallery->get('description'))), ENT_QUOTES );
		}
		
		$mainframe->setPageTitle( ' '. $title );
		$mainframe->appendMetaTag( 'description',  $description );
	}

	function getGalleryLimitBox(){
		$pagelinks = $this->pageNav->getLimitBox("index.php?option=com_rsgallery2");
		// add form for LimitBox
		$pagelinks = '<form action="'.JRoute::_("index.php?option=com_rsgallery2&gid=".$this->gallery->id).'" method="post">' .
			         $pagelinks . 
					'</form>';

		return $pagelinks; 
	}
	function getGalleryPageLinks(){
		$pagelinks = $this->pageNav->getPagesLinks("index.php?option=com_rsgallery2");
		return $pagelinks;
		
	}
	function getGalleryPagesCounter(){
		return $this->pageNav->getPagesCounter();
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
            <img class="rsg2-displayImage" src="<?php echo waterMarker::showMarkedImage($name);?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
            <?php
        } else {
            ?>
			<img class="rsg2-displayImage"  src="<?php echo imgUtils::getImgDisplay($name); ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>"  />
            <?php
        }
    }
    /**
     * Shows the comments screen
     */
    function _showComments() {
     	global $rsgConfig;
		$mainframe =& JFactory::getApplication();
		
		if ($rsgConfig->get('comment')) {
			$gallery = rsgGalleryManager::get();
			$item = $gallery->getItem();
			$id = $item->id;
			
    		//Adding stylesheet for comments (is this needed as it is in rsgcomments.class.php as well?)
			$doc =& JFactory::getDocument();
			$doc->addStyleSheet(JURI_SITE."/components/com_rsgallery2/lib/rsgcomments/rsgcomments.css");
			
			$comment = new rsgComments();
			$comment->showComments($id);
			$comment->editComment($id);
		} else {
			echo JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED');
		}
    }
    
    /**
     * Shows the voting screen
     */
    function _showVotes() {
    	global  $rsgConfig , $rsgAccess;
		$mainframe =& JFactory::getApplication();
		$gallery = rsgGalleryManager::get();
		$vote_view = $rsgConfig->get('voting') && 
					($rsgAccess->checkGallery("vote_view", $gallery->id) || 
					 $rsgAccess->checkGallery("vote_vote", $gallery->id) ) ;
		
		if ($vote_view) {
			//Adding stylesheet for comments 
			$doc =& JFactory::getDocument();
			$doc->addStyleSheet(JURI_SITE."/components/com_rsgallery2/lib/rsgvoting/rsgvoting.css");
    		
			$voting = new rsgVoting();
			if($rsgAccess->checkGallery("vote_view", $gallery->id))
				$voting->showScore();
			if($rsgAccess->checkGallery("vote_vote", $gallery->id))
    			$voting->showVoting();
    	} else {
    		echo JText::_('COM_RSGALLERY2_VOTING_IS_DISABLED');
    	}
    }
    
    /**
     * Shows either random or latest images, depending on parameter
     * @param String Type of images. Options are 'latest' or 'random'
     * @param Int Number of images to show. Defaults to 3
     * @param String Style, options are 'vert' or 'hor'.(Vertical or horizontal)
     * @return HTML representation of image block.
     */
    function showImages($type="latest", $number = 3, $style = "hor") {
    	global $rsgConfig;//MK// [not used check] $mainframe
    	$database = JFactory::getDBO();
		
		//Check if backend permits showing these images
		if ( $type == "latest" AND !$rsgConfig->get('displayLatest') ) {
			return;
		} elseif ( $type == "random" AND !$rsgConfig->get('displayRandom') ) {
			return;
		}
		
    	switch ($type) {
    		case 'random':
    			$database->setQuery("SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.title".
                        " FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal".
                        " WHERE file.gallery_id=gal.id and gal.published=1 AND file.published=1".
                        " ORDER BY rand() limit $number");
    			$rows = $database->loadObjectList();
    			$title = JText::_('COM_RSGALLERY2_RANDOM_IMAGES');
    			break;
    		case 'latest':
				$database->setQuery("SELECT file.date, file.gallery_id, file.ordering, file.id, file.name, file.title".
                        " FROM #__rsgallery2_files as file, #__rsgallery2_galleries as gal".
                        " WHERE file.gallery_id=gal.id AND gal.published=1 AND file.published=1".
                        " ORDER BY file.date DESC LIMIT $number");
    			$rows = $database->loadObjectList();
    			$title = JText::_('COM_RSGALLERY2_LATEST_IMAGES');
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
								<img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->title;?>" width="<?php echo $rsgConfig->get('thumb_width');?>" />
                                </a>
                            	</div>
                                <div class="rsg2-clr"></div>
                                <div class="rsg2_details"><?php echo JHTML::_("date",$row->date);?></div>
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
								<img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->title;?>" width="<?php echo $rsgConfig->get('thumb_width');?>"  />
                            	</a>
                            	</div>
                            	<div class="rsg2-clr"></div>
								<div class="rsg2_details"><?php echo JText::_('COM_RSGALLERY2_UPLOADED') ?>&nbsp;<?php echo JHTML::_("date",$row->date);?></div>
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
		global $rsgConfig;//MK// [not used check] $mainframe
		if ( $rsgConfig->get('displayDownload') ) {
			echo "<div class=\"rsg2-toolbar\">";
			if ($type == 'button') {
				?>
				<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>">
				<img height="20" width="20" src="<?php echo JPATH_COMPONENT_SITE.DS.'images'.DS.'download_f2.png';?>" alt="<?php echo JText::_('COM_RSGALLERY2_DOWNLOAD')?>">
				<?php
				if ($showtext == true) {
					?>
					<br /><span style="font-size:smaller;"><?php echo JText::_('COM_RSGALLERY2_DOWNLOAD')?></span>
					<?php
				}
				?>
				</a>
				<?php
			} else {
				?>
				<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>"><?php echo JText::_('COM_RSGALLERY2_DOWNLOAD');?></a>
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
		global $rsgConfig;
		
		if($rsgConfig->get('displaySearch') != 0)
		{
			require_once(JPATH_ROOT . DS . "components" . DS . "com_rsgallery2" . DS . "lib" . DS . "rsgsearch" . DS . "search.html.php");
			html_rsg2_search::showSearchBox();
		}
    }
    
    function showSearchBoxXX() {
    	$mainframe =& JFactory::getApplication();		
    	$css = "<link rel=\"stylesheet\" href=\"".JURI_SITE."/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css\" type=\"text/css\" />";
    	$mainframe->addCustomHeadTag($css);
    	?>

    	<div align="right">
    	<form name="rsg2_search" method="post" action="<?php echo JRoute::_('index.php');?>">
    		<?php echo JText::_('COM_RSGALLERY2_SEARCH');?>
    		<input type="text" name="searchtext" class="searchbox" onblur="if(this.value=='') this.value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS');?>';" onfocus="if(this.value=='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS');?>') this.value='';" value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS');?>' />
			<input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="search" />
			<input type="hidden" name="task" value="showResults" />
    	</form>
    	</div>
    	<?php
    }
    /*
    function showRSTopBar() {
        global $my, $mainframe, $rsgConfig,;
        $catid =JRequest::getInt( 'catid', 0 );
        $page = JRequest::getVar( 'page'  , null);
        ?>
        <div style="float:right; text-align:right;">
        <ul id='rsg2-navigation'>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2"); ?>">
                <?php echo JText::_('COM_RSGALLERY2_MAIN_GALLERY_PAGE'); ?>
                </a>
            </li>
            <?php 
            if ( !$my->id == "" && $page != "my_galleries" && $rsgConfig->get('show_mygalleries') == 1):
            ?>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries");?>">
                <?php echo JText::_('COM_RSGALLERY2_MY_GALLERIES'); ?>
                </a>
            </li>
            <?php
            elseif( $page == "slideshow" ): 
            ?>
            <li>
                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&page=inline&catid=".$catid."&id=".$_GET['id']);?>">
                <?php echo JText::_('COM_RSGALLERY2_EXIT_SLIDESHOW'); ?>
                </a>
            </li>
        <?php endif; ?>
        </ul>
        </div>
        <div style="float:left;">
        <?php if( isset( $catid )): ?>
            <h2 id='rsg2-galleryTitle'><?php htmlspecialchars(stripslashes(galleryUtils::getCatNameFromId($catid)), ENT_QUOTES) ?></h2>
        <?php elseif( $page != "my_galleries" ): ?>
            <h2 id='rsg2-galleryTitle'><?php echo JText::_('COM_RSGALLERY2_GALLERY') ?></h2>
        <?php endif; ?>
        </div>
        <?php
    }
	*/
}
?>
