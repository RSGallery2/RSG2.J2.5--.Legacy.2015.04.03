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
		$this->display( 'inline.php' );
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
		$limit = rsgInstance::getInt( 'limit', $rsgConfig->get('galcountNrs') );
		$limitstart = rsgInstance::getInt( 'limitstart', 0 );
		
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
	/**
	 * Shows the gallery details block when set in the backend
	 */
	function _showGalleryDetails( $kid ) {
		global $rsgConfig, $Itemid;
		$slideshow 	= $rsgConfig->get('displaySlideshow');
		$owner 		= $rsgConfig->get('showGalleryOwner');
		$size 		= $rsgConfig->get('showGallerySize');
		$date 		= $rsgConfig->get('showGalleryDate');
		
		//Check if items need to be shown
		if ( ($slideshow + $owner + $size + $date) > 0 ) {
			?>
			<div class="rsg_gallery_details">
			<div class="rsg2_details">
			<?php
			if ($slideshow) {
				?>
				<a href='<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;page=slideshow&amp;gid=".$kid->get('id')."&amp;Itemid=".$Itemid); ?>'><?php echo _RSGALLERY_SLIDESHOW; ?></a><br />
				<?php
			}
			
			if ($owner) {
				echo _RSGALLERY_TMPL_GAL_DETAILS_OWNER." "; echo $kid->owner;?><br />
				<?php
			} 
			
			if ($size) {
				echo _RSGALLERY_TMPL_GAL_DETAILS_SIZE." "; echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?><br />
			<?php
			}
			
			if ($date) {
				echo _RSGALLERY_TMPL_GAL_DETAILS_DATE." "; echo mosFormatDate( $kid->date,"%d-%m-%Y" );?><br />
				<?php
			}
			?>
			</div>
			</div>
			<?php
		}
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
			$doc =& JFActory::getDocument();
			$doc->addStyleSheet(JPATH_BASE."/components/com_rsgallery2/js_highslide/highslide.css");
			$doc->addScript(JPATH_BASE."/components/com_rsgallery2/js_highslide/highslide.js");
			$doc->addScriptDeclaration("
				hs.graphicsDir = '".$mosConfig_live_site."/components/com_rsgallery2/js_highslide/graphics/';
				hs.showCredits = false;
				hs.outlineType = 'drop-shadow';
				window.onload = function() {
					hs.preloadImages();
				} " );
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
			//<![CDATA[
            function deleteComment(id) {
                var yesno = confirm ('<?php echo _RSGALLERY_COMMENT_DELETE;?>');
                if (yesno == true) {
                    location = '<?php JRoute::_("index.php?option=com_rsgallery2&page=delete_comment&id=", false);?>'+id+'';
                }
            }
			//]]>
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
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid); ?>">
                <?php echo _RSGALLERY_MAIN_GALLERY_PAGE; ?>
                </a>
            </li>
            <?php 
            if ( !$my->id == "" && $page != "my_galleries" && $rsgConfig->get('show_mygalleries') == 1):
            ?>
            <li>
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid."&amp;rsgOption=myGalleries");?>">
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
	*/
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