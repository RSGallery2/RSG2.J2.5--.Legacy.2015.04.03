<?php
/**
* This file handles the used paths for rsg2
* @version $Id: img.utils.php 1090 2012-07-09 18:52:20Z mirjam $
* @package Rsg2
* @copyright (C) 2005 - 2014 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author finnern
* RSGallery2 is Free Software
*/

// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

jimport( 'joomla.filesystem.folder' );
// jimport( 'joomla.filesystem.path');

//--- Directory separator DS --------------------------------
// Define it once for all
// DIRECTORY_SEPARATOR ("\\" on Win, '/' Linux,...)
// PATH_SEPARATOR (';' on Win, ':' on Linux,...)
if(!defined('DS'))
{
	define('DS',DIRECTORY_SEPARATOR);
}

//--- General definition of site and admin -----------------

if (!defined('JPATH_RSGALLERY2_SITE'))
{
// JPATH_SITE
	define('JPATH_RSGALLERY2_SITE', JPATH_ROOT. DS .'components'. DS . 'com_rsg2');
}


if (!defined('JPATH_RSGALLERY2_ADMIN')) // ??? might also be defined in router.php is SEF is used
{	
	define('JPATH_RSGALLERY2_ADMIN', JPATH_ROOT. DS .'administrator' . DS . 'components' . DS . 'com_rsg2');
}

// ..... more fix pathes in admin for use in require once ...






//--- lib for joining pathes ---------------------
// require_once(JPATH_RSGALLERY2_ADMIN . DS . 'rsg_libraries' . DS . 'rsg2_LibPaths.php');
/* 
if (!defined('JPATH_RSG_ADMIN_IMAGES')) // ??? might also be defined in router.php is SEF is used
{	
	define('JPATH_RSG_ADMIN_IMAGES', JPATH_ROOT. DS .'administrator' . DS . 'components' . DS . 'com_rsg2' .DS. 'images' );
}
*/

class rsg2_Paths
{
	static const JPATH_RSG_ADMIN_IMAGES = JPATH_ROOT. DS .'administrator' . DS . 'components' . DS . 'com_rsg2' .DS. 'images';

	/* const may not be assingned by function ... use get functions instead 
	static const RSG2_IMAGES_BASE_DIR = rsg2_LibPaths::join_paths ('images', 'rsgallery');	
	static const RSG2_IMG_THUMB_DIR = rsg2_LibPaths::join_paths (RSG2_IMAGES_BASE_DIR, 'thumb');	
	static const RSG2_IMG_DISPLAY_DIR = rsg2_LibPaths::join_paths (RSG2_IMAGES_BASE_DIR, 'thumb');	
	static const RSG2_IMG_ORIGINAL_DIR = rsg2_LibPaths::join_paths (RSG2_IMAGES_BASE_DIR, 'thumb');	
	static const RSG2_IMG_WATERMARKED_DIR = rsg2_LibPaths::join_paths (RSG2_IMAGES_BASE_DIR, 'thumb');	
	*/	
	private $galleryBasePath;
	private $imgPath_thumb;
	private $imgPath_display;
	private $imgPath_original;
	private $imgPath_watermarked;

	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------
	Initialises all pathes
	regards site or admin origin
	
	------------------------------------------------------------------------------------*/
	
	public function __construct() 
	{
	
		/* $app = JFactory::getApplication();
		if ($app->isSite())  
		{
			initImgPathesSite ();
		}
		else
		{
			if ($app->isAdmin()) echo 'Client is administrator';
			{
				initImgPathesAdmin ();
			}
		}
		*/
		

		/* Path to  */
		
		
		
		/* Path to different version of the imported images */
		initImgPathes (JPATH_ROOT);
    }
	/*
	public function initImgPathesSite ()
	{
		$basepath = JPATH_RSGALLERY2_SITE
		initImgPathes ($basepath);
	}
	*/
/*	public function initImgPathesAdmin ()
	{
		$basepath = JPATH_RSGALLERY2_ADMIN
		initImgPathes ($basepath);
	}
*/	

	/* Path to different version of the imported images */
	public function initImgPathes (basepath = JPATH_ROOT)
	{
        $this->galleryBasePath     = rsg2_LibPaths::join_paths ($basePath, 'images',    'rsgallery');
        $this->imgPath_thumb       = rsg2_LibPaths::join_paths ($this->galleryBasePath, 'thumb');
        $this->imgPath_display     = rsg2_LibPaths::join_paths ($this->galleryBasePath, 'display');
        $this->imgPath_original    = rsg2_LibPaths::join_paths ($this->galleryBasePath, 'original');
		$this->imgPath_watermarked = rsg2_LibPaths::join_paths ($this->galleryBasePath, 'watermarked');
	}
	
	private function allPaths ()
	{
		return array($this->galleryBasePath, $this->imgPath_thumb, $this->imgPath_display, $this->imgPath_original, $this->imgPath_watermarked);
	}

	public function createRsg2ImagePaths ()
	{
		JLog::Add ('createRsg2ImagePaths: ' );
		
        $count = 0;
        
        foreach (allPaths() as $dir) {			
			JLog::Add ('Create dir: ' . $dir);
			// if (file_exists(JPATH_SITE.$dir) && is_dir(JPATH_SITE.$dir)) {
			if (JFolder::exists($dir)) {
				// Dir already exists, next
				// ToDo: raise message 
				// $this->writeInstallMsg(JText::sprintf('COM_RSG2_ALREADY_EXISTS', $dir),"ok");
				JLog::Add (' - Directory existed: ' . $dir);
			}
			else {
				// if(@mkdir(JPATH_SITE.$dir, 0777)) {
				if(JFolder::create ($dir, 0777)) {
					// $this->writeInstallMsg(JText::sprintf('COM_RSG2_FOLDER_IS_CREATED', $dir),"ok");
					// ToDO: raise message 
					JLog::Add ('Directory created: ' . $dir);
					$count++;
				}
				else {
					// ToDO: raise message 
					//$this->writeInstallMsg(JText::sprintf('COM_RSG2_FOLDER_COULD_NOT_BE_CREATED', $dir),"error");
					JLog::Add ('Error creating directory: ' . $dir);
				}
			}
        }
	
		// raise error when it fails
	}
	
/* 	
if( ! JFolder::create(JPATH_COMPONENT_ADMINISTRATOR.DS.'mein_ordner'.DS.'unterordner') )
{
   echo 'Das Verzeichnis konnte nicht erstellt werden';
}	
	
if(JFolder::exists(JPATH_ROOT.'/pfad/zum/verzeichnis'))
{
   echo 'Das Verzeichnis exisiert';
}	


if( JFolder::delete(JPATH_ROOT.DS.'pfad'.DS.'zum'.DS.'ordner') )
{
   echo 'Der Ordner wurde gelöscht';
}

jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );
$content = "My content";
JFile::write( 'my_file.txt', $content );
JFolder::create( 'new_folder' );
JFile::copy( 'my_file.txt', 'new_folder/my_file.txt' );	




*/
	
	
	public function deleteRsg2ImagePaths ()
	{
		JLog::Add ('createRsg2ImagePaths: ' );

		imgBasePath = $this->galleryBasePath;
		if (JFolder::exists(imgBasePath)) {
			if (!JFolder::delete (imgBasePath)
			{
				JLog::Add ('Error creating directory: ' . imgBasePath);
			}
		}
		
		// raise error when it fails
	}
	
	
	
	    /**
     * Function will recursively delete all directories and files in them, including subdirectories
     *
     * @param string $target Directory to delete
     * @param array $exceptions Array of files to exclude from the delete
     * @param boolean $output Status message for every file True or False
     * @return boolean True or False
     * /
    function deleteGalleryDir($target, $exceptions, $output=false) {
    
		JLog::add('rsgInstall: deleteGalleryDir: ' + $target);
		
		if (file_exists($target) && is_dir($target))
		{
			$sourcedir = opendir($target);
			while(false !== ($filename = readdir($sourcedir)))
				{
				if(!in_array($filename, $exceptions))
					{
					if($output)
						{
						echo JText::_('COM_RSGALLERY2_PROCESSING').$target."/".$filename."<br>";
						}
					if(is_dir($target."/".$filename))
						{
						// recourse subdirectory; call of function recursive
						$this->deleteGalleryDir($target."/".$filename, $exceptions);
						}
					else if(is_file($target."/".$filename))
						{
						// unlink file
						unlink($target."/".$filename);
						}
					}
				}
			closedir($sourcedir);
			if(rmdir($target))
				{
				//return 0;
				$this->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_DIRECTORY_STRUCTURE_DELETED', $target),"ok");
				}
			else
				{
				//return 1;
				$this->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_DELETING_OLD_DIRECTORY_STRUCTURE_FAILED', $target), "error");
				}
			}
		else
			{
			//return 2;
			$this->writeInstallMsg(JText::_("COM_RSGALLERY2_NO_OLD_DIRECTORY_STRUCTURE_FOUND_CONTINUE"),"ok");
			}
    }
    */
	
	function createIndexHtmlFilesInImagePaths ()
	{
		JLog::Add ('Create IndexHtmlFiles: ');

		foreach (allPaths() as $dir) {			
			if (JFolder::exists($dir)) {
				filePathName = $dir . DS . 'index.html'; 
				if (!JFile::exists(filePathName))
				{
					JLog::Add ('Create IndexHtmlFiles: ' . $dir);

					$buffer = '';	//needed: Cannot pass parameter 2 [of JFile::write()] by reference...
					if (!JFile::write(filePathName, buffer))
					{
						JLog::Add ('Error create IndexHtmlFile: ' . $dir);
					
					}
				}
			}
		}
	}

	
	function check4ImagePathsPermissions ()
	{
	
	// ToDo: Fill out and use ...
	}
	
	function IsExistingIndexHtmlFilesInImagePaths () // see writeWarningBox ...
	{
		JLog::Add ('Check IndexHtmlFiles: ');

		ErrFound = false;

		foreach (allPaths() as $dir) {			
			if (JFolder::exists($dir)) {
				filePathName = $dir . DS . 'index.html'; 
				if (!JFile::exists(filePathName))
				{
					JLog::Add ('IndexHtmlFile does not exist: ' . $dir);

					ErrFound = True;
				}
			}
		}
		return ErrFound;
	}
	
	
/*
			if (file_exists(JPATH_ROOT.$folder) && is_dir(JPATH_ROOT.$folder) ) {
				$perms = substr(sprintf('%o', fileperms(JPATH_ROOT.$folder)), -4);
				if (!is_writable(JPATH_ROOT.$folder) )
					$html .= "<p style=\"color: #CC0000;font-size:smaller;\"><img src=\"".JURI_SITE."/includes/js/ThemeOffice/warning.png\" alt=\"\">&nbsp;<strong>".JPATH_ROOT.$folder."</strong>".JText::_('COM_RSGALLERY2_IS_NOT_WRITABLE')."($perms)";
				// Check if the folder has a file index.html, if not, create it, but not for media folder
				if ((!JFile::exists(JPATH_ROOT.$folder.DS.'index.html')) AND ($folder != "/media")) {
					$buffer = '';	//needed: Cannot pass parameter 2 [of JFile::write()] by reference...
					JFile::write(JPATH_ROOT.$folder.DS.'index.html',$buffer);
				}
			} else {
				$html .= "<p style=\"color: #CC0000;font-size:smaller;\"><img src=\"".JURI_SITE."/includes/js/ThemeOffice/warning.png\" alt=\"\">&nbsp;<strong>".JPATH_ROOT.$folder."</strong>".JText::_('COM_RSGALLERY2_FOLDER_NOTEXIST');	
			}
			*/	
	
	public function text ()
	{ 
		outTxt = ''
        outTxt += '' . $this->galleryBasePath .'<br\>';
        outTxt += '' . $this->imgPath_thumb .'<br\>';
        outTxt += '' . $this->imgPath_display .'<br\>';
        outTxt += '' . $this->imgPath_original .'<br\>';
		outTxt += '' . $this->imgPath_watermarked .'<br\>';
		
		return outTxt;
	}
	
} // class
