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
		global $rsgConfig;
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		
		$catid = rsgInstance::getInt( 'catid'  , null);
		
		if ($catid) {
			//Edit category
			$database->setQuery("SELECT * FROM #__rsgallery2_galleries WHERE id ='$catid'");
			$rows = $database->LoadObjectList();
			getTemplateItem('galleryEdit.php');
		} else {
			//Check if maximum number of usercats are already made
			$count = galleryUtils::userCategoryTotal($my->id);
			if ($count >= $rsgConfig->get('uu_maxCat') ) {
				$mainframe->redirect("index.php?option=com_rsgallery2&page=my_galleries", JText::_('MAX_USERCAT_ALERT') );
			} else {
				//New category
				getTemplateItem('galleryEdit.php');
			}
		}
		
	}
	
	function save(){
	}
	function delete(){
	}

}






