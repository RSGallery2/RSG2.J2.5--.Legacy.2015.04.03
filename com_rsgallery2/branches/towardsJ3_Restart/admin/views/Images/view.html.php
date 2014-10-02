<?php

defined( '_JEXEC' ) or die;

jimport('joomla.application.component.view');

class Rsg2ViewImages extends JViewLegacy
{
	protected $items;
	protected $pagination;

	public function display ($tpl = null)
	{
        // Get data from the model
		$form = $this->get('Form');		
		$items = $this->get('Items');
		$this->state = $this->get('State');
        $pagination = $this->get('Pagination');
		Rsg2Helper::addSubMenu('images'); 

		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
		}
		
		// Assign the Data
		$this->items = $items;
        $this->pagination = $pagination;
		// $this->form = $form;
		
        // Set the toolbar and number of found items
        $this->addToolBar($this->pagination->total);	
		$this->sidebar = JHtmlSidebar::render ();

        // Set the document
        $this->setDocument();

        // Display the template
		parent::display ($tpl);
 
	}

        /**
         * Setting the toolbar
         */
	protected function addToolBar ()
	{
		JToolBarHelper::title(JText::_('COM_RSG2_MANAGE_IMAGES'), 'generic.png');

        //Reflect number of items in title!
//		JToolBarHelper::title(JText::_('Total: ').($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.')</span>':''), 'helloworld');
		// if ($canDo->get('core.edit.state')) 
        if (count($this->items))
        {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::addNew('image.add','JTOOLBAR_NEW');
        if (count($this->items))
        {
            JToolBarHelper::editList('image.edit','JTOOLBAR_EDIT');
            JToolBarHelper::deleteList('', 'images.delete','JTOOLBAR_DELETE');
        }
    }

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

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() 
    {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_RSG2_IMAGES'));
    }
		
}


