<?php
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

// identify active file
JLog::add('==> ctrl.SummaryTreeView.php ');

jimport('joomla.application.component.controlleradmin');

class Rsg2ControllerSummaryTreeView extends JControllerForm
{

    function __construct()
    {
        parent::__construct();

        $this->registerTask('SaveAsJson', 'SaveAsJson');
        // $this->_input = JFactory::getApplication()->input;

        // Add submenu
        //require_once JPATH_COMPONENT.'/helpers/easybookreloaded.php';
        //EasybookReloadedHelper::addSubmenu($this->_input->get('view', 'easybookreloaded'));
    }


    public function getModel($name = 'SummaryTreeView',
 							 $prefix = 'rsg2Model', 
//  							 $config = array('ignore_request' => true))
  							 $config = array())
	{
		$config ['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

    public function SaveAsJson ()
	{
// identify active file
        JLog::add('   SaveAsJson');

        /* How to call function in model ....
                        // Get the input
                    $input = JFactory::getApplication()->input;
                    $pks = $input->post->get('cid', array(), 'array');

                    // Sanitize the input
                    JArrayHelper::toInteger($pks);

                    // Get the model
                    $model = $this->getModel();

                    $return = $model->extrahello($pks);

                    // Redirect to the list screen.
                    $this->setRedirect(JRoute::_('index.php?option=com_hello&view=hellos', false));
        */

		$this->setRedirect(JRoute::_('index.php?option=com_rsg2&view=galleries', false));
	}
	
	
	
}


