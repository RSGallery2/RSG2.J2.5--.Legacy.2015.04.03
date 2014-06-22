<?php
defined('_JEXEC') or die;

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
