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

// jimport( 'joomla.filesystem.path');


/*------------------------------------------------------------------------------------
GalleryTreeBranch
--------------------------------------------------------------------------------------
Branch element of gallery tree. Contains images (leaves) and child branches
------------------------------------------------------------------------------------*/

class GalleryTreeBranch
{
	public $Gallery = array (); // one gallery data from DB
	public $Images = array ();  // contains images
	public $ChildGalleries = array (); 	// $GalleryTreeBranches
	
	/*------------------------------------------------------------------------------------
	__construct()
	--------------------------------------------------------------------------------------

	------------------------------------------------------------------------------------*/
	
	public function __construct() 
	{
		$this->Gallery = array (); // one gallery data
		$this->Images = array ();  // list
		$this->ChildGalleries = array ();	// GalleryTreeBranch 
	}
	
	public function CountImages () 
	{
		return count($this->Images);
	}
	
	public function CountChildGalleries () 
	{
		return count($this->ChildGalleries);
	}
	
	
	/*------------------------------------------------------------------------------------
	FillBranchFromGallery()
	--------------------------------------------------------------------------------------

	------------------------------------------------------------------------------------*/	
	public function FillBranchFromGallery ($GalleryIn)
	{
		JLog::add('==> FillBranchFromGallery ' . $GalleryIn->id);

		// First level of galleries
		$this->Gallery = $GalleryIn;
		
		// $Images = array ();  // contains images ---------------
		$DbImages = $this->DbImagesOfGallery ($GalleryIn->id);
		
		// Assign images with
		$this->Images = array ();	
		foreach ($DbImages as $DbImage)
		{
			$this->Images [] = array('Image' => $DbImage);
		}

		
		JLog::add('Images: ' . count ($this->Images));

		$DbChildGalleries = $this->DbChildGalleries ($GalleryIn->id);
		JLog::add('DbChildGalleries: ' . count ($DbChildGalleries));

		// Assign branches to all found root galleries
		foreach ($DbChildGalleries as $DbChildGallery)
		{	
			JLog::add('DbChildGalleries loop ');

			$ActGalleryLeaf = new GalleryTreeBranch;
			
			// Call This function for child
			$ActGalleryLeaf->FillBranchFromGallery ($DbChildGallery);
			
			// Add new leaf to list
			$this->ChildGalleries [] = $ActGalleryLeaf;		
		}
	}
	
	// List of galleries which are not childs
	private function DbChildGalleries ($GalleryId)
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		$query->select('*');
		$query->from('#__rsgallery2_galleries');
		$query->where('parent = '.(int) $GalleryId);
		
		$db->setQuery($query);  
		$OutGalleries = $db->loadObjectList();

		return $OutGalleries;
		
	}
	
	// List of galleries which are not childs
	private function DbImagesOfGallery ($GalleryId)
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		$query->select('*');
		$query->from('#__rsgallery2_files');
		$query->where('gallery_id = '.(int) $GalleryId);
		
		$db->setQuery($query);  
		$OutImages = $db->loadObjectList();

		return $OutImages;
	}


}

/*------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------

------------------------------------------------------------------------------------*/

class GalleryTree
{
	public $RootGalleryLeafs; // topgallery in Rsgallery2
	
	/*------------------------------------------------------------------------------------
	__construct()
	------------------------------------------------------------------------------------*/	
	public function __construct() 
	{
		$this->RootGalleryLeafs = array (); // type of GalleryTreeBranch  ();
	}
	
	/*------------------------------------------------------------------------------------
	FillGalleryTreeFromDB()
	--------------------------------------------------------------------------------------

	------------------------------------------------------------------------------------*/	
	public function FillRootGalleryTreeFromDB ()
	{
		JLog::add('==> FillRootGalleryTreeFromDB ');

		$this->RootGalleryLeafs = array (); // type of GalleryTreeBranch  ();
			
		// First level of galleries
		$RootGalleries = $this->DbRootGalleries ();

		JLog::add('RootGalleries: ' . count ($RootGalleries));

		// Assign branches to all found root galleries
		foreach ($RootGalleries as $Gallery)
		{	
			JLog::add('==> FillRootGalleryTreeFromDB->' . $Gallery->name);
			$ActGalleryLeaf = new GalleryTreeBranch;
			
			//--- $Gallery = array (); // one gallery data from DB ------------
			$ActGalleryLeaf->FillBranchFromGallery ($Gallery);
			
			// Add new leaf to list
			// $this->RootGalleryLeafs [] = array('gallery' => $ActGalleryLeaf);				
			$this->RootGalleryLeafs [] = $ActGalleryLeaf;				
		}
	}
	

	/**/
	// List of galleries which are not childs
	private function DbRootGalleries ()
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		$query->select('*');
		$query->from('#__rsgallery2_galleries');
		$query->where('parent = 0');
		
		$db->setQuery($query);  
		$OutGalleries = $db->loadObjectList();

		return $OutGalleries;		
	}
	/**/
	
	
}


