<?php
/**
 * @version		$Id: edit.php 60 2010-11-27 18:45:40Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

?>
<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=image&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm"  class="form-validate">

	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend>
				<?php echo JText::_( 'COM_RSG2_IMAGE' ); ?>
				<?php echo $this->item->id ? JText::_('COM_RSG2_EDIT') : JText::_('COM_RSG2_NEW');?>
			</legend>
					
			<!-- tr>
				<td align="right">
				<?php echo JText::_('COM_RSGALLERY2_PARENT_ITEM');?>
				</td>
				<td>
				<?php echo $lists['parent']; ?>
				</td>
			</tr -->

			<!--
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset() as $field): ?>
					<li><?php echo $field->label; echo $field->input;?></li>
				<?php endforeach; ?>
			</ul>
			-->
			<div class="adminformlist">
				<?php foreach($this->form->getFieldset() as $field): ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="image.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

