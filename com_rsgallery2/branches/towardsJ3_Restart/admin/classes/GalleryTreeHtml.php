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


// google: php treeview example
// http://phpflow.com/php/how-to-create-dynamic-tree-view-menu/
// http://phocoa.com/webapp/examples/ajax/tree


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
	
	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/	
	public function __construct($InRootGalleryLeafs) 
	{
		$this->RootGalleryLeafs = $InRootGalleryLeafs; // type of GalleryTreeBranch  ();
	}

    // ToDo: look at gallery tree class and remoel from there

	public function HtmlOut ()
	{
		// ToDo: following first try should be divided into further ...
		$OutHtml = '<h2>GalleryTree</h2>'; // ToDo: Add time and ...
		if (empty($this->RootGalleryLeafs)) :
			$OutHtml .=	'<div class="alert alert-no-items">';
			$OutHtml .=	JText::_('JGLOBAL_NO_MATCHING_RESULTS'). '<br>';
			$OutHtml .=	var_dump($this->RootGalleryLeafs); 
			$OutHtml .=	'</div>';
		else : 
			// All root galleries;
			foreach ($this->GalleryTree->RootGalleryLeafs as $ActGalleryLeaf) :
				$OutHtml .=	'<strong>Gallery:"' . $ActGalleryLeaf->Gallery->name . ' </strong> <br>';
				$OutHtml .=	json_encode($ActGalleryLeaf->Gallery) . '<br>';
				// All images of gallery
				foreach ($ActGalleryLeaf->Images as $ActImage) :
					$OutHtml .=	'       <strong>Image:' . $ActImage->name . ' </strong> <br>';
					$OutHtml .=	json_encode($ActImage) . '<br>';
				endforeach;
			endforeach;
			
		endif; 
				
		return $OutHtml;
	}
}






// Creating jQuery Tree view without plugin 
/*------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------

------------------------------------------------------------------------------------*/

class GalleryTreeHtml
{
	public $RootGalleryLeafs; // topgallery in Rsgallery2
	
	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/	
	public function __construct($InRootGalleryLeafs) 
	{
		$this->RootGalleryLeafs = InRootGalleryLeafs; // type of GalleryTreeBranch  ();
	}
	
	public function HtmlOut ()
	{
		// ToDo: following first try should be divided into further ...
		$OutHtml = '<h2>GalleryTree</h2>'; // ToDo: Add time and ...
		if (empty($this->RootGalleryLeafs)) :
			$OutHtml .=	'<div class="alert alert-no-items">';
			$OutHtml .=	JText::_('JGLOBAL_NO_MATCHING_RESULTS'). '<br>';
			$OutHtml .=	var_dump($this->RootGalleryLeafs); 
			$OutHtml .=	'</div>';
		else : 
			// All root galleries;
			foreach ($this->GalleryTree->RootGalleryLeafs as $ActGalleryLeaf) :
				$OutHtml .=	'<strong>Gallery:"' . $ActGalleryLeaf->Gallery->name . ' </strong> <br>';
				$OutHtml .=	json_encode($ActGalleryLeaf->Gallery) . '<br>';
				// All images of gallery
				foreach ($ActGalleryLeaf->Images as $ActImage) :
					$OutHtml .=	'       <strong>Image:' . $ActImage->name . ' </strong> <br>';
					$OutHtml .=	json_encode($ActImage) . '<br>';
				endforeach;
			endforeach;
			
		endif; 
				
		return $OutHtml;
	}

}
