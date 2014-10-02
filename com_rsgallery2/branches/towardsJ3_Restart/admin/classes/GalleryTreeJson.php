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



// http://phpflow.com/php/how-to-create-dynamic-tree-view-menu/



// no direct access
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

// jimport( 'joomla.filesystem.path');



/*------------------------------------------------------------------------------------
GalleryTreeBranch
--------------------------------------------------------------------------------------
Branch element of gallery tree. Contains images (leaves) and child branches
------------------------------------------------------------------------------------*/

class GalleryTreeBranchHtml
{
	public $RootGalleryLeafs; // topgallery in Rsgallery2

    // ToDo: look at gallery tree class and remoel from there

	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/	
	public function __construct($InRootGalleryLeafs) 
	{
		$this->RootGalleryLeafs = $InRootGalleryLeafs; // type of GalleryTreeBranch  ();
	}
	

	public function HtmlOut ()
    {



    }


}






// Creating jQuery Tree view without plugin 
/*------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------

------------------------------------------------------------------------------------*/

class GalleryTreeHtml
{


}
