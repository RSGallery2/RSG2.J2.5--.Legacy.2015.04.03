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

require_once( JPATH_ADMINISTRATOR .'/components/com_rsgallery2/options/templates.installer.php' );

/**
* RSGTemplate installer, based on the Joomla installer
* @package RSGallery2
* @author Ronald Smit <ronald.smit@rsdev.nl>
*/
class rsgInstallerTemplate extends mosInstallerRSG2 {
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
		return "index2.php?option=com_rsgallery2&rsgOption=templates";
	}
}