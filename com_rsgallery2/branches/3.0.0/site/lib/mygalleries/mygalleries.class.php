<?php
/**
* This file contains My galleries class
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2011 RSGallery2
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
        global $rsgConfig;
		$mainframe =& JFactory::getApplication();
		$my = JFactory::getUser();
		$database = JFactory::getDBO();

//MK change this: if user has 
//(core.login.site) and 
//((core.create on a gallery or the rsg2 component) OR
//(edit OR edit.state OR edit.own OR delete for a gallery or the RSG2 component))
//then it's ok
        if (!$rsgConfig->get('show_mygalleries'))
            $mainframe->redirect( $this->myg_url,JText::_('COM_RSGALLERY2_USER_GALLERIES_WAS_DISABLED_BY_THE_ADMINISTRATOR'));
        ?>
		<div class="rsg2">
        <h2><?php echo JText::_('COM_RSGALLERY2_MY_GALLERIES');?></h2>

		
		<?php
		//Load My Galleries javascript file after core-uncompressed.js to override its Joomla.submitbutton function
//		$filename = 'mygalleries.js';
//		$path = 'components/com_rsgallery2/lib/mygalleries/';
//		JHTML::script($filename, $path);
		//As long as I'm unable to get JText with .js files working use this fucntion:
		myGalleries::mygalleriesJavascript();
		
        //Show User information
        myGalleries::RSGalleryUSerInfo($my->id);

        //Start tabs
		jimport("joomla.html.pane");
        $tabs =& JPane::getInstance("Tabs");
		echo $tabs->startPane( 'tabs' );
			echo $tabs->startPanel( JText::_('COM_RSGALLERY2_MY_IMAGES'), 'my_images' );
				myGalleries::showMyImages($images, $pageNav);
//MK change: showImageUpload only with core.create for 1 or more galleries:
				myGalleries::showImageUpload();
		echo $tabs->endPanel();
//MK change: not depending on uu_createCat
        if ($rsgConfig->get('uu_createCat')) {
            echo $tabs->startPanel( JText::_('COM_RSGALLERY2_MY_GALLERIES'), 'my_galleries' );
//MK change: show My Galleries tab only if 
//core.edit/edit.state/edit.own/delete for rsg2 component/its galleries
				myGalleries::showMyGalleries($rows);
//MK change: show My Galleries tab only if core.create component
                myGalleries::showCreateGallery(NULL);
            echo $tabs->endPanel();
        }
		echo $tabs->endPane();
		?>
		</div>
        <div class='rsg2-clr'>&nbsp;</div>
        <?php
	}
	
	function showCreateGallery($rows) {
    	global $rsgConfig;
		$my = JFactory::getUser();
		$editor =& JFactory::getEditor();

		//Script for this form is found in myGalleries::mygalleriesJavascript();

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
        <form name="createGallery" id="createGallery" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveCat",true); ?>">
			<table class="adminlist">
				<tr>
					<td colspan="2"><h3><?php echo JText::_('COM_RSGALLERY2_CREATE_GALLERY'); ?></h3>
						<div style="float: right;">
						<?php
						/* Not used since this gives a problem with Joomla SEF on: routes to http://wwww.mysite/index.php/rsgallery2-menu-item# instead of what is given in the task function
						//	JToolBarHelper does not exist in the frontend, using JToolBar here
						jimport( 'joomla.html.toolbar' );
						$bar =& new JToolBar( 'MyGalleriesToolBar' );
						//appendButton: button type, class, display text on button, task, bool: selection from adminlist?
						$bar->appendButton( 'Standard', 'save', 'Save', 'createGallery.saveCat', false );
						$bar->appendButton( 'Standard', 'cancel', 'Cancel', 'createGallery.cancel', false );
						echo $bar->render();
						*/
						?>
						<a href="javascript:Joomla.submitbutton('createGallery.saveCat')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/save-active.png" alt="<?php echo JText::_('JSAVE'); ?>" width="32" />
						</a>
						<a href="javascript:Joomla.submitbutton('createGallery.cancel')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/cancel-active.png" alt="<?php echo JText::_('JCANCEL'); ?>" width="32" />
						</a>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_RSGALLERY2_TOP_GALLERY');?></td>
					<td>
						<?php
						if (!$rsgConfig->get('acl_enabled')) {
							galleryUtils::showCategories(NULL, $my->id, 'parent');
						} else {
							galleryUtils::showUserGalSelectList('up_mod_img', 'parent');
						}

						?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_RSGALLERY2_GALLERY_NAME'); ?></td>
					<td align="left"><input type="text" name="catname1" size="30" value="<?php echo $catname; ?>" /></td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?>
					</td>
					<td align="left">
						<?php echo $editor->display( 'description',  $description , '100%', '200', '10', '20' ,false) ; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_RSGALLERY2_PUBLISHED'); ?></td>
					<td align="left"><input type="checkbox" name="published" value="1" <?php if ($published==1) echo "checked"; ?> /></td>
				</tr>
			</table>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="com_rsgallery2" />
			<input type="hidden" name="rsgOption" value="<?php echo JRequest::getCmd('rsgOption'); ?>" />
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getCmd('Itemid'); ?>" />
        </form>
        <?php
	}
	
	/**
     * Displays details about the logged in user and the privileges he/she has
     * $param integer User ID from Joomla user table
     */
     function RSGalleryUserInfo($id) {
//MK change: shows username, user level, max usergalleries, max userimage
//user level becomes an idea of what the user can do or will be removed
//Might be some table showing what you may do:
//create gallery/upload image, edit (state) gallery/image, delete gallery/image, publish gallery/image
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
	     <table class="adminform">
			 <tr>
				<th colspan="2"><?php echo JText::_('COM_RSGALLERY2_USER_INFORMATION'); ?></th>
			 </tr>
			 <tr>
				<td width="250"><?php echo JText::_('COM_RSGALLERY2_USERNAME'); ?></td>
				<td><?php echo $my->username;?></td>
			 </tr>
			 <tr>
				<td><?php echo JText::_('COM_RSGALLERY2_MAXIMUM_USERGALLERIES'); ?></td>
				<td><?php echo $maxcat;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userCategoryTotal($my->id);?></strong></font> <?php echo JText::_('COM_RSGALLERY2_NR_OF_USERGALLERIES_CREATED');?>)</td>
			 </tr>
			 <tr>
				<td><?php echo JText::_('COM_RSGALLERY2_MAXIMUM_IMAGES_ALLOWED'); ?></td>
				<td><?php echo $max_images;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userImageTotal($my->id);?></strong></font> <?php echo JText::_('COM_RSGALLERY2_NR_OF_IMAGES_UPLOADED'); ?>)</td>
			 </tr>
	     </table>
	     <br><br>
	     <?php
	}
	
	function showImageUpload() {
        global $rsgConfig;
		$my = JFactory::getUser();
		$editor = JFactory::getEditor();

//MK change: showImageUpload only with core.create for 1 or more galleries (double check?)
        
        //Script for this form is found in myGalleries::mygalleriesJavascript();
        ?>

		
        <form name="imgUpload" id="imgUpload" method="post" action="
<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveUploadedItem"); ?>" enctype="multipart/form-data">
		<div class="rsg2">
        <table class="adminlist">
            <tr>
                <td>
				<h3><?php echo JText::_('COM_RSGALLERY2_ADD_IMAGE');?></h3>
				</td>
				<td>
                    <div style="float: right;">
						<a href="javascript:Joomla.submitbutton('imgUpload.saveUploadedItem')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/save-active.png" alt="<?php echo JText::_('JSAVE'); ?>" width="32" />
						</a>
						<a href="javascript:Joomla.submitbutton('imgUpload.cancel')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/cancel-active.png" alt="<?php echo JText::_('JCANCEL'); ?>" width="32" />
						</a>	
					</div>
				</td>
            </tr>
			<tr>
				<td>
				<?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>
				</td>
				<td>
					<?php 
					/*echo galleryUtils::galleriesSelectList(null, 'i_cat', false);*/

//MK change: acl_enabled no longer needed
//MK change: check galleryUtils::showCategories(gives fatal error)/showUserGalSelectList
//must be a list of galleries for which core.create is allowed (rest disabled 
//through JHTML::Select option where $disable is true is not allowed to use)
					if (!$rsgConfig->get('acl_enabled')) {
						galleryUtils::showCategories(NULL, $my->id, 'i_cat');
					} else {
						galleryUtils::showUserGalSelectList('up_mod_img', 'i_cat');
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_FILENAME') ?></td>
				<td align="left"><input size="49" type="file" name="i_file" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_TITLE') ?>:</td>
				<td align="left"><input name="title" type="text" size="49" /></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_RSGALLERY2_DESCRIPTION') ?>
				</td>
				<td align="left">
					<?php echo $editor->display( 'descr',  '' , '100%', '200', '10', '20' ,false) ; ?>
				</td>
			</tr>
<?php		if ($rsgConfig->get('graphicsLib') == '') { ?>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_THUMB'); ?></td>
				<td align="left"><input type="file" name="i_thumb" /></td>
			</tr>
<?php 		} ?>
        </table>
        </div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_rsgallery2" />
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
        <div class="rsg2-pageNav">
                <?php
                /*
                if( $numPics > $PageSize ){
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
                <ul id="rsg2-thumbsList">
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
                        <a href="#" onClick="if(window.confirm('<?php echo JText::_('COM_RSGALLERY2_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE');?>')) location='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=delete_image&id=".$row->id); ?>'"><img src="<?php echo JURI_SITE; ?>/administrator/images/delete_f2.png" alt=""  height="15" /></a>
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
                                <a href="#" onClick="if(window.confirm('<?php echo JText::_('COM_RSGALLERY2_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE');?>')) location='<?php echo JRoute::_("index.php?option=com_rsgallery2&page=delete_image&id=".$row->id); ?>'"><img src="<?php echo JURI_SITE; ?>/administrator/images/delete_f2.png" alt=""  height="15" /></a>
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
                echo JText::_('COM_RSGALLERY2_MAGIC_NOT_IMPLEMENTED_YET');
                ?>
                <table id='rsg2-thumbsList'>
                <tr>
                    <td><?php echo JText::_('COM_RSGALLERY2_MAGIC_NOT_IMPLEMENTED_YET')?></td>
                </tr>
                </table>
                <?php
                break;
            endswitch;
            ?>
            <div class="rsg2-pageNav">
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
            if (!$catid == 0)echo JText::_('COM_RSGALLERY2_NO_IMAGES_IN_GALLERY');
        }
    }
    
    
    function showMyGalleries($rows) {
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$Itemid = JRequest::getInt('Itemid');
		//Set variables
		$count = count($rows);
		
//MK change: get permissions in $canDo with the helper...

		?>
		<div class="rsg2">
		<table class="adminlist" >
            <tr>
                <td colspan="4"><h3><?php echo JText::_('COM_RSGALLERY2_MY_GALLERIES');?></h3></td>
            </tr>
            <tr>
                <th><div align="left"><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?></div></th>
                <th width="75"><div align="center"><?php echo JText::_('COM_RSGALLERY2_PUBLISHED'); ?></div></th>
                <th width="75"><div align="center"><?php echo JText::_('COM_RSGALLERY2_DELETE'); ?></div></th>
                <th width="75"><div align="center"><?php echo JText::_('COM_RSGALLERY2_EDIT'); ?></div></th>
            </tr>
            <?php
            if ($count == 0) { //No galleries
                ?>
                <tr><td colspan="4"><?php echo JText::_('COM_RSGALLERY2_NO_USER_GALLERIES_CREATED'); ?></td></tr>
                <?php
            } else { //List of galleries
                foreach ($rows as $row) {
                    ?>
                    <script type="text/javascript">
						//<![CDATA[
						function deletePres(catid) {
							var yesno = confirm ("<?php echo JText::_('COM_RSGALLERY2_DELCAT_TEXT');?>");
							if (yesno == true) {
								location = "<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=deleteCat", false);?>"+"&gid="+catid;
							}
						}
						//]]>
                    </script>

                    <tr>
                        <td>
<?php
//MK change: if core.edit on comp. then link else only name
?>
                        	<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&gid='.$row->id);?>">
                        		<?php echo stripslashes($row->name);?>
                        	</a>
                        </td>
                        <?php
//MK change: publish image source:
                        if ($row->published == 1)
                            $img = "published-active.png";
                        else
                            $img = "unpublished-active.png";?>
                        <td>
<?php //MK change: only publish image if core.edit.state for comp. else grey image ?>
							<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editStateGallery&gid='.$row->id.'&currentstate='.$row->published);?>">
								<div align="center">
									<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/<?php echo $img;?>" alt="<?php echo JText::_('JACTION_EDITSTATE'); ?>" width="19" position="top" >
								</div>
							</a>
						</td>
                        <td>
<?php //MK change: only delete icon if core.delete for comp. else grey image ?>
							<a href="javascript:deletePres(<?php echo $row->id;?>);">
                        		<div align="center">
                        			<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/delete-active.png" alt="<?php echo JText::_('JACTION_DELETE'); ?>" width="19" >
                        		</div>
                        	</a>
                        </td>
                        <td>
<?php //MK change: only edit icon if core.edit or owner and core.edit.own for comp. else grey image ?>
                        	<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&gid='.$row->id);?>">
                        		<div align="center">
                        			<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/edit-active.png" alt="<?php echo JText::_('JACTION_EDIT'); ?>" width="19" >
                        		</div>
                        	</a>
                        </td>
                    </tr>
                    <?php
                    $sql2 = "SELECT * FROM #__rsgallery2_galleries WHERE parent = $row->id ORDER BY ordering ASC";
                    $database->setQuery($sql2);
                    $rows2 = $database->loadObjectList();
                    foreach ($rows2 as $row2) { //each subgallery
//MK change: similar changes as for main gallery above... can this be done better?
                        if ($row2->published == 1)
                            $img = "published-active.png";
                        else
                            $img = "unpublished-active.png";?>
                        <tr>
                            <td>
                                <img src="<?php echo JURI_SITE;?>/administrator/components/com_rsgallery2/images/sub_arrow.png" alt="<?php echo JText::_('JACTION_EDITSTATE'); ?>" width="12" height="12" >
                                &nbsp;
                                <a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&gid='.$row2->id);?>">
                                	<?php echo $row2->name;?>
                                </a>
                            </td>
                            <td>
								<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editStateGallery&gid='.$row2->id.'&currentstate='.$row2->published);?>">
									<div align="center">
										<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/<?php echo $img;?>" alt="<?php echo JText::_('JACTION_EDITSTATE'); ?>" width="19" position="top" >
									</div>
								</a>
                            </td>
                            <td>
                                <a href="javascript:deletePres(<?php echo $row2->id;?>);">
                                    <div align="center">
										<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/delete-active.png" alt="<?php echo JText::_('JACTION_DELETE'); ?>" width="19" >
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editCat&gid='.$row2->id)?>">
                                <div align="center">
                                    <img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/edit-active.png" alt="<?php echo JText::_('JACTION_EDIT'); ?>" width="19" >
                                </div>
                                </a>
                            </td>
                        </tr>
                        <?php
					} //for each subgallery - end
				} //for each gallery - end
			} //end list of galleries (if any)
			?>
		</table>
		</div>
    <?php
    }
    /**
     * This will show the images, available to the logged in users in the My Galleries screen
     * under the tab "My Images".
     * @param array Result array with image details for the logged in users
     * @param array Result array with pagenav details
     */
    function showMyImages($images, $pageNav) {
//MK change
//column Name: only linked to edit page when core.edit for image of core.edit.own and user is owner
//column gallery = ok
//column delete: only with core.delete (else grey?)
//column published (to be added): only with core.edit.state (else grey?)
        global $rsgAccess;
        JHTML::_('behavior.tooltip');
		$Itemid = JRequest::getInt('Itemid');
        ?>
		
        <table class="adminlist" >
			<tr>
				<td colspan="5"><h3><?php echo JText::_('COM_RSGALLERY2_MY_IMAGES'); ?></h3></td>
			</tr>
			<tr>
				<th colspan="5"><div align="right">
					<?php //Since this is no form this won't work: onchange event: this.form.submit()
						//echo $pageNav->getLimitBox(); ?>
				</div></th>
			</tr>
			<tr>
				<th><?php echo JText::_('COM_RSGALLERY2_NAME'); ?></th>
				<th><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?></th>
				<th width="50"><?php echo JText::_('COM_RSGALLERY2_PUBLISHED'); ?></th>
				<th width="50"><?php echo JText::_('COM_RSGALLERY2_DELETE'); ?></th>
				<th width="50"><?php echo JText::_('COM_RSGALLERY2_EDIT'); ?></th>
			</tr>
			
			<?php
			if (count($images) > 0) {
				?>
				<script type="text/javascript">
					function deleteImage(id,itemid) {
						var yesno = confirm ('<?php echo JText::_('COM_RSGALLERY2_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE');?>');
						if (yesno == true) {
							location = 'index.php?option=com_rsgallery2&TEST&Itemid='+itemid+'&rsgOption=myGalleries&task=deleteItem&id='+id;
						}
					}
				</script>
				
				<?php
				foreach ($images as $image) {
					global $rsgConfig;
				   ?>
					<tr>
						<td>
	<?php //MK change: add Itemid to edit URL ?>
							<?php 
							if (!$rsgAccess->checkGallery('up_mod_img', $image->gallery_id)) {
								echo $image->name;
							} else {
							//tooltip: tip, tiptitle, tipimage, tiptext, url, depreciated bool=1 (@todo: this link has two // in it between root and imgPath_thumb)
							 echo JHTML::tooltip('<img src="'.JURI::root().$rsgConfig->get('imgPath_thumb').'/'.$image->name.'.jpg" alt="'.$image->name.'" />',
							 $image->name,
							 "",
							 htmlspecialchars($image->title,ENT_QUOTES,'UTF-8').'&nbsp;('.$image->name.')',	//turns into javascript safe so ENT_QUOTES needed with htmlspeciahlchars
						"index.php?option=com_rsgallery2&Itemid=".$Itemid."&rsgOption=myGalleries&task=editItem&id=".$image->id,1);
							}
							?>
						</td>
						<td><?php echo galleryUtils::getCatnameFromId($image->gallery_id)?></td>
						<td>
							<?php 
	//MK change: publish image source:
							if ($image->published == 1)
								$img = "published-active.png";
							else
								$img = "unpublished-active.png";
							?>
							<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editStateItem&id='.$image->id.'&currentstate='.$image->published);?>">
								<div align="center">
									<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/<?php echo $img;?>" alt="<?php echo JText::_('JACTION_EDITSTATE'); ?>" width="19" position="top" >
								</div>
							</a>
						</td>
						<td>
							<?php
							if (!$rsgAccess->checkGallery('del_img', $image->gallery_id)) {
								?>
								<div align="center">
									<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_delete.png" alt="<?php echo JText::_('JACTION_DELETE'); ?>" width="12" height="12" >
								</div>
								<?php
							} else {
							?>
							<a href="javascript:deleteImage(<?php echo $image->id.','.$Itemid;?>);">
								<div align="center">
									<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/delete-active.png" alt="<?php echo JText::_('JACTION_DELETE'); ?>" width="19"  >
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
									<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/no_edit.png" alt="<?php echo JText::_('JACTION_EDIT'); ?>" width="15" height="15" >
								</div>
								<?php
							} else {
							?>
							<a href="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=editItem&id=$image->id");?>">
							<div align="center">
								<img src="<?php echo JURI_SITE;?>/components/com_rsgallery2/images/edit-active.png" alt="<?php echo JText::_('JACTION_EDIT'); ?>" width="19" >
							</div>
							</a>
							<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			} //End list of images
			else
			{ //No images for this user
				?>
				<tr><td colspan="5"><?php echo JText::_('COM_RSGALLERY2_NO_IMAGES_IN_USER_GALLERIES'); ?></td></tr>
				<?php
			}
				?>
				<tr>
					<td colspan="5">
						<div class="pagination">
							<?php 
								echo $pageNav->getPagesLinks();
								echo "<br>".$pageNav->getPagesCounter();
							?>
						</div>
					</td>
				</tr>
		</table>
		<?php
    }
    
    function editItem($rows) {
        global $rsgConfig;
		$my = JFactory::getUser();
		$editor = JFactory::getEditor();

        foreach ($rows as $row) {
            $filename       = $row->name;
            $title          = htmlspecialchars($row->title, ENT_QUOTES);
            //$description    = $row->descr;
            $description    = htmlspecialchars($row->descr, ENT_QUOTES);
            $id             = $row->id;
            $limitstart     = $row->ordering - 1;
            $catid          = $row->gallery_id;
        }
		?>
		<script type="text/javascript">
			Joomla.submitbutton = function(formTask) {
				var action = formTask.split('.');
				//var formName = action[0];
				var task = action[1];
				var form = document.editItem; //since J!1.6: specific formname different than adminForm possible
				if (task == 'cancel') {
					form.reset();
					history.back();
					return;
				}

				<?php echo $editor->save('descr') ; ?>

				// Field validation, when OK submit.
				if (form.catid.value <= "0") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_SELECT_A_GALLERY'); ?>" );
				}
				else if (form.descr.value == ""){
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_DESCRIPTION'); ?>" );
				}
				else{
					Joomla.submitform(task, form);
					return;
				}
			}
		</script>
	
		<?php
        echo "<h3>".JText::_('COM_RSGALLERY2_EDIT_IMAGE')."</h3>";
        ?>

        <form name="editItem" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveItem"); ?>">
			<table class="adminlist" >
				<tr>
					<td colspan="3">
						<div style="float: right;">
							<a href="javascript:Joomla.submitbutton('editItem.saveItem')" class="toolbar">
								<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/save-active.png" alt="<?php echo JText::_('JSAVE'); ?>" width="32" />
							</a>
							<a href="javascript:Joomla.submitbutton('editItem.cancel')" class="toolbar">
								<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/cancel-active.png" alt="<?php echo JText::_('JCANCEL'); ?>" width="32" />
							</a>	
						</div>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo JText::_('COM_RSGALLERY2_CATEGORY_NAME'); ?></td>
					<td align="left">
						<?php galleryUtils::showUserGalSelectList('up_mod_img', 'catid', $catid);?>
					</td>
					<td rowspan="3"><img src="<?php echo imgUtils::getImgThumb($filename); ?>" alt="<?php echo $title; ?>"  /></td>
				</tr>
				<tr>
					<td align="left"><?php echo JText::_('COM_RSGALLERY2_FILENAME'); ?></td>
					<td  align="left"><strong><?php echo $filename; ?></strong></td>
					<!-- 2nd row of rowspan 3 -->
				</tr>
				<tr>
					<td align="left"><?php echo JText::_('COM_RSGALLERY2_TITLE');?></td>
					<td align="left"><input type="text" name="title" size="30" value="<?php echo $title; ?>" /></td>
					<!-- 3rd row of rowspan 3 -->
				</tr>
				<tr>
					<td align="left" valign="top"><?PHP echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></td>
					<td align="left" colspan="2">
					<?php echo $editor->display( 'descr', stripslashes($description) , '100%', '200', '10', '20',false ) ; ?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" 		value="<?php echo $id; ?>" />
			<input type="hidden" name="task" 	value="" />
			<input type="hidden" name="option" 	value="com_rsgallery2>" />
			<input type="hidden" name="Itemid"	value="<?php echo JRequest::getCmd('Itemid'); ?>" />
			<input type="hidden" name="rsgOption"	value="<?php echo JRequest::getCmd('rsgOption'); ?>" />
        </form>
        <?php
    }
    
function editCat($rows = null) {
	//Mirjam: In v1.13 catid was used where since v1.14 gid is used, but locally in a function catid is fine
    global $rsgConfig;
	$my = JFactory::getUser();
	$editor =& JFactory::getEditor();
	
    ?>
    <script type="text/javascript">
        Joomla.submitbutton = function(formTask) {
			var action = formTask.split('.');
			//var formName = action[0];
			var task = action[1];
            var form = document.editGallery;
            if (task == 'cancel') {
                form.reset();
                history.back();
                return;
            }

			<?php echo $editor->save( 'description' ) ; ?>

			// Field validation, when OK submit.
			if (form.catname1.value == "") {
				alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_GALLERY_NAME'); ?>" );
			} else if (form.description.value == ""){
				alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_DESCRIPTION'); ?>" );
			} else if (form.parent.value < "0"){
				alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_SELECT_A_GALLERY'); ?>" );
			} else {
				Joomla.submitform(task, form);
				return;
			}
        }
    </script>
	
    <?php
    if ($rows) {
        foreach ($rows as $row){
            $catname        = htmlspecialchars($row->name, ENT_QUOTES);
            $description    = htmlspecialchars($row->description, ENT_QUOTES);
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
	<h3><?php echo JText::_('COM_RSGALLERY2_EDIT_GALLERY'); ?></h3>
	<form name="editGallery" id="editGallery" method="post" action="<?php echo JRoute::_("index.php?option=com_rsgallery2&rsgOption=myGalleries&task=saveCat"); ?>">
        <table class="adminlist" >
			<tr>
				<th colspan="2">
					<div style="float: right;">
						<a href="javascript:Joomla.submitbutton('editGallery.saveCat')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/save-active.png" alt="<?php echo JText::_('JSAVE'); ?>" width="32" />
						</a>
						<a href="javascript:Joomla.submitbutton('editGallery.cancel')" class="toolbar">
							<img src="<?php echo JURI_SITE;?>components/com_rsgallery2/images/cancel-active.png" alt="<?php echo JText::_('JCANCEL'); ?>" width="32" />
						</a>	
					</div>				
				</th>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_TOP_GALLERY');?></td>
				<td>
					<?php //galleryUtils::showCategories(NULL, $my->id, 'parent');?>
					<?php echo galleryUtils::galleriesSelectList( $parent, 'parent', false );?>
					<?php //galleryUtils::createGalSelectList( NULL, $listName='parent', true );?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_GALLERY_NAME'); ?></td>
				<td align="left"><input type="text" name="catname1" size="30" value="<?php echo $catname; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></td>
				<td>
					<?php
					echo $editor->display( 'description',  $description , '100%', '200', '10', '20', false ) ; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_RSGALLERY2_PUBLISHED'); ?></td>
				<td align="left"><input type="checkbox" name="published" value="1" <?php if ($published==1) echo "checked"; ?> /></td>
			</tr>
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
        <input type="hidden" name="ordering" value="<?php echo $ordering; ?>" />
		<input type="hidden" name="task" 	value="" />
		<input type="hidden" name="option" 	value="com_rsgallery2>" />
        </table>
	</form>
        <?php
    }

/*
 * Javascript for My galleries forms which use Joomla.submitbutton function.
 * toolbarbutton sends information in formname.task format.
 */
function mygalleriesJavascript() {
	$editor =& JFactory::getEditor();
	?>
	<script type="text/javascript">
	Joomla.submitbutton = function(formTask) {
		var action = formTask.split('.');
		var formName = action[0];
		var task = action[1];
		
		//Two forms available: createGallery and imgUpload
		if (formName == 'createGallery') {
			var form = document.createGallery; //since J!1.6: specific formname different than adminForm possible
			if (task == 'cancel') {
				//form.task = createGallery.cancel
				form.reset();
				return;
			} else if (task == 'saveCat') {
				//form.task = createGallery.saveCat
				<?php echo $editor->save('description') ; ?>
				// Field validation, if OK then submit
				if (form.parent.value == "-1") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_NEED_TO_SELECT_A_PARENT_GALLERY'); ?>" );
				} else if (form.catname1.value == "") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_GALLERY_NAME'); ?>" );
				} else if (form.description.value == ""){
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_DESCRIPTION'); ?>" );
				} else{
					Joomla.submitform(task, form);
					return;
				}
			}
		} else if (formName == 'imgUpload') {
			var form = document.imgUpload; //since J!1.6: specific formname different than adminForm possible
			if (task == 'cancel') {
				//form.task = imgUpload.cancel
				form.reset();
				return;
			} else if (task == 'saveUploadedItem') {
				//form.task = imgUpload.saveUploadedItem
				<?php echo $editor->save('descr') ; ?>
				//Field validation, if OK then submit
				if (form.i_cat.value == "-1") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_SELECT_A_GALLERY'); ?>" );
				} 
				else if (form.i_cat.value == "0") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_SELECT_A_GALLERY'); ?>" );
				} 
				else if (form.i_file.value == "") {
					alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_FILE_TO_UPLOAD'); ?>" );
				}
			else {
				Joomla.submitform(task, form);
				return;
			}
			return;
			}
		}
		return;
	}
	</script>
	<?php
	}
	
}//end class
?>
