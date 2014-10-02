<?php // no direct access
defined( '_JEXEC' ) or die; ?>

<?php 
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
$nullDate = JFactory::getDbo()->getNullDate();

$listOrder	= '';
$listDirn	= '';
$saveOrder	= true;

$disabled = false;



/*
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_rsg2.category');
$saveOrder	= $listOrder == 'a.ordering';
*/

?>
<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=galleries'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	
	<div class="clearfix"> </div>		
	<?php
	// Search tools bar
	// echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('No existing gallery'); ?>
		</div>
	<?php else : ?>  		
		<table class="adminlist table table-striped" id="GalleryList">
			<thead>
				<tr>
					<th width="1%">
						<?php echo JText::_('JGRID_HEADING_ID'); ?>
					</th>
					<th width="1%"> 
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="Name">
						<?php echo JText::_('COM_RSG2_NAME')?>
					</th>
					<th width="5%">
						<?php echo JText::_('COM_RSG2_PUBLISHED')?>
					</th>
					<th width="5%">
						<?php echo JText::_('JGRID_HEADING_ACCESS')?>
					</th>
					<th width="5%">
						<?php echo JText::_('COM_RSG2_REORDER')?>
					</th>
					<th width="2%">
						<?php echo JText::_('COM_RSG2_ORDER')?>
					</th>
					<th width="2%">
						rows
						<!-- ?php echo JHtml::_('grid.order',  $this->items->itemCount()); ? -->
					</th>
					<th width="5%">
						<?php echo JText::_('COM_RSG2_ITEMS')?>
					</th>
					<th width="5%">
						<?php echo JText::_('COM_RSG2_HITS')?>
					</th>
					<!--th width="20" style="min-width:55px" class="nowrap center">
						1:<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th class="title">
						2:<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="25%" class="nowrap hidden-phone">
						3:<?php echo JHtml::_('grid.sort', 'COM_RSG2_HEADING_COMPANY', 'a.company', $listDirn, $listOrder); ?>
					</th>
					<th width="10" class="nowrap center hidden-phone">
						4:<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th -->
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				// $canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
				// $canChange  = $user->authorise('core.edit.state', 'COM_RSG2') && $canCheckin;
				// $canEdit    = $user->authorise('core.edit',       'COM_RSG2.category.' . $item->catid);
				$canCheckin = true;
				$canChange  = true;
				$canEdit    = true;
				$checked = false;
				$edit_gallery_link   = "index.php?option=com_rsg2&view=gallery&layout=edit&id=".$item->id;
				$imgages_link = "index.php?option=com_rsg2&view=images&gallery_id=".$item->id;
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
					<td class="order nowrap center hidden-phone">
						<?php echo $item->id; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>					
					<td>
						<!-- ToDo: tree name for sun galleries -->
						<a href="<?php echo $edit_gallery_link; ?>" name="Edit Gallery">
							<?php echo $this->escape($item->name); ?>
						</a>
						<img src="<?php echo 'templates/hathor/images/j_arrow.png';?>" style="margin: 0px 20px" alt="<?php echo JText::_('COM_RSG2_IMAGES'); ?>" />						
						<a href="<?php echo JRoute::_($imgages_link); ?>" name="view Imagges of gallery">
						   <?php echo JText::_('COM_RSG2_IMAGES'); ?>
						</a>
					</td>
				    <td align="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, '', true); ?>
					</td>
					<td>
						<?php echo "item->access_level";?>
					</td>
					<td class="order">
						<span>
							up /<!-- ?php echo $pageNav->orderUpIcon( $i , true ); ? -->
						</span>
						<span>
							down <!--?php echo $pageNav->orderDownIcon( $i, $n , true); ? -->
						</span>
					</td>
					<td colspan="2" align="center">
						<input type="text" name="order[]" <?php echo $disabled; ?> size="5" value="<?php echo $item->ordering; ?>" class="text_area" style="text-align: center" />
					</td>
					<td align="center">
						Cnt <!-- ?php $gallery = rsgGalleryManager::get( $item->id ); echo $gallery->itemCount(); ? -->
					</td>
					<td align="left">
						<?php echo $item->hits; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			
			
			
			
			
			<!-- tfoot>
				<tr>
					<td colspan="10">
						<! - - ?php echo $this->item->pagination->getListFooter(); ? - - >
						<! - - ?php echo $pageNav->getListFooter(); ? - - >
					</td>
				</tr>
			</tfoot -->
		</table>
		
		<?php endif; ?>
		<?php echo $this->pagination->getListFooter(); ?>
		<!-- ?php //Load the batch processing form. ? -->
		<!-- ?php echo $this->loadTemplate('batch'); ? -->

 		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />

        <!-- filter_order
 yyy       filter_order_dir
        -->

		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

