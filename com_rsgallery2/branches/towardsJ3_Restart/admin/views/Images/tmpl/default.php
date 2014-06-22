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
<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=images'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	
	<div class="clearfix"> </div>		
		<table class="adminlist table table-striped" id="ImageList">
			<thead>
				<tr>
					<th width="1%">
						<?php echo JText::_('JGRID_HEADING_ID'); ?>
					</th>
					<th width="1%"> 
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title">
						<?php echo JText::_('COM_RSG2_TITLE_FILENAME')?>
					</th>
			        <th width="5%">
						<?php echo JText::_('COM_RSG2_PUBLISHED')?>
					</th>
			        <th width="5%">
	                    <?php echo JText::_('COM_RSG2_REORDER')?>
                    </th>
			        <th width="2%">
	                    <?php echo JText::_('COM_RSG2_ORDER')?>
                    </th>
			        <th width="2%">
						rows
				        <!-- ?php echo JHtml::_('grid.order',  $item); ? -->
			        </th>
			        <th width="15%" align="left">
	                    <?php echo JText::_('COM_RSG2_GALLERY')?>
                    </th>
			        <th width="5%">
	                    <?php echo JText::_('COM_RSG2_HITS')?>
                    </th>
			        <th width="">
	                    <?php echo JText::_('COM_RSG2_DATE__TIME')?>
                    </th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
			    //Get permissions
			    // $can['EditItem']		= $user->authorise('core.edit',		'com_rsgallery2.item.'.$row->id);
			    // $can['EditOwnItem']	= $user->authorise('core.edit.own',	'com_rsgallery2.item.'.$row->id) AND ($row->userid == $userId);
			    // $can['EditStateItem']	= $user->authorise('core.edit.state','com_rsgallery2.item.'.$row->id);
			    // $can['EditGallery']		= $user->authorise('core.edit',		'com_rsgallery2.gallery.'.$row->gallery_id);
			    //$showMoveUpIcon = (($row->gallery_id == @$rows[$i-1]->gallery_id) AND ($can['EditStateItem']));
			    //$showMoveDownIcon = (($row->gallery_id == @$rows[$i+1]->gallery_id) AND ($can['EditStateItem']));
			    //$disabled = $can['EditStateItem'] ?  '' : 'disabled="disabled"';
    
			    $edit_img_link     = 'index.php?option=com_rsg2&view=image&layout=edit&id='.$item->id;
				$edit_gallery_link = 'index.php?option=com_rsg2&view=gallery&layout=edit&id='.$item->gallery_id;
				$ImagesModel = $this->getModel('Images');
				$ParentGalleryName = $ImagesModel->getParentGalleryName($item->gallery_id);
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
					<td class="order nowrap center hidden-phone">
						<?php echo $item->id; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td> 
					<td>
						<a href="<?php echo $edit_img_link; ?>" name="Edit Image">
							<?php echo $this->escape($item->title); ?>
							<?php echo ' (' . $this->escape($item->name) . ')'; ?>
						</a>
						<!--img src="<?php echo 'templates/hathor/images/j_arrow.png';?>" style="margin: 0px 20px" alt="<?php echo JText::_('COM_RSG2_IMAGES'); ?>" / -->
						<!--a href="<?php echo JRoute::_($edit_gallery_link); ?>" name="view Images of gallery" -->
							<!--?php echo $this->escape($item->); ?-->
						<!-- /a -->
					</td>
				    <td align="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, '', true); ?>
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


					<td>
						<a href="<?php echo $edit_gallery_link; ?>" title="Edit Gallery">					
							<?php echo $ParentGalleryName; ?>
						</a>
					</td>
					<td align="left">
						<?php echo $item->hits; ?>
					</td>
					<td align="left">
						<?php echo JHTML::Date($item->date, JText::_('COM_RSG2_DATE_FORMAT_WITH_TIME'));?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<!-- ?php echo $this->item->pagination->getListFooter(); ? -->
						<!-- ?php echo $pageNav->getListFooter(); ? -->
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
    <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

