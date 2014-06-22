<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

class Rsg2ViewRsg2 extends JViewLegacy
{

	public function display ($tpl = null)
	{
	
		$form = $this->get('Form');
		// $item = $this->get('Item');
		Rsg2Helper::addSubMenu('rsg2'); 
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		$this->form = $form;
		// $this->item = $item;
		
		// Define tabs options for version of Joomla! 3.0
        $this->tabsOptions = array(
            "active" => "tab1_id" // It is the ID of the active tab.
        );

        // Define tabs options for version of Joomla! 3.1
        $this->tabsOptionsJ31 = array(
            "active" => "tab1_j31_id" // It is the ID of the active tab.
        );
        
		
	
		$this->addToolbar ();
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);
		
		// Set the document
		$this->setDocument();		
	}

	protected function addToolbar ()
	{
		//JToolBarHelper::TitleText ("Test01");
		JToolBarHelper::title(JText::_('COM_RSG2_MENU_CONTROL_PANEL'), 'generic.png');
		//$input = JFactory::getApplication()->input;
		
		//$link = 'index.php?option=COM_RSG2&rsgOption=images&task=batchupload';


		//JToolBarHelper::custom('com_rsg2.Redirect2ControlCenter', 'config.png', 'config.png', 'COM_RSG2_MENU_CONTROL_PANEL', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Upload', 'rsg2', 'rsg2', JText::_('COM_RSG2_MENU_BATCH_UPLOAD'), false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Galleries', 'rsg2', 'rsg2', 'COM_RSG2_MENU_GALLERIES', false, false);
		
		//JToolBarHelper::custom('com_rsg2.Redirect2Images', 'mediamanager.png', 'mediamanager.png', 'COM_RSG2_MENU_IMAGES', false, false);
	}
	
	function Redirect2ControlCenter()
	{
	
		echo ('Redirect2ControlCenter');
	
		$link = 'index.php?option=com_rsg2&view=galleries';
		$this->setRedirect($link);
		$this->redirect();
	}
	function Redirect2Upload()
	{
		echo ('Redirect2Upload');
	
		$link = 'index.php?option=com_rsg2&view=upload';
		$this->setRedirect($link);
		$this->redirect();
	}
		
	function Redirect2Galleries()
	{
		echo ('Redirect2Galleries');
	
		$link = 'index.php?option=com_rsg2&view=galleries';
		$this->setRedirect($link);
		$this->redirect();
	}
		
	function Redirect2Images()
	{
		echo ('Redirect2Images');
	
		$link = 'index.php?option=com_rsg2&view=images';
		$this->setRedirect($link);
		$this->redirect();
	}
		
	
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() 
    {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_RSG2_MENU_CONTROL_PANEL'));
    }
	
	
	
	
/*		
Then in your controllerName controller you create a method:

function taskName()
{
    $this->setRedirect('index.php?option=com_SomeComponent&task=anotherController.anotherTask');
    $this->redirect();
}
*/
	

	/*
		 parameters

		Writes a custom option and task button for the button bar.

			@param string $task The task to perform (picked up by the switch($task) blocks.
			@param string $icon The image to display.
			@param string $iconOver The image to display when moused over.
			@param string $alt The alt text for the icon image.
			@param bool $listSelect True if required to check that a standard list item is checked. 

		public static function custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true)
		{
				$bar = JToolbar::getInstance('toolbar');
		 
				// Strip extension.
				$icon = preg_replace('#\.[^.]*$#', '', $icon);
		 
				// Add a standard button.
				$bar->appendButton('Standard', $icon, $alt, $task, $listSelect);
		}	
	*/
}


