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
		$limit = rsgInstance::getInt( 'limitg', $rsgConfig->get('galcountNrs') );
		$limitstart = rsgInstance::getInt( 'limitstartg', 0 );
		//Get number of galleries including main gallery
		$this->kids = $gallery->kids();
		$kidCountTotal = count( $gallery->kids() );

		$this->pageNav = false;
		
		if( $rsgConfig->get('dispLimitbox') == 1 ) {
			if( $kidCountTotal > $limit ){
				jimport('joomla.html.pagination');
				$this->kids = array_slice( $this->kids, $limitstart, $limit );
				$this->pageNav = new JPagination($kidCountTotal, $limitstart, $limit );
			}
		} elseif( $rsgConfig->get('dispLimitbox') == 2 ) {
			jimport('joomla.html.pagination');
			$this->kids = array_slice( $this->kids, $limitstart, $limit );
			$this->pageNav = new JPagination( $kidCountTotal, $limitstart, $limit );
		}
	
		$this->display( 'gallery.php' );
		
		//Show page navigation if selected in backend
	}

	function getGalleryLimitBox(){
		$pagelinks = $this->pageNav->getLimitBox("index.php?option=com_rsgallery2");
		$pagelinks = str_replace("\"limit", "\"limitg", $pagelinks);
		return $pagelinks; 
	}

	function getGalleryPageLinks(){
		$pagelinks = $this->pageNav->getPagesLinks("index.php?option=com_rsgallery2");
		$pagelinks = str_replace ("&amp;limit=","&amp;limitg=", $pagelinks);
		$pagelinks = str_replace ("&amp;limitstart=","&amp;limitstartg=", $pagelinks);
		if($this->pageNav->limitstart >= $this->pageNav->limit){
			$pagelinks = str_replace("&amp;limitstartg=".$this->pageNav->limit, "", $pagelinks);
		}
		return $pagelinks;
	
	}
	function getGalleryPagesCounter(){
		return $this->pageNav->getPagesCounter();
	}

    /***************************
		non page public functions
	***************************/
	/**
	 * Shows the gallery details block when set in the backend
	 */
	function _showGalleryDetails( $kid ) {
		global $rsgConfig, $Itemid;
		$slideshow = $rsgConfig->get('displaySlideshow') && $kid->itemCount() > 1;
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
				<a href='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=slideshow&gid=".$kid->get('id')."&Itemid=".$Itemid); ?>'><?php echo _RSGALLERY_SLIDESHOW; ?></a><br />
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
    function _showCustom( $kids, $cols, $subgalleries ) {
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
		
		$limit = $rsgConfig->get("display_thumbs_maxPerPage") ;
		$limitstart = rsgInstance::getInt( 'limitstart' );
		
		//instantiate page navigation
		jimport("joomla.html.pagination");
		$pagenav = new JPagination( $itemCount, $limitstart, $limit );
		
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
		<div class="rsg2-pageNav">
				<?php
				if( $itemCount > $limit ){
				global $Itemid;
					echo $pagenav->getPagesLinks();
					echo "<br /><br />".$pagenav->getPagesCounter();
				}
				?>
		</div>
		<?php
	}
    
    /**
     * Shows main image
     */
	function showDisplayImage(){
		global $rsgConfig, $mosConfig_live_site, $mainframe;
		
		$item = rsgInstance::getItem();

		if( $item->type != 'image' ){
			// item is not an image, return;
			return;
		}

		// increase hit counter
		$item->hit();
		
		if( $rsgConfig->get('displayPopup') == 2 ){
			//Lightbox++ scripts and CSS in document head
			$js1 = "<script src=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/lightbox++/js/prototype.js\" type=\"text/javascript\"></script>";
    		$mainframe->addCustomHeadTag($js1);
			$js2 = "<script src=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/lightbox++/js/scriptaculous.js?load=effects\" type=\"text/javascript\"></script>";
    		$mainframe->addCustomHeadTag($js2);
			$js3 = "<script src=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/lightbox++/js/lightbox++.js\" type=\"text/javascript\"></script>";
    		$mainframe->addCustomHeadTag($js3);
			$css = "<link rel=\"stylesheet\" href=\"".$mosConfig_live_site."/components/com_rsgallery2/lib/lightbox++/css/lightbox.css\" media=\"screen\" type=\"text/css\" />";
    		$mainframe->addCustomHeadTag($css);
		}
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td><h2 class='rsg2_display_name' align="center"><?php echo htmlspecialchars(stripslashes($item->title), ENT_QUOTES); ?></h2></td>
			</tr>
			<tr>
				<td>
				<div align="center">
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
						//Lightbox++ popup
						case 2:
							if ($rsgConfig->get('watermark')) {
								?><a rel="lightbox" title="<?php echo $item->title."<p>".$item->descr."</p>";?>" href="<?php echo waterMarker::showMarkedImage( $item->name, 'original' ); ?>"><?php
							} else {
								?><a rel="lightbox" title="<?php echo $item->title."<p>".$item->descr."</p>";?>" href="<?php echo imgUtils::getImgOriginal( $item->name ); ?>"><?php
							}
							$this->_showImageBox( $item->name, $item->descr );
							?>
							</a>
							<?php
							break;
					}
					?>
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
			rsgInstance::setVar( 'limitstart', $gallery->indexOfItem() );
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
                <form method="post" action="<?php global $Itemid; echo JRoute::_("index.php?option=com_rsgallery2&Itemid=$Itemid&page=vote"); ?>">
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
	function X_showEXIF( ) {
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
	* list sub galleries in a gallery
	* @param rsgGallery parent gallery
	*/
	function _subGalleryList( $parent ){
		global $Itemid;
		$kids = $parent->kids();

		if( count( $kids ) == 0 ) return;
		
		echo _RSGALLERY_TMPL_GAL_SUBGALLERIES;
		
		$kid = array_shift( $kids );
		
		while( true ){
			?>
			<a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&Itemid=$Itemid&catid=".$kid->id); ?>">
				<?php echo htmlspecialchars(stripslashes($kid->name), ENT_QUOTES); ?>
				(<?php echo $kid->itemCount(); ?>)</a><?php

			if( $kid = array_shift( $kids ))
				echo ', ';
			else
				break;
		}
	}
}