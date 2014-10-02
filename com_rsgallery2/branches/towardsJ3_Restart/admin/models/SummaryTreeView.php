<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/* test
JLoader::register('ComponentClassNameHelper',dirname(JPATH_COMPONENT.DS.'helpers'.DS.'file.php');
require_once( dirname(__FILE__).'/helper.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'file.php' );
JPATH_COMPONENT_ADMINISTRATOR
*/


// Include the JLog class.
jimport('joomla.log.log');


// Include gallery tree classes
require_once( dirname(__FILE__).'/../classes/GalleryTreeClass.php' );
 
/**
 * 
 */
class rsg2ModelSummaryTreeView extends  JModelForm
{
	/**
	 * Returns a reference to the Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Galleries', $prefix = 'rsg2Table', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
	public function getForm($data = array(), $loadData = true) 
	{		
		$options = array('control' => 'jform', 'load_data' => $loadData);
		// Get the form.
		$form = $this->loadForm('SummaryTreeView', 'SummaryTreeView', $options);
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		//$data = $app->getUserState('com_rsg2.edit.gallery.data', array());
		//if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to build an SQL query to load the gallerie list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('*')->from('#__rsgallery2_galleries');
		
		return $query;
	}
	
	public function getGalleries ()
	{
		
		// Get a list of galleries from rsgallery2
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('*');		
		$query->from('#__rsgallery2_galleries');
		
		$db->setQuery($query);  
		$OutGalleries = $db->loadObjectList();

		return $OutGalleries;
	}
	
	public function getImages ()
	{
		// Get a list of images from rsgallery2
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__rsgallery2_files');
		
		$db->setQuery($query);  
		$OutImages = $db->loadObjectList();
		
		return $OutImages;
	}
	
	public function getGalleryTree ()
	{
		JLog::add('==> getGalleryTree ');

		$GalleryTree = new GalleryTree ();
		$GalleryTree->FillRootGalleryTreeFromDB ();
	
		JLog::add('getGalleryTree leafs: ' . count ($GalleryTree->RootGalleryLeafs));

	
		return $GalleryTree;
	}

}