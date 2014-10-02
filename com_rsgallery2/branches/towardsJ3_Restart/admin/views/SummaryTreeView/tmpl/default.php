<?php // no direct access
defined( '_JEXEC' ) or die; ?>

<?php 
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

// Include gallery tree classes
require_once( dirname(__FILE__).'/../../../classes/GalleryTreeClass.php' );

/*------------------------------------------------------------------------------------

------------------------------------------------------------------------------------*/	

function GalleryLeafHtmlOut (GalleryTreeBranch $ActGalleryLeaf) // GalleryTreeBranch
{
	$OutHtml = '';
	$OutHtml .=	'<br><strong>Gallery:"' . $ActGalleryLeaf->Gallery->name . '" </strong> <br>';
	$OutHtml .=	json_encode($ActGalleryLeaf->Gallery) . '<br>';
	
	// All images of gallery
	foreach ($ActGalleryLeaf->Images as $ActImage):
		// $OutHtml .=	'       <strong>Image:' . $ActImage['Image']->name . ' </strong> <br>';
		$OutHtml .=	'       <strong>Image:' . $ActImage['Image']->alias . ' </strong> <br>';
		$OutHtml .=	json_encode($ActImage['Image']) . '<br>';
	endforeach;
	
	// All child galleries as leafs
	foreach ($ActGalleryLeaf->ChildGalleries as $ChildGalleryLeaf):
		$OutHtml .=	GalleryLeafHtmlOut ($ChildGalleryLeaf);
	endforeach;

	return $OutHtml;
}


/*------------------------------------------------------------------------------------

------------------------------------------------------------------------------------*/	

function RootGalleryHtmlOut ($InRootGalleryTree)
{
	// No data given ?
	if (empty($InRootGalleryTree)):
	{
		$OutHtml = '<h2>GalleryTree</h2></br> this is empty ... '; // ToDo: Add time and ...		
	}
	else:
	{
		// ToDo: following first try should be divided into further ...
		$OutHtml = '<h3>GalleryTree</h3>'; // ToDo: Add time and ...
		// $OutHtml .=	json_encode($InRootGalleryTree) . '<br>';
		
		if (empty($InRootGalleryTree)):
			$OutHtml .=	'<div class="alert alert-no-items">';
			$OutHtml .=	JText::_('JGLOBAL_NO_MATCHING_RESULTS'). '<br>';
			//$OutHtml .=	var_dump($InRootGalleryTree->RootGalleryLeafs); 
			$OutHtml .=	'</div>';
		else: 
			// All root galleries;
			foreach ($InRootGalleryTree->RootGalleryLeafs as $ActGalleryLeaf):
				$OutHtml .=	GalleryLeafHtmlOut ($ActGalleryLeaf);
			endforeach;	
		endif; 
	}
	endif; 
	
	return $OutHtml;
}




?>


<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=SummaryTreeView'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<div class="clearfix"> </div>		
	<br/>
	<?php
	// Search tools bar
	// echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<h3>GalleryTree Json</h3>
	<?php
		echo json_encode($this->GalleryTree);
	?>
	<br/>
	<?php
		echo RootGalleryHtmlOut ($this->GalleryTree);
	?>

	<br/>
	<!-- ?php echo $this->pagination->getListFooter(); ? -->
	<!-- ?php //Load the batch processing form. ? -->
	<!-- ?php echo $this->loadTemplate('batch'); ? -->

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="">
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>

    </div>
</form>

