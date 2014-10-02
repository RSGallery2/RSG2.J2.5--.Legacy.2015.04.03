<?php
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

// identify active file
JLog::add('==> ctrl.image.php ');



jimport('joomla.application.component.controlleradmin');

class Rsg2ControllerImages extends JControllerAdmin
{

	public function getModel($name = 'Image', 
 							 $prefix = 'rsg2Model', 
//  							 $config = array('ignore_request' => true))
  							 $config = array())
	{
		$config ['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

}
