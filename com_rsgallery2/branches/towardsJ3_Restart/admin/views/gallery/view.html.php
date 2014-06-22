<?php

defined( '_JEXEC' ) or die;

jimport('joomla.application.component.view');

class Rsg2ViewGallery extends JViewLegacy
{
	protected $item;
	protected $form;
	
	public function display ($tpl = null)
	{
		
		//JFactory::getApplication()->input->set('hidemainmanu',true);
	
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		/*
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
		}
		
		// Assign the Data
		$this->form = $form;
		// $this->item = $item;
		*/
	
		$this->addToolbar ();
		
		parent::display ($tpl);
		
		// Set the document
		$this->setDocument();
	}

	protected function addToolbar ()
	{
		// Disable main menu
		JRequest::setVar('hidemainmenu', true);

		// Toolbar
			
		$item=$this->item;
		if($item->id == 0) {
			JToolBarHelper::title(JText::_('COM_RSG2_NEW'));
		} else {
			JToolBarHelper::title(JText::_('COM_RSG2_EDIT'));
		}		
		
		/*
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();
		*/
	
		JToolBarHelper::apply('gallery.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('gallery.save', 'JTOOLBAR_SAVE');
        JToolBarHelper::save2copy('gallery.save2copy');
        JToolBarHelper::save2new('gallery.save2new');
        JToolBarHelper::spacer();
		if($item->id == 0) {
			JToolBarHelper::cancel('gallery.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
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
	
	// COM_RSGALLERY2_GALLERY
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() 
    {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_RSG2_MENU_GALLERY'));
    }
	
}


