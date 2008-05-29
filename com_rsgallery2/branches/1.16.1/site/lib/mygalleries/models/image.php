<?php
/**
 * @package		RSGallery2
 * @subpackage	MyGalleries
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// Import library dependencies
jimport( 'joomla.filesystem.folder' );

/**
 * RSGallery2 Template Manager Template Model
 *
 * @package		RSGallery2
 * @subpackage	MyGalleries
 * @since		1.5
 */
class ModelGallery extends JModel
{
	
	var $gallery_id;
	var $user_id;
	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		global $mainframe;
		
		// Call the parent constructor
		parent::__construct();
		
		//		// Set state variables from the request
		//		$this->setState('filter.string', $mainframe->getUserStateFromRequest( "com_rsgallery2_com_installer.templates.string", 'filter', '', 'string' ));
	}
	
	function getItem()
	{
		$this->db->setQuery("SELECT * FROM #__rsgallery2_gallery WHERE id = $gallery_id");
		$rows = $this->db->loadObjectList();

		return $rows;		
	}
	
	function getItems()
	{
		$this->db->setQuery("SELECT * FROM #__rsgallery2_gallery WHERE user_id = $user_id");
		$rows = $this->db->loadObjectList();

		return $rows;		
		
	}

	function save(){
	}
	function delete(){
	}
	
}






