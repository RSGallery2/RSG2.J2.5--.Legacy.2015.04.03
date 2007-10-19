<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
global $Itemid; 
$cols = $rsgConfig->get( 'display_thumbs_colsPerPage' );
$i = 0;
?>

<table id='rsg2-thumbsList'>
	<?php foreach( $this->gallery->items as $item ):
		$thumb = $item->thumb();
		
		if( $i % $cols== 0) echo "<tr>\n";
		?>
			<td>
				<!--<div class="img-shadow">-->
					<a href="<?php echo sefRelToAbs( "index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=inline&amp;id=".$item->id ); ?>">
					<img alt="<?php echo htmlspecialchars(stripslashes($item->descr), ENT_QUOTES); ?>" src="<?php echo $thumb->url(); ?>" />
					</a>
				<!--</div>-->
				<div class="clr"></div>
				<?php if($rsgConfig->get("display_thumbs_showImgName")): ?>
				<br />
				<span class='rsg2_thumb_name'>
					<?php echo htmlspecialchars(stripslashes($item->title), ENT_QUOTES); ?>
				</span>
				<?php endif; ?>
				<?php if( $this->allowEdit ): ?>
				<div id='rsg2-adminButtons'>
					<a href="<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=edit_image&amp;id=".$item->id); ?>"><img src="<?php echo JURI::base(); ?>/administrator/images/edit_f2.png" alt="" height="15" /></a>
					<a href="#" onClick="if(window.confirm('<?php echo _RSGALLERY_DELIMAGE_TEXT;?>')) location='<?php echo sefRelToAbs("index.php?option=com_rsgallery2&amp;Itemid=$Itemid&amp;page=delete_image&amp;id=".$item->id); ?>'"><img src="<?php echo JURI::base(); ?>/administrator/images/delete_f2.png" alt="" height="15" /></a>
				</div>
				<?php endif; ?>
			</td>
		<?php if( ++$i % $cols == 0) echo "</tr>\n"; ?>
	<?php endforeach; ?>
	<?php if( $i % $cols != 0) echo "</tr>\n"; ?>
</table>