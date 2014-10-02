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
<form action="<?php echo JRoute::_('index.php?option=com_rsg2&view=gallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm"  class="form-validate">

    <div class="row-fluid">
        <div class="span10 form-horizontal">

            <fieldset>
                <?php echo JHTML::_('bootstrap.startPane', 'myTab', array('active' => 'details')); ?>

                <!-- Panel header -->
                <?php
                    /*
                    $OutTxt = $this->item->id ? JText::_('COM_RSG2_EDIT') : JText::_('COM_RSG2_NEW');
                    $OutTxt = JText::_( 'COM_RSG2_GALLERY') . ' ' . $OutTxt;
                    echo JHTML::_('bootstrap.addPanel', 'myTab', 'details', $OutTxt); ?>
                    */
                echo JHTML::_('bootstrap.addPanel', 'myTab', 'details', $this->item->id ? JText::_('COM_RSG2_EDIT') : JText::_('COM_RSG2_NEW')); ?>

                <div class="adminformlist">
                    <?php foreach($this->form->getFieldset('gallery') as $field): ?>
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

                <?php echo JHTML::_('bootstrap.endPanel'); ?>

                <input type="hidden" name="task" value="" />
                <?php echo JHtml::_('form.token'); ?>

                <?php echo JHTML::_('bootstrap.endPane'); ?>
            </fieldset>
        </div>
    </div>
</form>

