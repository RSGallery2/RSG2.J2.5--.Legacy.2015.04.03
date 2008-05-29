		global $rsgConfig;
		$my = JFactory::getUser();
		
		if ($my->usertype == "Super Administrator" OR $my->usertype == "Administrator") {
			$maxcat = "unlimited";
			$max_images = "unlimited";
		} else {
			$maxcat = $rsgConfig->get('uu_maxCat');
			$max_images = $rsgConfig->get('uu_maxImages');
		}
		?>
		<table class="adminform" border="1">
		<tr>
		<th colspan="2"><?php echo JText::_('User information'); ?></th>
		</tr>
		<tr>
		<td width="250"><?php echo JText::_('Username'); ?></td>
		<td><?php echo $my->username;?></td>
		</tr>
		<tr>
		<td><?php echo JText::_('User level'); ?></td>
		<td><?php echo $my->usertype;?></td>
		</tr>
		<tr>
		<td><?php echo JText::_('Maximum usergalleries'); ?></td>
		<td><?php echo $maxcat;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userCategoryTotal($my->id);?></strong></font> <?php echo JText::_('created)');?></td>
		</tr>
		<tr>
		<td><?php echo JText::_('Maximum images allowed'); ?></td>
		<td><?php echo $max_images;?>&nbsp;&nbsp;(<font color="#008000"><strong><?php echo galleryUtils::userImageTotal($my->id);?></strong></font> <?php echo JText::_('uploaded)'); ?></td>
		</tr>
		<tr>
		<th colspan="2"></th>
		</tr>
		</table>
		<br><br>
		<?php
