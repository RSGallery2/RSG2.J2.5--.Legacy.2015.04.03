<?php

defined( '_JEXEC' ) or die;

jimport('joomla.application.component.view');
//jimport('joomla.application.component.model'); 
//JLoader::import( '', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_foo' . DS . 'models' );
//JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_rsg2/models');

class Rsg2ViewSummaryTreeView extends JViewLegacy
{
//	protected $items;
	protected $pagination;
//	protected $state;
	protected $form;

	protected $GalleryTree;
	protected $Galleries;
	protected $Images;

	public function display ($tpl = null)
	{
        // Get data from the model
		$this->form = $this->get('Form');
//		$this->items = $this->get('Items');
//		$this->items = $this->getListQuery();
		
		//$categoriesModel = JModelLegacy::getInstance( 'Categories', 'MyComponentModel' );
		//$model = JModelLegacy::getInstance('SummaryTreeView', 'rsg2');
//		$model = JModel::getInstance('SummaryTreeView', 'rsg2');
//		$this->items = $model->getListQuery();
		
		$this->Galleries = $this->get('Galleries');
		$this->Images = $this->get('Images');
		
		$this->GalleryTree = $this->get('GalleryTree');

// 		$this->state = $this->get('State');
//        $this->pagination = $this->get('Pagination');

		Rsg2Helper::addSubMenu('summarytreeview'); 
		
		// Check for errors.
		/*
		if (count($errors = $this->get('Errors'))) 
		{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
		}
		*/
		// Assign the Data
        // Set the toolbar and number of found items
        //$this->addToolBar($this->pagination->total);	
        $this->addToolBar();	

        $this->sidebar = JHtmlSidebar::render();

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

		JToolBarHelper::title(JText::_('COM_RSG2COM_RSG2_SUMMARY_TREE_VIEW'), 'generic.png');
/*
        //Reflect number of items in title!
//		JToolBarHelper::title(JText::_('Total: ').($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.')</span>':''), 'helloworld');
		// if ($canDo->get('core.edit.state')) 
		{
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::addNew('gallery.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('gallery.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'galleries.delete','JTOOLBAR_DELETE');
*/

// http://www.joomlaportal.de/joomla-erweiterungen-komponenten/124784-toolbar-buttons-erstellen-und-funktion-zuweisen.html
//<form name="adminForm">
//Alle deine Sachen die du Speichern möchtest.
//</form>


/* in der eigenen Seite ?
<?php
$task    = JRequest::getCmd('task');  //habe ich ohne und mit dem probiert. 
switch ( $task ) { 
case 'add': 
    echo "add"; 
break; 
case 'save': // habe ich auch mit: case 'save' probiert. 
     echo "save"; 
break; 
default: 
?> 
*/


// http://docs.joomla.org/Creating_a_toolbar_for_your_component
// http://docs.joomla.org/JToolBarHelper/custom
//         JToolBarHelper::custom('SummaryTreeView.SaveAsJson', 'extrahello.png', 'extrahello_f2.png', 'Save as Json file', true);
        //JToolBarHelper::custom('SummaryTreeView.SaveAsJson', '' , '', 'Save as Json file', false);
        JToolBarHelper::custom('SummaryTreeView.SaveAsJson', '' , '', 'Save as Json file', false);
        JToolBarHelper::custom('executeRegenerateDisplayImages','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_BUTTON_DISPLAY', false);
		JToolBarHelper::custom('executeRegenerateThumbImages','forward.png','forward.png','COM_RSGALLERY2_MAINT_REGEN_THUMBS', false);

        // http://tutsforu.com/adding-toolbar-at-backend-of-joomla-component.html

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
            $document->setTitle(JText::_('COM_RSG2_SUMMARY_TREE_VIEW'));
    }
	
}


