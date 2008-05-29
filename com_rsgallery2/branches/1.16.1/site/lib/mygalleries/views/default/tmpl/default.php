<div class="rsg2">
<h2><?php echo JText::_('My galleries');?></h2>
		
<?php
echo $this->loadTemplate('userinfo');

//Start tabs

$tabs &= JTabs::getInstance();
echo $tabs->startPane( 'tabs' );
	echo $tabs->startPanel( JText::_('My Images'), 'my_images' );
		echo $this->loadTemplate('images');
		echo $this->loadTemplate('upload');
	echo $tabs->endPanel();

	if ($rsgConfig->get('uu_createCat')) {
		echo $tabs->startPanel( JText::_('My galleries'), 'my_galleries' );
			echo $this->loadTemplate('galleries');
			echo $this->loadTemplate('create');
		echo $tabs->endPanel();
	}
echo $tabs->endPane();
?>
</div>
<div class='rsg2-clr'>&nbsp;</div>

