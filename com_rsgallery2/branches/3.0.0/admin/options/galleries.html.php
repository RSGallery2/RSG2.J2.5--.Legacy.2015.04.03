<?php
/**
* Galleries option for RSGallery2 - HTML display code
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2011 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Explain what this class does
 * @package RSGallery2
 */
class html_rsg2_galleries{
    /**
     * show list of galleries
     */
    function show( &$rows, &$lists, &$search, &$pageNav ){
        global $rsgOption;
		$option = JRequest::getCmd('option');//MK// [change] [option]	

		$my =& JFactory::getUser();
		JHTML::_("behavior.mootools");
		
		//Create 'lookup array' to find whether or not galleries with the same parent
		// can move up/down in their order: $orderLookup[id parent][#] = id child
		$orderLookup = array();
		foreach ($rows as $row) {
			$orderLookup[$row->parent][] = $row->id;
		}   		
		
		
        ?>
        <form action="index.php" method="post" name="adminForm">
        <table border="0" width="100%">
        <tr>
            <td width="50%">
            &nbsp;
            </td>
            <td style="white-space:nowrap;" width="50%" align="right">
            <?php echo JText::_('COM_RSGALLERY2_MAX_LEVELS')?>
            <?php echo $lists['levellist'];?>
            <?php echo JText::_('COM_RSGALLERY2_FILTER')?>:
            <input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
            </td>
        </tr>
        </table>

        <table class="adminlist">
        <thead>
        <tr>
            <th width="1%">
				ID
            </th>
            <th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
            </th>
            <th class="Name">
				<?php echo JText::_('COM_RSGALLERY2_NAME')?>
            </th>
            <th width="5%">
				<?php echo JText::_('COM_RSGALLERY2_PUBLISHED')?>
            </th>
			<th width="5%">
				<?php echo JText::_('COM_RSGALLERY2_REORDER')?>
            </th>
			<th width="2%">
				<?php echo JText::_('COM_RSGALLERY2_ORDER')?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.order',  $rows); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('COM_RSGALLERY2_ITEMS')?>
			</th>
            <th width="5%">
				<?php echo JText::_('COM_RSGALLERY2_HITS')?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $k = 0;
        for ($i=0, $n=count( $rows ); $i < $n; $i++) {
            $row = &$rows[$i];
			
            $link   = "index.php?option=$option&rsgOption=$rsgOption&task=editA&hidemainmenu=1&id=". $row->id;

            $task   = $row->published ? 'unpublish' : 'publish';
            $img    = $row->published ? 'publish_g.png' : 'publish_x.png';
            $alt    = $row->published ? 'Published' : 'Unpublished';

            $checked    = JHTML::_('grid.checkedout', $row, $i );

			//Use the $orderLookup array to determine if for the same 
			// parent one can still move up/down. First look up the parent info.
			$orderkey = array_search($row->id, $orderLookup[$row->parent]);
			$showMoveUpIcon		= isset($orderLookup[$row->parent][$orderkey - 1]);
			$showMoveDownIcon	= isset($orderLookup[$row->parent][$orderkey + 1]);
			
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
					<?php echo $row->id; ?>
                </td>
                <td>
					<?php echo $checked; ?>
                </td>
                <td>
					<?php
					if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
						echo stripslashes($row->name);
					} else { 
						?>
						<a href="<?php echo $link; ?>" name="Edit Gallery">
						<?php echo stripslashes($row->treename); ?>
						</a>
						<?php
					}
					?>

					<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&rsgOption=images&gallery_id='.$row->id); ?>" >
						<img src="<?php echo 'templates/bluestork/images/j_arrow.png';?>"  style="margin: 0px 20px" alt="<?php echo JText::_('COM_RSGALLERY2_ITEMS'); ?>" />
					</a>
					
                </td>
                <td align="center">
                	<?php echo JHtml::_('jgrid.published', $row->published, $i); ?>
                </td>
                <td class="order">
                	<span>
					<?php echo $pageNav->orderUpIcon( $i , $showMoveUpIcon); ?>
					</span>
                	<span>
					<?php echo $pageNav->orderDownIcon( $i, $n , $showMoveDownIcon); ?>
					</span>
                </td>
                <td colspan="2" align="center">
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
                </td>
                <td align="center">
					<?php $gallery = rsgGalleryManager::get( $row->id ); echo $gallery->itemCount()?>
                </td>
                <td align="left">
					<?php echo $row->hits; ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
        	<td colspan="10"><?php echo $pageNav->getListFooter(); ?></td>
        </tr>
        </tfoot>
        </table>
    
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="rsgOption" value="<?php echo $rsgOption;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="hidemainmenu" value="0" />
        </form>
        <?php
    }

    /**
     * warns user what will be deleted
     */
    function removeWarn( $galleries ){
        global $rsgOption;
		$option = JRequest::getCmd('option');//MK// [change] [optionn]		
        ?>
        <form action="index.php" method="post" name="adminForm">
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="rsgOption" value="<?php echo $rsgOption;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="hidemainmenu" value="0" />

<!--         these are the galleries the user has chosen to delete: -->
        <?php foreach( $galleries as $g ): ?>
            <input type="hidden" name="cid[]" value="<?php echo $g->get('id'); ?>" />
        <?php endforeach; ?>
        
        <h2>The following will be deleted:</h2>
        <div style='text-align: left;' >

        <?php html_rsg2_galleries::printTree( $galleries ); ?>
        
        </div>
        </form>
        <?php
    }
    function printTree( $galleries ){
        echo "<ul>";

        foreach( $galleries as $g ){
            // print gallery details
            echo "<li>". $g->get('name') ." (". count($g->itemRows()) ." images)";
            html_rsg2_galleries::printTree( $g->kids() );
            echo "</li>";
        }
        echo "</ul>";
    }

	/**
	* Writes the edit form for new and existing record
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param rsgGallery The gallery object
	* @param array An array of select lists
	* @param object Parameters
	* @param string The option
	*/
	function edit( &$row, &$lists, &$params, $option ) {
		global $rsgOption, $rsgAccess, $rsgConfig;

		JHTML::_('behavior.formvalidation');
		jimport("joomla.filter.output");
		$my =& JFactory::getUser();
		$editor =& JFactory::getEditor();
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES );

		//Get form for J!1.6 ACL rules (load library, get path to XML, get form)
		jimport( 'joomla.form.form' );
		JForm::addFormPath(JPATH_ADMINISTRATOR.'/components/com_rsgallery2/models/forms/');
		$form = &JForm::getInstance('com_rsgallery2.params','gallery',array( 'load_data' => true ));
		//Get the data for the form from $row (but only matching XML fields will get data here: asset_id)
		$form->bind($row);

		$task = JRequest::getVar( 'task'  , '');
		
		JHTML::_("Behavior.mootools");
		?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			var form = document.adminForm;
			
			if (task == 'cancel') {
				Joomla.submitform(task);
				return;
			}
			
			if (document.formvalidator.isValid(document.id('adminForm'))) {
				Joomla.submitform(task);
				return;
			} else {
				alert( "<?php echo JText::_('COM_RSGALLERY2_YOU_MUST_PROVIDE_A_GALLERY_NAME');?>" );
				return;
			}
		}
	
		function selectAll() {
			if(document.adminForm.checkbox0.checked) {
				for (i = 0; i < 12; i++) {
					document.getElementById('p' + i).checked=true;
				}
			} else {
				for (i = 0; i < 12; i++) {
					document.getElementById('p' + i).checked=false;
				}
			}
		}
		</script>
		
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo JText::_('COM_RSGALLERY2_GALLERY')?>:
			<small>
			<?php echo $row->id ? JText::_('COM_RSGALLERY2_EDIT') : JText::_('COM_RSGALLERY2_NEW');?>
			</small>
			</th>
		</tr>
		</table>
	
		<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo JText::_('COM_RSGALLERY2_DETAILS')?>
					</th>
				</tr>
				<tr>
					<td width="20%" align="right">
					<?php echo JText::_('COM_RSGALLERY2_NAME')?>
					</td>
					<td width="80%">
					<input class="text_area required" type="text"  name="name" size="50" maxlength="250" value="<?php echo stripslashes($row->name);?>"/>
					</td>
				</tr>
				<tr>
					<td width="20%" align="right">
					<?php echo JText::_('COM_RSGALLERY2_ALIAS')?>
					</td>
					<td width="80%">
					<input class="text_area" type="text" name="alias" size="50" maxlength="250" value="<?php echo stripslashes($row->alias);?>" />
					</td>
				</tr>
				<tr>
					<td align="right">
					<?php echo JText::_('COM_RSGALLERY2_OWNER');?>
					</td>
					<td>
					<?php echo $lists['uid']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('COM_RSGALLERY2_PERMISSIONS');?>
					</td>
					<td>
						<div class="button2-left">
						<div class="blank">
							<button type="button" onclick="document.location.href='#access-rules';">
							<?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?></button>
						</div>
						</div>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo JText::_('COM_RSGALLERY2_DESCRIPTION')?>
					</td>
					<td>
					<?php
					// parameters : areaname, content, hidden field, width, height, rows, cols
					echo $editor->display ( 'description',  stripslashes($row->description) , '100%', '300', '10', '20' ,false) ; ?>
					</td>
				</tr>
				<tr>
					<td align="right">
					<?php echo JText::_('COM_RSGALLERY2_PARENT_ITEM');?>
					</td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo JText::_('COM_RSGALLERY2_GALLERY_THUMBNAIL');?>
					</td>
					<td>
					<?php echo imgUtils::showThumbNames($row->id, $row->thumb_id); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo JText::_('COM_RSGALLERY2_ORDERING');?>
					</td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo JText::_('COM_RSGALLERY2_PUBLISHED');?>
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="1">
					<?php echo JText::_('COM_RSGALLERY2_PARAMETERS');?>
					</th>
				</tr>
				<tr>
					<td>
					<?php echo $params->render();?>
					</td>
				</tr>
				</table><br/>
			</td>
		</tr>
		</table>

		<div class="clr"></div>

<?php //Create the rules slider at the bottom of the page
//if ($this->canDo->get('core.admin')): ?>
			<div  class="width-100 fltlft">
				<?php echo JHtml::_('sliders.start','permissions-sliders-'.$row->id, array('useCookie'=>1)); ?>
				<?php echo JHtml::_('sliders.panel',JText::_('COM_RSGALLERY2_FIELDSET_RULES'), 'access-rules'); ?>	
					<fieldset class="panelform">
						<?php echo $form->getLabel('rules'); ?>
						<?php echo $form->getInput('rules'); ?>
					</fieldset>
				<?php echo JHtml::_('sliders.end'); ?>
			</div>
<?php //endif; ?>

		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="rsgOption" value="<?php echo $rsgOption;?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
}