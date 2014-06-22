<?php
defined('_JEXEC') or die;

// import Joomla controller library
jimport('joomla.application.component.controller');
 
class Rsg2Controller extends JControllerLegacy
{
    // protected $default_view = 'rsg2s';
    
    /**
     * display task
     *
     * @return void
     */
	public function display($cachable = false, $urlparams = false)
	{

		require_once JPATH_COMPONENT.'/helpers/rsg2.php';

		$view   = $this->input->get('view', 'rsg2');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		
/* ToDo:: Activate following: book extension entwickeln  page 208
		if ($view == 'rsg2' && $layout == 'edit' && !$this->checkEditId('com_rsg2.edit.rsgallery2', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_rsg2&view=rsgallery2s', false));

			return false;
		}
*/
		parent::display($cachable);

		return $this;
	}
	
}
