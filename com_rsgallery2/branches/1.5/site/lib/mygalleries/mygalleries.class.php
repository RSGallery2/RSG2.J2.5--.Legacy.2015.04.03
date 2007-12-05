<?php
/**
* This file contains xxxxxxxxxxxxxxxxxxxxxxxxxxx.
* @version xxx
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class myGalleries {

   	function myGalleries() {

   	}
   	
    /**
     * This presents the main My Galleries page
     * @param array Result array with category details for logged in users
     * @param array Result array with image details for logged in users
     * @param array Result array with pagenav information
     */
    function viewMyGalleriesPage($rows, $images, $pageNav) {
        global $rsgConfig,$mainframe;
		$my = JFactory::getUser();
		$database = JFactory::getDBO();

        if (!$rsgConfig->get('show_mygalleries'))
            $mainframe->redirect( JRoute::_($this->myg_url),_RSGALLERY_USERGAL_DISABLED);
        ?>
        <h2><?php echo _RSGALLERY_USER_MY_GAL;?></h2>

        <?php
        //Show User information
        myGalleries::RSGalleryUSerInfo($my->id);
        
        //Start tabs
		jimport("joomla.html.pane");
        $tabs =& JPane::getInstance("Tabs");
        echo $tabs->startPane( 'tabs' );
        echo $tabs->startPanel( _RSGALLERY_MY_IMAGES, 'my_images' );
            myGalleries::showMyImages($images, $pageNav);
            myGalleries::showImageUpload();
        echo $tabs->endPanel();
        if ($rsgConfig->get('uu_createCat')) {
            echo $tabs->startPanel( _RSGALLERY_USER_MY_GAL, 'my_galleries' );
                myGalleries::showMyGalleries($rows);
                myGalleries::showCreateGallery(NULL);
            echo $tabs->endPanel();
        }
        echo $tabs->endPane();
        ?>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
	}
	
	function showCreateGallery($rows) {
    	global $rsgConfig;
		$my = JFactory::getUser();
    	//Load frontend toolbar class
    	require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
	    ?>
	    <script type="text/javascript">
	        function submitbutton(pressbutton) {
	            var form = document.form1;
	            if (pressbutton == 'cancel') {
	                form.reset();
	                return;
	            }
	        
	        // do field validation
	        if (form.parent.value == "-1") {
	            alert( "<?php echo "** You need to select a parent gallery **"; ?>" );
	        } else if (form.catname1.value == "") {
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
	        $parent         = 0;
	    }
	    ?>
        <form name="form1" id="form1" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveCat"); ?>">
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
	
	/**
     * Displays details about the logged in user and the privileges he/she has
     * $param integer User ID from Joomla user table
     */
     function RSGalleryUserInfo($id) {
	     global $rsgConfig;
				$my = JFactory::getUser();

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
	
	function showImageUpload() {
        global $rsgConfig;
		$my = JFactory::getUser();
        
        //Load frontend toolbar class
        require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
        ?>
        <script  type="text/javascript">
        function submitbutton2(pressbutton) {
            var form = document.uploadform;
            if (pressbutton == 'cancel') {
                form.reset();
                return;
            }
            
            // do field validation
            if (form.i_cat.value == "-1") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_CAT; ?>" );
            } else if (form.i_cat.value == "0") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_CAT; ?>" );
            } else if (form.i_file.value == "") {
                alert( "<?php echo _RSGALLERY_UPLOAD_ALERT_FILE; ?>" );
            } else {
                form.submit();
            }
        }
        
    </script>
        <form name="uploadform" id="uploadform" method="post" action="
<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveUploadedItem"); ?>" enctype="multipart/form-data">
        <table border="0" width="100%">
            <tr>
                <td colspan="2"><h3>
<?php echo _RSGALLERY_ADD_IMAGE;?></h3></td>
            </tr>
            <tr>

                <td align="right">
                    <div style="float: right;">
                    <table cellpadding="0" cellspacing="3" border="0" id="toolbar">
                    <tr height="60" valign="middle" align="center">
                        <td>
                            <a class="toolbar" href="javascript:submitbutton2('save');" >
                            <img src="<?php echo JURI::root();?>/images/save_f2.png"  alt="Save" name="save" title="Save" align="middle" /></a>
                        </td>
                        <td>
                            <a class="toolbar" href="javascript:submitbutton2('cancel');" >
                            <img src="<?php echo JURI::root();?>/images/cancel_f2.png"  alt="Cancel" name="cancel" title="Cancel" align="middle" /></a>
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
     * Shows thumbnails for gallery and links to subgalleries if they exist.
     * @param integer Category ID
     * @param integer Columns per page
     * @param integer Number of thumbs per page
     * @param integer pagenav stuff
     * @param integer pagenav stuff
     */
    function RSShowPictures ($catid, $limit, $limitstart){
        global $rsgConfig;
		$my = JFactory::getUser();
		$database = JFactory::getDBO();

        $columns                    = $rsgConfig->get("display_thumbs_colsPerPage");
        $PageSize                   = $rsgConfig->get("display_thumbs_maxPerPage");
        //$my_id                      = $my->id;
    
        $database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE gallery_id='$catid'");
        $numPics = $database->loadResult();
        
        if(!isset($limitstart))
            $limitstart = 0;
        //instantiate page navigation
        $pagenav = new JPagination($numPics, $limitstart, $PageSize);
    
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
                    echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&catid=".$catid);
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
                    <a href="<?php  echo JRoute::_( "index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart++ ); ?>">
                        <!--<div class="img-shadow">-->
                        <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
                        <!--</div>-->
                        <span class="rsg2-clr"></span>
                        <?php if($rsgConfig->get("display_thumbs_showImgName")): ?>
                            <br /><span class='rsg2_thumb_name'><?php echo htmlspecialchars(stripslashes($row->title), ENT_QUOTES); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if( $allowEdit ): ?>
                    <div id='rsg2-adminButtons'>
                        <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&page=edit_image&id=".$row->id); ?>"><img src="<?php echo JURI_SITE; ?>/administrator/images/edit_f2.png" alt=""  height="15" /></a>
                        <a href="#" onClick="if(window.confirm('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>')) location='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=delete_image&id=".$row->id); ?>'"><img src="<?php echo JURI_SITE; ?>/administrator/images/delete_f2.png" alt=""  height="15" /></a>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
                </ul>
                <div class='rsg2-clr'>&nbsp;</div>
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
                                <a href="<?php echo JRoute::_( "index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart++ ); ?>">
                                <img border="1" alt="<?php echo htmlspecialchars(stripslashes($row->descr), ENT_QUOTES); ?>" src="<?php echo imgUtils::getImgThumb($row->name); ?>" />
                                </a>
                            <!--</div>-->
                            <div class="rsg2-clr"></div>
                            <?php if($rsgConfig->get("display_thumbs_showImgName")): ?>
                            <br />
                            <span class='rsg2_thumb_name'>
                                <?php echo htmlspecialchars(stripslashes($row->title), ENT_QUOTES); ?>
                            </span>
                            <?php endif; ?>
                            <?php if( $allowEdit ): ?>
                            <div id='rsg2-adminButtons'>
                                <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&page=edit_image&id=".$row->id); ?>"><img src="<?php echo JURI_SITE; ?>/administrator/images/edit_f2.png" alt=""  height="15" /></a>
                                <a href="#" onClick="if(window.confirm('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>')) location='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=delete_image&id=".$row->id); ?>'"><img src="<?php echo JURI_SITE; ?>/administrator/images/delete_f2.png" alt=""  height="15" /></a>
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
                        echo $pagenav->writePagesLinks("index.php?option=com_rsgallery2&catid=".$catid);
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
	$my = JFactory::getUser();
	$database = JFactory::getDBO();
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
            if ($count == 0) {
                ?>
                <tr><td colspan="5"><?php echo _RSGALLERY_NO_USER_GAL; ?></td></tr>
                <?php
            } else {
                //echo "This is the overview screen";
                foreach ($rows as $row) {
                    ?>
                    <script type="text/javascript">
						//<![CDATA[
						function deletePres(catid) {
							var yesno = confirm ("<?php echo _RSGALLERY_DELCAT_TEXT;?>");
							if (yesno == true) {
								location = "<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=deleteCat&catid=", false);?>"+catid;
							}
						}
						//]]>
                    </script>
                    <tr>
                        <td>
                        	<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row->id);?>">
                        		<?php echo $row->name;?>
                        	</a>
                        </td>
                        <?php
                        if ($row->published == 1)
                            $img = "publish_g.png";
                        else
                            $img = "publish_r.png";?>
                            
                        <td><div align="center"><img src="<?php echo JURI_SITE;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" ></div></td>
                        <td>
                        	<a href="javascript:deletePres(<?php echo $row->id;?>);">
                        		<div align="center">
                        			<img src="<?php echo JURI_SITE;?>/administrator/images/publish_x.png" alt="" width="12" height="12" >
                        		</div>
                        	</a>
                        </td>
                        <td>
                        	<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row->id);?>">
                        		<div align="center">
                        			<img src="<?php echo JURI_SITE;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" >
                        		</div>
                        	</a>
                        </td>
                        <td><a href="#" onclick="alert('Feature not implemented yet')"><div align="center"><img src="<?php echo JURI_SITE;?>/administrator/images/users.png" alt="" width="22" height="22"></div></td>
                    </tr>
                    <?php
                    $sql2 = "SELECT * FROM #__rsgallery2_galleries WHERE parent = $row->id ORDER BY ordering ASC";
                    $database->setQuery($sql2);
                    $rows2 = $database->loadObjectList();
                    foreach ($rows2 as $row2) {
                        if ($row2->published == 1)
                            $img = "publish_g.png";
                        else
                            $img = "publish_r.png";?>
                        <tr>
                            <td>
                                <img src="<?php echo JURI_SITE;?>/administrator/components/com_rsgallery2/images/sub_arrow.png" alt="" width="12" height="12" >
                                &nbsp;
                                <a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row2->id);?>">
                                	<?php echo $row2->name;?>
                                </a>
                            </td>
                            <td>
                                <div align="center">
                                    <img src="<?php echo JURI_SITE;?>/administrator/images/<?php echo $img;?>" alt="" width="12" height="12" >
                                </div>
                            </td>
                            <td>
                                <a href="javascript:deletePres(<?php echo $row2->id;?>);">
                                    <div align="center">
                                    <img src="<?php echo JURI_SITE;?>/administrator/images/publish_x.png" alt="" width="12" height="12" >
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&catid='.$row2->id);?>">
                                <div align="center">
                                    <img src="<?php echo JURI_SITE;?>/administrator/images/edit_f2.png" alt="" width="18" height="18" >
                                </div>
                                </a>
                            </td>
                            <td>
                                <a href="#" onclick="alert('<?php echo _RSGALLERY_FEAT_NOTIMP?>')">
                                <div align="center">
                                    <img src="<?php echo JURI_SITE; ?>/administrator/images/users.png" alt="" width="22" height="22" >
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
        global $rsgAccess;
        ?>
        <table width="100%" class="adminlist" border="1">
        <tr>
            <td colspan="4"><h3><?php echo _RSGALLERY_MY_IMAGES; ?></h3></td>
        </tr>
        <tr>
            <th colspan="4"><div align="right"><?php  echo $pageNav->getLimitBox(); ?></div></th>
        </tr>
        <tr>
            <th><?php echo _RSGALLERY_MY_IMAGES_NAME; ?></th>
            <th><?php echo _RSGALLERY_MY_IMAGES_CATEGORY; ?></th>
            <th width="75"><?php echo _RSGALLERY_MY_IMAGES_DELETE; ?></th>
            <th width="75"><?php echo _RSGALLERY_MY_IMAGES_EDIT; ?></th>
        </tr>
        
        <?php
        if (count($images) > 0) {
             ?>
            <script type="text/javascript">
					//<![CDATA[
				function deleteImage(id)
				{
					var yesno = confirm ('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>');
					if (yesno == true) {
						location = "<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=deleteItem&id=", false);?>"+id;
					}
				}
				//]]>
            </script>
            <?php
            foreach ($images as $image)
                {
                global $rsgConfig;
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
						 $image->title.'&nbsp;('.$image->name.')',
						 "index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editItem&id=".$image->id,
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
                                <img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_delete.png" alt="" width="12" height="12" >
                            </div>
                            <?php
                        } else {
                        ?>
                        <a href="javascript:deleteImage(<?php echo $image->id;?>);">
                            <div align="center">
                                <img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/delete.png" alt="" width="12" height="12" >
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
                                <img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_edit.png" alt="" width="15" height="15" >
                            </div>
                            <?php
                        } else {
                        ?>
                        <a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editItem&id=$image->id");?>">
                        <div align="center">
                            <img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/edit.png" alt="" width="15" height="15" >
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
                <th colspan="4">
                	<div align="center">
                		<?php 
                			echo $pageNav->getPagesLinks();
                			echo "<br>".$pageNav->getPagesCounter();
                		?>
                	</div>
                </th>
            </tr>
            </table>
            <?php
    }
    
    function editItem($rows) {
        global $rsgConfig;
		$my = JFactory::getUser();
        require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
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
        <form name="form1" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveItem"); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <table width="100%">
            <tr>
                <td align="right">
                    <img onClick="form1.submit();" src="<?php echo JURI_SITE; ?>/administrator/images/save.png" alt="<?php echo _RSGALLERY_TOOL_UP ?>"  name="upload" onMouseOver="document.upload.src='<?php echo JURI_SITE; ?>/administrator/images/save_f2.png';" onMouseOut="document.upload.src='<?php echo JURI_SITE; ?>/administrator/images/save.png';" />&nbsp;&nbsp;
                    <img onClick="history.back();" src="<?php echo JURI_SITE; ?>/administrator/images/cancel.png" alt="<?php echo _RSGALLERY_CANCEL; ?>"  name="cancel" onMouseOver="document.cancel.src='<?php echo JURI_SITE; ?>/administrator/images/cancel_f2.png';" onMouseOut="document.cancel.src='<?php echo JURI_SITE; ?>/administrator/images/cancel.png';" />
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
                    <?php galleryUtils::showUserGalSelectList('up_mod_img', 'catid', $catid);?>
                </td>
                <td rowspan="2"><img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>"  /></td>
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
    
function editCat($rows = null) {
    global $rsgConfig;
	$my = JFactory::getUser();
	$editor =& JFactory::getEditor();
	
    //Load frontend toolbar class
    require_once( JPATH_ROOT . '/includes/HTML_toolbar.php' );
    ?>
    <script type="text/javascript">
        function submitbutton(pressbutton) {
            var form = document.form2;
            if (pressbutton == 'cancel') {
                form.reset();
                history.back();
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
            <?php echo $editor->save( 'editor1' ) ; ?>
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
        <form name="form2" id="form2" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveCat"); ?>">
        <table width="100%">
        <tr>
            <td colspan="2"><h3><?php echo _RSGALLERY_CREATE_GALLERY; ?></h3></td>
        </tr>
        <tr>

            <td align="right">
                <div style="float: right;">
                    <img onClick="form2.submit();" src="<?php echo JURI_SITE; ?>/administrator/images/save.png" alt="<?php echo _RSGALLERY_TOOL_UP ?>"  name="upload" onMouseOver="document.upload.src='<?php echo JURI_SITE; ?>/administrator/images/save_f2.png';" onMouseOut="document.upload.src='<?php echo JURI_SITE; ?>/administrator/images/save.png';" />&nbsp;&nbsp;
                    <img onClick="history.back();" src="<?php echo JURI_SITE; ?>/administrator/images/cancel.png" alt="<?php echo _RSGALLERY_CANCEL; ?>"  name="cancel" onMouseOver="document.cancel.src='<?php echo JURI_SITE; ?>/administrator/images/cancel_f2.png';" onMouseOut="document.cancel.src='<?php echo JURI_SITE; ?>/administrator/images/cancel.png';" />
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
            <td colspan="2"><?php echo _RSGALLERY_DESCR; ?>
            
                <!--<textarea cols="20" rows="5" name="description"><?php echo htmlspecialchars(stripslashes($description)); ?></textarea>-->
                <?php
                // parameters : areaname, content, hidden field, width, height, rows, cols
                echo $editor->display( 'editor1',  $description , '600', '200', '35', '15' ) ; ?>
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

}//end class
?>