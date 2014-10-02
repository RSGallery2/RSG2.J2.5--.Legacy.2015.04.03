// Include the JLog class.
jimport('joomla.log.log');

<?php
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

// identify active file
JLog::add('==> ctrl.galleries.php ');


jimport('joomla.application.component.controlleradmin');

class Rsg2ControllerGalleries extends JControllerAdmin
{

	public function getModel($name = 'Gallery', 
 							 $prefix = 'rsg2Model', 
//  							 $config = array('ignore_request' => true))
  							 $config = array())
	{
		$config ['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
 
}