<?php
/**
 * This class encapsulates the HTML for the non-administration RSGallery pages.
 * @version $Id$
 * @package RSGallery2
 * @copyright (C) 2003 - 2006 RSGallery2
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_VALID_MOS' ) or die( 'Restricted Access' );


/**
 * this temporary class holds functions that need to be moved to proper classes
 * but they work here for now, so we'll leave them here
 */
class tempDisplay{
	var $gallery;
	var $items;
	var $item;
	var $limitstart;
		
	/**
		@todo move this constructor back to rsgDisplay when all functions have been moved out of tempDisplay
	**/
	function tempDisplay(){
		$gid = mosGetParam ( $_REQUEST, 'catid', 0 );
		$gid = mosGetParam ( $_REQUEST, 'gid', $gid );

		$this->gallery = rsgGalleryManager::get( $gid );
		$this->items = $this->gallery->items();
		
		$id = mosGetParam ( $_REQUEST, 'id', null );
		if( $id )
			$this->item = $this->gallery->getItem( $id );
			
		$limitstart = mosGetParam ( $_REQUEST, 'limitstart', null );
		if( $limitstart ){
			$this->item = $this->items[$limitstart];
			$this->limitstart = $limitstart;
		}
	}
	
	function mainPage(){
		$page = mosGetParam ( $_REQUEST, 'page', '' );
	
		switch( $page ){
			
			case "my_galleries":
				HTML_RSGALLERY::RSGalleryTitleblock(null, null);
				$this->my_galleries();
				HTML_RSGALLERY::RSGalleryFooter();
			break;
			
			case "edit_image":
				$id = mosGetParam ( $_REQUEST, 'id'  , '');
				$this->edit_image($id);
			break;
			case "save_image":
				$this->save_image();
			break;
			case "delete_image":
				$this->delete_image();
			break;
			
			case "slideshow":
				$catid = mosGetParam ( $_REQUEST, 'catid'  , ''); 
				$id = mosGetParam ( $_REQUEST, 'id'  , ''); 
				$this->slideshow($id,$catid);
				HTML_RSGALLERY::RSGalleryFooter(); 
			break;
			
			case 'vote':
				$this->addVote();
			break;
			
			case 'addcomment':
				$this->addComment();
			break;
			case 'delete_comment':
				if (isset($_REQUEST['id'])) $id = mosGetParam ( $_REQUEST, 'id'  , ''); 
				$this->deleteComment($id);
			break;
			
			case "newusercat":
				$this->userCat($my_id, 0);
			break;
			case "editusercat":
				$this->usercat();
			break;
			case "makeusercat":
				$this->makeusercat(NULL);
			break;
			case "delusercat":
				if (isset($_REQUEST['catid'])) $catid = mosGetParam ( $_REQUEST, 'catid'  , '');
				$this->delusercat($catid);
			break;
			
			case "doFrontUpload":
				$this->doFrontUpload();
			break;
			
			default:
				// we don't handle any of these pages
				return false;
		}
		// we handled the page, let rsgDisplay know.
		return true;
		
	}
	
	/**
	 * Wrapper function for showing the My Galleries interface
	 */
	function my_galleries() {
	global $my, $database, $rsgConfig;
	
	//Check if My Galleries is enabled in config, if not .............. 
	if ( !$rsgConfig->get('show_mygalleries') ) die(_RSGALLERY_MYGAL_NOT_AUTH);
	
	require_once(JPATH_ROOT. "/includes/pageNavigation.php");
	
	//Set limits for pagenav
	$limit      = trim( mosGetParam( $_REQUEST, 'limit', 10 ) );
	$limitstart = trim( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	
	//Get total number of records for paging
	$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE userid = '$my->id'");
	$total = $database->loadResult();
	
	//New instance of mosPageNav
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );
	
	$database->setQuery("SELECT * FROM #__rsgallery2_files".
						" WHERE userid = '$my->id'".
						" LIMIT $pageNav->limitstart, $pageNav->limit ");
	$images = $database->loadObjectList();
	$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE parent = 0 AND uid = '$my->id'");
	$rows = $database->loadObjectList();
	
	if($my->id) {
			//User is logged in, show it all!
			HTML_RSGALLERY::myGalleries($rows, $images, $pageNav);
		} else {
			//Not logged in, back to main page
			global $Itemid;
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"),_RSGALLERY_NO_USERCATS);
		}
	}

	function edit_image($id) {
	global $database;
	if ($id)
		{
		$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$id'");
		$rows = $database->loadObjectList();
		HTML_RSGALLERY::edit_image($rows);
		}
	}

	function save_image() {
		global $database;
		if (isset($_REQUEST['id'])) $id = mosGetParam ( $_REQUEST, 'id'  , '');
		if (isset($_REQUEST['title'])) $title = mosGetParam ( $_REQUEST, 'title'  , '');
		if (isset($_REQUEST['descr'])) $description = mosGetParam ( $_REQUEST, 'descr'  , '');
		if (isset($_REQUEST['catid'])) $catid = mosGetParam ( $_REQUEST, 'catid'  , '');
	
		$database->setQuery("UPDATE #__rsgallery2_files SET ".
				"title = '$title', ".
				"descr = '$description', ".
				"gallery_id = '$catid' ".
				"WHERE id= '$id'");
	
		if ($database->query())
			{
			global $Itemid;
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=my_galleries"), _RSGALLERY_SAVE_SUCCESS );
			}
		else
			{
			echo _RSGALLERY_ERROR_SAVE.mysql_error();
			}
	}
	
	function delete_image() {
		global $my, $database, $Itemid, $rsgAccess;
		
		if (isset($_REQUEST['id'])) {
			$id = mosGetParam ( $_REQUEST, 'id'  , '');
			
			//Get gallery id
			$gallery_id = galleryUtils::getCatidFromFileId($id);
			
			//Check if file deletion is allowed in this gallery
			if ($rsgAccess->checkGallery('del_img', $gallery_id )) {
				$filename 	= galleryUtils::getFileNameFromId($id);
				imgUtils::deleteImage($filename);
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_DELIMAGE_OK);
			} else {
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_USERIMAGE_NOTOWNER);
				}
			}
		else
			{
			//No ID sent, no delete possible, back to my galleries
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_DELIMAGE_NOID);
			}
	}
	
	function slideshow($id,$catid) {
		global $database;
		$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE gallery_id = '$catid' ORDER BY ordering ASC");
		$rows = $database->LoadObjectList();
		include(JPATH_RSGALLERY2_SITE.'/slideshow.rsgallery2.php');
	}
	
	/**
	* Adds a vote to the database
	* @todo Make sure everone can only vote once.
	*/
	function addVote() {
		global $database, $Itemid;
	
		if (isset($_REQUEST['picid']))      $picid = mosGetParam ( $_REQUEST, 'picid'  , '');
		if (isset($_REQUEST['limitstart'])) $limitstart = mosGetParam ( $_REQUEST, 'limitstart'  , '');
		if (isset($_REQUEST['vote']))       $vote = mosGetParam ( $_REQUEST, 'vote'  , '');
			
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
						alert("<?php echo _RSGALLERY_THANK_VOTING; ?>");
						location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid."&amp;page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart); ?>';
					</script>
					<?php
					//mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart),_RSGALLERY_THANK_VOTING);
					}
				else
					{
					?>
					<script type="text/javascript">
						alert("<?php echo _RSGALLERY_VOTING_FAILED; ?>");
						location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=".$Itemid."&amp;page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$limitstart); ?>';
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
	
	/**
	* Deletes a comment from the frontend. It is only accessible to Super Administrator
	* @param int Id of comment to delete
	*/
	function deleteComment($id) {
	global $database, $my, $Itemid;
	if ($my->usertype == 'Super Administrator')
		{
		//go ahead, delete comment
		$sql = "DELETE FROM #__rsgallery2_comments".
			" WHERE id = '$id'";
		$database->setQuery($sql);
		if ($database->query())
			{
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"),_RSGALLERY_COMMENT_DELETED);
			}
		else
			{
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"),_RSGALLERY_COMMENT_NOT_DELETED);
			}
		}
	else
		{
		//Not authorized, back to main screen
		mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid"),_RSGALLERY_COMMENT_NOT_DELETED);
		}
	}
	
	/**
	* Saves a comment into the database
	*/
	function addComment() {
		global $database, $rsgConfig, $Itemid;
	
		// die if commenting turned off
		if( $rsgConfig->get('displayComments') == false ) return;
		
		if (isset($_REQUEST['picid']))      $picid = mosGetParam ( $_REQUEST, 'picid'  , '');
		if (isset($_REQUEST['limitstart'])) $limitstart = mosGetParam ( $_REQUEST, 'limitstart'  , '');
		if (isset($_REQUEST['comment']))    $comment = mosGetParam ( $_REQUEST, 'comment'  , '');
		if (isset($_REQUEST['name']))       $name = mosGetParam ( $_REQUEST, 'name'  , '');
		if (isset($_REQUEST['ordering']))   $ordering = mosGetParam ( $_REQUEST, 'ordering'  , '');
		
		$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$picid'");
		$rows = $database->loadObjectList();
		foreach ($rows as $row)
			{
			$id = $row->id;
			$catid= $row->gallery_id;
			}
		if (!isset($comment) || !isset($name))
			{
			//Back to image
			?>
			<script type="text/javascript">
				alert("<?php echo _RSGALLERY_COMMENT_FIELD_CHECK; ?>");
				location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&page=inline&Itemid=".$Itemid."&id=".$id."&catid=".$catid."&limitstart=".$limitstart); ?>';
			</script>
			<?php
			}
		else
			{
			$name = addslashes($name);
			$comment = addslashes($comment);
			$database->setQuery("INSERT INTO #__rsgallery2_comments".
								" (name, comment, picid, date) VALUES".
								" ('$name','$comment','$picid', now())");
			if ($database->query())
				{
				?>
				<script type="text/javascript">
					alert("<?php echo _RSGALLERY_COMMENT_ADDED; ?>");
					location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&page=inline&Itemid=".$Itemid."&id=".$picid."&catid=".$catid."&limitstart=".$limitstart); ?>';
				</script>
				<?php
				//Retrieve comment count and increment it, thanks to Allan Kissack
				$database->setQuery("SELECT * FROM #__rsgallery2_files WHERE id = '$picid'");
				$rows = $database->loadObjectList();
				foreach ($rows as $row)
					{
					$comments = $row->comments +1;
					$database->setQuery("UPDATE #__rsgallery2_files SET comments = '$comments' WHERE id = '$row->id'");
					if (!$database->query()) 
						{
						?>
						<script type="text/javascript">
							alert("<?php echo _RSGALLERY_COMMENT_COUNT_NOT_ADDED; ?>");
							location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$row->id."&catid=".$row->gallery_id."&limitstart=".$ordering); ?>';
						</script>
						<?php
						}
					}
				}
			else
				{
				?>
				<script type="text/javascript">
					alert("<?php echo _RSGALLERY_COMMENT_NOT_ADDED; ?>");
					location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=inline&id=".$id."&catid=".$catid."&limitstart=".$limitstart); ?>';
				</script>
				<?php
				}
			}
		}

	
	function userCat() {
		global $my, $rsgConfig, $database, $Itemid;
		if (isset($_REQUEST['catid']))      $catid = mosGetParam ( $_REQUEST, 'catid'  , '');	
		
		if ($catid != 0)
			{
			//Edit category
			$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id ='$catid'");
			$rows = $database->LoadObjectList();
			HTML_RSGALLERY::showUserGallery($rows);
			}
		else
			{
			//Check if maximum number of usercats are already made
			$count = galleryUtils::userCategoryTotal($my->id);
			if ($count >= $rsgConfig->get('uu_maxCat') )
				{
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_MAX_USERCAT_ALERT );
				}
			else
				{
				//New category
				HTML_RSGALLERY::showUserGallery(NULL);
				}
	
			}
		}
		
	function makeusercat($id) {
		global $rsgConfig, $database, $my, $Itemid;
		//If gallery creation is disabled, unauthorized attempts die here.
		if (!$rsgConfig->get('uu_createCat')) die ("User category creation is disabled by administrator.");
		
		if (isset($_REQUEST['parent']))         $parent = mosGetParam ( $_REQUEST, 'parent'  , '');
		if (isset($_REQUEST['catid']))          $id = mosGetParam ( $_REQUEST, 'catid'  , 0);
		if (isset($_REQUEST['catname1']))       $catname1 = mosGetParam ( $_REQUEST, 'catname1'  , '');
		if (isset($_REQUEST['description']))    $description = mosGetParam ( $_REQUEST, 'description'  , '');
		if (isset($_REQUEST['published']))      $published = mosGetParam ( $_REQUEST, 'published'  , 0);
		if (isset($_REQUEST['ordering']))       $ordering = mosGetParam ( $_REQUEST, 'ordering'  , '');
		
		$maxcats        = $rsgConfig->get('uu_maxCat');
		if ($id) {
			$database->setQuery("UPDATE #__rsgallery2_galleries SET ".
				"name = '$catname1', ".
				"description = '$description', ".
				"published = '$published', ".
				"parent = '$parent' ".
				"WHERE id = '$id' ");
			if ($database->query())
				{
				echo "Query gelukt";
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_CATDETAILSOK );
				}
			else
				{
				echo "Query failed: ".mysql_error();
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_CATDETAILSNOTOK );
				}
			} else {
				//New category
				$userCatTotal = galleryUtils::userCategoryTotal($my->id);
				if (!isset($parent))
					$parent = 0;
				if ($userCatTotal >= $maxcats) {
					?>
					<script type="text/javascript">
					alert('<?php echo _RSGALLERY_MAX_USERCAT_ALERT;?>');
					location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"); ?>';
					</script>
					<?php
					//mosRedirect('index.php?option=com_rsgallery2&page=my_galleries',_RSGALLERY_MAX_USERCAT_ALERT);
				} else {
					//Create ordering, start at last position
					$database->setQuery("SELECT MAX(ordering) FROM #__rsgallery2_galleries WHERE uid = '$my->id'");
					$ordering = $database->loadResult() + 1;
					//Insert into database
					$database->setQuery("INSERT INTO #__rsgallery2_galleries ".
						"(name, description, ordering, parent, published, user, uid, date) VALUES ".
						"('$catname1','$description','$ordering','$parent','$published','1' ,'$my->id', now())");
						
					if ($database->query()) {
						//Create initial permissions for this gallery
						$database->setQuery("SELECT id FROM #__rsgallery2_galleries WHERE name = '$catname1' LIMIT 1");
						$gallery_id = $database->loadResult();
						$acl = new rsgAccess();
						if ( $acl->createDefaultPermissions($gallery_id) )
							mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_NEWCAT );
					} else {
						mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_NONEWCAT );
					}
				}
			}
		//mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&page=my_galleries") );
	}
	
	function delUserCat($catid) {
		global $database, $my, $mosConfig_absolute_path, $rsgConfig, $Itemid;
		
		//Get category details
		$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id = '$catid'");
		$rows = $database->LoadObjectList();
		foreach ($rows as $row)
			{
			$uid = $row->uid;
			$parent = $row->parent;
			}
			
		//Check if gallery has children
		$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_galleries WHERE parent = '$catid'");
		$count = $database->loadResult();
		if ($count > 0)
			{
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_USERCAT_SUBCATS);
			}
		
		//No children from here, so lets continue
		if ($uid == $my->id OR $my->usertype == 'Super Administrator')
			{
			//Delete images
			$database->setQuery("SELECT name FROM #__rsgallery2_files WHERE gallery_id = '$catid'");
			$result = $database->loadResultArray();
			$error = 0;
			foreach ($result as $filename)
				{
				if ( !imgUtils::deleteImage($filename) ) 
					$error++;
				}
			
			//Error checking
			if ($error == 0)
				{
				//Gallery can be deleted
				$database->setQuery("DELETE FROM #__rsgallery2_galleries WHERE id = '$catid'");
				if ( !$database->query() )
					{
					//Error message, gallery could not be deleted
					mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_ALERT_CATDELNOTOK);
					}
				else
					{
					//Ok, goto mainpage
					mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_ALERT_CATDELOK);
					}
				}
			else
				{
				//There were errors. Gallery will not be deleted
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_ALERT_CATDELNOTOK);
				}
			}
		else
			{
			//Abort and return to mainscreen
			mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"),_RSGALLERY_USERCAT_NOTOWNER);
			}
	}
	
	function doFrontUpload() {
		global $rsgAccess, $rsgConfig, $my, $database, $mosConfig_absolute_path, $Itemid;
		
		//Get category ID to check rights
		if (isset($_REQUEST['i_cat']))      $i_cat = mosGetParam ( $_REQUEST, 'i_cat'  , '');
		
		//Check if user can upload in this gallery
		if ( !$rsgAccess->checkGallery('up_mod_img', $i_cat) ) die('Unauthorized upload attempt!');
		
		//New instance of fileHandler
		$uploadfile = new fileHandler();
		
		//Check if maximum number of images is exceeded
		$database->setQuery("SELECT COUNT(1) FROM #__rsgallery2_files WHERE userid = '$my->id'");
		$count = $database->loadResult();
		if ($count >= $rsgConfig->get('uu_maxImages') ) {
			?>
			<script type="text/javascript">
			alert('<?php echo _RSGALLERY_MAX_USERIMAGES_ALERT;?>');
			location = '<?php echo sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"); ?>';
			</script>
			<?php
		} else {
			//Get parameters from form
			if (isset($_FILES['i_file']))       $i_file = mosGetParam ( $_FILES, 'i_file'  , ''); 
			if (isset($_REQUEST['i_cat']))      $i_cat = mosGetParam ( $_REQUEST, 'i_cat'  , ''); 
			if (isset($_REQUEST['title']))      $title = mosGetParam ( $_REQUEST, 'title'  , ''); 
			if (isset($_REQUEST['descr']))      $descr = mosGetParam ( $_REQUEST, 'descr'  , ''); 
			if (isset($_REQUEST['uploader']))   $uploader = mosGetParam ( $_REQUEST, 'uploader'  , ''); 
			
			//Check whether it is ZIP file or an image
			$file_ext = $uploadfile->checkFileType($i_file['name']);
			
			//Check whether directories are there and writable
			$check = $uploadfile->preHandlerCheck();
			if ($check !== true ) {
				mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), $check);
			}
				
			switch ($file_ext) {
				case 'zip':
					//Check if file is really a ZIP-file
    				if (!eregi( '.zip$', $i_file['name'] )) {
    					mosRedirect( "index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries", $i_file['name']." is not a valid archive format. Only ZIP-files are allowed!");
    				} else {
    					//Valid ZIP-file, continue
	            		if ($uploadfile->checkSize($i_file) == 1) {
	                		$ziplist = $uploadfile->handleZIP($i_file);
	                		
	                		//Set extract dir
	                		$extractdir = JPATH_ROOT . DS . "media" . DS . $uploadfile->extractDir . DS;
	                		
	                		//Import images into right folder
	                		for ($i = 0; $i<sizeof($ziplist); $i++) {
	                			$import = imgUtils::importImage($extractdir . $ziplist[$i], $ziplist[$i], $i_cat);
	                		}
	                		
	                		//Clean mediadir
	                		fileHandler::cleanMediaDir( $uploadfile->extractDir );
	                		
	                		//Redirect
	                		mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_UPLOADOK );
	            		} else {
	                		//Error message
	                		mosRedirect( "index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries", _RSGALLERY_ZIP_TO_BIG);
	            		}
    				}
					break;
				case 'image':
					//Check if image is too big
					if ($i_file['error'] == 1)
						mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), '*Image size is too big for upload!*' );
					
					$file_name = $i_file['name'];
					if ( move_uploaded_file($i_file['tmp_name'], $mosConfig_absolute_path."/media/".$file_name) ) {
						//Import into database and copy to the right places
						$imported = imgUtils::importImage($mosConfig_absolute_path."/media/".$file_name, $file_name, $i_cat, $title, $descr);
						
						if ($imported == 1) {
							if (file_exists($mosConfig_absolute_path."/media/".$file_name))
								unlink($mosConfig_absolute_path."/media/".$file_name);
						} else {
							mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), 'Importing image failed! Notify RSGallery2. This should never happen!');
						}
						mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_UPLOADOK );
					} else {
						mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_NOWRITE );
					}
					break;
				case 'error':
					mosRedirect( sefRelToAbs("index.php?option=com_rsgallery2&Itemid=".$Itemid."&page=my_galleries"), _RSGALLERY_ALERT_WRONGFORMAT );
					break;
				}//end switch
			}//end else
	} //end frontupload

}// end tempDisplay

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