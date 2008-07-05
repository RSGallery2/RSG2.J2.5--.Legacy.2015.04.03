<?php
/**
 * RSGallery2 template view class
 * Derived from Joomla 1.5 Joomla.Templates
 * @author John Caprez <john@swizzysoft.com>
 * @package RSGallery2
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct Access to this location is not allowed.');


/**
* @package		Joomla
* @subpackage	Templates
*/
class TemplatesView
{
	/**
	* @param array An array of data objects
	* @param object A page navigation object
	* @param string The option
	*/
	function showTemplates(& $rows, & $page, $option)
	{
		global $mainframe,$mosConfig_live_site;

		$limitstart = JRequest :: getVar('limitstart', '0', '', 'int');

		$user = & JFactory :: getUser();

		if (isset ($row->authorUrl) && $row->authorUrl != '') {
			$row->authorUrl = str_replace('http://', '', $row->authorUrl);
		}

		JHTML::_('behavior.tooltip');
?>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">

			<table class="adminlist">
			<thead>
				<tr>
					<th width="5" class="title">
						<?php echo JText::_( 'Num' ); ?>
					</th>
					<th class="title" colspan="2">
						<?php echo JText::_( 'Template Name' ); ?>
					</th>
					<th width="5%">
						<?php echo JText::_( 'Default' ); ?>
					</th>
					<th width="10%" align="center">
						<?php echo JText::_( 'Version' ); ?>
					</th>
					<th width="15%" class="title">
						<?php echo JText::_( 'Date' ); ?>
					</th>
					<th width="25%"  class="title">
						<?php echo JText::_( 'Author' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $page->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php

		$k = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = & $rows[$i];

			$author_info = @ $row->authorEmail . '<br />' . @ $row->authorUrl;
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td>
					<?php echo $page->getRowOffset( $i ); ?>
				</td>
				<td width="5">
				<?php

			if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out )) {
				?>
				&nbsp;
				<?php
			} else {
				?>
				<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->directory; ?>" onclick="isChecked(this.checked);" />
				<?php
			}
			?>
			</td>
			<td><?php 
			$img_path = $mosConfig_live_site.'com_rsgallery2'.'/templates/'.$row->directory.'/template_thumbnail.png'; 
			echo JHTML::tooltip('<img src="'.$img_path.'" alt="'.JText::_( 'No preview available' ).'"/>',
								$row->name,
								null,
								$row->name,
								'index2.php?option=com_rsgallery2&rsgOption=templateManager&task=edit&cid[]='.$row->directory,
								1);
			?>
				</td>
				<td align="center">
					<?php

				if ($row->published == 1) {
?>
								<img src="images/tick.png" alt="<?php echo JText::_( 'Default' ); ?>" />
								<?php

				} else {
?>
								&nbsp;
								<?php

				}
?>
					</td>
					<td align="center">
						<?php echo $row->version; ?>
					</td>
					<td>
						<?php echo $row->creationdate; ?>
					</td>
					<td>
					<?php
					echo JHTML::tooltip($author_info, 
										JText::_( 'Author Information' ),
										null,
										@$row->author != '' ? $row->author : '&nbsp;', null, 0);
					?>
					</td>
				</tr>
				<?php

		} // for
?>
			</tbody>
			</table>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="rsgOption" value="templateManager" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
	<?php

	}

	function previewTemplate($template, $option)
	{
		global $mainframe;

		$url =$mainframe->getSiteURL();
		$doc =&JFactory::getDocument();
		$doc->addStyleDeclaration("
					.previewFrame {
					border: none;
					width: 95%;
					height: 600px;
					padding: 0px 5px 0px 10px;
					}
					");

?>
		<table class="adminform">
			<tr>
				<th width="50%" class="title">
					<?php echo JText::_( 'Site Preview' ); ?>
				</th>
				<th width="50%" style="text-align:right">
					<?php echo JHTML::_('link', $url.'index2.php?option=com_rsgallery2&rsgTemplate='.$template, JText::_( 'Open in new window' ), array('target' => '_blank')); ?>
				</th>
			</tr>
			<tr>
				<td width="100%" valign="top" colspan="2">
					<?php echo JHTML::_('iframe', $url.'index2.php?option=com_rsgallery2&rsgTemplate='.$template,'previewFrame',  array('class' => 'previewFrame')) ?>
				</td>
			</tr>
		</table>
		<?php

	}

	/**
	* @param string Template name
	* @param string Source code
	* @param string The option
	*/
	function editTemplate($row, & $params, $option, & $ftp, & $template)
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		JHTML::_('behavior.tooltip');
?>
		<form action="index2.php" method="post" name="adminForm">

		<?php if($ftp): ?>
		<fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>" class="adminform">
			<legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>

			<?php echo JText::_('DESCFTP'); ?>

			<?php if(JError::isError($ftp)): ?>
				<p><?php echo JText::_($ftp->message); ?></p>
			<?php endif; ?>

			<table class="adminform nospace">
			<tbody>
			<tr>
				<td width="120">
					<label for="username"><?php echo JText::_('Username'); ?>:</label>
				</td>
				<td>
					<input type="text" id="username" name="username" class="input_box" size="70" value="" />
				</td>
			</tr>
			<tr>
				<td width="120">
					<label for="password"><?php echo JText::_('Password'); ?>:</label>
				</td>
				<td>
					<input type="password" id="password" name="password" class="input_box" size="70" value="" />
				</td>
			</tr>
			</tbody>
			</table>
		</fieldset>
		<?php endif; ?>

  		<div class="col50">
  			<fieldset class="adminform">
  				<legend><?php echo JText::_( 'Details' ); ?></legend>
  
  				<table class="admintable">
  				<tr>
  					<td valign="top" class="key">
  						<?php echo JText::_( 'Name' ); ?>:
  					</td>
  					<td>
  						<strong>
  							<?php echo JText::_($row->name); ?>
  						</strong>
  					</td>
  				</tr>
  				<tr>
  					<td valign="top" class="key">
  						<?php echo JText::_( 'Description' ); ?>:
  					</td>
  					<td>
  						<?php echo JText::_($row->description); ?>
  					</td>
  				</tr>
  				</table>
  			</fieldset>
		</div>

		<div class="col50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Parameters' ); ?></legend>
		<?php $templatefile = JPATH_RSGALLERY2_SITE.DS.'templates'.DS.$template.DS.'params.ini';
		echo is_writable($templatefile) ? JText::_( 'Writable' ):JText::_( 'Unwritable' ); ?>
				<table class="admintable">
				<tr>
					<td>
						<?php

		if (!is_null($params)) {
			echo $params->render();
		} else {
			echo '<i>' . JText :: _('No Parameters') . '</i>';
		}
?>
					</td>
				</tr>
				</table>
			</fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="id" value="<?php echo $row->directory; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="rsgOption" value="templateManager" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}

	function chooseCSSFiles($template, $t_dir, $t_files, $option)
	{
		JRequest::setVar( 'hidemainmenu', 1 );
?>
		<form action="index2.php" method="post" name="adminForm">

		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="220">
				<span class="componentheading">&nbsp;</span>
			</td>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="5%" align="left">
				<?php echo JText::_( 'Num' ); ?>
			</th>
			<th width="85%" align="left">
				<?php echo $t_dir; ?>
			</th>
			<th width="10%">
				<?php echo JText::_( 'Writable' ); ?>/<?php echo JText::_( 'Unwritable' ); ?>
			</th>
		</tr>
		<?php

		$k = 0;
		for ($i = 0, $n = count($t_files); $i < $n; $i++) {
			$file = & $t_files[$i];
?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td width="5%">
					<input type="radio" id="cb<?php echo $i;?>" name="filename" value="<?php echo htmlspecialchars( $file ); ?>" onClick="isChecked(this.checked);" />
				</td>
				<td width="85%">
			<a href="<?php echo JRoute::_('index2.php?option=com_rsgallery2&rsgOption=templateManager&task=edit_css&id='.$template.'&filename='.$file); ?>"><?php echo $file ?></a>
				</td>
				<td width="10%">
					<?php echo is_writable($t_dir.DS.$file) ? '<span style="color:green;"> '. JText::_( 'Writable' ) .'</span>' : '<span style="color:red"> '. JText::_( 'Unwritable' ) .'</span>' ?>
				</td>
			</tr>
		<?php

			$k = 1 - $k;
		}
?>
		</table>
		<input type="hidden" name="id" value="<?php echo $template; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $template; ?>" />
		<input type="hidden" name="rsgOption" value="templateManager" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php

	}

	function chooseOverrideFiles($template, $t_dir, $t_files, $option)
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		?>
		<form action="index2.php" method="post" name="adminForm">
		
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
		<td width="220">
		<span class="componentheading">&nbsp;</span>
		</td>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
		<th width="5%" align="left">
		<?php echo JText::_( 'Num' ); ?>
		</th>
		<th width="85%" align="left">
		<?php echo $t_dir; ?>
		</th>
		<th width="10%">
		<?php echo JText::_( 'Writable' ); ?>/<?php echo JText::_( 'Unwritable' ); ?>
		</th>
		</tr>
		<?php
		
		$k = 0;
		for ($i = 0, $n = count($t_files); $i < $n; $i++) {
			$file = & $t_files[$i];
			?>
			<tr class="<?php echo 'row'. $k; ?>">
			<td width="5%">
			<input type="radio" id="cb<?php echo $i;?>" name="filename" value="<?php echo htmlspecialchars( $file ); ?>" onClick="isChecked(this.checked);" />
			</td>
			<td width="85%">
			<a href="<?php echo JRoute::_('index2.php?option=com_rsgallery2&rsgOption=templateManager&task=edit_override&id='.$template.'&filename='.$file); ?>"><?php echo $file ?></a>
			</td>
			<td width="10%">
			<?php echo is_writable($t_dir.DS.$file) ? '<span style="color:green;"> '. JText::_( 'Writable' ) .'</span>' : '<span style="color:red"> '. JText::_( 'Unwritable' ) .'</span>' ?>
			</td>
			</tr>
			<?php
			
			$k = 1 - $k;
		}
		?>
		</table>
		<input type="hidden" name="id" value="<?php echo $template; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $template; ?>" />
		<input type="hidden" name="rsgOption" value="templateManager" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
		
	}
	
	function editFileSource($template, $filename, & $content, $option, & $ftp, $fileType)
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		switch ($fileType)
		{
			case "override":
				$file_path = JPATH_RSGALLERY2_SITE .DS. 'templates' .DS. 'html' .DS. $filename;
				break;
			case "css":
				$file_path = JPATH_RSGALLERY2_SITE .DS. 'templates' .DS. 'css' .DS. $filename;
				break;
			case "template":
			case "display":
			default :
				$file_path = JPATH_RSGALLERY2_SITE .DS. 'templates' .DS. $filename;
				break;
		}
		$file_path = JPATH_RSGALLERY2_SITE .DS. 'templates' .DS. $filename;
?>
		<form action="index2.php" method="post" name="adminForm">

	
		<?php TemplatesView::_writeFTPHeader($ftp); ?>

		<table class="adminform">
		<tr>
			<th>
				<?php echo $file_path; ?>
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea>
			</td>
		</tr>
		</table>

		<div class="clr"></div>

		<input type="hidden" name="id" value="<?php echo $template; ?>" />
		<input type="hidden" name="rsgOption" value="templateManager" />
		<input type="hidden" name="cid[]" value="<?php echo $template; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="filename" value="<?php echo $filename;?>" />
		<input type="hidden" name="fileType" value="<?php echo $fileType;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}

	function _writeFTPHeader(& $ftp)
	{
		return;
		if($ftp): ?>
			<fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>">
			<legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>
			
			<?php echo JText::_('DESCFTP'); ?>
			
			<?php if(JError::isError($ftp)): ?>
				<p><?php echo JText::_($ftp->message); ?></p>
				<?php endif; ?>
			
			<table class="adminform nospace">
			<tbody>
			<tr>
			<td width="120">
			<label for="username"><?php echo JText::_('Username'); ?>:</label>
			</td>
			<td>
			<input type="text" id="username" name="username" class="input_box" size="70" value="" />
			</td>
			</tr>
			<tr>
			<td width="120">
			<label for="password"><?php echo JText::_('Password'); ?>:</label>
			</td>
			<td>
			<input type="password" id="password" name="password" class="input_box" size="70" value="" />
			</td>
			</tr>
			</tbody>
			</table>
			</fieldset>
			<?php 
		
		endif;
		
	}
	
	function showInstall($option)
	{
		
	}
	
	function chooseInstall($option, &$ftp){

		$doc =&JFactory::getDocument();
		$doc->addScriptDeclaration('
			function submitbutton3(pressbutton) {
				var form = document.adminForm;

				// do field validation
				if (form.install_directory.value == ""){
					alert( "'.JText::_( "Please select a directory", true ).'" );
				} else {
					form.installtype.value = "folder";
					form.submit();
				}
			}

			function submitbutton4(pressbutton) {
				var form = document.adminForm;

				// do field validation
				if (form.install_url.value == "" || form.install_url.value == "http://"){
					alert( "'. JText::_( "Please enter a URL", true ).'" );
				} else {
					form.installtype.value = "ur";
					form.submit();
				}
			}
		');
		?>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm">

			<?php
			  TemplatesView::_writeFTPHeader($ftp); 
			?>

			<table class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_( 'Upload Package File' ); ?></th>
			</tr>
			<tr>
				<td width="120">
					<label for="install_package"><?php echo JText::_( 'Package File' ); ?>:</label>
				</td>
				<td>
					<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
					<input class="button" type="button" value="<?php echo JText::_( 'Upload File' ); ?> &amp; <?php echo JText::_( 'Install' ); ?>" onclick="form.submit()" />
				</td>
			</tr>
			</table>

			<table class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_( 'Install from directory' ); ?></th>
			</tr>
			<tr>
				<td width="120">
					<label for="install_directory"><?php echo JText::_( 'Install directory' ); ?>:</label>
				</td>
				<td>
					<input type="text" id="install_directory" name="install_directory" class="input_box" size="70" value="<?php //echo $this->state->get('install.directory'); ?>" />
					<input type="button" class="button" value="<?php echo JText::_( 'Install' ); ?>" onclick="submitbutton3()" />
				</td>
			</tr>
			</table>

			<table class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_( 'Install from URL' ); ?></th>
			</tr>
			<tr>
				<td width="120">
					<label for="install_url"><?php echo JText::_( 'Install URL' ); ?>:</label>
				</td>
				<td>
					<input type="text" id="install_url" name="install_url" class="input_box" size="70" value="http://" />
					<input type="button" class="button" value="<?php echo JText::_( 'Install' ); ?>" onclick="submitbutton4()" />
				</td>
			</tr>
			</table>

			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="doInstall" />
			<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="rsgOption" value="templateManager" />
		</form>

		<?php		
	}
}
?>
