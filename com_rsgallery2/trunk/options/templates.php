<?php
/**
* templates option for RSGallery2
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_VALID_MOS' ) or die( 'Restricted Access' );

require_once( $rsgOptions_path . 'templates.html.php' );

switch ($task) {
	/* Template system */
    case 'templates':
    	viewTemplates( $option );
    	break;
    case 'upload_template':
    	uploadTemplate( $option );
    	break;
    case 'remove':
    	removeTemplate( $option );
    	break;
    case 'default':
    	setDefaultTemplate( $option );
    	break;
    /* end template system */

// 	temporary bug fix. todo: code editing system
	default:
		viewTemplates( $option );
    	break;

	case 'edit_css':
		editTemplateCSS($option);
		HTML_RSGallery::RSGalleryFooter();
		break;
	case 'save_css':
		saveTemplateCSS($option, $template);
        break;
	case 'edit_main':
		editTemplateMain($option);
		HTML_RSGallery::RSGalleryFooter();
		break;
		// depreciated
	case 'edit_thumbs':
		editTemplateThumbs($option);
		HTML_RSGallery::RSGalleryFooter();
		break;
	case 'edit_display':
		editTemplateDisplay($option);
		HTML_RSGallery::RSGalleryFooter();
		break;
	case 'save_main':
		saveTemplateMain($option, $template);
        break;
	case 'save_thumbs':
		saveTemplateThumbs($option, $template);
        break;
	case 'save_display':
		saveTemplateDisplay($option, $template);
        break;
}


require_once( $mosConfig_absolute_path .'/administrator/components/com_installer/installer.class.php' );
/**
* RSGTemplate installer, based on the Joomla installer
* @package RSGallery2
* @author Ronald Smit <ronald.smit@rsdev.nl>
*/
class rsgInstallerTemplate extends mosInstaller {
	/**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path,$database;

		if (!$this->preInstallCheck( $p_fromdir, 'template' )) {
			return false;
		}

		$xmlDoc 	=& $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName($e->getText());
		$this->elementDir(mosPathName( JPATH_RSGALLERY2_SITE . DS . "templates" . DS . strtolower( str_replace( " ","_",$this->elementName() ) ) ) );
		if (!file_exists( $this->elementDir() ) && !mosMakePath( $this->elementDir() )) {
			$this->setError(1, 'Failed to create directory "' . $this->elementDir() . '"' );
			return false;
		}

		if ($this->parseFiles( 'files' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'images' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'css' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'media' ) === false) {
			return false;
		}
		if ($e = &$mosinstall->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}
		//Copy XML file to template directory
		return $this->copySetupFile('front');
	}
	/**
	* Custom install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function uninstall( $id, $option, $client=0 ) {
		global $database, $mosConfig_absolute_path;
		echo $id;
		if ($id == "default") {
			echo "Niet gelukt. ($id)";
			HTML_RSGallery::showInstallMessage( 'Cannot delete default template', 'Uninstall -  error',
				$this->returnTo( $option ) );
		} else {
			// Delete directories
			$path = JPATH_RSGALLERY2_SITE . '/templates/' . $id;
	
			$id = str_replace( '..', '', $id );
			if (trim( $id )) {
				if (is_dir( $path )) {
					return deldir( mosPathName( $path ) );
				} else {
					HTML_RSGallery::showInstallMessage( 'Directory does not exist, cannot remove files', 'Uninstall -  error',
						$this->returnTo( $option ) );
				}
			} else {
				HTML_RSGallery::showInstallMessage( 'Template id is empty, cannot remove files', 'Uninstall -  error',
					$this->returnTo( $option ) );
				exit();
			}			
		}
	}
	/**
	* return to method
	*/
	function returnTo( $option ) {
		return "index2.php?option=com_rsgallery2&task=templates";
	}
}

/**
* Compiles a list of installed, templates
*
* Based on xml files found.  If no xml file found the template
* is ignored
*/
function viewTemplates( $option ) {
	global $database, $mainframe, $rsgConfig;
	global $mosConfig_absolute_path, $mosConfig_list_limit;

	$limit = $mainframe->getUserStateFromRequest( 'viewlistlimit', 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );

	$templateBaseDir = mosPathName( JPATH_RSGALLERY2_SITE . DS .'/templates' );

	$rows = array();
	// Read the template dir to find templates
	$templateDirs		= mosReadDirectory($templateBaseDir);

	$cur_template = $rsgConfig->get('template');

	$rowid = 0;
	// Check that the directory contains an xml file
	foreach($templateDirs as $templateDir) {
		$dirName = mosPathName($templateBaseDir . $templateDir);
		$xmlFilesInDir = mosReadDirectory($dirName,'.xml$');

		foreach($xmlFilesInDir as $xmlfile) {
			// Read the file to see if it's a valid template XML file
			$xmlDoc = new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors( true );
			if (!$xmlDoc->loadXML( $dirName . $xmlfile, false, true )) {
				continue;
			}

			$root = &$xmlDoc->documentElement;

			if ($root->getTagName() != 'mosinstall') {
				continue;
			}
			if ($root->getAttribute( 'type' ) != 'template') {
				continue;
			}

			$row = new StdClass();
			$row->id 		= $rowid;
			$row->directory = $templateDir;
			$element 		= &$root->getElementsByPath('name', 1 );
			$row->name 		= $element->getText();

			$element 		= &$root->getElementsByPath('creationDate', 1);
			$row->creationdate = $element ? $element->getText() : 'Unknown';

			$element 		= &$root->getElementsByPath('author', 1);
			$row->author 	= $element ? $element->getText() : 'Unknown';

			$element 		= &$root->getElementsByPath('copyright', 1);
			$row->copyright = $element ? $element->getText() : '';

			$element 		= &$root->getElementsByPath('authorEmail', 1);
			$row->authorEmail = $element ? $element->getText() : '';

			$element 		= &$root->getElementsByPath('authorUrl', 1);
			$row->authorUrl = $element ? $element->getText() : '';

			$element 		= &$root->getElementsByPath('version', 1);
			$row->version 	= $element ? $element->getText() : '';

			// Get info from db
			if ($cur_template == $templateDir) {
				$row->published	= 1;
			} else {
				$row->published = 0;
			}

			$row->checked_out = 0;
			$row->mosname = strtolower( str_replace( ' ', '_', $row->name ) );

			$rows[] = $row;
			$rowid++;
		}
	}

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( count( $rows ), $limitstart, $limit );

	$rows = array_slice( $rows, $pageNav->limitstart, $pageNav->limit );
	
	$userfile 	= mosGetParam( $_REQUEST, 'userfile', dirname( __FILE__ ) );
	$userfile 	= mosPathName( $userfile );
	
	HTML_RSGallery::showInstallForm( 'Install new RSGTemplate <small><small>[ Site ]</small></small>', $option, $userfile, '<a href="index2.php?option=com_rsgallery2&task=templates">Back to Templates</a>');
	html_rsg2_templates::showTemplates( $rows, $pageNav, $option );
}

function uploadTemplate( $option ) {

	//Create new installer instance
	$installer = new rsgInstallerTemplate();

	// Check if file uploads are enabled
	if (!(bool)ini_get('file_uploads')) {
		HTML_RSGALLERY::showInstallMessage( "The installer can't continue before file uploads are enabled. ",
			'Installer - Error', $installer->returnTo( $option ) );
		exit();
	}

	// Check that the zlib is available
	if(!extension_loaded('zlib')) {
		HTML_RSGALLERY::showInstallMessage( "The installer can't continue before zlib is installed",
			'Installer - Error', $installer->returnTo( $option ) );
		exit();
	}
	//Get file details for uploaded file
	$userfile = mosGetParam( $_FILES, 'userfile', null );
	
    //If no file selected, return error message
    if (!$userfile) {
		HTML_RSGALLERY::showInstallMessage( 'No file selected', 'Upload new template - error',
			$installer->returnTo( $option ));
		exit();
	}
	$userfile_name = $userfile['name'];
	$element = "RSGTemplate";
	$msg = '';
	
	//Move uploaded file to /media directory
	$resultdir = uploadFile( $userfile['tmp_name'], $userfile['name'], $msg );

	if ($resultdir !== false) {
		if (!$installer->upload( $userfile['name'] )) {
			HTML_RSGALLERY::showInstallMessage( $installer->getError(), 'Upload '.$element.' - Upload Failed',
				$installer->returnTo( $option ) );
		}
		$ret = $installer->install();

		HTML_RSGALLERY::showInstallMessage( $installer->getError(), 'Upload '.$element.' - '.($ret ? 'Success' : 'Failed'),
			$installer->returnTo( $option ) );
		cleanupInstall( $userfile['name'], $installer->unpackDir() );
	} else {
		HTML_RSGALLERY::showInstallMessage( $msg, 'Upload '.$element.' -  Upload Error',
			$installer->returnTo( $option ) );
	}
}

/**
* Sets the active template for RSGallery2
* @param
*/
function setDefaultTemplate( $option ) {
	global $rsgConfig;
	
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
	if (!is_array( $cid )) {
		$cid = array(0);
	}

	if ( $rsgConfig->saveConfig($rsgConfig->set("template", $cid[0]) ) ) {
		$msg = "Active template set succesfully (".$cid[0].")";
	} else {
		$msg = "Active template(".$cid[0].") could not be changed!";
	}
	mosRedirect( "index2.php?option=com_rsgallery2&task=templates", $msg );
}

/**
* Removes template from system
* @param
*/
function removeTemplate( $option ) {
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
	if (!is_array( $cid )) {
		$cid = array(0);
	}
	
	$installer 	= new rsgInstallerTemplate();
	$result 	= false;
	//Check if template is not default template
	if ($cid[0] == 'default') {
		$msg = " - Cannot delete default template! ";
	} else {
		$result = $installer->uninstall( $cid[0], $option );
	}

	$msg .= $installer->getError();

	mosRedirect( $installer->returnTo( $option ), $result ? 'Success ' . $msg : 'Failed ' . $msg );
}


/** 
 * Function for editing HTML source of index.php template file
 * Function from joomla core
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * Adapted for RSgallery2
 */
 function editTemplateMain( $option) {
	global $mosConfig_absolute_path;
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}
	$template = $cid[0];
	$main_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/index.php';
	if ( $fp = fopen( $main_file, 'r' ) ) {
		$content = fread( $fp, filesize( $main_file ) );
		$content = htmlspecialchars( $content );

		editTemplateMainSource( $content, $option, $template);
	} else {
		mosRedirect( 'index2.php?option='. $option.'&task=templates', _RSGALLERY_EDITCSS_FAIL_NOOPEN. $template_path );
	}
}


function saveTemplateMain( $option, $template) {
	global $mosConfig_absolute_path;
	$filecontent 	= mosGetParam( $_POST, 'filecontent', '', _MOS_ALLOWHTML );
	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates'.'&client='. $client, 'Operation failed: Content empty.' );
	}

	$main_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/index.php';

	$enable_write 	= mosGetParam($_POST,'enable_write',0);
	$oldperms 		= fileperms($main_file);
	
	if ($enable_write) @chmod($main_file, $oldperms | 0222);

	clearstatcache();
	if ( is_writable( $main_file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: '. $main_file .' is not writable.' );
	}

	if ( $fp = fopen ($main_file, 'w' ) ) {
		fputs( $fp, stripslashes( $filecontent ), strlen( $filecontent ) );
		fclose( $fp );
		if ($enable_write) {
			@chmod($main_file, $oldperms);
		} else {
			if (mosGetParam($_POST,'disable_write',0))
				@chmod($main_file, $oldperms & 0777555);
		} // if
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Changes to main view of '. $template .' template saved.' );
	} else {
		if ($enable_write) @chmod($main_file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: Failed to open file for writing.' );
	}

}

function editTemplateMainSource($content, $option, $template) {
		global $mosConfig_absolute_path;
		$main_file =JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/index.php';
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="290"><table class="adminheading"><tr><th class="templates">Edit index.php</th></tr></table></td>
			<td width="220">
				<span class="componentheading">index.php is :
				<b><?php echo is_writable($main_file) ? '<font color="green"> Writeable</font>' : '<font color="red"> Unwriteable</font>' ?></b>
				</span>
			</td>
<?php
			if (mosIsChmodable($main_file)) {
				if (is_writable($main_file)) {
?>
			<td>
				<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
				<label for="disable_write">Make unwriteable after saving</label>
			</td>
<?php
				} else {
?>
			<td>
				<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
				<label for="enable_write">Override write protection while saving</label>
			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $main_file; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}


/** 
 * Function for editing HTML source of thumbs.php template file
 * Function from joomla core
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * Adapted for RSgallery2
 */
 function editTemplateThumbs( $option) {
	global $mosConfig_absolute_path;
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}
	$template = $cid[0];
	$thumbs_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/thumbs.php';
	if ( $fp = fopen( $thumbs_file, 'r' ) ) {
		$content = fread( $fp, filesize( $thumbs_file ) );
		$content = htmlspecialchars( $content );

		editTemplateThumbsSource( $content, $option, $template);
	} else {
		mosRedirect( 'index2.php?option='. $option.'&task=templates', _RSGALLERY_EDITCSS_FAIL_NOOPEN. $template_path );
	}
}


function saveTemplateThumbs( $option, $template) {
	global $mosConfig_absolute_path;
	$filecontent 	= mosGetParam( $_POST, 'filecontent', '', _MOS_ALLOWHTML );
	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates'.'&client='. $client, 'Operation failed: Content empty.' );
	}

	$thumbs_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/thumbs.php';

	$enable_write 	= mosGetParam($_POST,'enable_write',0);
	$oldperms 		= fileperms($thumbs_file);
	
	if ($enable_write) @chmod($thumbs_file, $oldperms | 0222);

	clearstatcache();
	if ( is_writable( $thumbs_file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: '. $thumbs_file .' is not writable.' );
	}

	if ( $fp = fopen ($thumbs_file, 'w' ) ) {
		fputs( $fp, stripslashes( $filecontent ), strlen( $filecontent ) );
		fclose( $fp );
		if ($enable_write) {
			@chmod($thumbs_file, $oldperms);
		} else {
			if (mosGetParam($_POST,'disable_write',0))
				@chmod($thumbs_file, $oldperms & 0777555);
		} // if
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Changes to display view of '. $template .' template saved.' );
	} else {
		if ($enable_write) @chmod($thumbs_file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: Failed to open file for writing.' );
	}

}

function editTemplateThumbsSource($content, $option, $template) {
		global $mosConfig_absolute_path;
		$thumbs_file =JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/thumbs.php';
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="290"><table class="adminheading"><tr><th class="templates">Edit thumbs.php</th></tr></table></td>
			<td width="220">
				<span class="componentheading">thumbs.php is :
				<b><?php echo is_writable($thumbs_file) ? '<font color="green"> Writeable</font>' : '<font color="red"> Unwriteable</font>' ?></b>
				</span>
			</td>
<?php
			if (mosIsChmodable($thumbs_file)) {
				if (is_writable($thumbs_file)) {
?>
			<td>
				<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
				<label for="disable_write">Make unwriteable after saving</label>
			</td>
<?php
				} else {
?>
			<td>
				<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
				<label for="enable_write">Override write protection while saving</label>
			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $thumbs_file; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
/** 
 * Function for editing HTML source of display.class.php template file
 * Function from joomla core
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * Adapted for RSgallery2
 */
 function editTemplateDisplay( $option) {
	global $mosConfig_absolute_path;
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}
	$template = $cid[0];
	$display_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/display.class.php';
	if ( $fp = fopen( $display_file, 'r' ) ) {
		$content = fread( $fp, filesize( $display_file ) );
		$content = htmlspecialchars( $content );

		editTemplateDisplaySource( $content, $option, $template);
	} else {
		mosRedirect( 'index2.php?option='. $option.'&task=templates', _RSGALLERY_EDITCSS_FAIL_NOOPEN. $template_path );
	}
}


function saveTemplateDisplay( $option, $template) {
	global $mosConfig_absolute_path;
	$filecontent 	= mosGetParam( $_POST, 'filecontent', '', _MOS_ALLOWHTML );
	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates'.'&client='. $client, 'Operation failed: Content empty.' );
	}

	$display_file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/display.class.php';

	$enable_write 	= mosGetParam($_POST,'enable_write',0);
	$oldperms 		= fileperms($display_file);
	
	if ($enable_write) @chmod($display_file, $oldperms | 0222);

	clearstatcache();
	if ( is_writable( $display_file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: '. $display_file .' is not writable.' );
	}

	if ( $fp = fopen ($display_file, 'w' ) ) {
		fputs( $fp, stripslashes( $filecontent ), strlen( $filecontent ) );
		fclose( $fp );
		if ($enable_write) {
			@chmod($display_file, $oldperms);
		} else {
			if (mosGetParam($_POST,'disable_write',0))
				@chmod($display_file, $oldperms & 0777555);
		} // if
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Changes to display view of '. $template .' template saved.' );
	} else {
		if ($enable_write) @chmod($display_file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=templates', 'Operation failed: Failed to open file for writing.' );
	}

}

function editTemplateDisplaySource($content, $option, $template) {
		global $mosConfig_absolute_path;
		$display_file =JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/display.class.php';
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="290"><table class="adminheading"><tr><th class="templates">Edit display.class.php</th></tr></table></td>
			<td width="220">
				<span class="componentheading">display.class.php is :
				<b><?php echo is_writable($display_file) ? '<font color="green"> Writeable</font>' : '<font color="red"> Unwriteable</font>' ?></b>
				</span>
			</td>
<?php
			if (mosIsChmodable($display_file)) {
				if (is_writable($display_file)) {
?>
			<td>
				<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
				<label for="disable_write">Make unwriteable after saving</label>
			</td>
<?php
				} else {
?>
			<td>
				<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
				<label for="enable_write">Override write protection while saving</label>
			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $display_file; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
/** 
 * Function for editing css file
 * Function from joomla core
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * Adapted for RSgallery2
 */
function editTemplateCSS( $option ) {
	$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}
	$template = $cid[0];
	$file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/css/template.css';

	if ($fp = fopen( $file, 'r' )) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );

		editCSSSource( $content, $option, $template);
	} else {
		mosRedirect( 'index2.php?option='. $option.'&task=templates', _RSGALLERY_EDITCSS_FAIL_NOOPEN. $file );
	}
}


function saveTemplateCSS( $option, $template) {
	$filecontent 	= mosGetParam( $_POST, 'filecontent', '' );

	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option.'&task=templates', 'Operation failed: Content empty.' );
	}
	$file = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/css/template.css';

	$enable_write 	= mosGetParam($_POST,'enable_write',0);
	$oldperms 		= fileperms($file);
	
	if ($enable_write) {
		@chmod($file, $oldperms | 0222);
	}

	clearstatcache();
	if ( is_writable( $file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', _RSGALLERY_EDITCSS_NOT_WRITABLE );
	}

	if ($fp = fopen ($file, 'w')) {
		fputs( $fp, stripslashes( $filecontent ) );
		fclose( $fp );
		if ($enable_write) {
			@chmod($file, $oldperms);
		} else {
			if (mosGetParam($_POST,'disable_write',0))
				@chmod($file, $oldperms & 0777555);
		} // if
		mosRedirect( 'index2.php?option='. $option .'&task=templates' );
	} else {
		if ($enable_write) @chmod($file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=templates', _RSGALLERY_EDITCSS_FAIL_NOTWRITING );
	}

}

function editCSSSource($content, $option, $template) {
		global $mosConfig_absolute_path, $rsgConfig;
		$css_path = JPATH_RSGALLERY2_SITE.'/templates/' . $template . '/css/template.css';
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="280"><table class="adminheading"><tr><th class="templates">Edit CSS</th></tr></table></td>
			<td width="260">
				<span class="componentheading">template.css is :
				<b><?php echo is_writable($css_path) ? '<font color="green"> Writeable</font>' : '<font color="red"> Unwriteable</font>' ?></b>
				</span>
			</td>
<?php
			if (mosIsChmodable($css_path)) {
				if (is_writable($css_path)) {
?>
			<td>
				<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
				<label for="disable_write">Make unwriteable after saving</label>
			</td>
<?php
				} else {
?>
			<td>
				<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
				<label for="enable_write">Override write protection while saving</label>
			</td>
<?php
				} // if
			} // if
?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $css_path; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
