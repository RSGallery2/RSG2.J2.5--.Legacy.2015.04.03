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
class rsgDisplay_tables extends rsgDisplay{

	/***************************
		pages
	***************************/
  
function RandomPic()
{
global $database;
global $mainframe;
	
	$mainframe->setPageTitle('Random Image');
	
	$query="SELECT * FROM #__rsgallery2_files WHERE published='1' ORDER BY rand() LIMIT 1 ";

    $database->setQuery( $query );
	$randompic = $database->loadObjectList();

	$query="SELECT * FROM #__rsgallery2_galleries WHERE id = " . $randompic[0]->gallery_id;
 	$database->setQuery( $query );
 	$galleryname = $database->loadObjectList();
	$galname = $galleryname[0]->name;
 
 	$query="SELECT COUNT(1) FROM #__rsgallery2_files WHERE published='1'";
	$database->setQuery( $query );
	$countofall = $database->loadResult();
				
 
 
	


?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="3">Title: 
      <h2 class='rsg2_display_name' align="center"><?php echo htmlspecialchars(stripslashes($randompic[0]->title), ENT_QUOTES); ?></h2>
      <br />
    In Gallery: <a href="index.php?option=com_rsgallery2&amp;catid=<?php echo $randompic[0]->gallery_id ?>"><?php echo $galname ?> </a>  <br /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="top">		
        
<table  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center"><a href="<?php echo imgUtils::getImgOriginal($randompic[0]->name); ?>" target="_blank">
                              <?php $this->_showImageBox($randompic[0]->name, $randompic[0]->desc); ?>
                        </a> </td>
                  </tr>
                  <tr>
                    <td align="center">Click image for Full Size</td>
                  </tr>
                </table>

        
        </td>
    <td width="240" align="right" valign="top"><p>Random from <strong><?php echo $countofall; ?></strong> Images</p>
      <p><a rel="nofollow" href="http://www.fantasyartwork.net/index.php?option=com_rsgallery2&amp;page=random"><img src="/components/com_rsgallery2/templates/ChefGroovy/images/next.jpg" alt="Next Random" width="120" height="67" border="0" title="Next Random Image" /></a></p>
    <p><a rel="nofollow" href="http://www.fantasyartwork.net/index.php?option=com_rsgallery2&amp;page=random">Next Random</a></p>
<?php
         global $my, $rsgConfig;
     if ($my->usertype == "Super Administrator" OR $my->usertype == "Administrator") {

		$this->showAdminMenu(1);
		echo _RSGALLERY_MY_IMAGES_DELETE;
		}
?>
				     <p>&nbsp;</p>
	  <script type="text/javascript"><!--
                            google_ad_client = "pub-4848754589931121";
                            //Random Image
                            google_ad_slot = "5565827800";
                            google_ad_width = 234;
                            google_ad_height = 60;
                            //--></script>
      <script type="text/javascript"
                            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                            </script>
                          <p>&nbsp;</p>
                                                      <p>&nbsp;</p>
                       
    
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top">              
    

                            

      <p>&nbsp;</p>
             				<?php
					// $this->Banner480x60();
				?>
                            
                  </td>
  </tr>
</table>







<?php


}
	
	
function ShowPicOnly() {

global $database;
global $mainframe;

		

 	$imageid = mosGetParam( $_REQUEST, 'id', '' );

	 $query = "SELECT *"
    . " FROM #__rsgallery2_files"
    . " WHERE id = '$imageid'";
    
    $database->setQuery( $query );
	$imagefromid = $database->loadObjectlist();
	if ( !$imagefromid ) {
		echo "Not Found";
		}
		else
		{
		$mainframe->setPageTitleNoSiteName($imagefromid[0]->title . ' - ' .  $this->gallery->get('name') . ' - ' . 'Fantasy Art' );
		?>
       
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
        <td align="center" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td align="center" valign="top"> <h3><?php echo $imagefromid[0]->title; ?></h3></td>
          </tr>
          <tr>
            <td align="center" valign="top"><a href="<?php echo imgUtils::getImgOriginal($imagefromid[0]->name); ?>" target="_blank">
              <?php $this->_showImageBox($imagefromid[0]->name, $imagefromid[0]->desc); ?>
            </a> </td>
          </tr>
			<tr>
            	<td>
               <?php $this->showDisplayPageNav(); ?>
                </td>
            </tr>
  			<tr align="center">
            	<td>
                <p>&nbsp;</p>
				  <script type="text/javascript"><!--
                            google_ad_client = "pub-4848754589931121";
                            //FANT 468x60, SMALL BANNER
                            google_ad_slot = "8470894963";
                            google_ad_width = 468;
                            google_ad_height = 60;
                            //--></script>
                  <script type="text/javascript"
                            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                            </script>
                </td>
            <tr>
        </table>      


	
<?php		
		}
	
?>
												

<?php
}
	
	function inline()
	{
			global $mainframe;
		
    ?>
<table width="100%">

<tr>
    <td>
    <?php $this->showDisplayImage(); ?>    </td>
</tr>
<tr>
    <td>
    <?php $this->showDisplayPageNav(); ?>    </td>
</tr>
<tr>
    <td>
    <?php $this->showDisplayImageDetails(); ?>    </td>
</tr>
</table>
<?php
    }
    
    function thumbs(){
    ?>
<table width="100%" border="0">
<tr>
	<td>
		<?php
		/**
		 * This will show any subgalleries in this gallery.
		 */
		$this->showMainGalleries('single', $subgalleries = true);
		?>
	</td>
</tr>
<tr>
	<td>
		<?php
		/**
		 * This will show the thumbs from the current gallery
		 */
		$this->showThumbs();?></td>
</tr>
</table>


    <?php
    }

    /**
     * Show main gallery page
     * @param string Style for main page (single, double, box)
     * @param boolean Show subgalleries or not.
     * @return HTML for main gallery page.
     */
    function showMainGalleries($style = "single", $cols = 3, $subgalleries = "true") {
        global $database, $Itemid, $rsgConfig;
        
        $gid = mosGetParam( $_REQUEST, 'catid', 0 );
        $gallery = rsgGalleryManager::get( $gid );
        
        //Get values for page navigation from URL
        $limit = mosGetParam ( $_REQUEST, 'limit', $rsgConfig->galcountNrs);
        $limitstart = mosGetParam ( $_REQUEST, 'limitstart', 0);
        
        //Get number of galleries including main gallery
        $kids = $gallery->kids();
        $kidCountTotal = count( $kids );

        $pageNav = false;
        
        if( $rsgConfig->get('dispLimitbox') == 1) {
            if( $kidCountTotal > $limit ){
                $kids = array_slice( $kids, $limitstart, $limit );
                $pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
            }
        } elseif($rsgConfig->get('dispLimitbox') == 2) {
            $kids = array_slice( $kids, $limitstart, $limit );
            $pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
        }

        //Show limitbox
        if( $pageNav ) {
        	$catid = mosGetParam ( $_REQUEST, 'catid', 0);
        	$catid = $catid? "&amp;catid=$catid" : '';
            ?>
            <div class="rsg2-pagenav-limitbox">
                <?php echo $pageNav->writeLimitBox("index.php?option=com_rsgallery2&amp;Itemid=$Itemid".$catid); ?>
            </div>
            <?php
        }
        
        switch ( $style ) {
            case "box":
                $this->_showBox( $kids, $subgalleries );
                break;
            case "double":
                $this->_showDouble( $kids, $subgalleries );
                break;
            case "custom":
                $this->_showCustom( $kids,  $cols, $subgalleries );
                break;
            case "single":
            default:
                $this->_showSingle( $kids,  $subgalleries );
                break;
        }
        //Show page navigation if selected in backend
        if( $pageNav ) {
        ?>
        <div class="rsg2-pageNav">
        <?php 
        	$catid = mosGetParam ( $_REQUEST, 'catid', 0);
        	$catid = $catid? "&amp;catid=$catid" : '';
            echo $pageNav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid".$catid);echo "<br>".$pageNav->writePagesCounter(); ?></div>
        <div class='clr'>&nbsp;</div>
        <?php
        }
    }
    
    /***************************
		non page public functions
	***************************/
    
    function _showGalleryDetails( $kid ) {
        global $rsgConfig;
        ?>
        <span class="rsg_gallery_details"><div class="rsg2_details">
        <?php echo _RSGALLERY_TMPL_GAL_DETAILS_OWNER; echo $kid->owner;?><br />
        <?php echo _RSGALLERY_TMPL_GAL_DETAILS_SIZE; echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?><br />
        <?php echo _RSGALLERY_TMPL_GAL_DETAILS_DATE; echo mosFormatDate( $kid->date,"%m-%d-%Y" );?><br />
        </div></span>
        <?php
    }
    
    /***************************
		private functions
	***************************/
    
    function _showSingle( $kids ) {
        global $rsgConfig;

        foreach ($kids as $kid) {
        ?>
        <table class="galleryblock">
        <tr>
            <td width="<?php echo $rsgConfig->get('thumb_width') + 10;?>">&nbsp;</td>
          <td>&nbsp;</td>
            <td width="50" height="30"><div class="rsg2-galleryList-status"><?php echo $kid->status;?></div></td>
        </tr>
        <tr>
            <td valign="top"><?php echo $kid->thumbHTML; ?></td>
            <td valign="top"><?php echo $kid->galleryName;?>
                <span class='rsg2-galleryList-newImages'><sup><?php echo galleryUtils::newImages($kid->get('id')); ?></sup></span>
                <?php echo $this->_showGalleryDetails( $kid );?>
                <div class='rsg2-galleryList-description'><?php echo $kid->description;?></div>
            </td>
            <td valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><span class="rsg_sub_url"><?php HTML_RSGALLERY::subGalleryList( $kid->get('id') ); ?></span></td>
            <td>&nbsp;</td>
        </tr>
        </table>
        <br />
        <?php
        }
    }
    
    function _showDouble( $kids ) {
        global $rsgConfig;
        ?>
        <table width="100%" border="0">
        <tr>
        <?php
        $i = 0;
        foreach ( $kids as $kid ) {
            $i++;
            ?>
            <td width="50%" valign="top">
                <table class="galleryblock" cellpadding="5" border="0">
                <tr>
                    <td rowspan="2" width="<?php echo $rsgConfig->get('thumb_width') + 10;?>" valign="top">
                        <?php echo $kid->thumbHTML; ?>
                        <?php echo $this->_showGalleryDetails( $kid );?>
                    </td>
              <td valign="top" height="30">
                        <div class="rsg2-galleryList-status"><?php echo $kid->status; ?></div>
                  </td>
                </tr>
                <tr>
                    <td valign="top">
                        <?php echo $kid->galleryName;?>
                        <div class='rsg2-galleryList-totalImages'><?php echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?></div>
                        <div class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($kid->get('id')); ?></div>
                        <div class='rsg2-galleryList-description'><?php echo $kid->description;?></div>
                        <span class="rsg_sub_url"><?php HTML_RSGALLERY::subGalleryList( $kid->get('id') ); ?></span>
                    </td>
                </table>
            </td>
            <?php
            if ($i%2 == 0) {
                echo "</tr><tr>";
            }
        }
        ?>
        <tr>
        </table>
        <?php
    }
    
    function _showBox( $kids, $subgalleries ) {
        ?>
            <table width="100%" border="0">
            <tr>
            <?php
            $i = 0;
            foreach ( $kids as $kid ) {
                $i++;
                ?>
                <td width="200" valign="top">
                    <table class="galleryblock">
                    <tr>
                        <td colspan="2"><div class="rsg2-galleryList-status"><?php echo $kid->status; ?></div></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php echo $kid->galleryName;?>
                            <sup><span class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($kid->get('id')); ?></span></sup>
                            <div class='rsg2-galleryList-totalImages'>(<?php echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?>)</div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <?php echo $kid->thumbHTML; ?>
                        </td>
                        <td valign="top">
                            <?php echo $this->_showGalleryDetails( $kid );?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            <div class='rsg2-galleryList-description'>
                            <?php echo $kid->description;?>
                            </div>
                            <span class="rsg_sub_url"><?php HTML_RSGALLERY::subGalleryList( $kid->get('id'), $subgalleries ); ?></span>
                        </td>
                    </table>
                </td>
                <?php
                if ($i%3 == 0) {
                    echo "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        <?php
    }
    
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
     * Shows thumbnails for gallery and links to subgalleries if they exist.
     * @param integer Category ID
     * @param integer Columns per page
     * @param integer Number of thumbs per page
     * @param integer pagenav stuff
     * @param integer pagenav stuff
     */ 
    function showThumbs() {
        global $mainframe, $database, $my, $mosConfig_live_site, $rsgConfig;
        
        // show list of images in gallery
        $gid        = mosGetParam ( $_REQUEST, 'catid', 0 );
        $limit      = mosGetParam ( $_REQUEST, 'limit', $rsgConfig->get("display_thumbs_maxPerPage") );
        $limitstart = mosGetParam ( $_REQUEST, 'limitstart', 0 );
        
        //Add to pathway
        $galleryname = galleryUtils::getCatNameFromId( $gid );
        //$mainframe->appendPathWay($galleryname);
        
        $thumb_width                = $rsgConfig->get("thumb_width");
        $columns                    = $rsgConfig->get("display_thumbs_colsPerPage");
        $PageSize                   = $rsgConfig->get("display_thumbs_maxPerPage");
        //$my_id                      = $my->id;
    
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id='$gid' AND published = '1'");
        $numPics = $database->loadResult();
        
        if(!isset($limitstart))
            $limitstart = 0;
        //instantiate page navigation
        $pagenav = new mosPageNav($numPics, $limitstart, $PageSize);
    
        $picsThisPage = min($PageSize, $numPics - $limitstart);
    
        if (!$picsThisPage == 0)
                $columns = min($picsThisPage, $columns);
                
        //Add a hit to the database
        if ($gid && !$limitstart)
            {
            galleryUtils::addCatHit($gid);
            }
        //Old rights management. If user is owner or user is Super Administrator, you can edit this gallery
        if(( $my->id <> 0 ) and (( galleryUtils::getUID( $gid ) == $my->id ) OR ( $my->usertype == 'Super Administrator' )))
            $allowEdit = true;
        else
            $allowEdit = false;

        $thumbNumber = 0;
        ?>
        <div class='rsg2-pageNav'>
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
        <?php           if ($picsThisPage){
            $sort = galleryUtils::getGallerySort($sortdirective);
          //  $this->writeSortLink($sortdirective, $gid );
            $database->setQuery("SELECT * FROM #__rsgallery2_files".
                                   " WHERE gallery_id='$gid'".
                                   " AND published ='1'".
                                   " ORDER BY $sort".
                                   " LIMIT $limitstart, $PageSize");
        $rows = $database->loadObjectList();
        
        switch( $rsgConfig->get( 'display_thumbs_style' )):
            case 'float':
                $floatDirection = $rsgConfig->get( 'display_thumbs_floatDirection' );
                ?>
                <ul id='rsg2-thumbsList'>
                <?php foreach( $rows as $row ): ?>
                <li <?php echo "style='float: $floatDirection'"; ?> >         <a href="<?php global $Itemid; echo sefRelToAbs( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=".$row->id."&amp;catid=".$row->gallery_id."&amp;limitstart=".$limitstart++."&amp;sort=".$sortdirective  ); ?>">
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
                            <!--<div class="img-shadow">-->         <a href="<?php global $Itemid; echo sefRelToAbs( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=".$row->id."&amp;catid=".$row->gallery_id."&amp;limitstart=".$limitstart++."&amp;sort=".$sortdirective  ); ?>">
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
            <div class='rsg2-pageNav'>
                    <?php
                    if( $numPics > $PageSize ){
                    global $Itemid;
                        echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$gid."&amp;sort=".$sortdirective);
                        echo "<br /><br />".$pagenav->writePagesCounter();
                    }
                    ?>
            </div>
            <?php
            }
        else {
            if (!$gid == 0)echo _RSGALLERY_NOIMG;
        }
    }
    
    /**
     * Shows main image
     */
    function showDisplayImage(){
        global $mosConfig_live_site, $rsgConfig, $database, $rows;
  		global $mainframe;
		
		 
		  
		  
        $limitstart = mosGetParam ( $_REQUEST, 'limitstart', 0);
        $gallery = rsgGalleryManager::get( mosGetParam ( $_REQUEST, 'catid', 0) );
		$catid = mosGetParam ( $_REQUEST, 'catid', 0);
		$thispicid = mosGetParam ( $_REQUEST, 'id', 0);
        $items = $gallery->items();
        $image = $items[$limitstart];
		
 $mainframe->setPageTitleNoSiteName($image['title'] . ' - ' .  $this->gallery->get('name'));
		
        $this->writeSLideShowLink();

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
                <td align="center"></td>
          </tr>
            <tr>
                <td>
                <div align="center">
                    <div id="highslide-container">


 <?php   
 
 // GET PREVIOUS AND 


		       //Set page size to 1 for 1 display image per page
        $pageSize   = 1;
		        //Get total number of images
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id = '$catid'");
        $numPics = $database->loadResult();
			//instantiate page navigation
        $pageNav    = new mosPageNav($numPics, $limitstart, $pageSize);
        $thisPage   = floor($limitstart/$pageSize)+1;
        $maxPage    = ceil($numPics / $pageSize);
		$prevPage = $thisPage-2;
		$nextPage = $thisPage;

if ($limitstart == '0')
{
    $database->setQuery("SELECT * FROM #__rsgallery2_files".
           " WHERE gallery_id = $catid".
           " ORDER BY date DESC".
           " LIMIT $limitstart,2");
		   $prevnexts = $database->loadObjectlist();
		$previous = '0';
		$current = $prevnexts[0]->id;
		$nextone = $prevnexts[1]->id;
		$nextthumb = $prevnexts[1]->name;
}
else
{
$j = $limitstart - 1;
    $database->setQuery("SELECT * FROM #__rsgallery2_files".
           " WHERE gallery_id = $catid".
           " ORDER BY date DESC".
           " LIMIT $j,3");
		   
		    $prevnexts = $database->loadObjectlist();
			$previous = $prevnexts[0]->id;
			$current = $prevnexts[1]->id;
			$nextone = $prevnexts[2]->id;
			$nextthumb = $prevnexts[2]->name;
			$prevthumb = $prevnexts[0]->name;
}
?>
    
<table width="100%" border="0" >
  <tr>
    
<td align="center">
<h2 class='rsg2_display_name' align="center"><?php echo htmlspecialchars(stripslashes($image['title']), ENT_QUOTES); ?></h2>
<br/>
<?php
                    switch ($rsgConfig->get('displayPopup')) {
                        //No popup
                        case 0:
                            $this->_showImageBox($image['name'], $image['descr']);
                            echo "TEST";
							break;
                        //Normal popup
                        case 1:
					
                            if ($rsgConfig->get('watermark')) {
                                ?>
  <p><a href="<?php echo waterMarker::showMarkedImage($image['name'], 'original'); ?>" target="_blank">
    <?php
                            } else {
                                ?>
    <a href="<?php echo imgUtils::getImgOriginal($image['name']); ?>" target="_blank">
    <?php
                            }
                            $this->_showImageBox($image['name'], $image['descr']);
                            ?>
    </a>
    <?php
                            break;
                        //Highslide popup
                        case 2:
                            if ($rsgConfig->get('watermark')) {
                                ?>
    <a href="<?php echo waterMarker::showMarkedImage($image['name'], 'original'); ?>" class="highslide" onclick="return hs.expand(this)">
      <?php
                            } else {
                                ?>
      <a href="<?php echo imgUtils::getImgOriginal($image['name']); ?>" class="highslide" onclick="return hs.expand(this)">
      <?php
                            }
                            $this->_showImageBox($image['name'], $image['descr']);
                            ?>
      </a>
    <?php
                            break;
                    }
                    ?>
  </p>
  <p>Click Image for Full Size</p>
  
  <?php	// $this->Banner480x60();	?>
                
                </td>
    <td width="70" align="center" valign="top">

    <?php 
	///////////   NEXT PAGE THUMBNAIL
	?>
    
	<div class="nextprev">

	    <?php 
		  if ($nextPage < $maxPage) {
		?>
	              
                  <a href="<?php echo "index.php?option=com_rsgallery2&page=inline&catid=$catid&id=$nextone&limitstart=$nextPage&limit=1";?>"> <img src="/components/com_rsgallery2/templates/ChefGroovy/images/next.jpg" alt="Next" width="120" height="67" border="0" title="Next Image" /> </a>
                  <p>&nbsp;</p>
 
                  <?php } 
				  
				  else
				  { ?>
                  <a href="<?php echo "index.php?option=com_rsgallery2&page=inline&catid=$catid&limitstart=0&limit=1";?>"> <img src="/components/com_rsgallery2/templates/ChefGroovy/images/next.jpg" alt="Next" width="120" height="67" border="0" title="Next Image" /> </a>
                  <p>&nbsp;</p>
            			

                        
				  <?php } ?>
                  						<script type="text/javascript"><!--
                        google_ad_client = "pub-4848754589931121";
                        /* 125x125, created 5/14/08 */
                        google_ad_slot = "2208446047";
                        google_ad_width = 125;
                        google_ad_height = 125;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                           <p>&nbsp;</p>  
                  <a href="index.php?option=com_rsgallery2&amp;page=random" rel="nofollow" >Display Random Image</a>                  </div>  

            </td>
  </tr>
</table>
                  </div>
                </div>                </td>
            </tr>
            <tr>
                <td align="center">
                          	
				
                            

          

                
                    <p>Hits: <?php echo $this->item['hits']+1; ?></p>
					<?php /* $this->_writeDownloadLink( $image['id'] );  */?>                </td>
          </tr>
          <tr>
          <td align="center">          </td>
          </tr>
        </table>
        
        

        
<?php
    }
    
    /**
     * Show page navigation for Display image
     */
    function showDisplayPageNav() {
        global $database;
      
		
        //Get variables from URL
        $limitstart = mosGetParam ( $_REQUEST, 'limitstart', 0);
        $gid        = mosGetParam ( $_REQUEST, 'catid', 0);
        $id         = mosGetParam ( $_REQUEST, 'id', 0);
        //Set page size to 1 for 1 display image per page
        $pageSize   = 1;
        
        //Get total number of images
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id = '$gid'");
        $numPics = $database->loadResult();
        
        //instantiate page navigation
        $pageNav    = new mosPageNav($numPics, $limitstart, $pageSize);
        $thisPage   = floor($limitstart/$pageSize)+1;
        $maxPage    = ceil($numPics / $pageSize);
		$prevPage = $thisPage-2;
		$nextPage = $thisPage;
        //Output page navigation
        ?>
        <div align="center">
        <?php
        // print page navigation.
        global $Itemid;
                $sort = galleryUtils::getGallerySort($sortdirective);
         echo $pageNav->writePagesLinks( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;catid=".$gid."&amp;id=".$id."&amp;sort=".$sortdirective );
        ?>
        </div>
        

        
        <?php
        
    }
    
    /**
     * Shows details of image
     */
    function showDisplayImageDetails() {
        global $rsgConfig;
        
        $useTabs=0;

        if ($rsgConfig->get("displayDesc") == 1)    $useTabs++;
        if ($rsgConfig->get("displayVoting") == 1)  $useTabs++;
        if ($rsgConfig->get("displayComments") ==1) $useTabs++;
        if ($rsgConfig->get("displayEXIF") == 1)    $useTabs++;
        $useTabs = $useTabs > 1 ? 1 : 0;

        $firstTab='';

        if( $rsgConfig->get("displayDesc") )
            $firstTab = 'tab1';
        elseif( $rsgConfig->get("displayVoting") )
            $firstTab = 'tab2';
        elseif( $rsgConfig->get("displayComments") )
            $firstTab = 'tab3';
        elseif( $rsgConfig->get("displayEXIF") )
            $firstTab = 'tab4';
    
        //Here comes the row with the tabs
        if ( $useTabs ) {
            $tabs = new mosTabs(0);
            $tabs->startPane( 'tabs' );
        }
        
        if ($rsgConfig->get("displayDesc") == 1) {
            if ($useTabs) {
                $tabs->startTab(_RSGALLERY_DESCR, 'rs-description' );
                $this->_showDescription(); 
                $tabs->endTab();
            }
        }
        
        if ($rsgConfig->get("displayVoting") == 1) {
            if ($useTabs){
                $tabs->startTab(_RSGALLERY_VOTING, 'Voting' );
                $this->_showVoting();
                $tabs->endTab();
            }
        }
        
        if ($rsgConfig->get("displayComments") == 1) {
            if ($useTabs) {
                $tabs->startTab(_RSGALLERY_COMMENTS, 'Comments' );
                $this->_showComments();
                $tabs->endTab();
            }
        }
    
        if ($rsgConfig->get("displayEXIF") == 1) {
            if ($useTabs) {
                $tabs->startTab(_RSGALLERY_EXIF, 'EXIF' );
                $this->_showEXIF();
                $tabs->endTab();
            }
        }
        if ( $useTabs ) {
            $tabs->endPane();
        }
    }


    /**
     * Show description
     */
    function _showDescription( ) {
        global $rsgConfig;
        ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="adminForm">
        <tr>
            <td>
                <table width="100%" cellpadding="2" cellspacing="1">
                    <?php if( $rsgConfig->get('displayHits')): ?>
                    <tr>
                        <td valign="top" width="100">&nbsp;<strong><?php echo _RSGALLERY_CATHITS; ?>:</strong></td>
                        <td valign="top"><?php echo $this->item['hits']+1; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td valign="top" colspan='2'><?php if ( $this->item['descr'] ) echo $this->item['descr']; else echo "<em>"._RSGALLERY_NODESCR."</em>"; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <?php
    }
    
    function _showVoting() {
        global $rsgConfig;
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
    
    function _showComments() {
        global $rsgConfig, $database, $my;
        $limitstart = $this->limitstart;
        $id = $this->item['id'];
        ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="adminForm">
        <?php
        $database->setQuery("SELECT * FROM #__rsgallery2_comments WHERE picid='$id' ORDER BY id DESC");
        $crows = $database->loadObjectList();
        if (!$crows) {
            ?>
            <tr>
                <td><?php echo _RSGALLERY_NO_COMMENTS; ?></td>
            </tr>
            <?php 
        } else {
        ?>
        <tr>
            <td>
                <table width="100%" cellpadding="2" cellspacing="1">
                <?php
                foreach ($crows as $crow) {
                    ?>
                    <tr>
                        <td width="120"><strong><?php echo _RSGALLERY_COMMENT_DATE; ?>:</strong></td>
                        <td><?php echo mosFormatDate($crow->date); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo _RSGALLERY_COMMENT_BY; ?>:</strong></td>
                        <td><?php echo htmlspecialchars(stripslashes($crow->name), ENT_QUOTES); ?></td>
                    </tr>
                    <tr>
                        <td valign="top"><strong><?php echo _RSGALLERY_COMMENT_TEXT; ?>:</strong></td>
                        <td><?php echo htmlspecialchars(stripslashes($crow->comment), ENT_QUOTES); ?></td>
                    </tr>
                    <?php
                    if ($my->usertype == 'Super Administrator') {
                        ?>
                        <tr>
                            <td colspan="2">
                            <div align="right">
                                <a href="#" onClick="javascript:deleteComment(<?php echo $crow->id;?>);">
                                <?php echo _RSGALLERY_DELETE_COMMENT;?>
                                </a>
                            </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" align="center"><hr></td>
                        </tr>              
                        <?php 
                    }
                    ?>
                </table>
            </td>
        </tr>
        <?php
        }
        ?> 
        <tr>
            <td colspan="2"><strong><font color="#FFFFFF">&nbsp;<?php echo _RSGALLERY_COMMENT_ADD; ?></font></strong></td>
        </tr>
        <tr>
            <td>
            <form method="post" action="<?php global $Itemid; echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=addcomment"); ?>">
            <input type="hidden" name="picid" value="<?php echo $id; ?>" />
            <input type="hidden" name="limitstart" value="<?php echo $limitstart; ?>" />
            <table width="100%" cellpadding="2" cellspacing="1">
                <tr>
                    <td width="130"><strong><?php echo _RSGALLERY_COMMENT_NAME; ?>:</strong></td>
                    <td><input class="inputbox" type="text" name="name" size="30" value="<?php echo $my->username; ?>" /></td>
                </tr>
                <tr>
                    <td width="130" valign="top"><strong><?php echo _RSGALLERY_COMMENT_ADD_TEXT; ?>:</strong></td>
                    <td><textarea class="inputbox" cols="30" rows="3" name="comment" /></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input class="button" type="submit" name="submit" value="<?php echo _RSGALLERY_COMMENT_ADD; ?>" /></td>
                </tr>
            </table>
            </form>
          </td>
        </tr>
        </table>
        <?php
    }
    
    function _showEXIF( ) {
        ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="adminForm">
        <tr>
            <td>
                <table width="100%" cellpadding="2" cellspacing="1">
                <tr>
                    <td align="center"><?php echo imgUtils::showEXIF(imgUtils::getImgOriginal($this->item['name'], true)); ?></td>
                </tr>
                </table>
            </td>
        </tr>
        </table>
        <?php
    }
    
    /**
     * Shows random images for display on main page
     */
    function showRandom($style = "hor", $count = 3) {
        global $database, $rsgConfig;
        if ( $rsgConfig->get('displayRandom') ) {
            $catid = mosGetParam( $_REQUEST, 'catid', 0 );
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
            $catid = mosGetParam( $_REQUEST, 'catid', 0 );
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
        $catid = mosGetParam ( $_REQUEST, 'catid', 0 );
        $Itemid = mosGetParam ( $_REQUEST, 'Itemid', 0 );
        if ( isset($_REQUEST['page']) ) 
            $page = mosGetParam ( $_REQUEST, 'page'  , '');
        else
            $page = NULL;

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
                <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&catid=".$catid."&id=". mosGetParam ( $_REQUEST, 'id'  , ''));?>">
                <?php echo _RSGALLERY_SLIDESHOW_EXIT; ?>
                </a>
            </li>
        <?php endif; ?>
        </ul>
        </div>
        <div style="float:left;">
        <?php if( isset( $catid )): ?>
            <h2 id='rsg2-galleryTitle'><?php echo htmlspecialchars(stripslashes(galleryUtils::getCatNameFromId($catid)), ENT_QUOTES) ?></h2>
        <?php elseif( $page != "my_galleries" ): ?>
            <h2 id='rsg2-galleryTitle'><?php echo _RSGALLERY_COMPONENT_TITLE ?></h2>
        <?php endif; ?>
        </div>
        <?php
    }
    
	function writeSlideShowLink() {
		global $rsgConfig, $Itemid;
		// if no slideshow, then return
		if ( !$rsgConfig->get('displaySlideshow') )
			return;
		
		$catid = mosGetParam ( $_REQUEST, 'catid'  , '');
		?>
			<div style="float: right;">
			<ul id='rsg2-navigation'>
				<li>
					<a href="<?php echo sefRelToAbs( 'index.php?option=com_rsgallery2&Itemid='.$Itemid.'&page=slideshow&catid='.$catid );?>">
					<?php echo _RSGALLERY_SLIDESHOW; ?>
					</a>
				</li>
			</ul>
			</div>
			<div class='rsg2-clr'>&nbsp;</div>
		<?php
	}
    
    function showIntroText() {
        global $rsgConfig;
        $catid = mosGetParam( $_REQUEST, 'catid', 0 );
        if (!$catid) {
            echo stripslashes( $rsgConfig->get('intro_text') );
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
            <div><br /><br />
                <?php echo $rsgVersion->getShortVersion(); ?>
            </div>
        </div>
<div class='rsg2-clr'>&nbsp;</div>
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
}


/**
 * HTML for the frontend
 * @package RSGallery2
 */
class HTML_RSGALLERY{

    /**
     *  write the footer
     */
    function RSGalleryFooter(){
        global $rsgConfig, $rsgVersion;

        $hidebranding = '';
        if( $rsgConfig->get( 'displayBranding' ) == false )
            $hidebranding ="style='display: none'";
            
        ?>
        <div id='rsg2-footer' <?php echo $hidebranding; ?>>
            <div><br /><br />
                <?php echo $rsgVersion->getShortVersion(); ?>
            </div>
        </div>
<div class='rsg2-clr'>&nbsp;</div>
        <?php
    }
    
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
                <?php //galleryUtils::galleriesSelectList( $parent, 'parent', false );?>
                <?php galleryUtils::createGalSelectList( NULL, $listName='parent', true );?>
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
        global $rsgConfig, $mosConfig_live_site, $mosConfig_absolute_path, $i_file, $conversiontype, $my, $Itemid;
        
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
            if (form.i_cat.value == "0") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_CAT; ?>" );
            } else if (form.i_file.value == "") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_FILE; ?>" );
            } else {
                form.submit();
            }
        }
        
    </script>
        <form name="uploadform" id="uploadform" method="post" action="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=doFrontUpload"); ?>" enctype="multipart/form-data">
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
                            if (!$rsgConfig->get('acl_enabled')) {
                                galleryUtils::showCategories(NULL, $my->id, 'i_cat');
                            } else {
                                galleryUtils::showUserGalSelectList('up_mod_img', 'i_cat');
                            }
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
        
        if ( isset($_REQUEST['page']) ) 
            $page = mosGetParam ( $_REQUEST, 'page'  , '');
        else
            $page = NULL;
            
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
            <h2 id='rsg2-galleryTitle'><?php echo htmlspecialchars(stripslashes(galleryUtils::getCatNameFromId($catid)), ENT_QUOTES) ?></h2>
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
     * show a sub gallery
     * @param string parent cat id
     */
    function subGalleryList($parent, $subgalleries = true){
        global $database, $Itemid, $rsgAccess;
        
        $database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE published = 1 and parent = '$parent' ORDER BY ordering ASC");
        $rows = $database->loadObjectList();
        if( count( $rows ) == 0 ) return;
        if ($subgalleries) {
            $html = "";
            echo "Subgalleries: ";
            foreach( $rows as $row ) {
                //check if viewer has the rights to view subgallery
                if ($rsgAccess->checkGallery('view',$row->id)) {
                    ?>
                    <a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$row->id); ?>">
                        <?php echo htmlspecialchars(stripslashes($row->name), ENT_QUOTES); ?>
                        (<?php echo galleryUtils::getFileCount($row->id); ?>)
</a>
                    <?php
                    if ($row !== end($rows))
                        echo ",";
                }
            }
        }
    }
    
    function RSGalleryList( $gallery ){
        global $Itemid, $rsgConfig;
        //Get values for page navigation from URL
        $limit = mosGetParam ( $_REQUEST, 'limit', $rsgConfig->galcountNrs);
        $limitstart = mosGetParam ( $_REQUEST, 'limitstart', 0);
        
        //Get number of galleries including main gallery
        $kids = $gallery->kids();
        $kidCountTotal = count( $kids );

        $pageNav = false;
        
        if( $rsgConfig->dispLimitbox == 1) {
            if( $kidCountTotal > $limit ){
                $kids = array_slice( $kids, $limitstart, $limit );
                $pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
            }
        } elseif($rsgConfig->dispLimitbox == 2) {
            $kids = array_slice( $kids, $limitstart, $limit );
            $pageNav = new mosPageNav( $kidCountTotal, $limitstart, $limit );
        }

        //Show page navigation
        if( $pageNav ): ?>
        <div class="rsg2-pagenav-limitbox">
        <?php echo $pageNav->writeLimitBox("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"); ?>
        </div>
        <?php endif; ?>

        <ul id='rsg2-galleryList'>
        <?php foreach( $kids as $kid ): ?>
            <li class='rsg2-galleryList-item' >
            <?php if ( $rsgConfig->get('displayStatus') ) {?>
                <div class="rsg2-galleryList-status"><?php echo $kid->status; ?></div>
                <?php }?>
                <!--<div class="img-shadow">-->
                    <a class='rsg2-galleryList-thumb' href="<?php echo sefRelToAbs($kid->url); ?>">
                    <?php echo $kid->thumbHTML; ?>
                  </a>
                <!--</div>-->
                <div class='rsg2-galleryList-info'>
                    <a class='rsg2-galleryList-title' href="<?php echo sefRelToAbs($kid->url); ?>"><?php echo htmlspecialchars(stripslashes($kid->get('name')), ENT_QUOTES); ?></a>
                    <div class='rsg2-galleryList-totalImages'><?php echo galleryUtils::getFileCount($kid->get('id')). _RSGALLERY_IMAGES;?></div>
                    <div class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($kid->get('id')); ?></div>
                    <div class='rsg2-galleryList-description'><?php echo ampReplace($kid->get('description'));?></div>
                    <?php HTML_RSGALLERY::subGalleryList( $kid->get('id') ); ?>
                </div>
                <div class='clr'>&nbsp;</div>
            </li>
        <?php endforeach; ?>
        </ul>

        <?php if( $pageNav ): ?>
        <div class="rsg2-pageNav">
        <?php 
            echo $pageNav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid");echo "<br>".$pageNav->writePagesCounter(); ?></div>
        <?php endif; ?>
        <div class='clr'>&nbsp;</div>

        <?php
    }

    /**
     * Shows gallery list on main gallery page
     * @TODO Depreciated!  Remove after not needed as a coding reference.
     */
    function RSGalleryList_legacy ($rows, $pageNav, $parentCat=0 ){
        global $database,$mosConfig_live_site;
        
        if (isset($pageNav) )
        {
        ?>
        <div style="text-align:right;"><?php global $Itemid; echo $pageNav->writeLimitBox("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"); ?></div>
        <?php
        }
        ?>
        <ul id='rsg2-galleryList'>
        <?php
        foreach ($rows as $row) {
            $c_id = $row->id;
            $database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE published = 1 and parent = '$c_id' ORDER BY ordering ASC");
            $rows2 = $database->loadObjectList();
            ?>     
            <li class='rsg2-galleryList-item' >
                <a class='rsg2-galleryList-thumb' href="<?php global $Itemid; echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$row->id); ?>"><?php echo galleryUtils::getThumb($row->id,0,0,""); ?></a>
                <div class='rsg2-galleryList-info'>
                    <a class='rsg2-galleryList-title' href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;catid=".$row->id); ?>"><?php echo htmlspecialchars(stripslashes($row->name), ENT_QUOTES); ?></a>
                    <div class='rsg2-galleryList-totalImages'><?php echo galleryUtils::getFileCount($row->id). _RSGALLERY_IMAGES;?></div>
                    <div class='rsg2-galleryList-newImages'><?php echo galleryUtils::newImages($row->id); ?></div>
                    <div class='rsg2-galleryList-description'><?php echo ampReplace($row->description);?></div>
                    <?php HTML_RSGALLERY::subGalleryList( $row->id ); ?>
                </div>
                <div class='clr'>&nbsp;</div>
            </li>
            <?php
            }
            ?>
        </ul>
        <?php
        if (isset($pageNav))
            {
            ?>
            <div class="rsg2-pageNav"><?php global $Itemid; echo $pageNav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid");echo "<br>".$pageNav->writePagesCounter(); ?></div>
            <?php
            }
        ?>
        <div class='clr'>&nbsp;</div>
        <?php
        }
    //End of  funcion RSGalleryList()


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
        $thumb_width                = $rsgConfig->get("thumb_width");
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
        <div class='rsg2-pageNav'>
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
                        <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" width="<?php echo $thumb_width; ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
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
                                <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" width="<?php echo $thumb_width; ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
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
            <div class='rsg2-pageNav'>
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
                    location = "<?php echo $mosConfig_live_site;?>/index.php?option=com_rsgallery2&page=delete_image&id="+id;
                }
            }
            </script>
            <?php
            foreach ($images as $image)
                {
                global $Itemid;
               ?>
                <tr>
                    <td>
                        <?php
                        if (!$rsgAccess->checkGallery('up_mod_img', $image->gallery_id)) {
                            echo $image->name;
                        } else {
                            ?>
                            <a href="../Copy of tables/index.php?option=com_rsgallery2&amp;Itemid=<?php echo $Itemid;?>&amp;page=edit_image&amp;id=<?php echo $image->id;?>">
                            <?php echo $image->name;?>
                      </a>
                            <?php
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
                        <a href="../Copy of tables/index.php?option=com_rsgallery2&amp;Itemid=<?php echo $Itemid;?>&amp;page=edit_image&amp;id=<?php echo $image->id;?>">
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
                <th colspan="4"><div align="center"><?php global $Itemid; echo $pageNav->writePagesLinks("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=my_galleries");echo "<br>".$pageNav->writePagesCounter(); ?></div></th>
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
    
		      // this one uses a combo, you could adapt it e.g. to list of Hrefs
      function writeSortLink( $sortdirective, $gid ) {
         global $Itemid;
         ?>
           <div class="rsg2-pagenav-limitbox">
         <?php echo _RSGALLERY_SORT_FILE_METHOD;?>&nbsp;:&nbsp;<?php
            $limits = array();
            $limits[] = mosHTML::makeOption(  "Date1" , _RSGALLERY_SORT_DATE_ASC ) ;
            $limits[] = mosHTML::makeOption(  "Date2" , _RSGALLERY_SORT_DATE_DESC ) ;
            // build the html select list
            $link = "index.php?option=com_rsgallery2&Itemid=".$Itemid."&catid=".$gid ;
            $link = $link ."&sort=' + this.options[selectedIndex].value +'" ;
            $link = sefRelToAbs( $link );
            echo mosHTML::selectList( $limits, 'limit', 'class="inputbox" size="1" onchange="document.location.href=\''. $link .'\';"', 'value', 'text', $sortdirective );
         ?>   
         </div>

           <?php
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


	
} //end class
?>