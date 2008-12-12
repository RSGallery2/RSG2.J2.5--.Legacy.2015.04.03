<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');

$item = $this->currentItem;
$templatePath = JURI_SITE . "components/com_rsgallery2/templates/". rsgInstance::getVar( 'rsgTemplate', $rsgConfig->get('template'));

$jsSwf = '
		window.addEvent("domready", function() {
		var flashvars = {movie:"' . $item->display->url() . '",
		fgcolor: "0x000000",
		bgcolor: "0x000000",
		autoload: "on",
		autorewind: "on",
		volume: "70"}; 
		swfobject.embedSWF("' .$templatePath .'/player.swf",
		"rsg2-flashMovie", 
		"320", "240", 
		"7", 
		"' .$templatePath .'/expressInstall.swf",
		flashvars,
		{ wmode: "transparent", loop:false, autoPlay:true }
		);
		});';

$doc =& JFactory::getDocument();
$doc->addScriptDeclaration($jsSwf);
$doc->addScript($templatePath . "/script/swfobject.js");

?><div id="rsg2-flashMovie"><p><?php echo JText::_("The movie should appear here."); ?></p></div><?php

?>