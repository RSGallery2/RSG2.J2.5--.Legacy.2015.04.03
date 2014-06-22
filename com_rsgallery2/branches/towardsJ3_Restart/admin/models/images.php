<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * HelloWorldList Model
 */
class rsg2ModelImages extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__rsgallery2_files');
		
		return $query;
	}
	
	public function getParentGalleryName($id)
	{
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__rsgallery2_files');
		
		
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$sql = 'SELECT `name` FROM `#__rsgallery2_galleries` WHERE `id` = '. (int) $id;
		$db->setQuery($sql);
		$db->query();
		// loadObjectstdClass(), loadObjectList,loadObject or any 
		// other methods you can use for fetching based on list or 
		// single row. ? any way the result return as array. ?
		
		// http://docs.joomla.org/Accessing_the_database_using_JDatabase/2.5#The_Query
		// http://docs.joomla.org/Selecting_data_using_JDatabase
		$name = $db->loadResult();
		return $name;
	}
	
	
	
	
	
	
	
	
	
}