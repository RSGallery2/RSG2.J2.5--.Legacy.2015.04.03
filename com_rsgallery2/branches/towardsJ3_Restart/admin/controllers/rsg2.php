<?php
defined('_JEXEC') or die;

// control center 
// ToDo:: rename to rsg_control and use as deafult ...

class Rsg2ControllerRsg2 extends JControllerForm
{

	function galleries()
	{
		$this->setRedirect('index.php?option=com_rsg2&view=galleries');
		$this->redirect();
	}	



}
