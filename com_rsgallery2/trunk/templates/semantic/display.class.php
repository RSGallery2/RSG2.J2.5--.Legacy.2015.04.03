<?php
/**
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );

/**
 * Template class for RSGallery2
 * @package RSGallery2
 * @author Ronald Smit <ronald.smit@rsdev.nl>
 */
class rsgDisplay_semantic extends rsgDisplay{

	function inline(){
		$this->display( 'inline.php' );;
	}

	/**
	* Show main gallery page
	* @param string Style for main page (single, double, box)
	* @param boolean Show subgalleries or not.
	* @return HTML for main gallery page.
	*/
	function showMainGalleries() {
		global $rsgConfig;
		
		$gallery =  rsgInstance::getGallery();
		$this->gallery = $gallery;
		
		//Get values for page navigation from URL
		$limit = rsgInstance::getInt( 'glimit', $rsgConfig->get('galcountNrs') );
		$limitstart = rsgInstance::getInt( 'glimitstart', 0 );
		
		//Get number of galleries including main gallery
		$this->kids = $gallery->kids();
		$kidCountTotal = count( $gallery->kids() );

		$this->pageNav = false;
		
		if( $rsgConfig->get('dispLimitbox') == 1 ) {
			if( $kidCountTotal > $limit ){
				$this->kids = array_slice( $this->kids, $limitstart, $limit );
				$this->pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
			}
		} elseif( $rsgConfig->get('dispLimitbox') == 2 ) {
			$this->kids = array_slice( $this->kids, $limitstart, $limit );
			$this->pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
		}
	
		$this->display( 'gallery.php' );
		
		//Show page navigation if selected in backend
	}

    /***************************
		non page public functions
	***************************/
	
	function _showGalleryDetails( $kid ) {
		global $rsgConfig;
		?>
		<span class="rsg_gallery_details"><div class="rsg2_details">
		<?php echo _RSGALLERY_GAL_OWNER." "; echo $kid->owner;?><br />
		Size: <?php echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?><br />
		Created: <?php echo mosFormatDate( $kid->date,"%d-%m-%Y" );?><br />
		</div></span>
		<?php
	}
    
    /***************************
		private functions
	***************************/

    /**
     * @todo this alternate gallery view needs to be moved to an html file and added as a template parameter
     */
    function _showDouble( $kids ) {
		global $rsgConfig;
        $i = 0;
		echo"<div class='rsg_double_fix'>";
        foreach ( $kids as $kid ) {
			$i++;
            ?>
            <div class="rsg_galleryblock_double_<?php echo $i?>">
				<div class="rsg2-galleryList-status"><?php echo $kid->status;?></div>
				<div class="rsg2-galleryList-thumb_double">
					<?php echo $kid->thumbHTML; ?>
				</div>
				<div class="rsg2-galleryList-text_double">
					<?php echo $kid->galleryName;?>
					<span class='rsg2-galleryList-newImages'>
						<sup><?php echo galleryUtils::newImages($kid->get('id')); ?></sup>
					</span>
					<?php echo $this->_showGalleryDetails( $kid );?>
					<div class="rsg2-galleryList-description"><?php echo $kid->description;?>
					</div>
				</div>
				<div class="rsg_sub_url"><?php $this->_subGalleryList( $kid ); ?>
				</div>
			</div>
            <?php
			if($i>1){
				$i = 0;
			}
        }
		echo "</div>";
    }
    
    /**
     * @todo this alternate gallery view needs to be moved to an html file and added as a template parameter
     */
    function _showBox( $kids, $subgalleries ) {
        ?>
		<div class="rsg_box_block">
            <?php
            $i = 0;
            foreach ( $kids as $kid ) {
                $i++;
				if($i>3){
					$i = 1;
					}
			 ?>
                <div class="rsg_box_box_<?php echo $i;?>">
                    <div class="rsg_galleryblock">
                    	<div>
							<div class="rsg2-galleryList-status"><?php echo $kid->status; ?></div>
                            <?php echo $kid->galleryName;?>
                            <sup><span class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($kid->get('id')); ?></span></sup>
                            <div class='rsg2-galleryList-totalImages'>(<?php echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?>)</div>
                        </div>
						<div>
                        	<div class="rsg2-galleryList-thumb_box">
								<?php echo $kid->thumbHTML; ?>
                        	</div>
                        	<div class="rsg2-galleryList-text_box">
                          		<?php echo $this->_showGalleryDetails( $kid );?>
                        	</div>
                    	</div>
                        <div class="rsg2-galleryList-description_box">
                            	<?php echo $kid->description;?>
						</div>
                        <div class="rsg_sub_url"><?php $this->_subGalleryList( $kid ); ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
			?>
            </div>
        <?php
    }
	
	/**
	 * @todo this alternate gallery view needs to be moved to an html file and added as a template parameter
	 */
    function _showCustom( $kids, $cols, $gubgalleries ) {
        echo "<h2>For testing purposes only!</h2>";
        $width = 100/$cols."%"; 
        ?>
        <ul id="rsg2-galleryList">
        <table width="100%" border="1">
        <tr>
        <?php
        $i = 0;
        foreach ( $kids as $kid ) {
            $i++;
            echo "<td width=\"$width\" valign=\"top\">";
            $this->_writeCustomGalleryBlock( $kid );
            echo "</td>";
            if ($i%$cols == 0) {
                echo "</tr><tr>";
            }
        }
        ?>
        </tr>
        </table>
        </ul>
        <?php
    }
    
    function _writeCustomGalleryBlock( $block ) {
        global $rsgConfig;
        
        //Set template selection
        if (!$rsgConfig->get('template')) {
            $cur_template = "default";
        } else {
            $cur_template = $rsgConfig->get('template');
        }
        //Include gallery block template file
        include( JPATH_RSGALLERY2_SITE. DS . 'tpl' . DS . $cur_template . DS . 'galleryblock.php' );
    }
    
	/**
	 * Shows thumbnails for gallery
	 */
	function showThumbs() {
		global $my, $rsgConfig, $Itemid;

		$itemCount = $this->gallery->itemCount();
		
		$limit = rsgInstance::getInt( 'limit', $rsgConfig->get("display_thumbs_maxPerPage") );
		$limitstart = rsgInstance::getInt( 'limitstart' );
		
		//instantiate page navigation
		$pagenav = new mosPageNav( $itemCount, $limitstart, $limit );
		
		// increase the gallery hit counter
		$this->gallery->hit();
		
		if( !$this->gallery->itemCount() ){
			if( $this->gallery->id ){
				// if gallery is not the root gallery display the message
				echo _RSGALLERY_NOIMG;
			}
			// no items to display, so we can return;
			return;
		}
	
		//Old rights management. If user is owner or user is Super Administrator, you can edit this gallery
		if(( $my->id <> 0 ) and (( $this->gallery->uid == $my->id ) OR ( $my->usertype == 'Super Administrator' )))
			$this->allowEdit = true;
		else
			$this->allowEdit = false;

		switch( $rsgConfig->get( 'display_thumbs_style' )){
			case 'float':
				$this->display( 'thumbs_float.php' );
			break;
			case 'table':
				$this->display( 'thumbs_table.php' );
			break;
		}
		?>
		<div id='rsg2-pageNav'>
				<?php
				if( $itemCount > $limit ){
				global $Itemid;
					echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;gid=".$this->gallery->id);
					echo "<br /><br />".$pagenav->writePagesCounter();
				}
				?>
		</div>
		<?php
	}
    
    /**
     * Shows main image
     */
	function showDisplayImage(){
		global $rsgConfig, $mosConfig_live_site;
		
		$item = rsgInstance::getItem();

		if( $item->type != 'image' ){
			// item is not an image, return;
			return;
		}
		
// 		$this->writeSLideShowLink();
	
		if( $rsgConfig->get('displayPopup') == 2 ){
			?>
			<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/components/com_rsgallery2/js_highslide/highslide.css" type="text/css" />
			<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/js_highslide/highslide.js"></script>
			<script type="text/javascript">    
				hs.graphicsDir = '<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/js_highslide/graphics/';
				hs.showCredits = false;
				hs.outlineType = 'drop-shadow';
				window.onload = function() {
					hs.preloadImages();
				}
			</script>
			<?php
		}
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td><h2 class='rsg2_display_name' align="center"><?php echo htmlspecialchars(stripslashes($item->title), ENT_QUOTES); ?></h2></td>
			</tr>
			<tr>
				<td>
				<div align="center">
					<div id="highslide-container">
					<?php
					switch ($rsgConfig->get('displayPopup')) {
						//No popup
						case 0:
							$this->_showImageBox( $item->name, $item->descr );
							break;
						//Normal popup
						case 1:
							if ($rsgConfig->get('watermark')) {
								?><a href="<?php echo waterMarker::showMarkedImage( $item->name, 'original' ); ?>" target="_blank"><?php
							} else {
								?><a href="<?php echo imgUtils::getImgOriginal( $item->name ); ?>" target="_blank"><?php
							}
							$this->_showImageBox( $item->name, $item->descr );
							?>
							</a>
							<?php
							break;
						//Highslide popup
						case 2:
							if ($rsgConfig->get('watermark')) {
								?><a href="<?php echo waterMarker::showMarkedImage( $item->name, 'original'); ?>" class="highslide" onclick="return hs.expand(this)"><?php
							} else {
								?><a href="<?php echo imgUtils::getImgOriginal( $item->name ); ?>" class="highslide" onclick="return hs.expand(this)"><?php
							}
							$this->_showImageBox( $item->name, $item->descr );
							?>
							</a>
							<?php
							break;
					}
					?>
					</div>
				</div>
				</td>
			</tr>
			<tr>
				<td><?php $this->_writeDownloadLink( $item->id );?></td>
			</tr>
		</table>
		<?php
	}
    
	/**
	 * Show page navigation for Display image
	 */
	function showDisplayPageNav() {
		$gallery = rsgGalleryManager::get();
		
		if( rsgInstance::getInt( 'id', 0 )){
			//  if id is set then we need to set limits and gid so that page nav works properlly
			rsgInstance::setVar( 'gid', $gallery->id );
			rsgInstance::setVar( 'limit', 1 );

			$item = $gallery->getItem();
			rsgInstance::setVar( 'limitstart', $item->ordering-1 );
		}

		$pageNav = $gallery->getPagination();		
		$pageLinks = $pageNav->getPagesLinks();

		if( rsgInstance::getInt( 'id', 0 )){
			// i'm not fond of this style of hackery
			// first we need to replace the item id with the gallery id
			// second, the limit parameter is not being written.  this is must be a bug or something.  weird.
			$pageLinks = str_replace( ";id={$item->id}", ";gid={$gallery->id}&amp;limit=1", $pageLinks );
		}

		?>
		<div align="center">
			<?php echo $pageLinks; ?>
		</div>
		<?php
	}

	/**
	 * Shows details of image
	 */
	function showDisplayImageDetails() {
		global $rsgConfig;

		// if no details need to be displayed then exit
		
		if (! ( $rsgConfig->get("displayDesc") || $rsgConfig->get("displayVoting") || $rsgConfig->get("displayComments") || $rsgConfig->get("displayEXIF") ))
			return;
	
		$tabs = new mosTabs(0);
		$tabs->startPane( 'tabs' );
		
		if ( $rsgConfig->get("displayDesc") ) {
			$tabs->startTab(_RSGALLERY_DESCR, 'rs-description' );
			$this->_showDescription(); 
			$tabs->endTab();
		}
		
		if ( $rsgConfig->get("displayVoting") ) {
			$tabs->startTab(_RSGALLERY_VOTING, 'Voting' );
			$this->_showVotes();
			$tabs->endTab();
		}
		
		if ( $rsgConfig->get("displayComments") ) {
			$tabs->startTab(_RSGALLERY_COMMENTS, 'Comments' );
			$this->_showComments();
			$tabs->endTab();
		}
	
		if ($rsgConfig->get("displayEXIF") ) {
			$tabs->startTab(_RSGALLERY_EXIF, 'EXIF' );
			$this->_showEXIF();
			$tabs->endTab();
		}
		$tabs->endPane();
	}

    /**
     * Show description
     */
	function _showDescription( ) {
		global $rsgConfig;
		$item = rsgInstance::getItem();
		
		if( $rsgConfig->get('displayHits')):
		?>
		<p class="rsg2_hits"><?php echo _RSGALLERY_CATHITS; ?> <span><?php echo $item->hits; ?></span></p>
		<?php
		endif;
		
		if ( $item->descr ):
		?>
		<p class="rsg2_description"><?php  echo $item->descr; ?></p>
		<?php
		endif;
	}
    
    /**
     * @todo work with the new rsgCommenting system
     */
    function _showVoting() {
        global $rsgConfig;
        
        return;
        ?>
        <script type="text/javascript">
            function deleteComment(id) {
                var yesno = confirm ('<?php echo _RSGALLERY_COMMENT_DELETE;?>');
                if (yesno == true) {
                    location = 'index.php?option=com_rsgallery2&page=delete_comment&id='+id+'';
                }
            }
            </script>
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="adminForm">
        <tr><td>
            <table width="100%" cellpadding="2" cellspacing="1">
                <form method="post" action="<?php global $Itemid; echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=vote"); ?>">
                <tr>
                        <td colspan="1" width="100"><strong><?php echo _RSGALLERY_VOTES_NR; ?>:</strong></td>
                        <td colspan="4"><?php echo $this->item['votes']; ?></td>
                </tr>
                <tr>
                    <td colspan="1"><strong><?php echo _RSGALLERY_VOTES_AVG; ?>:</strong></td>
                    <td colspan="4"><?php if ($this->item['votes'] > 0) echo galleryUtils::showRating( $this->item['id'] );else echo _RSGALLERY_NO_RATINGS; ?></td>
                </tr>
                <tr>
                    <input type="hidden" name="picid" value="<?php echo $this->item['id']; ?>" />
                    <input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
                    <td valign="top"><strong><?php echo _RSGALLERY_VOTE; ?>:</strong></td>
                    <td colspan="4">
                        <input type="radio" name="vote" value="1" /><?php echo _RSGALLERY_VERYBAD; ?><br />
                        <input type="radio" name="vote" value="2" /><?php echo _RSGALLERY_BAD; ?><br />
                        <input type="radio" name="vote" value="3" CHECKED/><?php echo _RSGALLERY_OK; ?><br />
                        <input type="radio" name="vote" value="4" /><?php echo _RSGALLERY_GOOD; ?><br />
                        <input type="radio" name="vote" value="5" /><?php echo _RSGALLERY_VERYGOOD; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="center"><input class="button" type="submit" name="submit" value="<?php echo _RSGALLERY_VOTE;?>" /></td>
                </tr>
                </form>
            </table>
        </td></tr>
        </table>
        <?php
    }
    
	/**
	 * shows exif data for the current item
	 */
	function _showEXIF( ) {
		$image = rsgInstance::getItem();

		// EXIF only available for images
		if( $image->type != 'image' )
			return;

		$exif = $image->exif();

		// no exif found display nothing.
		if( !$exif )
			return;

		$this->exif = $exif;

		$this->display('exif.php');
	}

    /**
     * Shows random images for display on main page
     */
    function showRandom( $style = "hor", $count = 3 ) {
        global $database, $rsgConfig;
        if ( $rsgConfig->get('displayRandom') ) {
            $catid = rsgInstance::getInt( 'catid', 0 );
            if (!$catid) {
                $database->setQuery("SELECT file.gallery_id, file.ordering, file.id, file.name, file.descr".
                                " FROM #__rsgallery2_files file, #__rsgallery2_galleries gal".
                                " WHERE file.gallery_id=gal.id and gal.published=1".
                                " ORDER BY rand() limit $count");
                $rows = $database->loadObjectList();
                HTML_RSGALLERY::showRandom($rows, $style);
            }
        }
    }
    
    /**
     * Shows latest uploaded images for display on main page
     */
    function showLatest( $style = "hor", $count = 3) {
        global $database, $rsgConfig;
        if ( $rsgConfig->get('displayLatest') ) {
            $catid = rsgInstance::getInt( 'catid', 0 );
            if (!$catid) {
                $database->setQuery("SELECT file.gallery_id, file.ordering, file.id, file.name, file.descr".
                                " FROM #__rsgallery2_files file, #__rsgallery2_galleries gal".
                                " WHERE file.gallery_id=gal.id and gal.published=1".
                                " ORDER BY file.date DESC limit $count");
                $rows = $database->loadObjectList();
                HTML_RSGALLERY::showLatest($rows);
            }
        }
    }
    
    function showRSTopBar() {
        global $my, $mosConfig_live_site, $rsgConfig, $Itemid;
        $catid =rsgInstance::getInt( 'catid', 0 );
        $Itemid = rsgInstance::getInt( 'Itemid', 0 );
        $page = rsgInstance::getVar( 'page'  , null);
        ?>
        <div style="float:right; text-align:right;">
        <ul id='rsg2-navigation'>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid); ?>">
                <?php echo _RSGALLERY_MAIN_GALLERY_PAGE; ?>
                </a>
            </li>
            <?php 
            if ( !$my->id == "" && $page != "my_galleries" && $rsgConfig->get('show_mygalleries') == 1):
            ?>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries");?>">
                <?php echo _RSGALLERY_MY_GALLERIES; ?>
                </a>
            </li>
            <?php
            elseif( $page == "slideshow" ): 
            ?>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&catid=".$catid."&id=".$_GET['id']);?>">
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

	/**
	 * Write downloadlink for image
	 * @param int image ID
	 * @param string Button or HTML link (button/link)
	 * @return HTML for downloadlink
	 */
	function _writeDownloadLink($id, $showtext = true, $type = 'button') {
	global $rsgConfig, $mosConfig_live_site;
	if ( $rsgConfig->get('displayDownload') ) {
		echo "<div class=\"rsg2-toolbar\">";
		if ($type == 'button') {
			?>
			<a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>">
			<img height="20" width="20" src="<?php echo $mosConfig_live_site;?>/administrator/images/download_f2.png" alt="<?php echo _RSGALLERY_DOWNLOAD?>">
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
			<a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&task=downloadfile&id='.$id);?>"><?php echo _RSGALLERY_DOWNLOAD?></a>
			<?php
		}
		echo "</div><div class=\"rsg2-clr\">&nbsp;</div>";
		}
	}
	
	
	/**
	* list sub galleries in a gallery
	* @param rsgGallery parent gallery
	*/
	function _subGalleryList( $parent ){
		global $Itemid;
		$kids = $parent->kids();

		if( count( $kids ) == 0 ) return;

		echo "Subgalleries: ";
		
		$kid = array_shift( $kids );
		
		while( true ){
			?>
			<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$kid->id); ?>">
				<?php echo htmlspecialchars(stripslashes($kid->name), ENT_QUOTES); ?>
				(<?php echo $kid->itemCount(); ?>)</a><?php

			if( $kid = array_shift( $kids ))
				echo ', ';
			else
				break;
		}
	}
}


/**
 * HTML for the frontend
 * @package RSGallery2
 */
class HTML_RSGALLERY{

    
    function showUserGallery($rows)
    {
    global $my, $rsgConfig, $mosConfig_live_site, $Itemid;
    //Load frontend toolbar class
    require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/HTML_toolbar.php' );
    ?>
    <script language="javascript" type="text/javascript">
        function submitbutton(pressbutton) {
            var form = document.form1;
            if (pressbutton == 'cancel') {
                form.reset();
                return;
            }
        
        // do field validation
        if (form.catname1.value == "") {
            alert( "<?php echo _RSGALLERY_MAKECAT_ALERT_NAME; ?>" );
        }
        else if (form.description.value == ""){
            alert( "<?php echo _RSGALLERY_MAKECAT_ALERT_DESCR; ?>" );
        }
        else{
            <?php //getEditorContents( 'editor1', 'description' ) ; ?>
            form.submit();
        }
        }
    </script>
    <?php
    if ($rows) {
        foreach ($rows as $row){
            $catname        = $row->name;
            $description    = $row->description;
            $ordering       = $row->ordering;
            $uid            = $row->uid;
            $catid          = $row->id;
            $published      = $row->published;
            $user           = $row->user;
            $parent         = $row->parent;
        }
    }
    else{
        $catname        = "";
        $description    = "";
        $ordering       = "";
        $uid            = "";
        $catid          = "";
        $published      = "";
        $user           = "";
        $parent         = "";
    }
    ?>
        <form name="form1" id="form1" method="post" action="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=makeusercat"); ?>">
        <table width="100%">
        <tr>
            <td colspan="2"><h3><?php echo _RSGALLERY_CREATE_GALLERY; ?></h3></td>
        </tr>
        <tr>

            <td align="right">
                <div style="float: right;">
                        <?php
                        // Toolbar
                        mosToolBar::startTable();
                        mosToolBar::save();
                        mosToolBar::cancel();
                        mosToolBar::endtable();
                        ?>
                </div>
            </td>

        </tr>
        </table>
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <input type="hidden" name="ordering" value="<?php echo $ordering; ?>" />
        <table class="adminlist" border="1">
        <tr>
            <th colspan="2"><?php echo _RSGALLERY_CREATE_GALLERY; ?></th>
        </tr>
        <tr>
            <td><?php echo _RSGALLERY_CATLEVEL;?></td>
            <td>
                <?php //galleryUtils::showCategories(NULL, $my->id, 'parent');?>
                <?php echo galleryUtils::galleriesSelectList( $parent, 'parent', false );?>
                <?php //galleryUtils::createGalSelectList( NULL, $listName='parent', true );?>
            </td>
        </tr>
        <tr>
            <td><?php echo _RSGALLERY_USERCAT_NAME; ?></td>
            <td align="left"><input type="text" name="catname1" size="30" value="<?php echo $catname; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo _RSGALLERY_DESCR; ?></td>
            <td align="left">
                <textarea cols="20" rows="5" name="description"><?php echo htmlspecialchars(stripslashes($description)); ?></textarea>
                <?php
                // parameters : areaname, content, hidden field, width, height, rows, cols
                //editorArea( 'editor1',  $description , 'description', '200', '300', '10', '10' ) ; ?>
            </td>
        </tr>
        <tr>
            <td><?php echo _RSGALLERY_CATPUBLISHED; ?></td>
            <td align="left"><input type="checkbox" name="published" value="1" <?php if ($published==1) echo "checked"; ?> /></td>
        </tr>
        </table>
        </form>
        <?php
        }
    
    function edit_image($rows)
        {
        global $my, $mosConfig_live_site, $rsgConfig, $Itemid;
        require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/HTML_toolbar.php' );
        foreach ($rows as $row) {
            $filename       = $row->name;
            $title          = $row->title;
            $description    = $row->descr;
            $id             = $row->id;
            $limitstart     = $row->ordering - 1;
            $catid          = $row->gallery_id;
        }
        echo "<h3>"._RSGALLERY_EDIT_IMAGE."</h3>";
        ?>
        <form name="form1" method="post" action="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=save_image"); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <table width="100%">
            <tr>
                <td align="right">
                    <img onClick="form1.submit();" src="<?php echo $mosConfig_live_site; ?>/administrator/images/save.png" alt="<?php echo _RSGALLERY_TOOL_UP ?>" border="0" name="upload" onMouseOver="document.upload.src='<?php echo $mosConfig_live_site; ?>/administrator/images/save_f2.png';" onMouseOut="document.upload.src='<?php echo $mosConfig_live_site; ?>/administrator/images/save.png';" />&nbsp;&nbsp;
                    <img onClick="history.back();" src="<?php echo $mosConfig_live_site; ?>/administrator/images/cancel.png" alt="<?php echo _RSGALLERY_CANCEL; ?>" border="0" name="cancel" onMouseOver="document.cancel.src='<?php echo $mosConfig_live_site; ?>/administrator/images/cancel_f2.png';" onMouseOut="document.cancel.src='<?php echo $mosConfig_live_site; ?>/administrator/images/cancel.png';" />
                </td>
            </tr>
        </table>
        <table class="adminlist" border="2" width="100%">
            <tr>
                <th colspan="3"><?php echo _RSGALLERY_EDIT_IMAGE; ?></th>
            </tr>
            <tr>
                <td align="left"><?php echo _RSGALLERY_CAT_NAME; ?></td>
                <td align="left">
                    <?php if (!$rsgConfig->get('acl_enabled')) {
                        galleryUtils::showCategories(NULL, $my->id, 'catid');
                    } else {
                        galleryUtils::showUserGalSelectList('up_mod_img', 'catid', $catid);
                    }
                    ?>
                </td>
                <td rowspan="2"><img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>" border="0" /></td>
            </tr>
            <tr>
                <td align="left"><?php echo _RSGALLERY_EDIT_FILENAME; ?></td>
                <td align="left"><strong><?php echo $filename; ?></strong></td>
            </tr>
            <tr>
                <td align="left"><?php echo _RSGALLERY_EDIT_TITLE;?></td>
                <td align="left"><input type="text" name="title" size="30" value="<?php echo $title; ?>" /></td>
            </tr>
            <tr>
                <td align="left" valign="top"><?PHP echo _RSGALLERY_EDIT_DESCRIPTION; ?></td>
                <td align="left" colspan="2">
                    <textarea cols="25" rows="5" name="descr"><?php echo htmlspecialchars(stripslashes($description)); ?></textarea>
                    <?php
                    // parameters : areaname, content, hidden field, width, height, rows, cols
                    //editorArea( 'editor1',  $description, 'descr', '100%;', '500', '75', '50' );
                    ?>
                </td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
        </table>
        </form>
        <?php
        }


    function showFrontUpload()
        {
        global $rsgConfig, $mosConfig_live_site, $mosConfig_absolute_path, $my, $Itemid;
        
        //Load frontend toolbar class
        require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/HTML_toolbar.php' );
        ?>
        <script language="javascript" type="text/javascript">
        function submitbutton2(pressbutton) {
            var form = document.uploadform;
            if (pressbutton == 'cancel') {
                form.reset();
                return;
            }
            
            // do field validation
            if (form.i_cat.value == "-1") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_CAT; ?>" );
            } else if (form.i_file.value == "") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_FILE; ?>" );
            } else {
                form.submit();
            }
        }
        
    </script>
        <form name="uploadform" id="uploadform" method="post" action="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=saveUpload"); ?>" enctype="multipart/form-data">
        <table border="0" width="100%">
            <tr>
                <td colspan="2"><h3><?php echo _RSGALLERY_ADD_IMAGE;?></h3></td>
            </tr>
            <tr>

                <td align="right">
                    <div style="float: right;">
                    <table cellpadding="0" cellspacing="3" border="0" id="toolbar">
                    <tr height="60" valign="middle" align="center">
                        <td>
                            <a class="toolbar" href="javascript:submitbutton2('save');" >
                            <img src="<?php echo $mosConfig_live_site;?>/images/save_f2.png"  alt="Save" name="save" title="Save" align="middle" border="0" /></a>
                        </td>
                        <td>
                            <a class="toolbar" href="javascript:submitbutton2('cancel');" >
                            <img src="<?php echo $mosConfig_live_site;?>/images/cancel_f2.png"  alt="Cancel" name="cancel" title="Cancel" align="middle" border="0" /></a>
                        </td>
                    </tr>
                    </table>
                    </div>
                </td>

            </tr>
            <tr>
                <td>
                    <table class="adminlist" border="1">
                    <tr>
                        <th colspan="2"><?php echo _RSGALLERY_USERUPLOAD_TITLE; ?></th>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_USERUPLOAD_CATEGORY; ?></td>
                        <td>
                            <?php 
                            echo galleryUtils::galleriesSelectList(null, 'i_cat', false);
                            /*
                            if (!$rsgConfig->get('acl_enabled')) {
                                galleryUtils::showCategories(NULL, $my->id, 'i_cat');
                            } else {
                                galleryUtils::showUserGalSelectList('up_mod_img', 'i_cat');
                            }
                            */
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_FILENAME ?></td>
                        <td align="left"><input size="49" type="file" name="i_file" /></td>
                    </tr>
                    </tr>
                        <td><?php echo _RSGALLERY_UPLOAD_FORM_TITLE ?>:</td>
                        <td align="left"><input name="title" type="text" size="49" />
                    </td>
                    </tr>
                    <tr>
                        <td><?php echo _RSGALLERY_DESCR ?></td>
                        <td align="left"><textarea cols="35" rows="3" name="descr"></textarea></td>
                    </tr>
                    <?php
                    if ($rsgConfig->get('graphicsLib') == '')
                        { ?>
                    <tr>
                        <td><?php echo _RSGALLERY_UPLOAD_THUMB; ?></td>
                        <td align="left"><input type="file" name="i_thumb" /></td>
                    </tr>
                        <?php } ?>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="cat" value="9999" />
                            <input type="hidden" name="uploader" value="<?php echo $my->id; ?>">
                        </td>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
        </form>
        <?php
        }

    
    /**
     * Shows header with appropriate links at top of each rsgallery page
     * @param integer category ID. Used to echo category name if present
     * @param string intro text to show on main gallery page.
     * @todo Rewrite this into cleaner coding style.
     */
    function RSGalleryTitleblock($catid, $intro_text)   {
        global $my, $mosConfig_live_site, $rsgConfig, $Itemid;
        $page = rsgInstance::getVar( 'page'  , null);
		
        //$user_cats  = $rsgConfig->get('uu_enabled');
        //$my_galleries = $rsgConfig->get('show_mygalleries');
        ?>
        <div style="float:right; text-align:right;">
        <ul id='rsg2-navigation'>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid); ?>">
                <?php echo _RSGALLERY_MAIN_GALLERY_PAGE; ?>
                </a>
            </li>
            <?php 
            if ( !$my->id == "" && $page != "my_galleries" && $rsgConfig->get('show_mygalleries') == 1):
            ?>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries");?>">
                <?php echo _RSGALLERY_MY_GALLERIES; ?>
                </a>
            </li>
            <?php
            elseif( $page == "slideshow" ): 
            ?>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&catid=".$catid."&id=".$_GET['id']);?>">
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
        <div id='rsg2-galleryIntroText'>
            <?php //echo htmlspecialchars(stripslashes($intro_text), ENT_QUOTES); ?>
            <?php echo stripslashes($intro_text); ?>
        </div>
        <?php
        ?>
        
        </div>
        <?php
    }


    /**
     * Shows thumbnails for gallery and links to subgalleries if they exist.
     * @param integer Category ID
     * @param integer Columns per page
     * @param integer Number of thumbs per page
     * @param integer pagenav stuff
     * @param integer pagenav stuff
     */
    function RSShowPictures ($catid, $limit, $limitstart){
        global $database, $my, $mosConfig_live_site, $rsgConfig;
        $columns                    = $rsgConfig->get("display_thumbs_colsPerPage");
        $PageSize                   = $rsgConfig->get("display_thumbs_maxPerPage");
        //$my_id                      = $my->id;
    
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id='$catid'");
        $numPics = $database->loadResult();
        
        if(!isset($limitstart))
            $limitstart = 0;
        //instantiate page navigation
        $pagenav = new mosPageNav($numPics, $limitstart, $PageSize);
    
        $picsThisPage = min($PageSize, $numPics - $limitstart);
    
        if (!$picsThisPage == 0)
                $columns = min($picsThisPage, $columns);
                
        //Add a hit to the database
        if ($catid && !$limitstart)
            {
            galleryUtils::addCatHit($catid);
            }
        //Old rights management. If user is owner or user is Super Administrator, you can edit this gallery
        if(( $my->id <> 0 ) and (( galleryUtils::getUID( $catid ) == $my->id ) OR ( $my->usertype == 'Super Administrator' )))
            $allowEdit = true;
        else
            $allowEdit = false;

        $thumbNumber = 0;
        ?>
        <div id='rsg2-pageNav'>
                <?php
                /*
                if( $numPics > $PageSize ){
                global $Itemid;
                    echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$catid);
                }
                */
                ?>
        </div>
        <br />
        <?php
        if ($picsThisPage) {
        $database->setQuery("SELECT * FROM #__rsgallery2_files".
                                " WHERE gallery_id='$catid'".
                                " ORDER BY ordering ASC".
                                " LIMIT $limitstart, $PageSize");
        $rows = $database->loadObjectList();
        
        switch( $rsgConfig->get( 'display_thumbs_style' )):
            case 'float':
                $floatDirection = $rsgConfig->get( 'display_thumbs_floatDirection' );
                ?>
                <ul id='rsg2-thumbsList'>
                <?php foreach( $rows as $row ): ?>
                <li <?php echo "style='float: $floatDirection'"; ?> >
                    <a href="<?php global $Itemid; echo sefRelToAbs( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=".$row->id."&amp;catid=".$row->gallery_id."&amp;limitstart=".$limitstart++ ); ?>">
                        <!--<div class="img-shadow">-->
                        <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
                        <!--</div>-->
                        <div class="clr"></div>
                        <?php if($rsgConfig->get("display_thumbs_showImgName")): ?>
                            <br /><span class='rsg2_thumb_name'><?php echo htmlspecialchars(stripslashes($row->title), ENT_QUOTES); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if( $allowEdit ): ?>
                    <div id='rsg2-adminButtons'>
                        <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=edit_image&amp;id=".$row->id); ?>"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/edit_f2.png" alt="" border="0" height="15" /></a>
                        <a href="#" onClick="if(window.confirm('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>')) location='<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=delete_image&amp;id=".$row->id); ?>'"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/delete_f2.png" alt="" border="0" height="15" /></a>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
                </ul>
                <div class='clr'>&nbsp;</div>
                <?php
                break;
            case 'table':
                $cols = $rsgConfig->get( 'display_thumbs_colsPerPage' );
                $i = 0;
                ?>
                <table id='rsg2-thumbsList'>
                <?php foreach( $rows as $row ): ?>
                    <?php if( $i % $cols== 0) echo "<tr>\n"; ?>
                        <td>
                            <!--<div class="img-shadow">-->
                                <a href="<?php global $Itemid; echo sefRelToAbs( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=".$row->id."&amp;catid=".$row->gallery_id."&amp;limitstart=".$limitstart++ ); ?>">
                                <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
                                </a>
                            <!--</div>-->
                            <div class="clr"></div>
                            <?php if($rsgConfig->get("display_thumbs_showImgName")): ?>
                            <br />
                            <span class='rsg2_thumb_name'>
                                <?php echo htmlspecialchars(stripslashes($row->title), ENT_QUOTES); ?>
                            </span>
                            <?php endif; ?>
                            <?php if( $allowEdit ): ?>
                            <div id='rsg2-adminButtons'>
                                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=edit_image&amp;id=".$row->id); ?>"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/edit_f2.png" alt="" border="0" height="15" /></a>
                                <a href="#" onClick="if(window.confirm('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>')) location='<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=delete_image&amp;id=".$row->id); ?>'"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/delete_f2.png" alt="" border="0" height="15" /></a>
                            </div>
                            <?php endif; ?>
                        </td>
                    <?php if( ++$i % $cols == 0) echo "</tr>\n"; ?>
                <?php endforeach; ?>
                <?php if( $i % $cols != 0) echo "</tr>\n"; ?>
                </table>
                <?php
                break;
            case 'magic':
                echo _RSGALLERY_MAGIC_NOTIMP;
                ?>
                <table id='rsg2-thumbsList'>
                <tr>
                    <td><?php echo _RSGALLERY_MAGIC_NOTIMP?></td>
                </tr>
                </table>
                <?php
                break;
            endswitch;
            ?>
            <div id='rsg2-pageNav'>
                    <?php
                    if( $numPics > $PageSize ){
                    global $Itemid;
                        echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$catid);
                        echo "<br /><br />".$pagenav->writePagesCounter();
                    }
                    ?>
            </div>
            <?php
            }
        else {
            if (!$catid == 0)echo _RSGALLERY_NOIMG;
        }
    }
    
    
    function showMyGalleries($rows) {
    global $mosConfig_live_site, $database;
    //Set variables
    $count = count($rows);
    ?>
    <table class="adminform" width="100%" border="1">
            <tr>
                <td colspan="5"><h3><?php echo _RSGALLERY_USER_MY_GAL;?></h3></td>
            </tr>
            <tr>
                <th><div align="center"><?php echo _RSGALLERY_MY_IMAGES_CATEGORY; ?></div></th>
                <th width="75"><div align="center"><?php echo _RSGALLERY_MY_IMAGES_PUBLISHED; ?></div></th>
                <th width="75"><div align="center"><?php echo _RSGALLERY_MY_IMAGES_DELETE; ?></div></th>
                <th width="75"><div align="center"><?php echo _RSGALLERY_MY_IMAGES_EDIT; ?></div></th>
                <th width="75"><div align="center"><?php echo _RSGALLERY_MY_IMAGES_PERMISSIONS; ?></div></th>
            </tr>
            <?php
            if ($count == 0)
                {
                ?>
                <tr><td colspan="5"><?php echo _RSGALLERY_NO_USER_GAL; ?></td></tr>
                <?php
                }
            else
                {
                //echo "This is the overview screen";
                foreach ($rows as $row)
                    {
                    global $Itemid;
                    ?>
                    <script type="text/javascript">
                    function deletePres(catid)
                    {
                        var yesno = confirm ("<?php echo _RSGALLERY_DELCAT_TEXT;?>");
                        if (yesno == true)
                        {
                            location = "<?php echo $mosConfig_live_site;?>/index.php?option=com_rsgallery2&page=delusercat&catid="+catid;
                        }
                    }
                    </script>
                    <tr>
                        <td><?php echo $row->name;?></td>
                        <?php
                        if ($row->published == 1)
                            $img = "publish_g.png";
                        else
                            $img = "publish_r.png";?>
                            
                        <td><div align="center"><img src="<?php echo $mosConfig_live_site;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" border="0"></div></td>
                        <td><a href="javascript:deletePres(<?php echo $row->id;?>);"><div align="center"><img src="<?php echo $mosConfig_live_site;?>/administrator/images/publish_x.png" alt="" width="12" height="12" border="0"></a></div></td>
                        <td><a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&page=editusercat&catid='.$row->id);?>"><div align="center"><img src="<?php echo $mosConfig_live_site;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" border="0"></a></div></td>
                        <td><a href="#" onclick="alert('Feature not implemented yet')"><div align="center"><img src="<?php echo $mosConfig_live_site;?>/administrator/images/users.png" alt="" width="22" height="22" border="0"></div></td>
                    </tr>
                    <?php
                    $sql2 = "SELECT * FROM #__rsgallery2_galleries WHERE parent = $row->id ORDER BY ordering ASC";
                    $database->setQuery($sql2);
                    $rows2 = $database->loadObjectList();
                    global $Itemid;
                    foreach ($rows2 as $row2)
                        {
                        if ($row2->published == 1)
                            $img = "publish_g.png";
                        else
                            $img = "publish_r.png";?>
                        <tr>
                            <td>
                                <img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_rsgallery2/images/sub_arrow.png" alt="" width="12" height="12" border="0">
                                &nbsp;
                                <?php echo $row2->name;?>
                            </td>
                            <td>
                                <div align="center">
                                    <img src="<?php echo $mosConfig_live_site;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" border="0">
                                </div>
                            </td>
                            <td>
                                <a href="javascript:deletePres(<?php echo $row2->id;?>);">
                                <!--<a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&Itemid=$Itemid&page=delusercat&catid='.$row2->id);?>">-->
                                    <div align="center">
                                    <img src="<?php echo $mosConfig_live_site;?>/administrator/images/publish_x.png" alt="" width="12" height="12" border="0">
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo sefRelToAbs('index.php?option=com_rsgallery2&Itemid=$Itemid&page=editusercat&catid='.$row2->id);?>">
                                <div align="center">
                                    <img src="<?php echo $mosConfig_live_site;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" border="0">
                                </div>
                                </a>
                            </td>
                            <td>
                                <a href="#" onclick="alert('<?php echo _RSGALLERY_FEAT_NOTIMP?>')">
                                <div align="center">
                                    <img src="<?php echo $mosConfig_live_site;?>/administrator/images/users.png" alt="" width="22" height="22" border="0">
                                </div>
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                    }
                }
                    ?>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                    </tr>
                </table>
    <?php
    }
    /**
     * This will show the images, available to the logged in users in the My Galleries screen
     * under the tab "My Images".
     * @param array Result array with image details for the logged in users
     * @param array Result array with pagenav details
     */
    function showMyImages($images, $pageNav) {
        global $rsgAccess, $mosConfig_live_site;
        ?>
        <table width="100%" class="adminlist" border="1">
        <tr>
            <td colspan="4"><h3><?php echo _RSGALLERY_MY_IMAGES; ?></h3></td>
        </tr>
        <tr>
            <th colspan="4"><div align="right"><?php echo $pageNav->writeLimitBox("index.php?option=com_rsgallery2&page=my_galleries"); ?></div></th>
        </tr>
        <tr>
            <th><?php echo _RSGALLERY_MY_IMAGES_NAME; ?></th>
            <th><?php echo _RSGALLERY_MY_IMAGES_CATEGORY; ?></th>
            <th width="75"><?php echo _RSGALLERY_MY_IMAGES_DELETE; ?></th>
            <th width="75"><?php echo _RSGALLERY_MY_IMAGES_EDIT; ?></th>
        </tr>
        
        <?php
        if (count($images) > 0)
            {
             ?>
            <script type="text/javascript">
            function deleteImage(id)
            {
                var yesno = confirm ('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>');
                if (yesno == true)
                {
                    location = "<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=delete_image&amp;id=");?>"+id;
                }
            }
            </script>
            <?php
            foreach ($images as $image)
                {
                global $Itemid,$rsgConfig;
               ?>
                <tr>
                    <td>
                        <?php
                        if (!$rsgAccess->checkGallery('up_mod_img', $image->gallery_id)) {
                            echo $image->name;
                        } else {
						 echo JHTML::tooltip('<img src="'.JURI::root().$rsgConfig->get('imgPath_thumb').'/'.$image->name.'.jpg" alt="'.$image->name.'" />',
						 _RSGALLERY_EDIT_IMAGE,
						 $image->name,
						 $image->title.'&nbsp;'.$image->name,
						 "index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=edit_image&id=".$image->id,
						1);
}
                        ?>
                    </td>
                    <td><?php echo galleryUtils::getCatnameFromId($image->gallery_id)?></td>
                    <td>
                        <?php
                        if (!$rsgAccess->checkGallery('del_img', $image->gallery_id)) {
                            ?>
                            <div align="center">
                                <img src="<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/images/no_delete.png" alt="" width="12" height="12" border="0">
                            </div>
                            <?php
                        } else {
                        ?>
                        <a href="javascript:deleteImage(<?php echo $image->id;?>);">
                            <div align="center">
                                <img src="<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/images/delete.png" alt="" width="12" height="12" border="0">
                            </div>
                        </a>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ( !$rsgAccess->checkGallery('up_mod_img', $image->gallery_id) ) {
                            ?>
                            <div align="center">
                                <img src="<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/images/no_edit.png" alt="" width="15" height="15" border="0">
                            </div>
                            <?php
                        } else {
                        ?>
                        <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=edit_image&amp;id=$image->id");?>">
                        <div align="center">
                            <img src="<?php echo $mosConfig_live_site;?>/components/com_rsgallery2/images/edit.png" alt="" width="15" height="15" border="0">
                        </div>
                        </a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                }
            }
        else
            {
            ?>
            <tr><td colspan="4"><?php echo _RSGALLERY_NOIMG_USER; ?></td></tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="4"><div align="center"><?php global $Itemid; echo $pageNav->writePagesLinks();echo "<br>".$pageNav->writePagesCounter(); ?></div></th>
            </tr>
            </table>
            <?php
    }
    /**
     * Displays details about the logged in user and the privileges he/she has
     * $param integer User ID from Joomla user table
     */
     function RSGalleryUserInfo($id) {
     global $my, $rsgConfig;
     if ($my->usertype == "Super Administrator" OR $my->usertype == "Administrator") {
        $maxcat = "unlimited";
        $max_images = "unlimited";
     } else {
        $maxcat = $rsgConfig->get('uu_maxCat');
        $max_images = $rsgConfig->get('uu_maxImages');
     }
     ?>
     <table class="adminform" border="1">
     <tr>
        <th colspan="2"><?php echo _RSGALLERY_USER_INFO; ?></th>
     </tr>
     <tr>
        <td width="250"><?php echo _RSGALLERY_USER_INFO_NAME; ?></td>
        <td><?php echo $my->username;?></td>
     </tr>
     <tr>
        <td><?php echo _RSGALLERY_USER_INFO_ACL; ?></td>
        <td><?php echo $my->usertype;?></td>
     </tr>
     <tr>
        <td><?php echo _RSGALLERY_USER_INFO_MAX_GAL; ?></td>
        <td><?php echo $maxcat;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userCategoryTotal($my->id);?></strong></font> <?php echo _RSGALLERY_USER_INFO_CREATED;?></td>
     </tr>
     <tr>
        <td><?php echo _RSGALLERY_USER_INFO_MAX_IMG; ?></td>
        <td><?php echo $max_images;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userImageTotal($my->id);?></strong></font> <?php echo _RSGALLERY_USER_INFO_UPL; ?></td>
     </tr>
     <tr>
        <th colspan="2"></th>
     </tr>
     </table>
     <br><br>
     <?php
     }
     
    /**
     * This presents the main My Galleries page
     * @param array Result array with category details for logged in users
     * @param array Result array with image details for logged in users
     * @param array Result array with pagenav information
     */
    function myGalleries($rows, $images, $pageNav)
        {
        global $rsgConfig, $my, $database, $mosConfig_live_site;
        if (!$rsgConfig->get('show_mygalleries'))
            mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"),_RSGALLERY_USERGAL_DISABLED);
        ?>
        <h2><?php echo _RSGALLERY_USER_MY_GAL;?></h2>
        
        <?php
        //Show User information
        HTML_RSGALLERY::RSGalleryUSerInfo($my->id);
        
        //Start tabs
        $tabs = new mosTabs(0);
        $tabs->startPane( 'tabs' );
        $tabs->startTab( _RSGALLERY_MY_IMAGES, 'my_images' );
            HTML_RSGALLERY::showMyImages($images, $pageNav);
            HTML_RSGALLERY::showFrontUpload();
        $tabs->endtab();
        if ($rsgConfig->get('uu_createCat')) {
            $tabs->startTab( _RSGALLERY_USER_MY_GAL, 'my_galleries' );
                HTML_RSGALLERY::showMyGalleries($rows);
                HTML_RSGALLERY::showUserGallery(NULL);
            $tabs->endtab();
        }
        $tabs->endpane();
        ?>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
        }
        
    function showRandom($rows, $style = "hor") {
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
                                <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" border="0" />
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
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" border="0" />
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
    
        function showLatest($rows, $style = "hor") {
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
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" border="0" />
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
                            <img src="<?php echo imgUtils::getImgThumb($row->name);?>" alt="<?php echo $row->descr;?>" border="0" />
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
}//end class